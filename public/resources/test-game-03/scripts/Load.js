function Load() {
  alert("~~~ Test HTML 5 Game by Jonathan Edwards ~~~\n\n" +
        "This pre-alpha demo is currently compatible with modern versions of Firefox and Chrome.\n" + 
        "It was written entirely in Javascript using the standard HTML5 API (no additional libraries). \n" +
        "Guide the blue rectangle around by left clicking on the grid. Take care to avoid the pink baddies and the missiles, 3 hits and it's game over!\n\n" +
        "Notes: \n - Ensure the zoom is set to 100% for best results.\n - Internet Explorer is not currently supported.")
  this.debug = true;
  this.canvas = document.getElementById("canvas");
  this.context = this.canvas.getContext('2d');
  this.client = new Client(this.canvas);
  this.compare = function(a, b) { return (a[X] === b[X] && a[Y] === b[Y]); }

  this.getActor = function(name) {
    var i, c;
    var m = MANIFEST;
    if (m.length)
      for (c in m) 
        if (m[c].constructor === Actor && m[c].getName() === name)
          return m[c];
    return false;
  } 
  this.cls = function(from, to) {
    this.context.clearRect(
      from ? from[X] : 0, 
      from ? from[Y] : 0, 
      to ? to[W] : this.client.getWidth(), 
      to ? to[H] : this.client.getHeight())
  }
  this.mouse = [0,0];
  this.oRegister = new ObstacleRegister();
  this.pRegister = new ProjectileRegister();
  
}
// -- CONSTANTS --
// ---------------
  var UNVISITED = -1, 
  X = W = COS = COORDS = DIRECTION = 0, 
  Y = H = SIN = DISTANCE = OBSTACLE = 1, 
  ACTOR = UP = 2, 
  PLAYER = DOWN = 3, 
  ENEMY = LEFT = 4, 
  RIGHT = 5,
  PATROLLER = 6,
  CHASER = 7,
  GUARDIAN_CHASER = 8;
  PROJECTILE = 50;
  var X_Y = [X, Y];

// -- LOAD EVENTS --
// -----------------
(function() {
  this.$ = new Load()
  // assign mouse's coordinate on map to the $ object.
  this.$.canvas.addEventListener('mousemove', function(evt) {
    var c = this.getBoundingClientRect();
    window.$.mouse[X] = Math.round((evt.clientX-c.left) /(c.right-c.left)*this.width); 
    window.$.mouse[Y] = Math.round((evt.clientY-c.top) /(c.bottom-c.top)*this.height);
  });
  this.DELTA = 0;
  // Actor Manifest
  this.MANIFEST = [
    Player(new Actor("Player", [5,5], 1.1, 'blue')),   
    NPC_Patroller(new Actor("Baddy1", [3,1], 0.2), [[1,1], [1,7], [1,1], [3,1]]),
    NPC_Chaser(new Actor("Baddy2", [4,9], 0.4), 3),
    NPC_GuardianChaser(new Actor("Baddy3", [8,3], 0.7), 3),
    new Obstacle([2,2]),
    new Obstacle([6,6]),
    new Obstacle([4,1]),
    new Obstacle([3,2]), 
    new Obstacle([8,9]),
    new Obstacle([7,7]),
    new Obstacle([5,9]),
    new Obstacle([6,9]),
    new Obstacle([7,9]), 
    new Obstacle([8,9])    
  ]
  
  })()