<?php
/*
PRESTASHOPMODULE.COM
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
		if (!parent::install() OR !$this->registerHook('home'))
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
		if (_PS_VERSION_ < '1.4.0.0')
		$t .='<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
		<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>';
		else
		$t .='
			<script type="text/javascript">	
			var iso = \''.$isoTinyMCE.'\' ;
			var pathCSS = \''._THEME_CSS_DIR_.'\' ;
			var ad = \''.$ad.'\' ;
			</script>
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce.inc.js"></script>
		';
		
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
}
