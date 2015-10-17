var Core = {
  get : {
    id: function(id) { return document.getElementById(id); },
    event: function() { try { return Core.refresh.caller.name; } catch(e) { return "mousemove" } },    
  },  
  
  load: function() {    
    Core.render(performance.now());   
  },
  
  refresh: function() {
    var a = arguments;
    var plyr = $.getActor("Player");
    var mouse = $.client.getLocalNode($.mouse);
    try { 
      if (a < 1) throw "no arguments";
      else 
        switch(Core.get.event()) {
          case "onclick":
            if (plyr.isIdle() || $.compare(mouse, plyr.getDes()))
              plyr.trigger($.client.getLocalNode($.mouse));                                   
            break;        
        }                      
    } catch(e) {
      alert(e);      
    } 
  },
  
  render: function(time) {
    $.client.refreshRate(time);
    var c;
    //try {
      $.cls();
      if ($.debug) 
        Debug.show();
      var m = MANIFEST.concat($.pRegister.get());
      for (c in m.sort(function(a, b) { return a.order()[Y]-b.order()[Y] })) 
        switch(m[c].type) {
          case OBSTACLE:
            m[c].draw();
            break;
          case PLAYER:
            m[c].oRegisterHits();
            m[c].isHit();
            m[c].playerWalk();
            break;
          case PATROLLER:
            m[c].patrol();
            m[c].shoot($.getActor("Player").getNow());
            break;
          case CHASER:
          case GUARDIAN_CHASER:
            m[c].chase();            
            break;
          case PROJECTILE:
            m[c].fire();
        }

      requestAnimationFrame(Core.render);
  }
}