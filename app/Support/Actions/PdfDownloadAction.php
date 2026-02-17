<?php

namespace App\Support\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Closure;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Livewire;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class PdfDownloadAction
{
    public function __construct(
        protected string $viewName,
        protected string|Closure $fileName
    ) {}

    public function getViewName()
    {
        return $this->viewName;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public static function make(string $viewName, string|Closure $fileName)
    {
        return (new static($viewName, $fileName));
    }

    public function download()
    {
        return Action::make('download_pdf')
            ->icon(Heroicon::OutlinedDocumentArrowDown)
            ->color('info')
            ->action(function (Model $record, Component $livewire) {

                if ($this->getFileName() instanceof Closure) {
                    $this->fileName = $this->getFileName()($record);
                }

                return response()->streamDownload(function () use ($record) {
                    echo Pdf::loadView($this->getViewName(), ['record' => $record])
                        ->setOption('defaultFont', 'DejaVu Sans')
                        ->output();
                }, $this->getFileName() . '.pdf');
            });
    }

    public function print()
    {
        return Action::make('print_pdf')
            ->icon(Heroicon::OutlinedDocumentDuplicate)
            ->color('success')
            ->action(function (Model $record, Component $livewire) {

                $html = view($this->getViewName(), [
                    'record' => $record
                ])->render();

                $pdfBase64 = base64_encode(
                    Pdf::loadHTML($html)
                        ->setOption('defaultFont', 'DejaVu Sans')
                        ->setPaper('A4', 'portrait')
                        ->output()
                );

                if ($this->getFileName() instanceof Closure) {
                    $this->fileName = $this->getFileName()($record);
                }

                // One clean line — all logic lives in pdf-print.js
                $livewire->js("window.printPdf('{$pdfBase64}', 'Stock Transfer {$this->getFileName()}')");
            });
    }
}
