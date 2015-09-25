<?php

use Illuminate\Database\Seeder;

class pagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('pages')->insert([
	        ['id'=>1, 'title'=>'home', 'type_id'=>1, 'slug'=>'home'],
	        ['id'=>2, 'title'=>'About Me', 'type_id'=>2, 'slug'=>'about-me'],
	        ['id'=>3, 'title'=>'HBC BACAS Project', 'type_id'=>3, 'slug'=>'hbc-bacas-project']
        ]);        
    }
}
