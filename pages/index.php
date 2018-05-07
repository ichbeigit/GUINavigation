<?php

echo rex_view::title($this->i18n('guinav_name'));
rex_view::addJsFile( $this->getAssetsUrl('js/guinav.js') );

// function
$func = rex_request('func', 'string');

$content = "";
$title = "";


// welche Seite ist es
$subpage = rex_be_controller::getCurrentPagePart(2);

// löschen
if($func == "delete") {

    $del_id = intval(rex_request('id', 'string'));
  
    if (!is_int($del_id) || empty($del_id)) {

        echo rex_i18n::msg('guinav_delete_error_invalid');

    } else {
    
        $sql = rex_sql::factory();
        $sql->setTable(rex::getTablePrefix() . 'guinavigation');
        $sql->setWhere(['id' => $del_id]);
    
        $sql->delete();
        if($sql->getRows() !== 1) echo rex_i18n::msg('guinav_delete_error_query');

    }

	$func = "";
}


if($func == "" and $subpage != "info"){
	// liste anzeigen

	echo rex_api_function::getMessage();

	$title = rex_i18n::msg("guinav_field_list_caption_$subpage");

	$list = rex_list::factory('SELECT id, nav_name, base_id, depth, nav_unit, nav_type, simple_link FROM ' . rex::getTablePrefix() . 'guinavigation WHERE nav_type ="' . $subpage . '" ORDER BY id', 30, 'GUINav', false);
    $list->addTableAttribute('class', 'table-striped');

    $tdIcon = '<i class="rex-icon fa-link"></i>';
    $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '"><i class="rex-icon rex-icon-add"></i></a>';
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnParams($thIcon, ['func' => 'edit', 'id' => '###id###']);

     // ausgabe festlegen
    $list->removeColumn('id');
    $list->removeColumn('nav_type');

    $bc = $subpage == "breadcrumb";
    $ls = $subpage == "langswitch";

    if( $subpage == "simple" || $ls || $bc ) {

        $list->removeColumn('base_id');
        $list->removeColumn('depth');
        $list->removeColumn('nav_unit');

        if($bc){

            $list->removeColumn('simple_link');
            $list->removeColumn('langswitch_if_necessary');

        } else if($ls) {

            $list->removeColumn('simple_link');

        } else {

             $list->removeColumn('langswitch_if_necessary');

        }

    } else {

        $list->removeColumn('simple_link');
        $list->removeColumn('langswitch_if_necessary');

    }

    
    $list->setColumnLabel('nav_name', rex_i18n::msg('guinav_field_name_label'));
    $list->setColumnParams('nav_name', ['func' => 'edit', 'id' => '###id###']);

    
    // name start rausfinden
    function nsn($params){
        $start = false;
        $start_id = (int) $params['subject'];
        $unit = $params['list']->getValue('nav_unit');
        $type = $params['list']->getValue('nav_type');

        if($type == "static"){

            // wenn homepage, also root, gewählt
            if($start_id === 0) $start_name = "Homepage/Root"; 
            elseif($unit == "cat") $start = rex_category::get($start_id);
            else $start = rex_article::get($start_id);
            $start_name = $start ? $start->getName() : $start_name;
            return $start_name . " (id: " . $start_id . ")";

        } elseif ($type == "context") {

            switch(true){
                case ($start_id < 0): 
                    return ($start_id * -1) . " " . rex_i18n::msg('guinav_ctxt_field_start_low');
                    break;
                case ($start_id > 0): 
                    return  rex_i18n::msg('guinav_ctxt_field_start_high') . " " . $start_id;
                    break;
                case ($start_id == 0): 
                    return rex_i18n::msg('guinav_ctxt_field_start_current');
                    break;
            }

        }
    }
 
    $list->setColumnLabel('base_id', rex_i18n::msg('guinav_field_base_label'));
    $list->setColumnFormat('base_id', 'custom', 'nsn');
    
    // depth
    function ndcb($params){

        $d = (int) $params['subject'];
        return $d === -1 ? rex_i18n::msg('guinav_field_depth_all') : $d;

    }

    $list->setColumnLabel('depth', rex_i18n::msg('guinav_field_depth_label'));
    $list->setColumnFormat('depth', 'custom', 'ndcb');

    // cat und art ausgeben
    function nun($params){
        if($params['subject'] == 'cat') return rex_i18n::msg('guinav_categories');
        else return rex_i18n::msg('guinav_articles');
    }

    $list->setColumnLabel('nav_unit', rex_i18n::msg('guinav_field_unit_label'));
    $list->setColumnFormat('nav_unit', 'custom', 'nun');

    // simple
    $list->setColumnLabel('simple_link', rex_i18n::msg('guinav_sp_field_link_label'));
    
    // langswitch
    function ls_in($params){
        return $params['subject'] ? rex_i18n::msg('guinav_field_ls_in_yes_label') : rex_i18n::msg('guinav_field_label_ls_in_no');
    }

    $list->setColumnLabel('langswitch_if_necessary', rex_i18n::msg('guinav_ls_field_in_label'));
    $list->setColumnFormat('langswitch_if_necessary', 'custom', 'ls_in');

    // function edit
    $list->addColumn(rex_i18n::msg('guinav_field_functions_label'), '<i class="rex-icon rex-icon-edit"></i> ' . rex_i18n::msg('edit'));
    $list->setColumnLayout(rex_i18n::msg('guinav_field_functions_label'), ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams(rex_i18n::msg('guinav_field_functions_label'), ['func' => 'edit', 'id' => '###id###']);
    $list->addLinkAttribute(rex_i18n::msg('guinav_field_functions_label'), 'class', 'rex-edit');

    // function delete
    $list->addColumn('delete', '<i class="rex-icon rex-icon-delete"></i> ' . rex_i18n::msg('delete'));
    $list->setColumnLayout('delete', ['', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams('delete', ['func' => 'delete', 'id' => '###id###']);
    $list->addLinkAttribute('delete', 'data-confirm', rex_i18n::msg('delete') . ' ?');
    $list->addLinkAttribute('delete', 'class', 'rex-delete');

    // empty
    $list->setNoRowsMessage(rex_i18n::msg('guinav_no_nav_found'));

    $content .= $list->get();

    $fragment = new rex_fragment();
    $fragment->setVar('title', $title);
    $fragment->setVar('content', $content, false);
    $content = $fragment->parse('core/page/section.php');

    echo $content;

} elseif ($func == 'edit' or $func == 'add' or $subpage == "info") {

    rex_be_controller::includeCurrentPageSubPath();
    
}