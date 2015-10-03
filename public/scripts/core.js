// - - - - - - - - - - - 
// GLOBAL OBJECT LITERAL
var xmlhttp;
var _GET = {
  last:undefined,
  current:undefined,
  menu: ["aboutMe", "expertise", "employment", "education"],
  id : function(id) { return document.getElementById(id); },
  ajax : function(get, callback) {
    xmlhttp = (window.XMLHttpRequest) 
    ? new XMLHttpRequest() // code for IE7+, Firefox, Chrome, Opera, Safari
    : new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5    
    xmlhttp.onreadystatechange=function() { 
      if (xmlhttp.readyState==4 && xmlhttp.status==200) { 
        _GET.id("pageContent").innerHTML=xmlhttp.responseText; 
        callback(); } else return; }
    xmlhttp.open("GET","page.php?get=" + get,true);
    xmlhttp.send();
  },
}
// - - - - - - - - - - -
// ANIMATION HANDLERS
function animateIO(opts) {
  var start = new Date  
  var out = true;
  var id = setInterval(function() {
    var timePassed = new Date - start
    var progress = timePassed / opts.duration
    if (progress > 1) progress = 1        
    var delta = opts.delta(progress)
    opts.step(delta, out)
    
    if (progress == 1) {
      out = !out
      progress = 0
      start = new Date;
    }
  }, opts.delay || 10) 
}
function animate(opts, callback) {
    var start = new Date  
  var goingDown = true;
  var id = setInterval(function() {
    var timePassed = new Date - start
    var progress = timePassed / opts.duration
    if (progress > 1) progress = 1        
    var delta = opts.delta(progress)
    opts.step(delta)    
    if (progress == 1) {      
      clearInterval(id);
      if (callback) callback();
    }
  }, opts.delay || 10)   
}
// - - - - - - - - - - -
// ONLOAD()
(function() { 
  var i;
  var arrA = ["J", "N2", "D1", "R"];
  var toA = 10; // TODO refactor based on page width  
  // animation sequence A
    animateIO({       
      delay: 0.001,
      duration: 1400, // 1 sec by default
      delta: function d(progress) {return progress;},
      step: function(delta, goingDown) {
        for (i in arrA)
          if (_GET.id(arrA[i]))
            _GET.id(arrA[i]).style.top = goingDown ? toA*delta + "px" : toA-(toA*delta) + "px";
    }});
  // animation sequence B
  var arrB = [ "O", "Y", "W", "D2" ];
  var toB = 10;
    animateIO({       
      delay:  0.001,
      duration: 1300, // 1 sec by default
      delta: function d(progress) {return progress;},
      step: function(delta, goingDown) {
        for (i in arrB)
          if (_GET.id(arrB[i]))
            _GET.id(arrB[i]).style.top = !goingDown ? toB*delta + "px" : toB-(toB*delta) + "px";
    }});
    
  // animation sequence C
  var arrC = [ "N1", "E", "A", "S"];
  var toC = 9;
    animateIO({       
      delay:  0.001,
      duration: 1100, // 1 sec by default
      delta: function d(progress) {return progress;},
      step: function(delta, goingDown) {
        for (i in arrC) 
          if (_GET.id(arrC[i]))
            _GET.id(arrC[i]).style.top = !goingDown ? toC*delta + "px" : toC-(toC*delta) + "px";
        
    }});        
  }
)()
var last;
var current;
// - - - - - - - - - - -
// MENU HANDLER
function loadPage(name)
{ 
//  if (_GET.id(name)) return; // prevent the same page reloading.
//  else {    
//    var menu = ["aboutMe", "expertise", "employment", "education"];
//    for (var i in _GET.menu) 
//      _GET.id(_GET.menu[i] + "Link").disabled = true;      
//    
//    _GET.current = name; // name would lose scope so we need to keep it in a global.
//    if (_GET.last) {      
//      animate({
//          delay: 0.3,
//          duration: 300, // 1 sec by default
//          delta: function linear(progress) { return progress; },
//          step: function(delta) { _GET.id(_GET.last).style.left = -355+(-850*delta) + "px"; }
//          }, function() { 
//            _GET.last = undefined; 
//            loadPage(_GET.current); 
//          });         
//    } else {
//      _GET.ajax(
//        _GET.current, 
//        function() {              
//          animate({
//            delay: 0.3,
//            duration: 600, // 1 sec by default
//            delta: function bounce(progress) {
//              progress = 1-progress;
//              for(var a = 0, b = 1, result; 1; a += b, b /= 2) 
//                if (progress >= (7 - 4 * a) / 11) 
//                  return -Math.pow((11 - 6 * a - 11 * progress) / 4, 2) + Math.pow(b, 2);
//            },
//            step: function(delta) {
//              try {
//                _GET.id(_GET.current).style.left = (-850* delta)-355 + "px";
//              } catch (ex) { return; }
//            }      
//          }, function() { 
//            for (var i in _GET.menu) 
//              _GET.id(_GET.menu[i] + "Link").disabled = false;
//            _GET.last = _GET.current; });
//        });
//    }      
//  }
}


