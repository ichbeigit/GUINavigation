<?php

class GUINavigation {

	use rex_factory_trait;

	// vars
	private $nana; // navigation name
	private $sql;
	private $unknownNameMessage;

	// environment
	private $clang_id; // current lang id
	private $cart_id; // current article id
   	private $ccat_id = false; // current category id
   	private $root = false;
   	private $path;
   	private $last_cat_in_path = false;
  	private $ssaid; // site start article id
   	private $langs;
   	private $art;
   	private $io = true; // igonre offlines

	// settings
	private $nav_type;
	private $nav_unit;
	private $nuc; // nav unit cat
	private $base_id;
	private $lsp; // list starting point
	private $depth;
	private $los; // link on self
	private $cc; // current class
	private $alc; // active link class
	private $ii; // indivdual id
	private $home;
	private $exclude;
	private $separator; 

	// langswitch
	private $ls_if_necessary; // if necessary
	private $ls_show_active; //  show active
	private $ls_show_offline; // show offline
	private $ls_offline_class; // offline class

	// intern
	private $workDepth;
	private $ctxtStart;
	private $ctxtStartDepth;



	protected function __construct($navName){

		$this->nana =  $navName;

		$this->sql = rex_sql::factory();

		$this->sql->setTable( rex::getTablePrefix(). "guinavigation" )->setWhere( [ 'nav_name' => $this->nana] )->select();

		
		$this->unknownNameMessage = rex_i18n::msg('guinav_unknown_message') . " - " . $this->nana;

		if($this->sql->getRows()){

			// settings
			$this->nav_type = $this->sql->getValue('nav_type');
			$this->nav_unit = $this->sql->getValue('nav_unit');
			$this->nuc = ( $this->nav_unit == "cat" );
			$this->base_id = intval($this->sql->getValue('base_id'));
			$this->lsp = $this->sql->getValue('list_starting_point');
			$this->depth = intval($this->sql->getValue('depth'));
			$this->los = $this->sql->getValue('link_on_self');
			$cc = $this->sql->getValue('current_class');
			$this->cc = $cc === NULL ? false : $cc;
			$this->alc = $this->sql->getValue('active_link_class');
			$this->ii = $this->sql->getValue('individual_id');
			$h = $this->sql->getValue('home');
			$this->home = strlen($h) ?  $h : false; 
			$ex = $this->sql->getValue('exclude');
			$this->exclude = strlen($ex) ? explode(",", $ex) : array();
			$lfsc = $this->sql->getValue('link_first'); // link first subcat
			$this->linkFirst = strlen($lfsc) ? explode(",", $lfsc ) : array();
			$this->ctxtStartDepth = $this->sql->getValue('context_start_depth');
			$ss = $this->sql->getValue('separator_string');
			$this->separator = strlen($ss) ? $ss : " ";

			// simple nav
			$sl = $this->sql->getValue('simple_link');
			$this->sl = strlen($sl) ? explode(",", $sl ) : array();

			// langswitch
			$this->ls_show_active = strlen($this->sql->getValue('langswitch_show_active')) ? true : false;
			$this->ls_show_offline = strlen($this->sql->getValue('langswitch_show_offline')) ? true: false;
			$this->ls_offline_class = $this->sql->getValue('langswitch_offline_class');

		} else {

			$this->notFound();
			return false;

		}

		// check root
		if($this->base_id === 0) $this->root = true;

		// environment
		$this->clang_id = rex_clang::getCurrentId();
		$this->langs = rex_clang::getAll();	
		$this->cart_id = rex_article::getCurrentId();
		$this->ssaid = rex_article::getSiteStartArticleId();
		$this->art = rex_article::getCurrent($this->clang_id);
		$this->ccat_id = $this->art->getCategoryId();
		$this->path = explode( "|", trim("0" . $this->art->getPath(), "|") );
		$last_path_el = $this->path[ ( count($this->path)-1 ) ];

		if( $last_path_el  != $this->cart_id ) {

			$this->last_cat_in_path = $last_path_el;

			// article am Ende einfügen - für breadcrumb
			array_push($this->path, $this->cart_id);
		}

		$this->workDepth = 1;

		// context
		if($this->nav_type == "context"){

			if($this->depth === 0) return false; // mis config error

			$cp = count($this->path)-1;

			switch(true){
				case ($this->base_id < 0): 
					// von aktuellem standpunkt nach oben
					//$cp = array_search($this->cart_id, $this->path);
                    $this->ctxtStart = $this->path[$cp + $this->base_id]; 
                    // echo $this->path[$cp + $this->depth]; 
                    // var_dump($this->ctxtStart);
                    break;
                case ($this->base_id > 0): 
                	// ab ebene
                    $this->ctxtStart = $this->path[$this->base_id];
                    break;
                case ($this->base_id === 0): 
                	// aktueller standpunkt nach unten
                	$pc = count($this->path)-1;
                    $this->ctxtStart = $this->path[($this->last_cat_in_path !== false ? $pc : $pc - 1)]; 	
                    break;
			}

		} else if ($this->nav_type == "static" and $this->depth === "0") return false; // keine Tiefe, nix ausgeben

		// navigation holen
		$this->get();

	}	


