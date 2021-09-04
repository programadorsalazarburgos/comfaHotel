<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CuentaReserva;
use DateTime;
use DateTimeZone;

class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

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
     * @return mixed
     */
    public function handle()
    {
      
      $fechaActual = date('Y-m-d');
      $data = CuentaReserva::whereDate('fecha_hora', $fechaActual)->update(['estado' => true ]);
    }
}
