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
    scrollHeight: function(elem) {
      var curtop = 0;
      var elem = this.id(elem);
      if (elem.offsetParent) {
          do {
              curtop += elem.offsetTop;
          } while (elem = elem.offsetParent);
      return curtop;
      }
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
  animate: function (elements, howFar, duration, delta, step, action) {
    var start = new Date
    var direction = true;
    var id = setInterval(function () {
      var timePassed = new Date - start
      var progress = timePassed / duration
      if (progress > 1) progress = 1;
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
    }, 0.000000001);
  },
  // Delta pattern handlers. i.e. Movement behaviour.     
  linear: function (progress) {
    return progress;
  },
  powerOfN: function (progress) {
    return Math.pow(progress, 2);
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
      // expand text.
      elem.style.fontSize = 
        70 + Math.round(howMuch * (delta)) + '%';  
      // warm colour.
      elem.style.backgroundColor = 
        'rgb(' + 
        (117 + Math.round(45 * (delta))) + ',' + 
        (218 + Math.round(18 * (delta))) + ',' + 
        (208 - Math.round(16 * (delta))) + ')';
      elem.style.zIndex = '10';
      if (Math.round(elem.style.fontSize.replace('%', '')) > 84) {
        // set standard completion values
        elem.style.fontSize = '90%';            
        elem.style.backgroundColor = 'rgb(162, 236, 192)';
        // push the element to the front of the page. helps the page behave a little better.
        elem.style.zIndex = '25';
        clearInterval(elem.id);
      }
    },

    contractMenuItem : function (delta, howMuch, elem) {         
      // shrink text.
      elem.style.fontSize = 
        90 - Math.round(howMuch * delta) + '%';
      // cool colour.
      elem.style.backgroundColor = 
        'rgb(' + 
         (162 - Math.round(45 * delta)) + ',' + 
        (236 - Math.round(18 * delta)) + ',' + 
        (192 + Math.round(16 * delta)) + ')';
      elem.style.zIndex = '10';
      if (Math.round(elem.style.fontSize.replace('%', '')) < 76) {      
        // default values on completion
        elem.style.fontSize = '70%';            
        elem.style.backgroundColor = 'rgb(117, 218, 208)';              
        clearInterval(elem.id);            
      }
    },
    
    // handles expanding and collapsing div holders.
    collapseeHeight : '', // a string literal holding Json data at runtime for managing collapsibles    
    expandCollapsee : function (delta, howMuch, elem) {                      
      elem.style.height = (0 + Math.round(howMuch * delta)) + 'px';                  
    },
    
    collapseCollapsee : function (delta, howMuch, elem) {                      
      var height = JSON.parse(Behaviour.collapseeHeight)[elem.id];
      elem.style.height = (height - Math.round(howMuch * delta)) + 'px';                  
    },
    
    elementGlow : function(delta, howMuch, elem) {
      elem.style.backgroundColor = 
        'rgb(' + 
        (117 + Math.round(45 * delta)) + ',' + 
        (218 + Math.round(18 * delta)) + ',' + 
        (208 - Math.round(16 * delta)) + ')';
    },
    
    elementDim : function(delta, howMuch, elem) {
      elem.style.backgroundColor = 
        'rgb(' + 
        (162 - Math.round(45 * delta)) + ',' + 
        (236 - Math.round(18 * delta)) + ',' + 
        (192 + Math.round(16 * delta)) + ')';
    },
    
    scrollTo : function(delta, howMuch, offset) {                 
      window.scrollTo(0, offset + Math.round(howMuch * delta));      
    },          
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
      
      // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
      // 1. standard menus
      var menuItems = this.get.class('menuItem');
      var events = ['move', 'enter', 'leave'];
      for (i = 0; i < menuItems.length; i++) 
        for (ii in events) 
          this.set.event(menuItems.item(i), 'mouse' + events[ii], this.menuEvent.bind(Core));
      
      // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
      // 2. nav menu for mobile 
      this.set.event(this.get.id('navMenu'), 'click', this.navMenuEvent.bind(Core));
      
      // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
      // 3. collapsible pains
      var collapsers = this.get.class('collapser'); // as above, declared before the loop.
      var collapsee;
      for (i = 0; i < collapsers.length; i++) {
        this.set.event(collapsers.item(i), 'click', this.collapsibleEvent.bind(Core));        
        collapsee = this.get.id(collapsers.item(i).id.replace('_collapser', '_collapsee'));
        // assign initial dynamic offset variable to Json string
        Behaviour.collapseeHeight += 
          (i == 0 ? '{"' : ',"') +  // apply brace at start and comma thereafter prior to variable
          collapsee.id + '":"' +    // KEY
          (collapsee.offsetHeight > 500 ? 500 : collapsee.offsetHeight) + '"'; // VALUE (max 500;
        // now we have the collapsee's default height, we can hide the text.
        collapsee.style.height = '0px';
        collapsee.style.overflowY = 'scroll';
        collapsee.style.overflowX = 'initial';
      }
      // close off the Json tag (where applicable);
      Behaviour.collapseeHeight += Behaviour.collapseeHeight ? '}' : '';
      
      // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
      // 4. ScrollTo/From
      var scrollMenu = this.get.class('scroll_menu');
      if (scrollMenu) 
        for (i = 0; i < scrollMenu.length; i++)
          this.set.event(scrollMenu.item(i), 'click', this.scrollTo.bind(Core));
      var scrollReturn = this.get.class('scroll_return');
      if (scrollReturn)
        for (i = 0; i < scrollReturn.length; i++)
          this.set.event(scrollReturn.item(i), 'click', this.scrollFrom.bind(Core));
      
      // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
      // 5. DropDown 
      if (this.get.class('dropdown')) {
        var dropDowns = this.get.class('dropdown');
        for (i = 0; i < dropDowns.length; i++)
          this.set.event(dropDowns.item(i), 'click', DropDown.handler); 
      } 
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
            Math.floor((Math.random() * (700)) + (650)), // 1000 = 1 second, add extra 500 for surname as DOM renders it faster.
            this.linear,
            Behaviour.oscillateTitle, // call oscillation animation handler in the 'options' scope
            true);
    }
    
    // all other events are copied to caller obj so do not have access to Core.
    this.menuEvent = function (e) {
      var elem = e.currentTarget;
      switch(e.type.replace('on', '')) {
        case 'mousemove':
          if (Math.round(elem.style.fontSize.replace('%', '')) > 72) return;
        case 'mouseenter':          
          this.animate(
            elem,
            20,
            20,
            this.linear,
            Behaviour.expandMenuItem);            
          break;
        case 'mouseleave':             
          this.animate(
            elem,
            20,
            250,
            this.powerOfN,
            Behaviour.contractMenuItem)            
          break;
      }        
    }
    
    this.navMenuEvent = function (e) {
      var elem = e.currentTarget;
      switch(e.type.replace('on', '')) {
        case 'click':
          var menuItems = elem.get.class('menuItem');
          for (var i = 0; i < menuItems.length; i++)
            menuItems.item(i).style.display = 
              (menuItems.item(i).style.display)
                ? ''
                : 'inline';
        break;
      }      
    }
    
    this.collapsibleEvent = function (e) {
      var elem = e.currentTarget;
      var collapsee = this.get.id(elem.id.replace('_collapser', '_collapsee'));      
      var height = JSON.parse(Behaviour.collapseeHeight)[collapsee.id];      
      switch (collapsee.style.height) {
        case '0px':
          // 1. perform transition on header component.
          this.animate(
            elem,
            20,
            height,
            this.linear,
            Behaviour.elementGlow);  
            elem.innerHTML = elem.innerHTML.replace('Expand', 'Collapse');
            elem.style.backgroundImage = 'url("/images/pointerDown.svg")';            
          // 2. expand div
          this.animate(
            collapsee,
            height, 
            height, 
            this.linear,
            Behaviour.expandCollapsee);                                      
            collapsee.style.overflowX = 'auto';
          break;
          
        case height + 'px':          
          this.animate(
            elem,
            20,
            height,
            this.linear,
            Behaviour.elementDim); 
            elem.innerHTML = elem.innerHTML.replace('Collapse', 'Expand');
            elem.style.backgroundImage = 'url("/images/pointerRight.svg")';
          this.animate(
            collapsee,
            height,
            height, 
            this.linear,
            Behaviour.collapseCollapsee);           
            collapsee.style.overflowX = 'hidden'; // prevents unwanted overflow.
          break;
      }
    }
    
    this.scrollTo = function(e) {
      var elem = e.currentTarget;
      var offset = window.scrollY || document.documentElement.scrollTop;
      var goto = this.get.scrollHeight(elem.id.replace('call','point')) - offset;
      this.animate(
        offset -5,
        goto,
        goto / 3,
        this.linear,
        Behaviour.scrollTo);      
    }
    
    this.scrollFrom = function() {       
      var start = window.scrollY || document.documentElement.scrollTop;      
      var destination = this.get.id('scroll_home');
      var goto = (destination 
        ? this.get.scrollHeight('scroll_home') 
        : this.get.scrollHeight('PageWrapper')) - start;
      this.animate(
        start,
        goto,
        start / 5,
        this.linear,
        Behaviour.scrollTo);      
    }
  }
  
  // allow the Action variable below to inherit from GlobalObject.  This allows for the load event to use "this" rather than "Core".
  local.prototype = GlobalObject;
  
  return new local();
}

// Objects need only call the Action handler, it will do the rest.
var Action = Local(Core);

