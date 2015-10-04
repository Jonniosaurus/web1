<!DOCTYPE html>
<html>
  <head>
    <title>JonnyEdwards.net</title>        		        
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{!! route('home') . '/scripts/core.js' !!}"></script>
    <script src="{!! route('home') . '/css/dist/js/bootstrap-select.js' !!}"></script>      


    <link rel="stylesheet" href="/css/dist/css/bootstrap-select.css">            
    <?php 
    // Build title css sheets.
    $titleCSS = public_path() . '/css/title/';
    $relRoute = route('home') . '/css/title/';
    $media;
    foreach(File::allFiles($titleCSS) as $file) {
      $media = false;
      switch(str_replace('.css', '', ($file = $file->getFilename()))) {
        case 'noDotNet':
          $media = 'screen and (max-width: 350px)';
          break;
        case 'small':
          $media = 'screen and (max-width: 450px)';
          break;
        case 'medium':
          $media = 'screen and (min-width: 450px) and (max-width: 750px)';
          break;
        case 'large':
          $media = 'screen and (min-width: 750px)';
          break;
      }

      echo ($media)
        ? HTML::style($relRoute . $file, ['media' => $media])
        : HTML::style($relRoute . $file);
    } ?>
  </head>
  <body onload="Action.load()">
    <div id="header">
      <div id="dynamicWrapper">   
        <div id="mainWrapper">
          <div id="wrapper">
            <div id="webBackWrapper"></div>
            <div id="Jonny" class="letterSet">
              <!-- encapsulate images in an anchor tag -->
              <a href="{!! route('home') !!}" class="homeLink">              
                <!-- Render "JONNY" -->
                @foreach(['J', 'O', 'N1', 'N2', 'Y'] as $letter)            
                  {!! HTML::image(
                        route('home') . '/images/letter' . $letter . '.svg', 
                        'The Letter ' . $letter . ' of the animated header', 
                        ['id'=>$letter, 'class'=>($letter == 'J' ? 'CapsheadLetter' : 'headLetter jonny')]
                        ) 
                  !!}
                @endforeach                                                    
                <img src="{!! route('home') . '/images/webBack.svg' !!}" id="webBack"></img>        
              </a>     
            </div>        
          </div>            
          <div id="Edwards" class="letterSet">
            <a href="{!! route('home') !!}" class="homeLink">
              <!-- Render "EDWARDS" -->
              @foreach(['E', 'D1', 'W', 'A', 'R', 'D2', 'S'] as $letter)            
                {!! HTML::image(
                      route('home') . '/images/letter' . $letter . '.svg', 
                      'The Letter ' . $letter . ' of the animated header', 
                      ['id'=>$letter, 'class'=>($letter == 'E' ? 'CapsheadLetter' : 'headLetter jonny')]
                      ) !!}
              @endforeach              
              <img src="{!! route('home') . '/images/dotNet.svg' !!}" id="dotNet"></img>
            </a>
          </div>
          
          <div id="contentWrapper">          
            <div id="menuWrapper">
              <div id="menu">      
                @foreach(
                  DB::table('pages')
                    ->join('types', 'pages.type_id', '=', 'types.id')
                    ->where('type', '=', 'default')
                    ->get()
                  as $page)
                  {!! HTML::link(
                    route('page', [$page->slug]),
                    $page->title,
                    ['id'=>str_replace(" ", "_", $page->title), 'class'=>'menuItem']
                  )  !!}                
                @endforeach
              </div>              
            </div>     
            <div id="pageBody">   
              <div id="pageContent">     
              @yield('content')
              </div>
            </div>      
          </div>             
        </div>         
      </div>  
    </div>
  </body>
</html>
