<?php

$c = "checked";

$func = rex_request('func', 'string');

$content = "";

$id = rex_request('id', 'int');
$brid = rex_post('base_id_root', 'string');

$form =  rex_form::factory(rex::getTablePrefix() . 'guinavigation', rex_i18n::msg('guinav_breadcrumb'), "id=" . $id, "post", false);

$form->addParam('id', $id);

if ($func == 'edit') {

    $form->setEditMode(true);
    $title = rex_i18n::msg('guinav_bc_field_fieldset_edit');
    $dpc = $dhlc = $dd = null;
    $add = false;

} else {

    $title = rex_i18n::msg('guinav_bc_field_fieldset_create');
    $dpc = 'current';
    $dhlc = 'active';
    $dd = 1;
    $add = true;
    
}

$field = $form->addHiddenField('nav_type', 'breadcrumb');

$field = $form->addTextField('nav_name');
$field->setLabel(rex_i18n::msg('guinav_input_name_label'));

// home
$field = $form->addCheckboxField('home');
$field->setLabel(rex_i18n::msg('guinav_bc_input_home_label'));
$field->addOption('', 1);
if($add) $field->setAttribute($c, $c);

// link on self
$field = $form->addCheckboxField('link_on_self');
$field->setLabel(rex_i18n::msg('guinav_bc_input_los_label'));
$field->addOption('', 1);

// active link class
$field = $form->addTextField('active_link_class', $dhlc);
$field->setLabel(rex_i18n::msg('guinav_bc_input_alc_label'));
$field->setAttribute("maxlength", "63");

// seperator
$field = $form->addTextField('separator_string');
$field->setLabel(rex_i18n::msg('guinav_input_sep_label'));
$field->setAttribute("maxlength", "63");

// exclude articles
$field = $form->addLinkListField('exclude');
$field->setLabel(rex_i18n::msg('guinav_input_exclude_label'));


$content .= $form->get();

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $title);
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;



?>