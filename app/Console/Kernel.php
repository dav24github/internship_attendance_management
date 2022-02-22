<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('faltas:update'); //->hourlyAt(17); todos los dia a las 17:00 | ->twiceDaily(1, 13);	Todos los dias a la 1:00 & 13:00. Ver https://laravel.com/docs/7.x/scheduling
        // Instruciones para para Linux:
        //      0. descomentar el metodo hourlyAt,twiceDaily,etc
        //      1. Ejecutar: crontab -e
        //      2. Apretar la tecla "I" => * * * * * php /PATH-DEL-PROYECTO/artisan schedule:run >> /dev/null 2>&1
        //      3. Esc
        //      4. wq
        //      5. Verificar el crontab con: crontab -l
        //      6. php artisan schedule:run
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