// The basis of a simple OOP framework for JS DOM by Jonathan Edwards
// -------------------------------------------------
// public static class Global
var Core = {
  get: {
    id: function (id) {
      return document.getElementById(id);
    },
    class: function (className) {
      return document.getElementsByClassName(className);
    },
    ajax: function (get, callback) {
      var xmlhttp = (window.XMLHttpRequest)
              ? new XMLHttpRequest() // code for IE7+, Firefox, Chrome, Opera, Safari
              : new ActiveXObject('Microsoft.XMLHTTP'); // code for IE6, IE5    
      xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          callback();
        } else
          return;
      }
      xmlhttp.open('GET', '?get=' + get, true);
      xmlhttp.send();
    }
  },
  set: {
    event: function (elem, eventType, handler) {
      if (elem.addEventListener)
        elem.addEventListener(eventType, handler, false);
      else if (elem.attachEvent)
        elem.attachEvent('on' + eventType, handler);
    }
  },
  /* A simple Animation Handler
   * based on http://javascript.info/tutorial/type-detection
   * @param {object} options - expects { delay:int, duration:int, delta:function(), step:function() }
   * @param {object/bool} action - true/false = looper OR function = callback (e.g. another animation)
   * @returns {undefined}
   */
  animate: function (elements, howFar, delay, duration, delta, step, action) {
    var start = new Date
    var direction = true;
    var id = setInterval(function () {
      var timePassed = new Date - start
      var progress = timePassed / duration
      if (progress > 1)
        progress = 1
      step(delta(progress), howFar, elements, direction)
      if (progress == 1)
        if (typeof (action) == 'boolean' && action) {
          // if oscillating, send a flag to the step function to alter direction. 
          direction = !direction;
          progress = 0; // reset progress counter.
          start = new Date; // reset timer.
        } else {
          clearInterval(id); // stop timer
          // arrange next action (where applicable)
          if (typeof (action) == 'function')
            action();
        }
    }, delay || 10)
  },
  // Delta pattern handlers. i.e. Movement behaviour.     
  linear: function (progress) {
    return progress;
  },
  powerOfN: function (progress) {
    return Math.pow(progress, 2)
  },
  bounce: function (progress) {
    var a, b;
    for (a = 0, b = 1, result; 1; a += b, b /= 2)
      if (progress >= (7 - 4 * a) / 11)
        return -Math.pow((11 - 6 * a - 11 * progress) / 4, 2) + Math.pow(b, 2)
  },
}
// -------------------------------------------------
// public class Local : GlobalObject
function Local(GlobalObject) {
  function local() {
    this.load = function () {
      // load title.
      this.animateTitle();

      // add event handlers to menuItems
      var menuItems = this.get.class('menuItem');
      for (var i in menuItems) {
        this.set.event(menuItems[i], 'mouseover', this.menuEvent);
        this.set.event(menuItems[i], 'mouseout', this.menuEvent);
      }
    },
    // Bounce the title letters.
    this.animateTitle = function () {
      var i, ii;
      var letters = [['J', 'O', 'N1', 'N2', 'Y'], ['E', 'D1', 'W', 'A', 'R', 'D2', 'S']];
      for (i in letters)
        for (ii in letters[i])
          Core.animate(// see parameters required in Global
            letters[i][ii],
            4,
            0.0000001,
            Math.floor((Math.random() * (700)) + (650)), // 1000 = 1 second, add extra 500 for surname as DOM renders it faster.
            Core.linear,
            Behaviour.oscillateTitle, // call oscillation animation handler in the 'options' scope
            true);
    }

    
    this.menuEvent = function (e) { 
      switch(e.type) {
        case 'mouseover':
        case 'onmouseover':
          Core.animate(
            this,
            30,
            0.0000001,
            150,
            Core.linear,
            Behaviour.expandMenuItem);          
          break;
        case 'mouseout':
        case 'onmouseout':
          Core.animate(
            this,
            30,
            0.0000001,
            500,
            Core.powerOfN,
            Behaviour.contractMenuItem);
          break;
      }
        
    }
  }


  // NB: DO NOT CHANGE -----
  // Inherit global's attributes AFTER definition.
  local.prototype = GlobalObject;
  return new local();

}

//
var Behaviour = {
  /* Step handlers.
   * Control the actual pixel-by-pixel rendering
   * @param {func} delta - what kind of progress behaviour (e.g. linear, curve, etc.)
   * @param {bool} whichWay - e.g. are we currently going up or down?
   * @param {int} howFar - howFar (in px) do you want object to traverse?
   * @param {array} elements - the elements to apply the action to. 
   */
  oscillateTitle: function (delta, howFar, elem, whichWay) {
    if (Core.get.id(elem))
      Core.get.id(elem).style.top = whichWay
              ? howFar * delta + '%'
              : howFar - (howFar * delta) + '%';
  },
    
  expandMenuItem: function (delta, howMuch, elem) {          
    var fontSize = elem.style.fontSize     
    // expand text.
    elem.style.fontSize = 
      70 + Math.round(howMuch * (delta)) + '%';  
    // warm colour.
    elem.style.backgroundColor = 
      'rgb(' + 
      (117 + Math.round(45 * (delta))) + ',' + 
      (218 + Math.round(18 * (delta))) + ',' + 
      (208 - Math.round(16 * (delta))) + ')';
    if (Math.round(fontSize.replace('%', '')) > 95) {
      // set standard completion values
      elem.style.fontSize = '100%';            
      elem.style.backgroundColor = 'rgb(162, 236, 192)';
      clearInterval(elem.id);
    }
    
  },
  
  contractMenuItem: function (delta, howMuch, elem) {      
    var fontSize = elem.style.fontSize;    
    // shrink text.
    elem.style.fontSize = 
      100 - Math.round(howMuch * (delta)) + '%';
    // cool colour.
    elem.style.backgroundColor = 
      'rgb(' + 
      (162 - Math.round(45 * (delta))) + ',' + 
      (236 - Math.round(18 * (delta))) + ',' + 
      (192 + Math.round(16 * (delta))) + ')';
    
    if (Math.round(fontSize.replace('%', '')) < 75) {      
      // default values on completion
      elem.style.fontSize = '70%';            
      elem.style.backgroundColor = 'rgb(117, 218, 208)';      
      clearInterval(elem.id);            
    }
  }
  
}


// Objects need only call the Action handler, it will do the rest.
var Action = Local(Core);


