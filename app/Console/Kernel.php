<?php

namespace App\Console;

use App\Round;
use App\Qualifier;
use Carbon\Carbon;
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
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        /** 
         * Ranking system to calculate Ranks in the 
         * test 7 minutes after the test is completed.
         * This cron job will be called hourly.
         */
        $schedule->call(function(){
            $rounds = Round::where('end_date_time','<',Carbon::now()->subMinutes(7))->where('ranked',false)->get();
            foreach($rounds as $round)
            {
                $qualifiers = Qualifier::where('round_id',$round->id)->orderBy('score','desc')->orderBy('completion_time','asc')->get();
                $i = 1;
                foreach($qualifiers as $qualifier)
                {
                    $qualifier->rank = $i;
                    $qualifier->save();
                    $i++;
                }
                if($round->cutoff)
                {
                    $next_qualifiers = Qualifier::where('round_id',$round->id)->where('rank','<=',$round->cutoff)->get();
                    $next_round = $round->event->rounds->where('no',strval($round->no + 1))->first();
                    foreach($next_qualifiers as $next_qualifier)
                    {
                        $qualifier =  new Qualifier;
                        $qualifier->save();
                        $next_round->qualifiers()->save($qualifier);
                        $next_qualifier->student->qualifiers()->save($qualifier);
                    }
                }
                $round->ranked = true;
                $round->save();
            }
        })->everyMinute();
    }
}
