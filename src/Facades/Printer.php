<?php

namespace MedSpec\LaravelPrinter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string print(\MedSpec\LaravelPrinter\Contracts\Printable|string|array $view, array $data = [])
 *
 * @see \MedSpec\LaravelPrinter\Printer
 */
class Printer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'printer';
    }
}
