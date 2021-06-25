<?php

namespace MedSpec\LaravelPrinter\Contracts;

interface Renderer
{
    /**
     * The mime type of the rendered document.
     *
     * @return string
     */
    public function format(): string;

    /**
     * Render a document.
     *
     * @param  string  $document
     * @return string
     */
    public function render($document, $data): string;
}
