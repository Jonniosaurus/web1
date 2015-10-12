<!DOCTYPE html>
<html>
  <head>
    <title>JonnyEdwards.net</title>    
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
    
    <script>tinymce.init({
      selector:'textarea',
      @if (Auth::user() && Auth::user()->is_admin)      
        plugins: ["image link anchor table contextmenu paste code"]
      @endif
      });</script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">   
    <link rel="stylesheet" href="/css/dist/css/bootstrap-select.css">            
    <?php 
    // Build title css sheets.
    $myCSS = public_path() . '/css/main/';
    $relRoute = route('home') . '/css/main/';
    $media;
    foreach(File::allFiles($myCSS) as $file) {
      $media = false;
      switch(str_replace('.css', '', ($file = $file->getFilename()))) {
        case 'noDotNet':
          $media = 'screen and (max-width: 350px)';
          break;
        case 'small':
          $media = 'screen and (max-width: 450px)';
          break;        
        case 'medium':
          $media = 'screen and (min-width: 550px) and (max-width: 850px)';
          break;
        case 'smallMedium':
          $media = 'screen and (min-width: 450px) and (max-width: 550px)';
          break;
        case 'large':
          $media = 'screen and (min-width: 850px)';
          break;
      }

      echo ($media)
        ? HTML::style($relRoute . $file, ['media' => $media])
        : HTML::style($relRoute . $file);
    } ?>
  </head>
  <body onload="Action.load()">           
      <div id="PageWrapper">   
        <div id="mainWrapper">
          <div id="titleWrapper">
          <div id="wrapper">
            <div id="WebBackWrapper"></div>
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
              <img src="{!! route('home') . '/images/dotNet.svg' !!}" id="dotNet"/>
            </a>
          </div>
          <img src="{!! route('home') . '/images/webBack.svg' !!}" id="WebBack" />
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
                <a href="{{ route('page', [$page->slug]) }}" class="menuLink">
                    <div id="{{ str_replace(" ", "_", $page->title) }}"
                         class="menuItem">
                      {{ $page->title }}                              
                    </div>                       
                </a>                
              @endforeach
            </div>  
            <button type="button" class="navbar-toggle" id="navMenu">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>                        
            </button>
          </div>                                            
          <div id="pageBody">                           
            @yield('content')                                               
          </div> 
        </div>
      </div>       
    </div>  
    <script type="text/javascript" src="{!! route('home') . '/scripts/core.js' !!}"></script>    
  </body>
</html>
