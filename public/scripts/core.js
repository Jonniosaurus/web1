// public protected class Global
var Global = {  
  get: {
    id : function(id) { return document.getElementById(id); },
    class: function(className) { return document.getElementsByClassName(className); },
    ajax : function(get, callback) {
      var xmlhttp = (window.XMLHttpRequest) 
      ? new XMLHttpRequest() // code for IE7+, Firefox, Chrome, Opera, Safari
      : new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5    
      xmlhttp.onreadystatechange=function() { 
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {           
          callback(); } else return; }
      xmlhttp.open("GET","?get=" + get,true);
      xmlhttp.send();
    }
  },
  /* A simple Animation Handler
   * based on http://javascript.info/tutorial/type-detection
   * @param {object} options - expects { delay:int, duration:int, delta:function(), step:function() }
   * @param {object/bool} action - true/false = looper OR function = callback (e.g. another animation)
   * @returns {undefined}
   */  
  animate: function(elements, howFar, delay, duration, delta, step, action) {    
    var start = new Date  
    var direction = true;
    var id = setInterval(function() {
      var timePassed = new Date - start
      var progress = timePassed / duration
      if (progress > 1) progress = 1
      step(delta(progress), direction, howFar, elements)
      if (progress == 1) 
        if (typeof(action) == "boolean" && action) { 
          // if oscillating, send a flag to the step function to alter direction. 
          direction = !direction; 
          progress = 0; // reset progress counter.
          start = new Date; // reset timer.
        } else {
          clearInterval(id); // stop timer
          // arrange next action (where applicable)
          if (typeof(action) == "function") action(); 
        }        
    }, delay || 10)             
  },
  
  /* Step handler.
   * Controls the actual pixel-by-pixel rendering
   * @param {func} delta - what kind of progress behaviour (e.g. linear, curve, etc.)
   * @param {bool} whichWay - e.g. are we currently going up or down?
   * @param {int} howFar - howFar (in px) do you want object to traverse?
   * @param {array} elements - the elements to apply the action to. 
   */     
  oscillate: function(delta, whichWay, howFar, elements) {
        for (i in elements)
            if (Global.get.id(elements[i]))
              Global.get.id(elements[i]).style.top = whichWay
                ? howFar*delta + "%" 
                : howFar-(howFar*delta) + "%";      
  },
  /* Delta pattern handlers. i.e. Movement behaviour.   
   */
  linear: function(progress) {return progress;},
  powerOfN: function (progress) { return Math.pow(progress, 2) },
  bounce: function(progress) {
    var a, b;
    for(a = 0, b = 1, result; 1; a += b, b /= 2) 
      if (progress >= (7 - 4 * a) / 11) 
        return -Math.pow((11 - 6 * a - 11 * progress) / 4, 2) + Math.pow(b, 2)              
  }
}

// public class Local : GlobalObject
function Local(GlobalObject) {
  function local() {
    this.load = function() { 
      
      // Bounce the title letters.
      var i;
      var letters = [
        ["J", "N2"],["D1", "R"],
        ["O", "Y"],["W", "D2"],
        ["N1", "E"],["A", "S"]]
      
      for (i in letters)
        this.animate( // see parameters required in Global
          letters[i], 
          4,
          0.001, 
          Math.floor((Math.random() * 1300) + 1200), // 1000 = 1 second
          this.linear,
          this.oscillate, // call oscillation animation handler in the "options" scope
          true);            
    }    
  }
// NB: DO NOT CHANGE -----
// Inherit global's attributes AFTER definition.
  local.prototype = GlobalObject;
  return new local();  
}

// Objects need only call the Action handler, it will do the rest.
var Action = Local(Global);


