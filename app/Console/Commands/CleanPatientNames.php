<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Patient;

class CleanPatientNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patients:clean-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean patient names by removing extra spaces and trimming them.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Searching for patients with extra spaces in their names...');

        $patients = Patient::where('first_name', 'LIKE', '% %')
                           ->orWhere('last_name', 'LIKE', '% %')
                           ->get();

        $count = 0;

        foreach ($patients as $p) {
            $old_first = $p->first_name;
            $old_last = $p->last_name;
            
            // Remove multiple spaces and trim
            $new_first = trim(preg_replace('/\s+/', ' ', (string) $p->first_name));
            $new_last = trim(preg_replace('/\s+/', ' ', (string) $p->last_name));
            
            if ($old_first !== $new_first || $old_last !== $new_last) {
                $p->first_name = $new_first;
                $p->last_name = $new_last;
                $p->save();
                $count++;
            }
        }

        $this->info("Successfully cleaned {$count} patient records!");

        return Command::SUCCESS;
    }
}
