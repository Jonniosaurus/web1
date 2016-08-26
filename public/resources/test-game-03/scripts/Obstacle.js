function ObstacleRegister() {
  var i, ii, x, y, a;
  var o = [];
  this.add = function(coords) { o.push(coords); }
  this.get = function() { return o; }
  
  // takes either (x,y) or ([x,y])
  this.check = function() {    
    if (o.length) {
      if (arguments.length == 2) {
        x = arguments[0]
        y = arguments[1]
        for (i in o) 
          if (o[i][0] == x && o[i][1] == y)
            return true;
      }
      else if (arguments.length == 1) {
        a = arguments[0]
        for (var i in o)
          if (o[i][0] == a[X] && o[i][1] == a[Y])
            return true;        
      }
    }
    return false;
  }
}

function Obstacle(coords) {
  var c = coords;
  this.type = OBSTACLE;
  (function() {$.oRegister.add(coords)})()
  this.draw = function(size) {
    var o = new Shape.rectangle("#14e7a5");
    o.draw($.client.getPercent(coords),size || [7, 10]);
  }  
  this.order = function() {return $.client.getFromNode(c);}
}