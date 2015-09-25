<?php

namespace web1\Http\Controllers;

use Illuminate\Http\Request;
use web1\Page;
use web1\Content;
use web1\Def;
use web1\Http\Requests;
use web1\Http\Controllers\Controller;
use Form;

class pageController extends Controller
{
	public function __construct(Page $page, Content $content) {
		$this->page = $page->ofType('default'); // only load standard pages.
		$this->content = $content;
		 
		//web1\Page::with('contents')->wheretitle("About Me")->get();		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    	
        return view('Pages.index')->with('pages', $this->page->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {   	
    	return view("Pages.show")
    		->with('contents', $this->content->ofUri($slug)->get())
    		->with('page', $this->page->whereslug($slug)->first());        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug, Def $defs)
    {   	    	
    	
    	$definitions=array();
    	foreach ($defs->all() as $def) {
    		$definitions[$def->id] = $def->definition;
    	}
    	
    	
    	$fields = [
    		'order' => 'text', 
    		'content' => 'textarea', 
    		'wrapper_id' => 'text', 
    		'wrapper_class' => 'text',
    		'def_id' => 'select'
    		];
    	
    	$array = array();
    	$i = 0;
    	foreach($fields as $key => $value) {
	    	switch($value){
	    		case 'text':
					 $array[$key] = Form::text($key,null, ['class'=> 'form-control', 'id' =>  'Form_' . $key . $i ]);
					 break; 			
	    		case 'textarea':
	    			$array[$key] = Form::textarea($key,null, ['class'=> 'form-control', 'id' =>  'Form_' . $key . $i ]);
	    			break;
	    		case 'select':
	    			$array[$key] = Form::select($key,$defs, $i,['class'=> 'selectpicker', 'id' =>  $key . '_' . $i ]);
	    			break;
	    	}
	    	$i++;
    	}
    	
        return view("Pages.edit", 
        	['page'=> $this->page->whereslug($slug)->first(), 
        	'data' => $this->content->ofUri($slug)->get(),
        	'defs' => $definitions,
        	'fields'=> $array]);
        	    		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
    	$content = $this->content
    		->ofUri($slug)
    		->whereid($request->get('id'))->first();    
        $content
        	->fill($request->input())
        	->save();    
    	return redirect('/' . $slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
