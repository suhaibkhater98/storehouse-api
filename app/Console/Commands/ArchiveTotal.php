<?php

namespace App\Console\Commands;

use App\Http\Controllers\v1\DashboardsController;
use Illuminate\Console\Command;

class ArchiveTotal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:total';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $dashboardController = new DashboardsController();
        try {
            $dashboardController->calculateTotals();
            \Log::info("New Total has been calculated successfully.");
        } catch (\Exception $e){
            \Log::info('Ops!' . $e);
        }
    }
}
