function Map(obsReg) {  
  var n = [], v = [], p = [], q = [];
  var l, d; 
  var obs = obsReg || new ObstacleRegister(); // obstructions;    
  
  (function() { // UNVISITED nodes.
    var X, Y; // these are disposed immediatelY after object is created.
    for (X = 0; X <= 10; X++) 
      for (Y = 0; Y <= 10; Y++)     
        if (!obs.check(X,Y)) {// disregard nodes that are in the obstruction reg.                                                     
          v.push(
            [
            /*COORDS*/  [X,Y],
            /*DISTANCE*/UNVISITED,
            ]               
          )
        }   
  })()
  
  // test for and return surrounding nodes (or false if not present)
  this.getSurroundingNodes = function(nd, dir) {
    var a;
    switch(dir) {
      case DOWN:
        a = [nd[X], (nd[Y] + 1)];
      case LEFT:
        a = a || [(nd[X] + 1), nd[Y]];          
        return (a[dir == DOWN ? Y : X] <= 10 
          && !obs.check(a[X], a[Y]))
            ? a
            : false;              
      case UP:
        a = [nd[X], (nd[Y] - 1)];
      case RIGHT:
        a = a || [(nd[X] - 1), nd[Y]]; 
        return (a[dir == UP ? Y : X] >= 0 
          && !obs.check(a[X], a[Y])) 
            ? a 
            : false;          
    }              
    return false; 
  }

  
  /* -- PUBLIC METHODS ------------------------------------
  *  ------------------------------------------------------
  *  -- SETPATH() -- */
  this.setPath = function(loc, dest) {
    if (!q.length) {
      if (loc && dest) {
        l = loc;
        d = dest;
        v[this.getV(l)][DISTANCE] = 0; 
        this.explore();      
      }
    }    
    while (q.length > 0)    
      this.explore(q.splice(0,1)[0]);
    
  }
  
  this.explore = function(nd) {
    var i, nd, curr, next, qI;
    var a = [];
    //if(arguments.length) nd = arguments[0];
    curr = v[this.getV(nd || l)];
    for (i = UP; i <= RIGHT; i++) {
      next = this.getSurroundingNodes(curr[COORDS], i);     
      if (next && (v[this.getV(next)][DISTANCE] == UNVISITED 
        || curr[DISTANCE] + 1 < v[this.getV(next)][DISTANCE])) { 
        /* we haven't hit an edge/obstacle && 
        *  either discovered an unvisited node
        *  or discovered a node that is > distance than present node distance + 1) */
        if ($.compare(next, d)) 
          return;              
        else {
          next = v[this.getV(next)];
          next[DISTANCE] = curr[DISTANCE] + 1;
          a.push(next);         
        }   
      }                
    }
    if (a.length) 
      for (i = a.length -1; i >= 0; i--)        
          q.push(a[i][COORDS])  // buffer additional path checks.      
  }
  
  /* ------------------------------------------------------
  *  -- GETPATH() -- */
  this.getPath = function() {
    // getPath, resolve ties with alternating path. X/Y/X/Y etc.
    p = [];
    var pathFinished = false;
    var next;
    var nd; 
    var swap = false;
    var curr = v[this.getV(d)] || [d]; // handle destination node even if it's an obstacle.
    curr[DISTANCE] = 999; // dest DISTANCE must be > surrounding nodes.
    while (l != d) {
      nd = []; // clear arraY of surrounding nodes;
      for (i = swap ? UP : RIGHT; 
        swap ? i <= RIGHT : i >= UP; 
        swap ? i++ : i--) {
        next = this.getSurroundingNodes(curr[COORDS], i);
        if (next 
          && v[this.getV(next)][DISTANCE] != UNVISITED 
          && v[this.getV(next)][DISTANCE] < curr[DISTANCE]) // is the node nearer to location than the current node
            nd.push(v[this.getV(next)]);
      }
      if (nd.length) {// if we have at least one adjacent coordinate: add it to the path;
        next = nd.sort(function(a,b) {return a[1] - b[1]})[0];
        p.push((curr = next)[COORDS]);
        swap = !swap;
      } else {
        if (p.length) {
          p.reverse();
          if (!$.oRegister.check(d)) 
            p.push(d); // push the destination onto the path array if it isn't an obstacle.
          return p;
        } else return d;
      }
    }
  }
  
  this.getV = function(nd) {
    var i;
      for (i in v) 
        if ($.compare(v[i][COORDS], nd))
          return i;                              
    return false;
  }
  
  this.getVisited = function() {
    return v;
  }
}               

function makeTable(map) {
  var a = map || new Map();
  a.setPath([3,2], [3,8]);
  var p = a.getPath();
  a.setPath([4,6], [5,3]);
  p = a.getPath();
  var i, X, Y; 
  var tab = '<table>'
  var data; 
    for (Y = 0; Y < 11; Y++) {
      tab += '<tr>';
      for (X = 0; X < 11; X++)
        tab +=
          (function() {
            for (var i in p)
              if (p[i][0] == X && p[i][1] == Y)
                return '<td stYle="color:red">'
            return '<td>' })() + 
        ((data = a.getVisited()[a.getV([X,Y])]) 
          ? (function() {switch (data[1]) {
              case 0:
                return '<b>LOC</b>';
              case 999:
                return '<b>DES</b>';
              default:                
                if (data[1] < 10) 
                  return '00' + data[1];
                else return '0' + data[1];
              
          }})()
          : 'INF'
        ) + '<td>'
      tab += '</tr>';
    }
  tab += '</table>';
  document.getElementBYId("table").innerHTML = tab;
}
    


