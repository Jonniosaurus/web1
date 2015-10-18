/* ~~~~~~~~ GLOBAL OBJECT ~~~~~~~~~
* This object holds generic helper methods and would be available to every service.
* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
var entityGlobal = function () {
  // GET HELPERS
  this.GET = {
    a: Xrm.Page.getAttribute,
    c: Xrm.Page.getControl,
    t: function (tab) { return Xrm.Page.ui.tabs.get(tab); },
    s: function (tab, section) { return this.t(tab).sections.get(section); },
    status: function () { return Xrm.Page.ui.getFormType(); },
    
    /* RESTfully query other entities using OData endpoint (see: https://msdn.microsoft.com/en-us/library/gg334767%28v=crm.5%29.aspx)
    *  NB. The below requires https://msdn.microsoft.com/en-us/library/gg334427.aspx#BKMK_SDKREST to work
    *  PARAMETERS (in any order):
    *  string   GUID              - the name of the instance you want to call.
    *  string   entityLogicalName - the full logical name of the
    *  func     successCallback   - function to call on a 200 
    *  string[] selectedFields    - (optional) array of fields by schema name */    
    endPoint: function () { 
      var GUID, logicalName, callback, select = '';
      var i, ii;      
      try {
        // Parse argument functions
        if (arguments.length < 3) throw 'request requires a record GUID, a logical name and a success callback (in any order).'
        for (i in arguments) 
          switch(typeof(arguments[i])) {
            // GUID and Logical Name
            case 'string':
              if (arguments[i].search(/[A-F0-9]{8}(?:-[A-F0-9]{4}){3}-[A-F0-9]{12}/) > -1) // Handle GUID parameter
                GUID = (arguments[i].search('{') < 0 ? '{' : '') 
                  + arguments[i] + (arguments[i].search('}') < 0 ? '}' : '');
              else logicalName = arguemnts[i];
              break;
            // Selected Fields as an array of strings (optional)
            case 'object': // Array of comma-separated values for CRM to handle 
              for (ii in arguments[i]) {
                select += (ii > 1 ? ',' : '') + arguments[i];
              }
              break;
            // The function to call back on success.
            case 'function':
              callback = arguments[i];
              break;
          }
        
        if (GUID && logicalName && callback) { 
          SDK.REST.retrieveRecord(
            GUID, 
            logicalName, 
            (select) ? select : null, 
            null, 
            callback, 
            function (error) { throw error.message; }
          );      
        } else throw 'one or more argument parameters are missing from Service.endpoint().' + 
            'local variables are,\n GUID: ' + GUID + 
            '\n logicalName: ' + logicalName + 
            '\n callBack: ' + callback ? callback.toString().substring(0, 30) + '...' : 'undefined';                     
      } catch (ex) {
        alert(ex.message);
      }
    }
  };
  
  // SET HELPERS
  this.SET = {
    // set a field as visible & required||set a field as invisible & unrequired.
    visibleAndRequired: function (attribName, value) { 
      Entity.GET.a(attribName).setRequiredLevel(value ? "required" : "none");
      Entity.GET.c(attribName).setVisible(value);
      if (!value) Entity.GET.a(attribName).setValue();
    }, 
    // set an optevia message field
    messageKey: function (webResourceName, messageKey) {
      var IFRAME_wr = Entity.GET.c((webResourceName = webResourceName.replace("optevia", "WebResource"))).getObject(),
        wra = $get(webResourceName).src.split("?"),
        src = wra[0];
      src += "?data=messageKey%3d" + messageKey;
      IFRAME_wr.src = src;
      return messageKey;
    },
    // set an optionset using its text value rather than an integer.
    optionSet: function(attribName, text) {
      var option = Entity.GET.a(attribName).getOptions();
      for (var i in option) {
        if (option[i].text == text) {
          Entity.GET.a(attribName).setValue(option[i].value)
          return;
        } 
      }
    },
    // use CRM's foreach function with a delegated callback.  Useful for enabling/disabling etc. en masse.
    forEachSectionControl: function (tabName, sectionName, delegateCallback) {
      Entity.GET.s(tabName, sectionName).controls.forEach(
        function (c) {
          switch (c.getControlType()) {
            case "lookup":
            case "standard":
            case "optionset":
              delegateCallback(c);
              break;
          }
        }
      );
    }
  };
  this.STATUS = { create: 1, update: 2, readonly: 3, disabled: 4, quickcreate: 5, bulkedit: 6, optimized: 7 };
  this.HOST = location.hostname;
}

/* ~~~~~~~~ LOCAL OBJECT ~~~~~~~~~
* Each entity form would implement one of the below.
* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function EntityParent(entityGlobal) {
  function entity() {    
  // ---------------------------------------
  // Catch-all methods ---------------------
    this.onLoad = function () {
      // status
      switch (this.GET.status()) {
        case this.STATUS.create: // do something onCreate
          break;          
        case this.STATUS.update: // do something onUpdate
          break;
        // etc.
      }
    }

    this.onSave = function (executionObj) {     

    }

    this.onChange = function (executionObj) {
      var attribute;
      try { attribute = executionObj.getEventSource(); } catch (e) { 
        try { attribute = executionObj; } catch (e) { alert(e.message); }}      

      if (attribute) {
        switch (attribute.getName()) {
          // each field is pointed to its appropriate method.
          case "new_implementationfield":
            this.implementationField_onchange(attribute);
            break;
        }
      }
    }
  // ---------------------------------------
  // local field methods -------------------
    this.implementationField_onchange = function (attribute) {   
      if (attribute.getValue) {
        this.GET.a("new_fieldtochange").setValue("altered value");
        this.SET.optionSet("new_fieldtochange", "option");
      }
    }
  }
// ---------------------------------------
// NB: DO NOT CHANGE ---------------------
  entity.prototype = entityGlobal;
  return new entity();  
}

/* ~~~~~~~~ CALLING OBJECT ~~~~~~~~
* This object is called for all events. For example: fields requiring on_change events would call "Entity.onChange" and pass in their execution object.
* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
var Entity = EntityParent(new EntityGlobal());
// -----------------------
