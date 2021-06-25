<?php

namespace MedSpec\LaravelPrinter\Renderers;

use MedSpec\LaravelPrinter\Contracts\Renderer as RendererContract;
use Illuminate\Support\Collection;

use PDF;


class FileRenderer extends Renderer implements RendererContract
{

    private $data;
    /**
     * The collection of documents.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $documents;

    /**
     * Create a new array renderer instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->documents = new Collection();
    }

    /**
     * {@inheritdoc}
     */
    public function render($document, $data): string
    {

        $this->data = $data;
        $path = $this->storePdf($document);

        return $path;
    }

    /**
     * Store the document.
     *
     * @return \Illuminate\Support\Collection
     */
    public function storePdf($document)
    {   
        // create base pdf object
        $pdf = PDF::loadHTML($document);
        
        // add header
        if(isset($this->data['header'])){
            $pdf->setOption('header-html', $this->data['header']);
        } else {
            create_default_pdf_header("");
        }

        // add footer
        if(isset($this->data['footer'])){
            $pdf->setOption('footer-html', $this->data['footer']);
        } else {
            create_default_pdf_footer();
        }

        // set options
        $pdf->setPaper('a4', 'portrait');

        $pdf->setOption('margin-top', '125px');
        $pdf->setOption('margin-right', '0px');
        $pdf->setOption('margin-left', '0px');
        $pdf->setOption('margin-bottom', '100px');
        $pdf->setOption('header-spacing', 15);
        $pdf->setOption('footer-spacing', 0);

        $path       = $this->data['path'] ?? "/"; 
        $filename   = $this->data['filename'] ?? "test.pdf" ;
        $full       = $path.$filename;
        $disk       = $this->data['disk'] ?? false;

        if($disk){
            \Storage::disk($disk)->put($full, $pdf->output());
        } else {
            \Storage::put($full, $pdf->output());
        }

        return $pdf->inline();
    }

    public function format(): string
    {
        return "application/pdf";
    }

    /**
     * Retrieve the collection of documents.
     *
     * @return \Illuminate\Support\Collection
     */
    public function documents()
    {
        return $this->documents;
    }

    /**
     * Clear all of the documents from the local collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function flush()
    {
        return $this->documents = new Collection();
    }
}
