<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DateTime;
use DateTimeZone;
use App\Models\TblHotel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
       Commands\DemoCron::class,
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

    $datosHotel = TblHotel::find(1);
    $date = $datosHotel->hora_utc;
    $dt = new DateTime($date, new DateTimeZone('UTC'));
    // $dt->setTimezone(new DateTimeZone($datosHotel->zona_horaria));
    $hora = $dt->format('H:i');
    $schedule->command('test:cron')
               ->dailyAt($date);
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
