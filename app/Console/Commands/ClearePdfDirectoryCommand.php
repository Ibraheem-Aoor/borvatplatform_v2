<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearePdfDirectoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf-dir:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear The public Pdf Dir from temp pdf files';


    public function __construct()
    {
        parent::__construct();
    }




    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pdf_dir = public_path('storage/temp_pdf/');
        $files  = array_diff(scandir($pdf_dir), ['.' , '..']);
        foreach($files as $file)
        {
            unlink($pdf_dir.$file);
        }
        info('PDF Dir Cleared successfully');
    }
}
