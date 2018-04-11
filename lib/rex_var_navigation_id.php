<?php
// rex var navigation id

class rex_var_navigation_id extends rex_var {

	// welche id gibts denn?
	

	protected function getOutput() {
		// hier ausgabe der navi
		// nav name aus rex_navigation_id
		$nav_id = self::getArg(0);

		// query db
		$sql = rex_sql::factory();
		$sql->setTable( rex::getTablePrefix(). "navigation" )->setWhere( [ 'nav_name' => $nav_id ] )->select();

		// get rows
		if($sql->getRows()) { // nicht 0!
			//$sql->hasNext(); 
		    $nid = $sql->getValue('id');
		    //$ntype = $sql->getValue('type');
			$nbid = $sql->getValue('base_id');
			$jsonArr = json_encode(array("navigation" => $nav_id));
			error_log($jsonArr);

			return self::toArray($jsonArr);
		} else return self::quote("<div>Keine passende Navigation gefunden - Navigations ID unbekannt . </div>\n");

    	
  	}
}


?>