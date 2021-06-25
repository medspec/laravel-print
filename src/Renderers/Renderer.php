<?php

namespace MedSpec\LaravelPrinter\Renderers;

use MedSpec\LaravelPrinter\Contracts\Renderer as RendererContract;

abstract class Renderer implements RendererContract
{
    /**
     * {@inheritdoc}
     */
    public function format(): string
    {
        return 'text/html';
    }
}