	public static function factory($navName){

		$class = static::getFactoryClass();

		return new $class($navName);

	}


	private function notFound(){

		print($this->unknownNameMessage);	

	}


	private function get(){

		// was für eine navi ist es 
		switch($this->nav_type){

			case "simple" : $this->getSimple();
			break;

			case "static" : $this->getStatic();
			break;

			case "context": $this->getCtxt();
			break;

			case "breadcrumb": $this->getBreadCrumb();
			break;

			case "langswitch": $this->getLangSwitch();
			break;

			default: $this->notFound();

		}

	}

	/**********************************/
	/***    einfache Navigation 	***/
	/**********************************/

	private function getSimple(){

		$linkArr = array();

		foreach($this->sl as $v){

			$art = rex_article::get($v);
			$linkArr[] = array( $this->getLStr($v,$art) );

		}
		
		if( $this->ulLinks( $linkArr ) ) return true;

		else return false;
	}


	/**********************************/
	/***   statische Navigation    	***/
	/**********************************/

	private function getStatic() {

		if( ! $linkArr = $this->getLinks() ) return false;

		// startpunkt einfügen  list start point
		if( $this->lsp and !$this->root and !$this->home){

			$linkArr = $this->homeIn($linkArr, $this->base_id);

		}

		

		if(!$this->nuc) {

			$linkArr = $this->getArtArr($linkArr);

			if($this->root) {

				$rootarts = $this->getArtLinks();
				$rootartskeys = array_keys($rootarts);
				$linkArr = array( $rootartskeys[0] => ($rootarts + array($linkArr)));

			}
		}

		// home
		if($this->home) $linkArr = $this->homeIn($linkArr);

		// nav ausgeben
		if( $this->ulLinks($linkArr) ) return true;

		else return false;
	}


	/**********************************/
	/***     Context Navigation 	***/
	/**********************************/

	private function getCtxt() {

		// ebene checken
		$curr_depth =  count($this->path) - ($this->last_cat_in_path !== false ? 0 : 1);

		if( $curr_depth <= $this->ctxtStartDepth) return false;
	
		$FLObjs = $this->getChildren($this->ctxtStart);

		if(!$FLObjs) return false;

		$linkArr = $this->getLinks($FLObjs);

		if(!$this->nuc) $linkArr = $this->getArtArr($linkArr);

		// startpunkt einfügen bei contextueller nav
		$ctb = array( array($linkArr) );
		$linkArr = $this->homeIn($linkArr, $this->ctxtStart);
			
		// home
		if($this->home) $linkArr = $this->homeIn($linkArr);
		
		// nav ausgeben
		if( $this->ulLinks($linkArr) ) return true;
		else return false;

	}

	/**********************************/
	/*** Sprachumschalter ausgeben 	***/
	/**********************************/

