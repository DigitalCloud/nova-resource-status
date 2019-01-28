<?php

namespace DigitalCloud\NovaResourceStatus\Events;

use DigitalCloud\NovaResourceStatus\Models\Status;

use Illuminate\Database\Eloquent\Model;

class StatusUpdated
{
    /** @var \DigitalCloud\NovaResourceStatus\Models\Status|null */
    public $oldStatus;

    /** @var \DigitalCloud\NovaResourceStatus\Models\Status */
    public $newStatus;

    /** @var \Illuminate\Database\Eloquent\Model */
    public $model;

    public function __construct(?Status $oldStatus, Status $newStatus, Model $model) {
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->model = $model;
    }
}
