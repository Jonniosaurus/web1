/*
* ~~~~~~ ABOUT THIS SAMPLE ~~~~~~
* You'll notice the absence of object literals in my behaviour definitions. This is because arrays are faster.
* You'll also note some variables which are not declared (e.g. the "X" in location[X] below), these refer to
* global variables managed elsewhere.
*/

/*------------------------------------------------------------------
* -- ACTOR --
* ------------------------------------------------------------------ */
function Actor(name, location, speed, color) {
  var i, ii, actor, loc, frm, des, nm, interrupt, map, prev;
  var curr = 0, next = 1, path = [];
  var mv = $.client.getFromPcnt(speed || 10); // the $ is my own global on this occassion. No JQuery
  var idle = true;
  var col = color;
  this.type = ACTOR;
  
  // "Constructor"
  (function() { 
    // 3 variables requiring 3 memory locations.
    loc = [location[X], location[Y]];    // player starting point.
    des = [location[X], location[Y]];    // player destination
    prev = [location[X], location[Y]];   // the node the actor was on prior to destination.
    frm = $.client.getFromNode(location); // animations between start & dest.
    nm = name;    
    actor = new Shape.actor(col);
    interrupt = false;
  })()
  
  // shorthand for managing paths. l == location, d == destination
  function drawPath(l, d) {
    var p = new Map($.oRegister);
    p.setPath(l, d);
    p = p.getPath();
    des = p[p.length - 1]; // ensure the destination matches the calculated path destination
    return p;
  }
  
  this.isIdle = function() { return idle; }
  this.order = function() { return frm; }
  
  this.getName = function() { return nm; }
  this.getLoc = function() { return loc; }
  this.getNext = function() { 
    if (path.length)
      return path[next]; 
  }
  this.getDes = function() { return des; }
  this.getNow = function() { return $.client.getLocalNode(frm); }
  this.getPrev = function() { return prev; } 
  this.setSpeed = function(speed) {
    mv = $.client.getFromPcnt(speed);
  }
  
  this.setColor = function(c) {
    col = c;
    actor = new Shape.actor(col);    
  }
  // event listener;
  this.trigger = function(destination) {
    if (!path.length) {
      des = destination;
      path = drawPath(loc, des);      
      curr = 0;
    } else {
      if (next + 1 < path.length) 
        path.splice(next + 1); // cut off rest of path array.
      for (var i in des)
        des[i] = path[path.length - 1][i];
      // set the interrupt so it can be triggered once actor has reached destination.
      interrupt = destination;
    }    
  }
  
  // core animator;
  this.move = function() {
    var i, ii, p;
    if (path.length) {      
      // is there another node in the path?
      idle = false;
      if (next < path.length) {
        p = $.client.getFromNode(path[next]); // get path
        if (frm[X] == p[X]
          && frm[Y] == p[Y]) {  
              //prev = [path[curr][X], path[curr][Y]];
              next++;    
              curr++;                          
        } else 
          for (var i in frm) 
            if (frm[i] == p[i])
              continue;
            else { // the following logic helps to keep transitions smooth rather than bumping along between each step.
              if (frm[i] > p[i]) {
                if (frm[i] - $.client.getSpeed(mv) < p[i]) { // have we overshot?
                  if ((next + 1 < path.length) // if overshoot, are we continuing on same axis?
                    && path[next + 1][i] == (path[next][i] - 1)) { 
                      frm[i] -= $.client.getSpeed(mv);                      
                      next ++;
                      curr ++;
                    } else frm[i] = p[i]; 
                  prev = [path[curr][X], path[curr][Y]];
                } else frm[i] -= $.client.getSpeed(mv) ; // We haven't overshot and we haven't reached the next step.
              } 
              if (frm[i] < p[i]) { 
                if (frm[i] + $.client.getSpeed(mv) > p[i]) { // have we overshot?
                  if ((next + 1 < path.length) // if overshoot, are we continuing on same axis?
                    && path[next + 1][i] == (path[next][i] + 1)) {
                      frm[i] += $.client.getSpeed(mv);                                                               
                      next ++;
                      curr ++;
                    } else frm[i] = p[i];
                  prev = [path[curr][X], path[curr][Y]];
                } else frm[i] += $.client.getSpeed(mv); // We haven't overshot and we haven't reached the next step.
              }
            }       
            
            // set previous path item;
        actor.draw(frm,[7,10])
        return;
      } else {// -- Idle state --
        path = [];
        curr = 0;
        next = 1;
        for (var i in des)
          loc[i] = des[i];
      }               
    }
    actor.draw($.client.getFromNode(loc),[7,10])
    if (interrupt) {      
      for (var i in des)
        des[i] = interrupt[i]
      interrupt = false;
      path = drawPath(loc, des);            
    } else idle = true;
  }
}

