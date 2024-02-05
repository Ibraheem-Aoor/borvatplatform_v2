<?php

namespace App\Console;

use App\Console\Commands\CacheBorvatCouponCodes;
use App\Console\Commands\ClearePdfDirectoryCommand;
use App\Console\Commands\ConfirmBorvatOrdersCommand;
use App\Console\Commands\FetchBorvatOrdersCommand;
use App\Console\Commands\FetchProductImageCommand;
use App\Console\Commands\OrderFetch;
use App\Console\Commands\SaveShipmentLabelCommand;
use App\Console\Commands\SendCouponsToBorvatCommand;
use App\Console\Commands\ShipmentFetch;
use App\Jobs\OrderFetchJob;
use App\Models\BolAccount;
use App\Traits\BoolApiTrait;
use App\Traits\OrderTrait;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{


    protected $command = [
        OrderFetch::class,
        ShipmentFetch::class,
        SaveShipmentLabelCommand::class,
        FetchProductImageCommand::class,
        ClearePdfDirectoryCommand::class,
    ];
    //     $schedule->command('shipments:get')->everyMinute()->runInBackground();

    // }


    private function scheduleCommand(Schedule $schedule, $command, $duration)
    {
        $schedule->command($command)
            ->$duration()
                ->runInBackground();
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $bol_accounts = BolAccount::get();
        foreach ($bol_accounts as $bol_account) {
            $this->scheduleCommand($schedule, "shipments:get {$bol_account->id}", "everyMinute");
            $this->scheduleCommand($schedule, "shipment-label:get {$bol_account->id}", "everyMinute");
            $this->scheduleCommand($schedule, "product-image:fetch {$bol_account->id}", "everyMinute");
        }
        $schedule->command('pdf-dir:clear')->everyFiveMinutes()->runInBackground();
        $schedule->command('backup:run')->twiceDaily(0, 12)->runInBackground();

    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
