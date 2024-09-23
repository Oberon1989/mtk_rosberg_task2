<?php

namespace App\Jobs;

use App\Models\Character;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\NoReturn;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportCharacterToExel implements ShouldQueue
{
    use Queueable;

  protected $character;

    public function __construct($character)
    {
        $this->character = $character;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->exportCharacterToExcel($this->character);
    }


}
