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
    },    
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
        return -Math.pow((11 - 6 * a - 11 * progress) / 4, 2) + Math.pow(b, 2);
  },
}

// public static class Behaviour
var Behaviour = {  
    /* Step handlers.
     * Control the actual pixel-by-pixel rendering
     * @param {func} delta - what kind of progress behaviour (e.g. linear, curve, etc.)
     * @param {bool} whichWay - e.g. are we currently going up or down?
     * @param {int} howFar - howFar (in px) do you want object to traverse?
     * @param {array} elements - the elements to apply the action to. 
     */
    oscillateTitle : function (delta, howFar, elem, whichWay) {
      if (Core.get.id(elem))
        Core.get.id(elem).style.top = whichWay
                ? howFar * delta + '%'
                : howFar - (howFar * delta) + '%';
    },
    
    expandMenuItem : function (delta, howMuch, elem) {          
      var fontSize = elem.style.fontSize;  
      // expand text.
      elem.style.fontSize = 
        70 + Math.round(howMuch * (delta)) + '%';  
      // warm colour.
      elem.style.backgroundColor = 
        'rgb(' + 
        (117 + Math.round(45 * (delta))) + ',' + 
        (218 + Math.round(18 * (delta))) + ',' + 
        (208 - Math.round(16 * (delta))) + ')';
      if (Math.round(fontSize.replace('%', '')) > 84) {
        // set standard completion values
        elem.style.fontSize = '90%';            
        elem.style.backgroundColor = 'rgb(162, 236, 192)';
        clearInterval(elem.id);
      }
    },

    contractMenuItem : function (delta, howMuch, elem) {      
      var fontSize = elem.style.fontSize;    
      // shrink text.
      elem.style.fontSize = 
        90 - Math.round(howMuch * (delta)) + '%';
      // cool colour.
      elem.style.backgroundColor = 
        'rgb(' + 
         (162 - Math.round(45 * (delta))) + ',' + 
        (236 - Math.round(18 * (delta))) + ',' + 
        (192 + Math.round(16 * (delta))) + ')';

      if (Math.round(fontSize.replace('%', '')) < 76) {      
        // default values on completion
        elem.style.fontSize = '70%';            
        elem.style.backgroundColor = 'rgb(117, 218, 208)';      
        clearInterval(elem.id);            
      }
    },
    
    // handles expanding and collapsing div holders.
    collapseeHeight : '', // a string literal holding Json data at runtime for managing collapsibles    
    expandCollapsee : function (delta, howMuch, elem) {                      
      elem.style.height = (0 + Math.round(howMuch * (delta))) + 'px';                  
    },
    collapseCollapsee : function (delta, howMuch, elem) {                      
      var height = JSON.parse(Behaviour.collapseeHeight)[elem.id];
      elem.style.height = (height - Math.round(howMuch * (delta))) + 'px';                  
    },
    elementGlow : function(delta, howMuch, elem) {
      elem.style.backgroundColor = 
        'rgb(' + 
        (117 + Math.round(45 * (delta))) + ',' + 
        (218 + Math.round(18 * (delta))) + ',' + 
        (208 - Math.round(16 * (delta))) + ')';
    },
    elementDim : function(delta, howMuch, elem) {
      elem.style.backgroundColor = 
        'rgb(' + 
        (162 - Math.round(45 * (delta))) + ',' + 
        (236 - Math.round(18 * (delta))) + ',' + 
        (192 + Math.round(16 * (delta))) + ')';
    }
}

