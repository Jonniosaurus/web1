function Client(c) {
  var c = c.getBoundingClientRect();
  var i;
  // nodes as percentage of canvas from 0 to 10;
  var n = (function() {var nodes = []; for (var i = 4.55; i <= 100; i += 9.1) nodes.push(i); return nodes})()
  this.getWidth = function() { return Math.round(c.right - c.left); }
  this.getHeight = function() { return Math.round(c.bottom - c.top); }
  
  // -- GETNODEPERCENT --
  // get a node as a percent of X * Y (where 0x0 = top-left & 100x100 = bottom-right);
  this.getPercent = function(nd) { return [n[nd[X]], n[nd[Y]]]; }
  
  // -- GETFROMPCNT --
  // get the pixel coordinate of X * Y on screen from a percentage.
  this.getFromPcnt = function(pcnt) {
    switch(typeof arguments[0]) {
      case 'object': // -- BOTH AXES
        return [/*X*/ Math.round((pcnt[X] * (c.right - c.left) / 100)),
                /*Y*/ Math.round((pcnt[Y] * (c.bottom - c.top) / 100))];
      case 'number': // -- SINGLE AXIS
        return pcnt * (c.right - c.left) / 100;
    }
  }
  
  // -- GETFROMNODE --
  // get the pixel from a node on 11*11 grid (where [0,0] = top-left & [10,10] = bottom-right.
  this.getFromNode = function(node) { 
    return [/*X*/ Math.round((this.getPercent(node)[X] * (c.right - c.left) / 100)),
            /*Y*/ Math.round((this.getPercent(node)[Y] * (c.bottom - c.top) / 100))];    
  }
  
  // -- GETLOCALNODE --
  // get the nearest adjacent node to a pixel coordinate.
  this.getLocalNode = function(coords) {
    var i, ii;
    var nd = [];
    var c = coords;
    for (i in c) 
      for (ii in n) // check through each possible
        if (c[i] >= this.getFromPcnt(n[ii] - 4.55)
            && c[i] <= this.getFromPcnt(n[ii] + 4.55))
              nd.push(parseInt(ii));
    return (nd.length == 2)
      ? nd
      : false
  }
  
  // -- REFRESHRATE --
  // Calculate the refresh rate and any movement compensation required.
  var oldTime = 0,
    frameTime = 1000/60, // 60 FPS
    timeElapsed = 0,
    modifier = 0;
  this.refreshRate = function(time) {
    timeElapsed = time - oldTime; // get difference;
    oldTime = time;
    modifier = timeElapsed / frameTime; 
  }
  this.getElapsedTime= function() { return timeElapsed;}
  // speed managed with time.
  this.getSpeed = function(speed) {    
    return speed * modifier;    
  }  
}
