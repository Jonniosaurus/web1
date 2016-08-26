function ProjectileRegister() {
  var i, ii, x, y, a, p;
  var pReg = [];
  this.add = function(coords) { pReg.push(coords); }
  this.get = function() { return pReg; }  
  this.remove = function(projectile) {    
    if ((p = this.check(projectile)) > -1) 
      pReg.splice(p, 1);      
  }
  this.check = function(projectile) {    
        for (var i in pReg)
          if (pReg[i] === projectile)
            return i;           
    return -1;  
  }      
}



function Projectile(location, speed) {
  // INIT
  var i, des, dir, diff, ang, vel;   
  var mv = $.client.getFromPcnt(speed || 0.3),
    loc = location,
    frm = $.client.getFromNode(location), // assign the same values to a different location in memory.
    circ = new Shape.circle(),
    name = "projectile"; // todo.
  $.pRegister.add(this);
  this.getNow = function() {
    return $.client.getLocalNode(frm);    
  }  
  this.getName = function() {
    return name;    
  }
  
  this.type = PROJECTILE;
  this.trigger = function(destination) {
    des = destination;
    vel = this.getVelocityComponents(mv);
  }
  this.order = function() { return frm; }
  this.fire = function() {
    if (loc && des) {
      if ($.oRegister.check($.client.getLocalNode(frm)) || ($.client.getLocalNode(frm)[X] == 10 || ($.client.getLocalNode(frm)[X] == 0 
        || ($.client.getLocalNode(frm)[Y] == 10 || $.client.getLocalNode(frm)[Y] == 0))))  
        $.pRegister.remove(this); // Done. Delete projectile.
      else 
        for (i in frm)
          frm[i] += $.client.getSpeed(vel[i]);          
      circ.draw(frm, 10);
    } // move projectile toward des.
  }
    
  this.getAngle = function() {    
    diff = [des[X] - loc[X], des[Y] - loc[Y]];
      return Math.atan2(diff[X], diff[Y]);
  }

  this.getVelocityComponents = function(speed){
    ang = this.getAngle();
    return [(Math.sin(ang) * speed), (Math.cos(ang) * speed)]     
  }
}