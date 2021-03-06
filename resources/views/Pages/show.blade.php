@extends('master')

@section('content')
@foreach ($contents as $content)
  <?php
    switch($content->ofType($content->def_id)) {
      case 'cartoon':
        echo '<div>' . HTML::image(route('home') . '/images/cartoons/' . $content->content . '.svg', 
                    $content->wrapper_id,
                    ['id'=>$content->wrapper_id, 'class'=>'cartoon ' . $content->wrapper_class]) . '</div>';
        break;
      
      case 'paragraph':
      case 'paragraph raw':
        echo '<div id="' . $content->wrapper_id . '" class="paragraph ' . $content->wrapper_class . '">' . $content->content . '</div>';
        break;
      
      case 'code view':        
        $output = '<pre id="' . $content->wrapper_id . '">';
        $code = preg_split('/[\n]/', htmlspecialchars($content->content));
        $even = false;
        $i = 1;
        foreach($code as $line) { // generate stripes for code lines
          $output .= '<div class="' . ($even ? 'codeEven' : 'codeOdd') . '">' . 
          $i . '  ' . $line . '</div>';
          $i++;
          $even = !$even;
        }
        $output .= '</pre>';
        echo $output;
        break;
        
      case 'collapsible': // build collapsible component
      case 'collapsible raw':
        echo 
          '<div class="Collapsible">' .
            '<div class="collapser" id="' . $content->wrapper_id . '_collapser"><strong>Click to Expand</strong></div>' .
            '<div class="collapsee" id="' . $content->wrapper_id . '_collapsee">' .
              $content->content .
            '</div></div>';
        break;
            
      case 'image':
        echo '<div>' . 
                HTML::image(route('home') . '/images/content/' . $content->content, 
                  $content->wrapper_id,
                  ['id'=>$content->wrapper_id, 
                   'class'=>'image ' . $content->wrapper_class]) .
              '</div>';
        break;
      
      case 'scroll return':
        echo '<div class="scroll_return">' .
                $content->content .
                HTML::image(route('home') . '/images/scrollReturn.svg', 'scroll return button') .
             '</div>';
        break;
    } 
  
  ?>
@endforeach
@stop
