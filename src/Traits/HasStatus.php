<?php

namespace DigitalCloud\NovaResourceStatus\Traits;

use DigitalCloud\NovaResourceStatus\Exceptions\InvalidStatus;
use DigitalCloud\NovaResourceStatus\StatusObserver;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use DigitalCloud\NovaResourceStatus\Events\StatusUpdated;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use DigitalCloud\NovaResourceStatus\Models\Status;

trait HasStatus
{

    public static function bootHasStatus() {
        static::observe(app(StatusObserver::class));
    }

    public function statuses(): MorphMany
    {
        return $this->morphMany(
            config('nova-resource-status.status_model'),
            'model',
            'model_type',
            'model_id'
        )->latest('id');
    }

    public function setStatus(string $status, ?string $note = null): self
    {
        if (! $this->isValidStatus($status, $note)) {
            throw InvalidStatus::create($status);
        }

        return $this->forceSetStatus($status, $note);
    }

    public function isValidStatus(string $status, ?string $note = null): bool
    {
        return true;
    }

    /**
     * @param string|array $names
     *
     * @return null|Status
     */
    public function latestStatus(...$names): ?Status
    {
        $names = is_array($names) ? Arr::flatten($names) : func_get_args();

        $statuses = $this->relationLoaded('statuses') ? $this->statuses : $this->statuses();

        if (count($names) < 1) {
            return $statuses->first();
        }

        return $statuses->whereIn('status', $names)->first();
    }

    public function scopeCurrentStatus(Builder $builder, ...$names)
    {
        $names = is_array($names) ? Arr::flatten($names) : func_get_args();
        $builder
            ->whereHas(
                'statuses',
                function (Builder $query) use ($names) {
                    $query
                        ->whereIn('status', $names)
                        ->whereIn(
                            'id',
                            function (QueryBuilder $query) {
                                $query
                                    ->select(DB::raw('max(id)'))
                                    ->from($this->getStatusTableName())
                                    ->where('model_type', $this->getStatusModelType())
                                    ->groupBy('model_id');
                            }
                        );
                }
            );
    }

    /**
     * @param string|array $names
     *
     * @return void
     **/
    public function scopeOtherCurrentStatus(Builder $builder, ...$names)
    {
        $names = is_array($names) ? Arr::flatten($names) : func_get_args();
        $builder
            ->whereHas(
                'statuses',
                function (Builder $query) use ($names) {
                    $query
                        ->whereNotIn('status', $names)
                        ->whereIn(
                            'id',
                            function (QueryBuilder $query) use ($names) {
                                $query
                                    ->select(DB::raw('max(id)'))
                                    ->from($this->getStatusTableName())
                                    ->where('model_type', $this->getStatusModelType())
                                    ->groupBy( 'model_id');
                            }
                        );
                }
            )
            ->orWhereDoesntHave('statuses');
    }

    public function forceSetStatus(string $status, ?string $note = null): self
    {
        $oldStatus = $this->latestStatus();

        $newStatus = $this->statuses()->create([
            'status'   => $status,
            'note' => $note,
        ]);

        event(new StatusUpdated($oldStatus, $newStatus, $this));

        return $this;
    }

    protected function getStatusTableName(): string
    {
        $modelClass = config('nova-resource-status.status_model');

        return (new $modelClass)->getTable();
    }

    protected function getStatusModelType(): string
    {
        return array_search(static::class, Relation::morphMap()) ?: static::class;
    }
}
