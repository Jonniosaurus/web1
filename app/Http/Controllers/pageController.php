<?php

namespace web1\Http\Controllers;

use Illuminate\Http\Request;
use web1\Page;
use web1\Content;
use web1\Def;
use web1\Http\Requests;
use web1\Http\Controllers\Controller;
use web1\Classes\FormBuilder;
use Form;

class pageController extends Controller
{
  
  public function __construct(Page $page, Content $content, Def $defs) {
  	$this->page = $page->ofType('default'); // only load standard pages.
  	$this->content = $content;
  	$this->definition = array();
    foreach ($defs->all() as $def) { $this->definition[$def->id] = $def->definition; }     
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
  public function create($slug)
  {
    return view("Pages.create", [
      'page'=> $this->page->whereslug($slug)->first(),
      'forms'=> new FormBuilder(
        [
          'order' => 'text', 
          'content' => 'textarea', 
          'wrapper_id' => 'text', 
          'wrapper_class' => 'text',
          'def_id' => ['select' => $this->definition],
          'page_id' => ['hidden' => $this->page->whereslug($slug)->first()->id]
        ],        
        ['page', $slug],
        'POST',
        'Create')
      ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, $slug)
  {
    $this->content->create($request->all());
    return redirect()->route('page', $slug);
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
    return view("Pages.edit", [
      'page'=> $this->page->whereslug($slug)->first(),
      'contents' => $this->content->ofUri($slug)->get(),
      'forms'=> new FormBuilder(
        [
          'order' => 'text', 
          'content' => 'textarea', 
          'wrapper_id' => 'text', 
          'wrapper_class' => 'text',
          'def_id' => ['select' => $this->definition]
        ],        
        ['page', $slug],
        'PATCH',
        'Update')
      ]);  		
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
