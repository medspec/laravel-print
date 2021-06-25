<?php

namespace MedSpec\LaravelPrinter;

use MedSpec\LaravelPrinter\Contracts\Printable;
use MedSpec\LaravelPrinter\Contracts\Printer as PrinterContract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\Factory;

class Printer implements PrinterContract
{
    /**
     * The view factory instance.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $views;

    /**
     * The renderer instance.
     *
     * @var \MedSpec\LaravelPrinter\RendererManager
     */
    protected $renderer;

    /**
     * Create a new PrintManager instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $views
     * @param  \MedSpec\LaravelPrinter\RendererManager  $printerManager
     * @return void
     */
    public function __construct(Factory $views, RendererManager $printerManager)
    {
        $this->views = $views;
        $this->renderer = $printerManager;
    }

    /**
     * Render the given view.
     *
     * @param  string|array  $view
     * @param  array  $data
     * @return string
     */
    public function render($view, array $data = []): string
    {
        return $this->views->make($view, $data)->render();
    }

    /**
     * Print a document to a string.
     *
     * @param  \MedSpec\LaravelPrinter\Contracts\Printable|string  $view
     * @param  array  $data
     * @return string
     */
    public function print($view, array $data = []): string
    {
        if ($view instanceof Printable) {
            return $this->printPrintable($view);
        }
        
        $document = $this->render($view, $data);

        return $this->renderer->render($document, $data);
    }


    /**
     * Print the given printable.
     *
     * @param  \MedSpec\LaravelPrinter\Contracts\Printable  $printable
     * @return mixed
     */
    protected function printPrintable(Printable $printable)
    {
        return $printable->print($this);
    }
    /**
     * The mime type of the rendered document.
     *
     * @return string
     */
    public function format(): string
    {
        return $this->renderer->format();
    }
}
