<!DOCTYPE html>
<html>
  <head>
    <title>JonnyEdwards.net</title>    
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    @if (Auth::user() && Auth::user()->is_admin)  
    <script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>           
    <script>tinymce.init({
        selector:'.mce-enabled',          
        plugins: ["image link anchor table contextmenu paste code"]      
      });</script>
    @endif    
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">   
    <link rel="stylesheet" href="/css/dist/css/bootstrap-select.css">            
    <?php 
    // Build title css sheets.
    $myCSS = 'css/main/';
    $relRoute = 'css/main/';
    $media;
    
    foreach(File::allFiles($myCSS) as $file) {
      $media = false;
      $blockCSS = false;      
      if (BrowserDetect::browserFamily() != 'Internet Explorer' && 
        (BrowserDetect::isMobile() || BrowserDetect::isTablet())) {
        switch(str_replace('.css', '', ($file = $file->getFilename()))) {
          case 'tablet':
            $blockCSS = false;
            break;
          case 'mobile':
            $blockCSS = !BrowserDetect::isMobile();
            break;
          case 'noDotNet':            
          case 'small':           
          case 'smallMedium':                        
          case 'medium':                        
          case 'large':                        
          case 'small':                        
            $blockCSS = BrowserDetect::browserFamily() != 'Internet Explorer';   
            break;
        }  
      } else { //default to desktop view.
        switch(str_replace('.css', '', ($file = $file->getFilename()))) {
          case 'noDotNet':
            $media = 'screen and (max-width: 350px)';
            break;
          case 'small':
            $media = 'screen and (max-width: 500px)';
            break;
          case 'smallMedium':
            $media = 'screen and (min-width: 500px) and (max-width: 650px)';
            break;
          case 'medium':
            $media = 'screen and (min-width: 650px) and (max-width: 900px)';
            break;
          case 'large':
            $media = 'screen and (min-width: 900px)';
            break;
          case 'small':
            $media = 'only screen and (max-width: 1500px)';
            break;
          case 'mobile':
          case 'tablet':
            $blockCSS = true;      
            break;
        }
      }
      
      if (!$blockCSS)
        echo ($media)
          ? HTML::style($relRoute . $file, ['media' => $media])
          : HTML::style($relRoute . $file);
      }
     ?>
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
                         class="menuItem"
                         style="z-index: 10;">
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
            @if (!BrowserDetect::javaScriptSupport())
            <h4>Warning! This site requires JavaScript to behave correctly!</h4>
            @endif
            @yield('content')
            <p class='bottomMenu'>
              <?php $i = 0; ?>
              @foreach(
                DB::table('pages')
                  ->join('types', 'pages.type_id', '=', 'types.id')
                  ->where('type', '=', 'default')
                  ->get()
                as $page)
                @if ($i > 0)
                  |
                  @endif
                <a href="{{ route('page', [$page->slug]) }}" class="menuLink">                                                          
                  {{ $page->title }}                  
                  <?php $i = 1; ?>
                </a> 
              @endforeach
            </p><br/><br/>                        
            <div class="footer">
              <span class="left">Images &#169; 2015 JMEdwards</span>
              <span class="right"><a href="{{ route('page', [$page->slug]) }}">Contact</a></span>
            </div>
          </div> 
          
        </div>
      </div>       
    </div>

    <script type="text/javascript" src="{!! route('home') . '/scripts/Core.js' !!}"></script>    
    @if (Auth::user() && Auth::user()->is_admin)
    <script type="text/javascript" src="{!! route('home') . '/scripts/DropDown.js' !!}"></script>    
    @endif
  </body>
</html>
