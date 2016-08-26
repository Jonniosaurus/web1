var Shape = {  // TODO, investigate putImageData();
  rectangle: function(style) {
    var c, d;
    this.draw = function(coords,size) {
      c = $.client.getFromPcnt(coords);
      d = $.client.getFromPcnt(size);
      $.context.fillStyle = style || "#ff00ff"; // #14e7a5
      $.context.fillRect(
        c[X] - (d[X] / 2), 
        c[Y] - (d[Y] - (d[Y] / 3)), d[X], d[Y]);
    }
  }, 

  actor: function(style) {
    var c, d;
    this.draw = function(coords,size) {
      c = coords;
      d = $.client.getFromPcnt(size);
      $.context.fillStyle = style || "#ff00ff"; // #14e7a5
      $.context.fillRect(
        c[X] - (d[X] / 2), 
        c[Y] - (d[Y] - (d[Y] / 3)), d[X], d[Y]);
    }
  },

  circle: function(style) {
    var s = style;
    this.draw = function(coords,size) {
      $.context.beginPath();
      $.context.arc(coords[X], coords[Y], size || 10, 0, 2 * Math.PI, false);
      $.context.fillStyle = s || 'yellow';
      $.context.fill();
      $.context.strokeStyle = '#003300';
      $.context.stroke();
    }
  },  

  grid: function() {
    this.draw = function() {
    var g = [0,0];
    var i, from, to;
    $.context.beginPath();
    for (i in g) 
      for (g[i] = 0; g[i] < 105; g[i] += 9.1) {
        from =  $.client.getFromPcnt(
            [(i == X) ? g[i] : 0,
            (i == Y) ? g[i] : 0]
          )
        to = $.client.getFromPcnt(
            [(i == X) ? g[i] : 100,
            (i == Y) ? g[i] : 100]
          )
        
        $.context.moveTo(from[X], from[Y]);
        $.context.lineTo(to[X], to[Y]);
        
      }
      $.context.stroke();
    }
  }
}