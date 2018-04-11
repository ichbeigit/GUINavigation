<?php

$c = "checked";

$func = rex_request('func', 'string');

$content = "";

$id = rex_request('id', 'int');
$brid = rex_post('base_id_root', 'string');

$form =  rex_form::factory(rex::getTablePrefix() . 'guinavigation', rex_i18n::msg('guinav_langswitch'), "id=" . $id, "post", false);

$form->addParam('id', $id);

if ($func == 'edit') {
    $form->setEditMode(true);
    $title = rex_i18n::msg('guinav_ls_field_fieldset_edit');
    $dpc = $dhlc = $dd = null;
    $add = false;
} else {
    $title = rex_i18n::msg('guinav_ls_field_fieldset_create');
    $dpc = 'current';
    $dhlc = 'active';
    $dd = 1;
    $add = true;
    //$sel = 'selected';
}

$field = $form->addHiddenField('nav_type', 'langswitch');

$field = $form->addRawField("<div class='rex-form-group form-group'><b>" . rex_i18n::msg('guinav_ls_input_text') . "</B></div>\n");

$field = $form->addTextField('nav_name');
$field->setLabel(rex_i18n::msg('guinav_input_name_label'));



// $field = $form->addLinkListField('simple_link');
// $field->setLabel(rex_i18n::msg('guinav_input_label_simple_link'));

// $field = $form->addTextField('base_id');
// $field->setAttribute('class', 'form-control nav-d3');
// $field->setLabel(rex_i18n::msg('guinav_input_label_start'));

// startpunkt
// $field = $form->addSelectField('base_id');
// $field->setLabel(rex_i18n::msg('guinav_input_label_start'));
// $category_select = new rex_category_select(false, false, true);
// $category_select->setSize('1');
// $field->setSelect($category_select);
// startpunkt mit anzeigen
// $field = $form->addCheckboxField('list_starting_point');
// $field->setLabel(rex_i18n::msg('guinav_input_label_lsp'));
// $field->addOption('', 1);
// tiefe
// $field = $form->addTextField('depth', $dd);
// $field->setAttribute('class', 'form-control nav-d3');
// $field->setLabel(rex_i18n::msg('guinav_input_label_depth'));
// unit
// $field = $form->addSelectField('nav_unit');
// $field->setLabel(rex_i18n::msg('guinav_input_label_unit'));
//$category_select = new rex_category_select(false, false, true);
// $select = $field->getSelect();
// $select->setSize('1');
// $select->addOptions(array("cat" => rex_i18n::msg('guinav_field_unit_cat'), "art" => rex_i18n::msg('guinav_field_unit_art')));
//home
// $field = $form->addSelectField('home');
// $field->setLabel(rex_i18n::msg('guinav_input_label_home'));
// $select = $field->getSelect();
// $select->setSize('1');
// $select->addOptions( array("nope" => rex_i18n::msg('guinav_input_home_nope'), 
//     "start" => rex_i18n::msg('guinav_input_home_start'),
//     "end" => rex_i18n::msg('guinav_input_home_end') ));

// $field = $form->addInputField('checkbox', 'link_on_self', 1);
// $field->setLabel(rex_i18n::msg('guinav_input_label_los'));

// $field = $form->addInputField('checkbox', 'last_level_articles', null);
// $field->setLabel(rex_i18n::msg('guinav_input_label_lla'));

// $field = $form->addInputField('checkbox', 'exclude_start_article', null);
// $field->setLabel(rex_i18n::msg('guinav_input_label_esa'));

// $field = $form->addCheckboxField('langswitch_if_necessary');
// $field->setLabel(rex_i18n::msg('guinav_input_label_ls_in'));
// $field->addOption(rex_i18n::msg('guinav_input_option_ls_in'), 1);
//if($add) $field->setAttribute($c, $c);

$field = $form->addCheckboxField('langswitch_show_active');
$field->setLabel(rex_i18n::msg('guinav_ls_input_sa_label'));
$field->addOption('', 1);
if($add) $field->setAttribute($c, $c);

$field = $form->addCheckboxField('link_on_self');
$field->setLabel(rex_i18n::msg('guinav_ls_input_los_label'));
$field->addOption('', 1);
if($add) $field->setAttribute($c, $c);

$field = $form->addTextField('active_link_class', $dhlc);
$field->setLabel(rex_i18n::msg('guinav_ls_input_alc_label'));
$field->setAttribute("maxlength", "63");

$field = $form->addTextField('separator_string');
$field->setLabel(rex_i18n::msg('guinav_ls_input_sep_label'));
//$field->setAttribute("size", "15");_label
$field->setAttribute("maxlength", "63");
//$field->setAttribute("style", "width:auto;");

// $field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_input_text_lla') . "</div>\n");

// $field = $form->addCheckboxField('last_level_articles');
// $field->setLabel(rex_i18n::msg('guinav_input_label_lla'));
// $field->addOption(rex_i18n::msg('guinav_input_lla_option'), 1);

// $field = $form->addCheckboxField('exclude_start_article');
// $field->setLabel(rex_i18n::msg('guinav_input_label_esa'));
// $field->addOption(rex_i18n::msg('guinav_input_esa_option'), 1);


// mark parent
// $field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_input_text_cc') . "</div>\n");
// $field = $form->addTextField('current_class', $dpc);
// $field->setLabel(rex_i18n::msg('guinav_input_label_cc'));

$field = $form->addCheckboxField('langswitch_show_offline');
$field->setLabel(rex_i18n::msg('guinav_ls_input_so_label'));
$field->addOption(rex_i18n::msg('guinav_ls_input_so_option'), 1);
//if($add) $field->setAttribute($c, $c);

$field = $form->addTextField('langswitch_offline_class', ($add ? "offline" : null));
$field->setLabel(rex_i18n::msg('guinav_ls_input_oc_label'));
$field->setAttribute("maxlength", "63");


$field = $form->addLinkListField('exclude');
$field->setLabel(rex_i18n::msg('guinav_ls_input_exclude_label'));
// // erklärung exclude
//$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_input_exclude_text') . "</div>\n");


$content .= $form->get();

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $title);
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;



?>