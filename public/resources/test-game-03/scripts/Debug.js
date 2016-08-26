"use strict"

var Debug = {  
  show: function() {
    var s = new Shape.grid();
    s.draw();
    $.context.font = '18pt Calibri';
    $.context.fillStyle = 'black';
   // $.context.fillText("mouse x " + $.mouse[X] + " mouse y " + $.mouse[Y], 10, 25);
  }
}