<?php

namespace App\Providers;

use App\Actions;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public array $bindings = [
        Actions\Product\Contracts\SavesProduct::class => Actions\Product\SaveProduct::class,
    ];
}
