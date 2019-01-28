<?php

namespace DigitalCloud\NovaResourceStatus;

use Illuminate\Database\Eloquent\Model;

class StatusObserver
{
    /**
     * Listening to any saved events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function saved(Model $model) {
        $statusField = method_exists($model, 'statusField') ? $model->statusField() : config('nova-resource-status.status-field');
        if($model->getOriginal($statusField) !== $model->getAttribute($statusField)) {
            $model->setStatus($model->getAttribute($statusField));
        }
    }
}
