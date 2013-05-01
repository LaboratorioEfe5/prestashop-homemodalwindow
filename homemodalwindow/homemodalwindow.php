<?php
/*
PRESTASHOPMODULE.COM
Updated by somosf5.com
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class HomeModalWindow extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'homemodalwindow';
		$this->tab = 'front_office_features';
		$this->version = '0.1';
		$this->author = 'PrestaShopModul.Com';
		$this->need_instance = 0;

		parent::__construct();
		
		$this->displayName = $this->l('Displays modal window at homepage ');
		$this->description = $this->l('Displays multi-language content in a window (fancybox modal window) at homepage ');
	}

	function install()
	{
		if (!parent::install() ||  !$this->registerHook('home')|| !$this->registerHook('header'))
			return false;
			$lngs  = Language::getLanguages(true);
			$frms = array();
			$ctxt = '<p style="text-align: left;"><a href="https://apps.facebook.com/eticsoft/" target="_blank"><img style="float: left; margin: 0px 10px;" src="modules/homemodalwindow/img/hiring.gif" alt="" /></a></p>
<h2 style="text-align: left;">WE ARE HIRING !</h2>
<p style="text-align: left;">Lorem ipsum dolor sit amet, consectetur adipisicing <br />elit, sed do eiusmod tempor incididunt ut labore et<br /> dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamca.Laboris nisi ut aliquip <br />exea commodo consequat.Duis aute irure dolor in <br />reprehenderit in voluptate velit esse cillum dolore <br />eu fugiat nulla ?pariatur. <br />Excepteur sint occaecat cupidatat non proident, sunt in culpaqui officia deserunt<br /> mollit anim id est laborum??</p>
<p> </p>';
			foreach ($lngs as $lng)
					$frms[$lng['id_lang']] = $ctxt ;
			Configuration::updateValue('HOME_MODAL_WIN', $frms, true);
		return true;
	}
	
	
	public function getContent()
	{
		if (isset($_POST['save_submit']))
		{
			$lngs  = Language::getLanguages(true);
			$frms = array();
			
			foreach ($lngs as $lng){
				$ctxt = str_replace("\n", '', $_POST['form_'.$lng['id_lang']]);
				$ctxt = str_replace("\r", '', $ctxt);
				$ctxt = str_replace("\t", '', $ctxt);
				$frms[$lng['id_lang']] = $ctxt ;
			}
			Configuration::updateValue('HOME_MODAL_WIN', $frms, true);
			$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('ok').'" /> '.$this->l('Settings updated').'</div>';
		}
		$this->_displayForm();
		return $this->_html;
	}

	public function _displayForm(){
		$this->__construct();
		global $cookie;
		$t='';
		$t .= '<img src="../modules/homemodalwindow/logo.gif" style="float:left; margin-right:15px;"><b>'.$this->l('This module shows content by modal window at home page.').'</b><br /><br />';
		$iso = Language::getIsoById((int)($cookie->id_lang));
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
		$ad = dirname($_SERVER["PHP_SELF"]);
		
		
		if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$t .= '
			<script type="text/javascript">	
				var iso = \''.(file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'\' ;
				var pathCSS = \''._THEME_CSS_DIR_.'\' ;
				var ad = \''.dirname($_SERVER['PHP_SELF']).'\' ;
			</script>
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce.inc.js"></script>
			<script language="javascript" type="text/javascript">
				id_language = Number('.$id_lang_default.');
				tinySetup();
			</script>';
		else
		{
			$t .= '
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript">
				tinyMCE.init({
					mode : "textareas",
					theme : "advanced",
					plugins : "safari,pagebreak,style,layer,table,advimage,advlink,inlinepopups,media,searchreplace,contextmenu,paste,directionality,fullscreen",
					theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,,|,forecolor,backcolor",
					theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,ltr,rtl,|,fullscreen",
					theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,pagebreak",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : false,
					content_css : "'.__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/global.css",
					document_base_url : "'.__PS_BASE_URI__.'",
					width: "600",
					height: "auto",
					font_size_style_values : "8pt, 10pt, 12pt, 14pt, 18pt, 24pt, 36pt",
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js",
					elements : "nourlconvert",
					entity_encoding: "raw",
					convert_urls : false,
					language : "'.(file_exists(_PS_ROOT_DIR_.'/js/tinymce/jscripts/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'"
				});
				id_language = Number('.$id_lang_default.');
			</script>';
		}
		
		$t .= '
		
		<script>
			function openCloseLayer (id){
				$(\'div#\'+id).toggle(500);
			}
		</script>
		<form>
		<legend><img src="../img/admin/contact.gif" />'.$this->l('Help - Support').' </legend>
		<fieldset>
			<p>
			<a href="http://www.prestashop-tr.com/forum/home-page-modal-popup-window-t22.html" class="button" style="margin:20px"><img src="../img/admin/tab-groups.gif" />'.$this->l('Forum').' </a> 
			<a href="http://www.prestashopmodul.com/" class="button" style="margin:20px"><img src="../img/admin/search.gif" />'.$this->l('Modules From PrestashopModul.Com').' </a> 
			<span class="button" style="margin:20px"><img src="../img/admin/information.png" />'.$this->displayName.' V '.$this->version.' </span> 
			</p>
		</fieldset>
		</form>
		<br/>
		<br/>
		<div style="clear:both"></div><br/>
		<hr/>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<legend><img src="../img/admin/contact.gif" /> Modal Windows</legend>
			<fieldset>
			<p>'.$this->l(' Type content per language. ').'
		';
		$lngs  = (object)Language::getLanguages(true);
		
		foreach ($lngs as $lng){
			$lng = (object)$lng;
			$t .= '
			<h4 onclick="openCloseLayer(\'lang'.$lng->id_lang.'-f\')" style="cursor:pointer; width:100%; background-color:#fec; padding:10px;" >'
			.$lng->name.' '.$this->l('Content ').' <img src="../img/admin/add.gif" /></h4>
			<div id="lang'.$lng->id_lang.'-f"  style="display:none">
			<textarea cols="80" rows="1" name="form_'.$lng->id_lang.'" class="rte" id="form_'.$lng->id_lang.'" >';
			$t .= Configuration::get('HOME_MODAL_WIN', $lng->id_lang);
			$t .= '
			</textarea>
			</div>';
		}

	$t .= '
		<input type="hidden" name="save_submit" value="yes"/>
		<input type="submit" name="send_proc_btn" class="button" value="'.$this->l('Save Forms').'"/>
		</fieldset>
		</form>';
	$this->_html .= $t;
	
	}

	function hookHome($params)
	{
		global $cookie;
		
		if (!Configuration::get('HOME_MODAL_WIN', $cookie->id_lang) )
			return false;

		if (isset($_GET['dontshowhmw']) && $_GET['dontshowhmw'] == "yes")
			$cookie->__set('homemodal', 1);
		
		
		if (isset($cookie->homemodal) && $cookie->homemodal){
			return false;
		}
		
		global $smarty;
		$dontshowhmwlink = '<br/><a href="?dontshowhmw=yes">'.$this->l(' Dont Show This Again ').'</a>';
		$content = str_replace ("'", '&#39;',  Configuration::get('HOME_MODAL_WIN', $cookie->id_lang).$dontshowhmwlink);
		$smarty->assign(array(
		'content' => $content,
		));

		return $this->display(__FILE__, 'homemodalwindow.tpl');
	}
	public function hookHeader($params)
	{
	    global $smarty, $cookie;
        
		if (version_compare(_PS_VERSION_,'1.5','<'))
        {
		   
		    Tools::addCSS(_PS_CSS_DIR_.'jquery.fancybox-1.3.4.css', 'all');		   
		    Tools::addJS(_PS_JS_DIR_.'jquery/jquery.fancybox-1.3.4.js');
		}
		else
		{
		    
			$this->context->controller->addCSS(_PS_JS_DIR_.'jquery/plugins/fancybox/jquery.fancybox.css', 'all');
			$this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/fancybox/jquery.fancybox.js');
		}

	}
}
