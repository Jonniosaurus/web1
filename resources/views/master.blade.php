<!DOCTYPE html>
<html>
  <head>
      <title>JonnyEdwards.net</title>        		        
      <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="scripts/core.js"></script>
      <link rel="stylesheet" href="/css/dist/css/bootstrap-select.css">
      <script src="/css/dist/js/bootstrap-select.js"></script>
              <link rel="stylesheet" 
        type="text/css" 
        href="css/title/default.css" />
      <link rel="stylesheet" 
        media="screen and (max-width: 350px)"
        type="text/css" 
        href="css/title/noDotNet.css" />
      <link rel="stylesheet" 
        media="screen and (max-width: 450px)"
        type="text/css" 
        href="css/title/small.css" />
      <link rel="stylesheet" 
        media="screen and (min-width: 451px) and (max-width: 750px)"
        type="text/css" 
        href="css/title/medium.css" />
      <link rel="stylesheet" 
        media="screen and (min-width: 751px)"
        type="text/css" 
        href="css/title/large.css" />  
  </head>
  <body>
    <div id="header">
      <div id="dynamicWrapper">   
        <div id="mainWrapper">
          <div id="wrapper">
            <div id="webBackWrapper"></div>
            <div id="Jonny">
              <a href="about:blank" class="homeLink">
                <img src="images/letterJ.svg" id="J" alt="The Letter J of the animated header" class="CapsheadLetter " />   
                <img src="images/letterO.svg" id="O" alt="The Letter O of the animated header" class="headLetter jonny" />
                <img src="images/letterN1.svg" id="N1" alt="A Letter N of the animated header" class="headLetter jonny" />
                <img src="images/letterN2.svg" id="N2" alt="A Letter N of the animated header" class="headLetter jonny" />
                <img src="images/letterY.svg" id="Y" alt="The Letter Y of the animated header" class="headLetter jonny" />
                <img src="images/webBack.svg" id="webBack"></img>        
              </a>     
            </div>        
          </div>            
          <div id="Edwards">
            <a href="about:blank" class="homeLink">
              <img src="images/letterE.svg" id="E" alt="The Letter E of the animated header" class="CapsheadLetter" />
              <img src="images/letterD1.svg" id="D1" alt="A Letter D of the animated header" class="headLetter edwards" />
              <img src="images/letterW.svg" id="W" alt="The Letter W of the animated header" class="headLetter edwards" />
              <img src="images/letterA.svg" id="A" alt="The Letter A of the animated header" class="headLetter edwards" />
              <img src="images/letterR.svg" id="R" alt="The Letter R of the animated header" class="headLetter edwards" />
              <img src="images/letterD2.svg" id="D2" alt="A Letter D of the animated header" class="headLetter edwards" />
              <img src="images/letterS.svg" id="S" alt="The Letter S of the animated header" class="headLetter edwards" />    
              <img src="images/dotNet.svg" id="dotNet"></img>
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
                ) . ' . ' !!}
                
              @endforeach                            
              <div id="menuUnderstroke"></div>
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
