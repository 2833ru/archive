<?php

class Editor {

    var $InstanceName;
    var $Width;
    var $Height;
    var $Value;

    function Editor($instanceName) {
        $this->InstanceName = $instanceName;
        $this->Width = '100%';
        $this->Height = '300';
        $this->Value = '';
    }

    function AddGUI() {
        return $this->Textarea();
    }

    // Отключенный редактор
    function Textarea() {
        if (strpos($this->Width, '%') === false)
            $WidthCSS = $this->Width . 'px';
        else
            $WidthCSS = $this->Width;

        if (strpos($this->Height, '%') === false)
            $HeightCSS = $this->Height . 'px';
        else
            $HeightCSS = $this->Height;

        $html ='
        <script src="./editors/tinymce/tinymce.min.js"></script>
        ';
        
            
        $html.= "
        <script>
        $(function(){
        tinymce.init({ 
        selector:'textarea[name=".$this->InstanceName."]',
        menubar: false,
        init_instance_callback: function (editor) {
        editor.on('Change', function (e) {
        $('textarea[name=".$this->InstanceName."]').html(tinymce.activeEditor.getContent());
        });
        },
        theme: 'modern',
        file_browser_callback : elFinderBrowser,
        directionality : 'ru',
        relative_urls : false,
        remove_script_host : true,
        browser_spellcheck: true,
        language: 'ru',
        insert_toolbar: 'quickimage quicktable',
        selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
        toolbar: [
        'undo redo | styleselect | bold italic  underline strikethrough textcolor textpattern removeformat formats | link image media | alignleft aligncenter alignright table | code fullscreen'
         ],
        plugins: [
        'advlist autolink lists link image charmap print preview anchor table imagetools textpattern media textcolor searchreplace visualblocks code fullscreen insertdatetime media table contextmenu paste code'
        ],
        });
        })
        
  function elFinderBrowser (field_name, url, type, win) {
  var pathelFinderBrowser = 'image';

  tinymce.activeEditor.windowManager.open({
    
    file: './editors/default/elfinder/elfinder.php?path='+type,// use an absolute path!
    title: 'Найти файл',
    width: 900,  
    height: 520,
    resizable: 'yes'
  }, {
    setUrl: function (url) {
    
      win.document.getElementById(field_name).value = url;
    }
  });
  return false;
}
        </script>";
        $html.='<textarea name="' . $this->InstanceName . '" class="hidden-edit form-control" style="width:'.$WidthCSS.';height:'.$HeightCSS.'">' . $this->Value . '</textarea>';

        return $html;
    }

}

?>
