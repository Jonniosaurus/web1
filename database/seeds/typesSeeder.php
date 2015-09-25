<?php

use Illuminate\Database\Seeder;

class typesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->insert([
        	['id'=>1, 'type' => 'home'],
        	['id'=>2, 'type' => 'default'],
        	['id'=>3, 'type' => 'project']
        ]);
    }
}
