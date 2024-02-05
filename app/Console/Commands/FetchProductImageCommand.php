<?php

namespace App\Console\Commands;

use App\Models\BolAccount;
use App\Models\Product;
use App\Services\BOL\BolProductService;
use App\Services\BolService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FetchProductImageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product-image:fetch {bol_account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Product Images From Bol';

    protected $bol_account;
    protected $bol_product_service;


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return intSh
     */
    public function handle()
    {
        $this->bol_account = BolAccount::query()->find($this->argument('bol_account_id'));
        $this->bol_product_service = new BolProductService($this->bol_account);
        $this->bol_product_service->fetchImages();

    }
}
#4326470682468
#2078165248337
#8785259539649
#3836517211748
