<?php

namespace App\Console;

use App\Models\Reservation;
use App\Mail\ReservationReminder;
use App\Mail\ReviewReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        $schedule->call(function () {
        Log::info('Reservation reminder sent.');
        $todayReservations = Reservation::whereDate('reservation_date', now())->get();
        foreach ($todayReservations as $reservation) {
        Mail::to($reservation->user->email)->send(new ReservationReminder($reservation));
        }
        })->dailyAt('11:59');
        $schedule->call(function () {
        Log::info('Review reminder sent');
        $yesterdayReservations = Reservation::whereDate('reservation_date', now()->subDay())->where('status', '予約済み')->get();
        foreach ($yesterdayReservations as $reservation) {
        Mail::to($reservation->user->email)->send(new ReviewReminder($reservation));}
        })->dailyAt('10:00');
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
