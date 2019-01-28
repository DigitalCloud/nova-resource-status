<?php

namespace DigitalCloud\NovaResourceStatus\Models;

use DigitalCloud\Blameable\Traits\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Status extends Model
{
    use Blameable;

    protected $guarded = [];

    protected $table = 'statuses';

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getDateAttribute() {
        return $this->created_at? $this->created_at->format('Y-m-d H:i') : '';
    }

}