	private function getLangSwitch(){

		$allLangs = rex_clang::getAll(true);
		$linkArr = array();

		foreach($allLangs as $la){

			$ca = array();
			$lssoff = false;
			$laid = $la->getId();

			// skip activ lang 
			if(( $alang = ( $laid == $this->clang_id ) ) and !$this->ls_show_active) continue;

			$art = rex_article::get($this->cart_id, $laid);

			// show offline
			if( !$art->isOnline() and ( $this->ls_show_offline or ($alang and $this->ls_show_active) ) ){

				$ca[] = $this->ls_offline_class;
				$lssoff = true;

			} elseif (!$art->isOnline()) continue;


			$url = rex_getUrl($this->cart_id, $laid);
			$nm = $la->getName();

			if($alang) $ca[] = $this->alc;

			$alang_class = count($ca) ? " class='" . implode(" ", $ca) . "'" : "";

			$linkArr[] =  (($alang and $this->los) or (!$alang and !$lssoff)) ? "<a href='$url'$alang_class>$nm</a>" : "<span$alang_class>$nm</span>";

		}

		if( count($linkArr) > 0  and !in_array($this->cart_id, $this->exclude)) echo "<div class='guinav-$this->nana'>" . implode($this->separator, $linkArr) . "</div>";
		
	}


	/**********************************/
	/*** Breadcrumb nav ausgeben 	***/
	/**********************************/

	private function getBreadCrumb(){

		$bcl_arr = array();
		$path_len = count($this->path);

		foreach($this->path as $k => $v){
			
			// ausgeschlossene Artikel, oder site start article
			if( in_array($v, $this->exclude) or intval($v) === 0 or  $v == $this->ssaid ) continue; 

			else {
				
				// wenn letzter Punkt ein Artikel ist
				if($k == ($path_len -1) and $this->ccat_id != $v){

					$bco = $this->art;

				} else {

					$bco = rex_category::get($v);

				}

				$bcl_arr[] = $this->getLStr($v, $bco);
			}
			
		}

		// startartikel einbeziehen
		if($this->home) {
			array_unshift($bcl_arr, $this->getLStr($this->ssaid, rex_article::get($this->ssaid))); 
		}
	
		echo "<div class='guinav-$this->nana'>" . implode($this->separator, $bcl_arr) . "</div>";

	}

	/* get children 
	gibt die  kinder zurück, egal ob cat oder art
	parameter:
	nxlid - next level id 
	*/

	private function getChildren($nxlid = false){

		$bid = $nxlid ? $nxlid : $this->base_id;

		if($this->root and !$nxlid){
	
			$nos = rex_category::getRootCategories($this->io, $this->clang_id);

		} else {

			$start_cat = rex_category::get($bid, $this->clang_id);

			if( ! ($start_cat instanceOf rex_category) ) return false;

			$nos = $start_cat->getChildren($this->io);

		}

		if(count($nos) < 1) return false;

		$FLObjs = false;

		foreach($nos as $v) {
			
			$FLObjs[$v->getId()] = $v;

		}

      	return $FLObjs;
	}

	/* 
	link String erzeugen 
	kid -  article/category id
	vo - article/category object
	*/

	private function getLStr($kid,$vo){

		// eltern objecte markieren
		$oacc = false;
		if( $kid == $this->cart_id ) {

				$oacc[] =  $this->alc;

		} else if( in_array( $kid, $this->path ) and $this->nav_type != "simple" and $this->cc ) {

			if($this->nuc or $this->last_cat_in_path != $kid)  $oacc[] =  $this->cc;

		}

		if($this->depth >=  3) $oacc[] = "level-" . $this->workDepth;
		if($kid == $this->ssaid) $oacc[] = "site-start";
		$iistr = $this->ii ? " id='id-$kid' " : ""; 

		
		// link first sub categorie
		if(in_array($kid, $this->linkFirst)) {
			if($sc = $this->getChildren($kid, false)){
				$fsc = array_shift($sc);
				$kid = $fsc->getId();
			}
			$oacc[] = "link-first-subcat"; // link first sub
		}

		$lstrc = is_array($oacc) ? " class='" . trim(implode(" ", $oacc)) . "'" : "";

		if(!$this->los and $this->cart_id == $kid){

			// nicht auf sich selbst verlinken
			$ls = "<span$lstrc$iistr>" . $vo->getName() . "</span>";

		} else {

			$ls =  '<a href="' . rex_getUrl($kid,$this->clang_id) . '"' . $lstrc . $iistr . '>' . $vo->getName() . "</a>";
			
		}

		return $ls;

	}

