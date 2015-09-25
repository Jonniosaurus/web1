<?php

use Illuminate\Database\Seeder;

class defsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('defs')->insert([
        	['id'=>1, 'definition' => 'image'],
        	['id'=>2, 'definition' => 'paragraph'],
        ]);
    }
}
