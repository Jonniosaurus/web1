<?php

namespace web1\Http\Controllers;

use Illuminate\Http\Request;
use web1\Http\Requests;
use web1\Http\Controllers\Controller;
use web1\Http\Requests\CreateSampleRequests as CreateSampleRequests;
use web1\Http\Requests\UpdateSampleRequests as UpdateSampleRequests;
use web1\Page;
use web1\Type;

use web1\Classes\FormBuilder;
use web1\Classes as my;


class sampleController extends Controller
{
  public function __construct(Page $page, Type $type) {
  	$this->sample = $page->ofType('sample'); // only load standard pages.  	        
    $this->type = $type;
  	$this->middleware('admin', ['except' => ['show']]);
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('Samples.index') 
        ->with('samples', $this->sample->get());    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Samples.create', [
          'create' => new FormBuilder(
          [
            'title'=>'text',
            'slug'=>'text',
            'type_id'=>'hidden',
            'id'=>'hidden'              
          ],
          ['samples.store'],
          'POST',
          'Create'
          )          
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // retrieve sample id privately.
      $sample = $request->all();
      $sample['type_id'] = $this->type->wheretype('sample')->first()->id;
      // samples are stored in the page table as usual.
      Page::create($sample);
      return redirect()->route('samples');
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        switch($slug) {
          case 'form-sample':
            return view('Samples.test_form', [
              'test' => new FormBuilder(
                [
                  'fooGeneric' =>  new my\FormAttributeBag('text', [
                      new my\FieldValue('I am a generic field that has had content injected into it.')
                    ]),                    
                  'fooType' => new my\FormAttributeBag(
                    'select', [ 
                      new my\FieldOptionset(['foo'=>'Foo and Bar', 'foo1'=>'Bar and Foo']),
                      new my\FieldLabel('I am a dynamically built and labelled select box')
                    ]),
                  'fooTextArea' 
                    => new my\FormAttributeBag('textarea', 
                      [ new my\FieldClass('mce-enabled'), 
                        new my\FieldLabel('I Have a Custom Title and my custom class is "mce-enabled"')
                      ]
                    )
                ],
              ['samples.show', $slug], // Laravel dynamic URL route
              'GET', // Form submission type
              'Submit')]); // Form button label               
            break;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {      
      $sample = $this->sample->whereslug($slug)->first();
      return view('Samples.edit', [
        'sample' => $sample,
        'edit' => new FormBuilder(
          [
            'title'=>'text',
            'slug'=>'text',
            'id'=>'hidden',            
          ],
          ['samples.update', $slug],
          'PATCH',
          'Update'
          ),
        'delete' => new FormBuilder(
        [], 
        ['samples.destroy', $sample->id],
        'DELETE',
        'Delete'
      )]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSampleRequests $request)
    {      
      $sample = $this->sample
        ->whereid($request->get('id'))->first();
      
      $sample
        ->fill($request->input())
        ->save();
      return redirect(route('samples'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $this->sample->whereid($id)->delete();
      return redirect(route('samples'));
    }
}
