@extends('master')

@section('content')
@foreach ($contents as $content)
  <?php
    switch($content->ofType($content->def_id)) {
      case 'cartoon':
        echo HTML::image(route('home') . '/images/cartoons/' . $content->content . '.svg', 
                    $content->wrapper_id,
                    ['id'=>$content->wrapper_id, 'class'=>'cartoon ' . $content->wrapper_class]);
        break;
      case 'paragraph':  
        echo '<div id="' . $content->wrapper_id . '" class="paragraph ' . $content->wrapper_class . '">' . nl2br($content->content) . '</div>';
        break;
      case 'code view':
        
        $output = '<pre id="' . $content->wrapper_id . '">';
        $code = preg_split('/[\n]/', htmlspecialchars($content->content));
        $even = false;
        $i = 1;
        foreach($code as $line) {
          $output .= '<div class="' . ($even ? 'codeEven' : 'codeOdd') . '">' . 
          $i . '  ' . $line . '</div>';
          $i++;
          $even = !$even;
        }
        $output .= '</pre>';
        echo $output;
        break;
    
        
    }
  
  ?>
@endforeach
@stop
