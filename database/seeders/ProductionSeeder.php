<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        \App\Production::truncate();

        factory(\App\Production::class, 60)->create();
    }
}
