<?php

use Illuminate\Database\Seeder;

class IndemnityFileColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Job::whereNotNull('indemnity_file')->chunk(100, function ($jobs) {
            foreach($jobs as $job) {
                if(!is_array(json_decode($job->indemnity_file))) {
                    $job->update(['indemnity_file' => json_encode([$job->indemnity_file])]);
                }
            }
        });
    }
}
