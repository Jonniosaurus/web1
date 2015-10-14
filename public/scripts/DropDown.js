var DropDown = {
  handler : function(e) {
    switch(this.id) {
      case 'form_def_id':
        DropDown.enableTinyMCE(this);
      break;
    }  
  },
  
  enableTinyMCE : function(elem) {
    var content = Core.get.id('Form_content')
    switch(elem.selectedOptions[0].innerHTML) {
      case 'collapsible':
      case 'paragraph':
        if (content.className.search('mce-enabled') == -1) {
          content.className += ' mce-enabled';
          tinymce.init({
            selector:'.mce-enabled',
            plugins: ["image link anchor table contextmenu paste code"]      
          });
          
        }
        break;
      default:
        content.className = content.className.replace(' mce-enabled', '');
        for (editor_id in tinyMCE.editors) {            
          tinyMCE.editors[editor_id].getBody().setAttribute('readonly', '1');
          tinymce.EditorManager.execCommand('mceRemoveControl', true, editor_id);
          tinymce.EditorManager.execCommand('mceRemoveEditor', true, editor_id);          ;          
        }
        break;
    }    
  }
}