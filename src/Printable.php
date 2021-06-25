<?php

namespace MedSpec\LaravelPrinter;

use MedSpec\LaravelPrinter\Contracts\Printable as PrintableContract;
use MedSpec\LaravelPrinter\Contracts\Printer;
use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Traits\Localizable;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Response;

class Printable implements PrintableContract, Renderable, Responsable
{
    use Localizable;

    /**
     * The locale of the message.
     *
     * @var string
     */
    public $locale;

    /**
     * The view to use for the message.
     *
     * @var string
     */
    public $view;

    /**
     * The view data for the message.
     *
     * @var array
     */
    public $viewData = [];

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {

        return $this->withLocale($this->locale, function () {
            Container::getInstance()->call([$this, 'build']);

            return Container::getInstance()->make('printer')->render(
                $this->view, $this->buildViewData()
            );
        });
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function store()
    {   

        $printer = Container::getInstance()->make('printer');
        $this->print($printer);

        return  true;
    }

    /**
     * Print the page using using the given printer.
     *
     * @param  \MedSpec\LaravelPrinter\Contracts\Printer  $printer
     * @return string
     */
    public function print(Printer $printer): string
    {

        return $this->withLocale($this->locale, function () use ($printer) {
            Container::getInstance()->call([$this, 'build']);

            return $printer->print($this->view, $this->buildViewData());
        });

    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {

        $printer = Container::getInstance()->make('printer');


        return new Response($this->print($printer), Response::HTTP_OK, [
            'Content-Type' => $printer->format(),
        ]);
    }

    /**
     * Build the view data for the message.
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function buildViewData()
    {
        $data = $this->viewData;

        foreach ((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->getDeclaringClass()->getName() !== self::class) {
                $data[$property->getName()] = $property->getValue($this);
            }
        }

        return $data;
    }

    /**
     * Set the view and view data for the message.
     *
     * @param  string  $view
     * @param  array  $data
     * @return $this
     */
    public function view($view, array $data = [])
    {
        $this->view = $view;
        $this->viewData = array_merge($this->viewData, $data);

        return $this;
    }

    /**
    * Set the header of the message.
    *
    * @param  string  $header
    * @return $this
    */
    public function header($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
    * Set the footer of the message.
    *
    * @param  string  $footer
    * @return $this
    */
    public function footer($footer)
    {
        $this->footer = $footer;

        return $this;
    }


    /**
    * Set the disk of the message.
    *
    * @param  string  $disk
    * @return $this
    */
    public function disk($disk)
    {
        $this->disk = $disk;

        return $this;
    }

    /**
    * Set the path of the message.
    *
    * @param  string  $path
    * @return $this
    */
    public function path($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
    * Set the filename of the message.
    *
    * @param  string  $filename
    * @return $this
    */
    public function filename($filename)
    {
        $this->filename = $filename;

        return $this;
    }


    /**
     * Set the view data for the message.
     *
     * @param  string|array  $key
     * @param  mixed   $value
     * @return $this
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->viewData = array_merge($this->viewData, $key);
        } else {
            $this->viewData[$key] = $value;
        }

        return $this;
    }
}
