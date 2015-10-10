<?php

namespace web1\Http\Controllers;

use Illuminate\Http\Request;

use web1\Http\Requests;
use web1\Http\Requests\CreateContentRequests as CreateContentRequests;
use web1\Http\Requests\UpdateContentRequests as UpdateContentRequests;
use web1\Http\Controllers\Controller;

use web1\Page;
use web1\Content;
use web1\Def;

use web1\Classes\FormBuilder;
use web1\Classes as my;

class pageController extends Controller
{
  
  public function __construct(Page $page, Content $content, Def $defs) {
  	$this->page = $page->ofType('default'); // only load standard pages.
  	$this->content = $content;
  	$this->definition = array();
    foreach ($defs->all() as $def) { $this->definition[$def->id] = $def->definition; }     
  	$this->middleware('admin', ['except' => ['index', 'show']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {    	
    return view('Pages.show')
      ->with('pages', $this->page->get())
      ->with('contents', $this->content->ofUri('home')->get());
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($slug, Page $home)
  {
   $page = ($slug == 'home') 
     ? $home::whereslug($slug)->first()
     : $this->page->whereslug($slug)->first();
    if ($page != null) {
      return view("Pages.create", [
        'page'=> $page,
        'forms'=> new FormBuilder(
          [
            'order' => 'text', 
            'content' => 'textarea', 
            'wrapper_id' => 'text', 
            'wrapper_class' => 'text',
            'def_id' => new my\FormAttributeBag('select', [ new my\FieldOptionset($this->definition)]), 
            'page_id' => new my\FormAttributeBag('hidden',[ new my\FieldValue($page->id)]),
            'id' => 'hidden'
          ],        
          ['page', $slug],
          'POST',
          'Create')
        ]);
     }
     return redirect()->route('page', $slug);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CreateContentRequests $request, $slug)
  {    
    $this->content->create($request->all());
    return redirect()->route('page.edit', $slug);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($slug, Def $definitions)
  {   	    
    if ($this->page->whereslug($slug)->first()) {
      return view("Pages.show")
        ->with('contents', $this->content->ofUri($slug)->get())
        ->with('definitions', $definitions::all())
        ->with('page', $this->page->whereslug($slug)->first());        
    }
    return redirect()->route('home');    
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($slug, Page $home, Def $def)
  { 
    if ($home::whereslug($slug)->first() != null) {
      $content = $this->content->ofUri($slug);            
      $page = ($slug == 'home') 
       ? $home::whereslug($slug)->first()
       : $this->page->whereslug($slug)->first();
      if ($page != null && $content->count() > 0) {
        return view("Pages.edit")
          ->with('contents', $content->get())
          ->with('page', $page)
          ->with('def', $def);
      } 
      return redirect()->route('home');
    }
  }

  public function editContent($slug, $content, Page $home) {
    $content = $this->content->ofUri($slug)->wherewrapperId($content)->first();        
    $page = ($slug == 'home') 
     ? $home::whereslug($slug)->first()
     : $this->page->whereslug($slug)->first();
    if ($page != null && $content != null) {
      return view("Pages.Edit.content", [
        'page'=> $page,
        'content' => $content,
        'form'=> new FormBuilder(
          [
            'order' => 'text', 
            'content' => 'textarea', 
            'wrapper_id' => 'disabled', 
            'wrapper_class' => 'text',
            'def_id' => new my\FormAttributeBag('select', [ new my\FieldOptionset($this->definition)]), 
            'page_id' => new my\FormAttributeBag('hidden', [ new my\FieldValue($page->id)]),
            'id' => 'hidden'
          ], 
          ['page', $slug],
          'PATCH',
          'Update'),      
        'delete' => new FormBuilder(
          [], 
          ['page', $content->id],
          'DELETE',
          'Delete'
        )]);
    } 
    return redirect()->route('home');
  }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContentRequests $request, $slug)
    {    
      $content = $this->content
        ->ofUri($slug)
        ->whereid($request->get('id'))->first();    
      $content
        ->fill($request->input())
        ->save();    
      return redirect(route('page.edit', $slug));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->content->whereid($id)->delete();
        return redirect('/');
    }
}
