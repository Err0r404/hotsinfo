<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:progress';

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
        $bar = $this->output->createProgressBar(100);
        $bar->setOverwrite(true);
    
        for($i = 0; $i < 100; $i++){
            $bar->advance();
            sleep(1);
        }
        
        $bar->finish();
    }
}