/*------------------------------------------------------------------
* -- PLAYER --
* ------------------------------------------------------------------ */
function Player(actor) {
  function player() {
    var i, pNow, nNow, attacker, direction, hitReg;
    var ii = 0, iii = 0, iiii = 0, flick = false, hit = false;
    var lives = 3;
    this.type = PLAYER;
    this.isInjured = function() { return hit; }
    this.oRegisterHits = function() {
      pNow = this.getNow();
      hitReg = MANIFEST.concat($.pRegister.get());
      for (i in hitReg) {
        if (hitReg[i].type != PLAYER 
          && hitReg[i].type != OBSTACLE) {
            attacker = hitReg[i].getName();
            nNow = hitReg[i].getNow();
            if ($.compare(nNow, pNow)) {              
              hit = true;
              return;
            }
          }
        else this.setColor('blue');
      }
    }
    this.isHit = function() {
      if (hit || ii > 0) {
        if (ii == 1) {
          lives--; 
          if (lives == 0) {
            alert("Game Over!");
            location.reload(); 
          }
          else if (this.isIdle() && this.getPrev()) {
            /* - which direction the enemy is coming in on? 
            *  - push player in that direction if possible;
            *  - if not possible, player stays in present location; */
            if (a = $.getActor(attacker)) {
              direction = a.getPrev()[X] == a.getNow()[X]
                ? (a.getPrev()[Y] > a.getNow()[Y] 
                  ? [this.getNow()[X], (this.getNow()[Y] - 1 > -1 
                    ? this.getNow()[Y] - 1 
                    : 0)] 
                  : [this.getNow()[X], (this.getNow()[Y] + 1 < 11 
                    ? this.getNow()[Y] + 1 
                    : 10)])
                : (a.getPrev()[X] < a.getNow()[X] 
                  ? [(this.getNow()[X] + 1 < 11 
                    ? this.getNow()[X] + 1 
                    : 10), 
                    this.getNow()[Y]] 
                  : [(this.getNow()[X] - 1 > -1 
                    ? this.getNow()[X] - 1 
                    : 0), 
                    this.getNow()[Y]]);
              // TODO: handle recoil into edge of map.
              if (!$.oRegister.check(direction))
                this.trigger(direction);            
            }
          }
        }          
        if (iii < (ii - 10)) {
          flick = (!flick)
          iii += 10;
        }
        this.setColor(flick ? 'yellow' : 'red');
        if (ii >= 150) { 
          this.setColor('blue'); 
          ii = 0; 
          iii = 0;
          hit = false;
          flick = false; }
        else ii++;        
      }
    }
    
    // make player bounce away when hit.
    this.recoil = function() {    
      if (iiii == 0)
        this.setSpeed(3.5);
      iiii++;
      if (iiii == 10) {
        this.setSpeed(1.5);
        iiii == 0;
        hit = false;
      }    
    }
    
    this.playerWalk = function() {
      if (hit)
        this.recoil();
      this.move();           
    }
  }
  player.prototype = actor;
  return new player();
}

/*------------------------------------------------------------------
* -- NON-PLAYER-CHARACTER ABSTRACT --
* ------------------------------------------------------------------ */
function NPC(actor) {
  function npc() {
    var shot, projectile, plyr;
    var shooter = false, i = 0;
    // fire a shot when readyShot = 0;
    var readyShot = (function() {return Math.floor((Math.random() * 30) + 60)})();
    this.setShoot = function(bool) { shooter = bool; 
    }
    this.shoot = function(des) {
      if (readyShot > 0)
        readyShot--;
      else {  
        var now = this.getNow();
        shot = new Projectile([now[X], now[Y]]);        
        shot.trigger([des[X], des[Y]]);        
        readyShot = Math.floor((Math.random() * 50) + 100);
      }
    }    
  }
  npc.prototype = actor;
  return new npc();
}