	/* get Kategorie Links */

	private function getLinks($FLObjs = false){

		// ohne objecte
		// init kind-cats holen
		if(!$FLObjs) $FLObjs = $this->getChildren();

		$linkArr = array();

		foreach($FLObjs as $kid => $vo){

			if( in_array($kid, $this->exclude) ) continue; // ausgeschlossene Artikel

			// link_first

			$linkArr[$kid] = $this->nuc ? array( $this->getLStr($kid, $vo) ) : array($vo);

			// weitere ebenen 
			if($this->workDepth < $this->depth or $this->depth == (int) -1 ){

				$this->workDepth++;
				$nxlc = $this->getChildren($kid);
				
				if($nxlc){

					$nxlcl = $this->getLinks($nxlc);  

					// ohne key wird eine neues array angefügt
					$ak = array_keys($linkArr);
					$linkArr[ array_pop($ak) ][] = $nxlcl;

				} 

				$this->workDepth--;

			} 
			
		}
		
		return $linkArr;

	}

	/***
	get article links
	linkArr - link array (array)
	***/

	private function getArtArr($linkArr){

		$nla = array();
		// $addrootarts = false;
		
		foreach ($linkArr as $k => $v) {

			if($k === 0) {
			
				continue;

			} 

			$galarr = $this->getArtLinks($v[0]);
			$cv = count($v) > 1;

			foreach ( $galarr as $ka => $va){

				$nla[$k][$ka] = $va;
				if( $cv and $k == $ka) $nla[$k][1] = $this->getArtArr($v[1]);

			} 
			
		}

		return $nla;

	}




	private function getArtLinks($cat = false){

		$ala = array();

		if($cat) {

			$arts = $cat->getArticles(true);

		} else {

			$arts = rex_article::getRootArticles(true);

		}

		foreach($arts as $v) {

			$vid = $v->getId();
			// site start article skip
			if($vid == $this->ssaid) continue;
			$ala[$vid] = $this->getLStr($vid, $v);

 		}

 		return $ala;
	}


	/* ulLinks -  in verschachtelte uls packen 
	linkArr - linkstr in an array
	$lno - level number
	*/

	private function ulLinks($linkArr, $lno = 1){

		$navStr = $lno > 1 ? "<ul class='lvl$lno'>" : "<ul class='guinav-$this->nana'>";

		foreach($linkArr as $v){

			$ak = array_keys($v);
			$ac = 0;

			foreach($v as $kk => $vv) {

				$ac++;

				if(is_array($vv)) continue;

				$navStr .= "<li>" . $vv; 
			
				if( $ac < count($v) and is_array($v[$ak[$ac]]) ){
					// 
					$navStr .= $this->ulLinks($v[$ak[$ac]], ($lno+1) );
				}  
	
				$navStr .= "</li>\n";
			}

		}

		$navStr .= "</ul>";

		if( $lno === 1) {

			print $navStr;
			return true;

		} else return $navStr;

	}

	/* 
	home link hinzufügen 
	linkArr - link array
	id - id article (int)
	pos - position (start/end)
	*/

	private function homeIn($linkArr, $id = false, $pos = false){

		if($id) {

			$iid = $id;
			$ao = $this->nuc ? rex_category::get($iid, $this->clang_id) : rex_article::get($iid, $this->clang_id);

		} else {

			$iid = $this->ssaid;
			$ao = rex_article::get($iid, $this->clang_id);

		}

		$pos = $pos ? $pos : $this->home;

		if($id){

			$linkArr =  array( $iid => array( $iid => $this->getLStr($iid, $ao) , $linkArr) );

		} else {

			$lnkhm = array( $iid => array( $iid => $this->getLStr($iid, $ao) ) );
		
			if($pos == "end") $linkArr = $linkArr + $lnkhm;

			else $linkArr = $lnkhm + $linkArr;

		}

		return $linkArr;

	}
}

?>