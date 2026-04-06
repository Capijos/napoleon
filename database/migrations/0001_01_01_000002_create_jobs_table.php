<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsMongoSeeder extends Seeder
{
    public function run(): void
    {
        // Jobs
        DB::collection('jobs')->insert([
            'queue' => 'default',
            'payload' => json_encode(['job' => 'ExampleJob', 'data' => []]),
            'attempts' => 0,
            'reserved_at' => null,
            'available_at' => time(),
            'created_at' => time(),
        ]);

        // Job Batches
        DB::collection('job_batches')->insert([
            'id' => 'batch_1',
            'name' => 'Example Batch',
            'total_jobs' => 1,
            'pending_jobs' => 1,
            'failed_jobs' => 0,
            'failed_job_ids' => [],
            'options' => null,
            'cancelled_at' => null,
            'created_at' => time(),
            'finished_at' => null,
        ]);

        // Failed Jobs
        DB::collection('failed_jobs')->insert([
            'uuid' => 'uuid_1',
            'connection' => 'mongodb',
            'queue' => 'default',
            'payload' => json_encode(['job' => 'ExampleJob', 'data' => []]),
            'exception' => '',
            'failed_at' => now(),
        ]);
    }
}