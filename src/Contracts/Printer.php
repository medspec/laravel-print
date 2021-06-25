<?php

namespace MedSpec\LaravelPrinter\Contracts;

interface Printer
{
    /**
     * Print a document to a string.
     *
     * @param  \MedSpec\LaravelPrinter\Contracts\Printable|string  $view
     * @param  array  $data
     * @return string
     */
    public function print($view, array $data = []): string;
}
