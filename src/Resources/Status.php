<?php

namespace DigitalCloud\NovaResourceStatus\Resources;


use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Status extends Resource {

    public static $model = 'DigitalCloud\NovaResourceStatus\Models\Status';

    public static $displayInNavigation = false;

    public function fields(Request $request)
    {
        return [
            Text::make('Status', 'status'),
            Text::make('Date', 'date'),
        ];
    }

}
