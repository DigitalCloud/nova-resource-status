<?php

namespace DigitalCloud\NovaResourceStatus\Fields;

use Laravel\Nova\Fields\Field;

class Status extends Field {

    public $component = 'dce-status-field';

    public function statuses($statuses) {
        return $this->withMeta([
            'statuses' => collect($statuses ?? [])->map(function ($label, $value) {
                return ['label' => $label, 'value' => $value];
            })->values()->all(),
        ]);
    }

}