// -------------------------------------------------
// public class Local : GlobalObject
function Local(GlobalObject) {
  function local() {
    this.load = function () {
      // the load command inherits from Core so can call get, set, etc.
      // --------------------------
      // OnLoad Events
      this.animateTitle();            
      ///this.floatClouds();
      
      // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
      // Other Event Listeners.
      var i = 0, ii = 0; // optimisation suggests the declaration should come before the loops.
      // 1. standard menus
      var menuItems = this.get.class('menuItem');
      var events = ['move', 'enter', 'leave'];
      for (i = 0; i < menuItems.length; i++) 
        for (ii in events) 
          this.set.event(menuItems.item(i), 'mouse' + events[ii], this.menuEvent);
      // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
      // 2. nav menu for mobile 
      this.set.event(this.get.id('navMenu'), 'click', this.navMenuEvent);
      // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
      // 3. collapsible pains
      var collapsers = this.get.class('collapser'); // as above, declared before the loop.
      var collapsee;
      for (i = 0; i < collapsers.length; i++) {
        this.set.event(collapsers.item(i), 'click', this.collapsibleEvent);        
        collapsee = this.get.id(collapsers.item(i).id.replace('_collapser', '_collapsee'));
        // assign initial dynamic offset variable to Json string
        Behaviour.collapseeHeight += 
          (i == 0 ? '{"' : ',"') +  // apply brace at start and comma thereafter prior to variable
          collapsee.id + '":"' +    // KEY
          (collapsee.offsetHeight > 500 ? 500 : collapsee.offsetHeight ) + '"'; // VALUE (max 500;
        // now we have the collapsee's default height, we can hide the text.
        collapsee.style.height = '0px';
        collapsee.style.overflowY = 'scroll';
        collapsee.style.overflowX = 'initial';
      }
      // close off the Json tag (where applicable);
      Behaviour.collapseeHeight += Behaviour.collapseeHeight ? '}' : '';  
      // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
    },
            
    // Bounce the title letters.  still inherits from Core so can use this.
    this.animateTitle = function () {
      var i, ii;
      var letters = [['J', 'O', 'N1', 'N2', 'Y'], ['E', 'D1', 'W', 'A', 'R', 'D2', 'S']];
      for (i in letters)
        for (ii in letters[i])
          this.animate(// see parameters required in Global
            letters[i][ii],
            4,
            0.00000000000001,
            Math.floor((Math.random() * (700)) + (650)), // 1000 = 1 second, add extra 500 for surname as DOM renders it faster.
            this.linear,
            Behaviour.oscillateTitle, // call oscillation animation handler in the 'options' scope
            true);
    }
    
    // all other events are copied to caller obj so do not have access to Core.
    this.menuEvent = function (e) {       
      switch(e.type.replace('on', '')) {

        case 'mouseenter':          
          Core.animate(
            this,
            20,
            0.1,
            150,
            Core.linear,
            Behaviour.expandMenuItem);                    
          break;
        case 'mouseleave':                     
          Core.animate(
            this,
            20,
            0.1,
            250,
            Core.linear,
            Behaviour.contractMenuItem)
          break;
      }        
    }
    
    this.navMenuEvent = function (e) {
      switch(e.type.replace('on', '')) {
        case 'click':
          var menuItems = Core.get.class('menuItem');
          for (var i = 0; i < menuItems.length; i++)
            menuItems.item(i).style.display = 
              (menuItems.item(i).style.display)
                ? ''
                : 'inline';
        break;
      }      
    }
    
    this.collapsibleEvent = function () {
      var collapsee = Core.get.id(this.id.replace('_collapser', '_collapsee'));
      var height = JSON.parse(Behaviour.collapseeHeight)[collapsee.id];      
      switch (collapsee.style.height) {
        case '0px':
          // 1. perform transition on header component.
          Core.animate(
            this,
            20,
            0.0000001,
            height,
            Core.linear,
            Behaviour.elementGlow);  
            this.innerHTML = this.innerHTML.replace('Expand', 'Collapse');
            this.style.backgroundImage = 'url("/images/pointerDown.svg")';
          // 2. expand div
          Core.animate(
            collapsee,
            height, 
            0.0000001,
            height, 
            Core.linear,
            Behaviour.expandCollapsee);                                      
          break;
          
        case height + 'px':
          Core.animate(
            collapsee,
            height,
            0.0000001,
            height, 
            Core.linear,
            Behaviour.collapseCollapsee);           
          
          Core.animate(
            this,
            20,
            0.0000001,
            height,
            Core.linear,
            Behaviour.elementDim); 
            this.innerHTML = this.innerHTML.replace('Collapse', 'Expand');
            this.style.backgroundImage = 'url("/images/pointerRight.svg")';
          break;
      }
    }
  }

  // allow the Action variable below to inherit from GlobalObject.  This allows for the load event to use "this" rather than "Core".
  local.prototype = GlobalObject;
  
  return new local();
}




// Objects need only call the Action handler, it will do the rest.
var Action = Local(Core);

