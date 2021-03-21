<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSeedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-seed';

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
        $courses_handle = fopen(base_path().'/database/seeders/ub-courses.csv', 'r');
        $sections_handle = fopen(base_path().'/database/seeders/ub-sections.csv', 'r');

        return 0;
    }
}
