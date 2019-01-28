<?php

return [
    /*
     * The class name of the status model that holds all statuses.
     * The model must be or extend `DigitalCloud\NovaResourceStatus\Models\Status`.
     */
    'status_model' => DigitalCloud\NovaResourceStatus\Models\Status::class,

    /*
     * The default name of the status attribute if not declared in model.
     */
    'status-field' => 'status'
];
