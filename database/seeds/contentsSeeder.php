<?php

use Illuminate\Database\Seeder;

class contentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contents')->insert([
	        ['id'=>1, 'wrapper_id'=>'testPageData', 'wrapper_class'=>'test', 'order'=>1, 'content'=>'Test Home Page Data: Lorem Ipsum Dolor...', 'page_id'=>1, 'def_id'=>2 ],
	        ['id'=>2, 'wrapper_id'=>'testPageData', 'wrapper_class'=>'test', 'order'=>1, 'content'=>'Test Page Data: Lorem Ipsum Dolor...', 'page_id'=>2, 'def_id'=>2 ],
	        ['id'=>3, 'wrapper_id'=>'testProjectData', 'wrapper_class'=>'test', 'order'=>1, 'content'=>'Test Project Data: Lorem Ipsum Dolor...', 'page_id'=>3, 'def_id'=>2 ]	        
        ]); 
    }
}
