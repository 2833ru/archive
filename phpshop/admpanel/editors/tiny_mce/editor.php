<?php

class Editor {
    var $InstanceName ;
    var $BasePath ;
    var $Width ;
    var $Height ;
    var $ToolbarSet ;
    var $Value ;
    var $Config ;

      function Editor($instanceName, $mod_enabled=false) {
        
        if($mod_enabled)
            $this->BasePath = '../../../admpanel/editors/tiny_mce/';
            else $this->BasePath='../editors/tiny_mce/';

        $this->InstanceName	= $instanceName ;
        $this->Width		= '100%' ;
        $this->Height		= '50' ;
        $this->ToolbarSet	= 'Default' ;
        $this->Value		= '' ;
        $this->Mod		= 'exact' ;
        $this->Config		= array() ;
    }


    function Create() {
        echo $this->CreateHtml() ;
    }


    function AddGUI() {
        return $this->CreateHtml() ;
    }

    function CreateHtml() {
        static $elements;
        $HtmlValue = $this->Value;
        
        if(empty($elements))
            $elements=$this->InstanceName;
        else $elements.=','.$this->InstanceName;
        
        $Html = '<script type="text/javascript" src="'.$this->BasePath.'tiny_mce.js"></script>
<script src="'.$this->BasePath.'plugins/tinybrowser/tb_tinymce.js.php" type="text/javascript"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "'.$this->Mod.'",
	        elements : "'.$elements.'",
		theme : "advanced",
                skin  : "cirkuit",
                relative_urls : false,
                language: "ru",
		plugins : "style,table,advimage,advlink,inlinepopups,preview,media,searchreplace,paste,directionality,fullscreen,noneditable,visualchars,wordcount,advlist",
                extended_valid_elements : "iframe[name|src|framespacing|border|frameborder|scrolling|title|height|width],object[declare|classid|codebase|data|type|codetype|archive|standby|height|width|usemap|name|tabindex|align|border|hspace|vspace]",

                // Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontsizeselect,|,removeformat,charmap,media,fullscreen,|,table,hr,",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,replace,|,outdent,indent,|,undo,redo,|,link,unlink,image,cleanup,code,|,preview,|,forecolor,backcolor",
                theme_advanced_buttons3 : "",
                theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "'.$this->Config['EditorAreaCSS'].'",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

                // Image Browser
                file_browser_callback : "tinyBrowser",

		// Style formats
		style_formats : [
			{title : "Bold text", inline : "b"},

],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<textarea id="'.$this->InstanceName.'" name="'.$this->InstanceName.'" 
    style="width:'. $this->Width.'; height: '.$this->Height.'px">'.$HtmlValue.'</textarea>
' ;


        return $Html ;
    }


}

?>