/*------------------------------------------------------------------
* -- PATROLLER ENEMY --
* ------------------------------------------------------------------ */
function NPC_Patroller(actor, paths) {
  var i, p;
  function patroller(p) {
    p = p;
    i = 0; 
    this.type = PATROLLER;
    // patrol a sequence of paths.
    this.patrol = function() {
      if (p.length && this.isIdle()) {
        if (i < p.length) {                    
          this.trigger(p[i]);
          i++
        } else {
          i = 0; // go back to one before start position      
        }
      }
      this.move();
    }
    
    this.setNewPatrol = function(patrol) {
      p = patrol; // set it.
      this.patrol(); // start it.
    }
  }
  patroller.prototype = NPC(actor);
  return new patroller(paths);
}

/*------------------------------------------------------------------
* -- CHASER ENEMY --
* ------------------------------------------------------------------ */
function NPC_Chaser(actor, distToChase) {
  var i, ii, dir, loc, p, nd, des, dist, maxDist, player, chaser;
  var wait = 0;    
  // if player is in line with NPC, give chase.
  function chaser(maximumDistance) {
    maxDist = maximumDistance || 10;
    this.type = CHASER;
    this.getMaxDist = function() { return maxDist; }
    // in which direction has the player been "seen"?
    this.confirmDirection = function(l, d) {
      return (l[Y] < d[Y])
          ? UP
          : (l[Y] > d[Y]
            ? DOWN
            : (l[X] > d[X]
              ? LEFT
              : (l[X] < d[X]
                ? RIGHT
                : 0)))      
    }
    
    // how far away is the player from NPC?
    this.confirmDistance = function(l, d) {
      dir = (this.confirmDirection(l,d));
      return (dir == UP // define distance from loc to dest
          ? d[Y] - l[Y]
          : (dir == DOWN
            ? l[Y] - d[Y]
            : (dir == LEFT
              ? l[X] - d[X]
              : (dir == RIGHT
                ? d[X] - l[X]                    
                : 0))));
    }
    
    // if NPC has line of sight with player, confirm player is close enough and its view isn't blocked
    this.hasLineOfSight = function(l, d) {
      des = d;      
      loc = l;
      dist = this.confirmDistance(loc, des);
      if (!dist || dist > maxDist) return false;
      for (ii = 0; ii <= dist; ii++)
        if (dir == UP
          ? $.oRegister.check(loc[X], loc[Y] + ii)
          : (dir == DOWN
            ? $.oRegister.check(loc[X], loc[Y] - ii)
            : (dir == LEFT
              ? $.oRegister.check(loc[X] - ii, loc[Y])
              : (dir == RIGHT 
                ? $.oRegister.check(loc[X] + ii, loc[Y])
                : false))))
                return false;                                    
      return true;
    }
    
    this.findActor = function(a, c) {
      p = a.getNext() || a.getDes();
      var npcLoc = c || this.getLoc(); // the "c" allows the npc to choose a different location from which to be triggered.
      if (p)
        for (var i in p) 
          if (npcLoc[i] == p[i]) { // player is on same axis.
            nd = [p[X], p[Y]]; // record values to local variable
            if (this.hasLineOfSight(npcLoc, nd)) 
              return nd;                                                             
          }
      return false;
    }
    
    this.chase = function() {
      player = $.getActor("Player").getNow();
      var chase = this.findActor($.getActor("Player"));
      chaser = this.getNow();
      if ($.compare(player, this.getNow()) || wait > 0) {
        wait++;
        if (wait > 50) { 
          wait = 0;
          if (chase) 
            this.trigger(chase);          
        }
      } else {        
        if (chase) 
          this.trigger(chase);    
      }                      
      this.move();           
    }
  }
  chaser.prototype = NPC(actor);
  return new chaser(distToChase)
}

/*------------------------------------------------------------------
* -- GUARDIAN CHASER ENEMY --
* ------------------------------------------------------------------ */
function NPC_GuardianChaser(actor, distToChase) {
  function guardianChaser() {
    this.type = GUARDIAN_CHASER;
    var startLoc = (function() { return [actor.getLoc()[X], actor.getLoc()[Y]]})();
    this.getStartLoc = function() { return startLoc; }
    this.chase = function() {
      var intruder = this.findActor($.getActor("Player"), startLoc);
      if (intruder && this.isIdle() && !$.getActor("Player").isInjured())
        this.trigger(intruder);       
      else {
        if (this.isIdle() && !$.compare(this.getNow(), startLoc))
          this.trigger(startLoc);
      }
      this.move();           
    }
  }
  guardianChaser.prototype = NPC_Chaser(actor, distToChase);
  return new guardianChaser();
}
