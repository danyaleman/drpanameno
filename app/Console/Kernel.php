<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
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
        // $schedule->command('inspire')->hourly();

        /**
         * Comando de recordatorios de vacunas.
         * Se ejecuta todos los días a las 8:00 AM.
         * Busca vacunas pendientes para los próximos 3 días y genera
         * notificaciones internas (campana) y correos simulados a pacientes.
         *
         * Para activar el scheduler en producción, agregar al cron del servidor:
         * * * * * * cd /ruta/del/proyecto && php artisan schedule:run >> /dev/null 2>&1
         */
        $schedule->command('vaccines:check-reminders')
                 ->dailyAt('08:00')
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/vaccine-reminders.log'));
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
