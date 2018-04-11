<?php

//var_dump($_POST);

$func = rex_request('func', 'string');

$content = "";
// fixed navigation

$id = rex_request('id', 'int');
$brid = rex_post('base_id_root', 'string');
//var_dump(rex_request('nav_name', 'string')); 
// error_log(var_dump($brid));

$form =  rex_form::factory(rex::getTablePrefix() . 'guinavigation', rex_i18n::msg('guinav_ctxt_form_headline'), "id=" . $id, "post", false);

$form->addParam('id', $id);

$c = "checked";

if ($func == 'edit') {
    $form->setEditMode(true);
    $title = rex_i18n::msg('guinav_field_fieldset_edit');
    $dpc = $dhlc = $dd = null;
    $add = false;
} else {
    $title = rex_i18n::msg('guinav_ctxt_field_fieldset_create');
    $dpc = 'current';
    $dhlc = 'active';
    $dd = 1;
    $add = true;
    //$sel = 'selected';
}

$field = $form->addHiddenField('nav_type', 'context');

// startpunkt
$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_ctxt_text') . "</div>\n");


$field = $form->addTextField('nav_name');
$field->setLabel(rex_i18n::msg('guinav_input_name_label'));

// ab welcher Ebene soll die Navigation dargestellt werden
$field = $form->addTextField('context_start_depth');
$field->setLabel(rex_i18n::msg('guinav_ctxt_input_start_depth_label'));

// $field = $form->addTextField('base_id');
// $field->setAttribute('class', 'form-control nav-d3');
// $field->setLabel(rex_i18n::msg('guinav_input_label_start'));

// startpunkt
$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_ctxt_input_start_text') . "</div>\n");

$field = $form->addTextField('base_id');
$field->setLabel(rex_i18n::msg('guinav_ctxt_input_start_label'));

// tiefe
$field = $form->addTextField('depth', $dd);
$field->setAttribute('class', 'form-control nav-d3');
$field->setLabel(rex_i18n::msg('guinav_input_depth_label'));
// unit
$field = $form->addSelectField('nav_unit');
$field->setLabel(rex_i18n::msg('guinav_input_unit_label'));
//$category_select = new rex_category_select(false, false, true);
$select = $field->getSelect();
$select->setSize('1');
$select->addOptions(array("cat" => rex_i18n::msg('guinav_categories'), "art" => rex_i18n::msg('guinav_articles')));

$field = $form->addSelectField('home');
$field->setLabel(rex_i18n::msg('guinav_input_home_label'));
$select = $field->getSelect();
$select->setSize('1');
$select->addOptions( array("" => rex_i18n::msg('guinav_input_home_nope'), 
    "start" => rex_i18n::msg('guinav_input_home_start'),
    "end" => rex_i18n::msg('guinav_input_home_end') ));

// $field = $form->addInputField('checkbox', 'link_on_self', 1);
// $field->setLabel(rex_i18n::msg('guinav_input_label_los'));

// $field = $form->addInputField('checkbox', 'last_level_articles', null);
// $field->setLabel(rex_i18n::msg('guinav_input_label_lla'));

// $field = $form->addInputField('checkbox', 'exclude_start_article', null);
// $field->setLabel(rex_i18n::msg('guinav_input_label_esa'));

$field = $form->addCheckboxField('link_on_self');
$field->setLabel(rex_i18n::msg('guinav_input_los_label'));
$field->addOption('', 1);
if($add) $field->setAttribute($c, $c);

$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_input_lla_text') . "</div>\n");

// $field = $form->addCheckboxField('last_level_articles');
// $field->setLabel(rex_i18n::msg('guinav_input_lla_label'));
// $field->addOption(rex_i18n::msg('guinav_input_lla_option'), 1);

// $field = $form->addCheckboxField('exclude_start_article');
// $field->setLabel(rex_i18n::msg('guinav_input_esa_label'));
// $field->addOption(rex_i18n::msg('guinav_input_esa_option'), 1);


// mark parent
$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_input_cc_text') . "</div>\n");
$field = $form->addTextField('current_class', $dpc);
$field->setLabel(rex_i18n::msg('guinav_input_cc_label'));

$field = $form->addTextField('active_link_class', $dhlc);
$field->setLabel(rex_i18n::msg('guinav_input_alc_label'));

$field = $form->addLinkListField('exclude');
$field->setLabel(rex_i18n::msg('guinav_input_exclude_label'));


$content .= $form->get();

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $title);
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;
