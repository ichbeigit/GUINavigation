<?php

$func = rex_request('func', 'string');

$content = "";

$id = rex_request('id', 'int');
$brid = rex_post('base_id_root', 'string');

$form =  rex_form::factory(rex::getTablePrefix() . 'guinavigation', rex_i18n::msg('guinav_sp_form_headline'), "id=" . $id, "post", false);

$form->addParam('id', $id);

$c = "checked";

if ($func == 'edit') {

    $form->setEditMode(true);
    $title = rex_i18n::msg('guinav_field_fieldset_edit');
    $dpc = $dhlc = $dd = null;
    $add = false;

} else {

    $title = rex_i18n::msg('guinav_sp_field_fieldset_create');
    $dpc = 'current';
    $dhlc = 'active';
    $dd = 1;
    $add = true;
    
}

$field = $form->addHiddenField('nav_type', 'simple');

$field = $form->addTextField('nav_name');
$field->setLabel(rex_i18n::msg('guinav_input_name_label'));

// disable
$field = $form->addCheckboxField('nav_disable');
$field->setLabel(rex_i18n::msg('guinav_input_disable_label'));
$field->addOption('', 1);

$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_sp_input_text') . "</div>\n");

$field = $form->addLinkListField('simple_link');
$field->setLabel(rex_i18n::msg('guinav_sp_input_link_label'));

// link on self
$field = $form->addCheckboxField('link_on_self');
$field->setLabel(rex_i18n::msg('guinav_input_los_label'));
$field->addOption('', 1);
if($add) $field->setAttribute($c, $c);

// active link class
$field = $form->addTextField('active_link_class', $dhlc);
$field->setLabel(rex_i18n::msg('guinav_input_alc_label'));

// individual class
$field = $form->addCheckboxField('individual_class');
$field->setLabel(rex_i18n::msg('guinav_input_ic_label'));
$field->addOption(rex_i18n::msg('guinav_input_ic_text'), 1);

$content .= $form->get();

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $title);
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;
