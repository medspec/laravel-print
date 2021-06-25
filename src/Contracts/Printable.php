<?php

namespace MedSpec\LaravelPrinter\Contracts;

interface Printable
{
    /**
     * Print the page using using the given printer.
     *
     * @param  \MedSpec\LaravelPrinter\Contracts\Printer  $printer
     * @return string
     */
    public function print(Printer $printer): string;
}
