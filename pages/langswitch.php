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
   
}

$field = $form->addHiddenField('nav_type', 'langswitch');

$field = $form->addRawField("<div class='rex-form-group form-group'><b>" . rex_i18n::msg('guinav_ls_input_text') . "</B></div>\n");

$field = $form->addTextField('nav_name');
$field->setLabel(rex_i18n::msg('guinav_input_name_label'));

// disable
$field = $form->addCheckboxField('nav_disable');
$field->setLabel(rex_i18n::msg('guinav_input_disable_label'));
$field->addOption('', 1);

// show active
$field = $form->addCheckboxField('langswitch_show_active');
$field->setLabel(rex_i18n::msg('guinav_ls_input_sa_label'));
$field->addOption('', 1);
if($add) $field->setAttribute($c, $c);

// link on self
$field = $form->addCheckboxField('link_on_self');
$field->setLabel(rex_i18n::msg('guinav_ls_input_los_label'));
$field->addOption('', 1);
if($add) $field->setAttribute($c, $c);

// active link class
$field = $form->addTextField('active_link_class', $dhlc);
$field->setLabel(rex_i18n::msg('guinav_ls_input_alc_label'));
$field->setAttribute("maxlength", "63");

// individual id
$field = $form->addCheckboxField('individual_id');
$field->setLabel(rex_i18n::msg('guinav_input_ii_label'));
$field->addOption(rex_i18n::msg('guinav_input_ii_text'), 1);

// seperator
$field = $form->addTextField('separator_string');
$field->setLabel(rex_i18n::msg('guinav_input_sep_label'));
$field->setAttribute("maxlength", "63");

// show offline
$field = $form->addCheckboxField('langswitch_show_offline');
$field->setLabel(rex_i18n::msg('guinav_ls_input_so_label'));
$field->addOption(rex_i18n::msg('guinav_ls_input_so_option'), 1);

// offline class
$field = $form->addTextField('langswitch_offline_class', ($add ? "offline" : null));
$field->setLabel(rex_i18n::msg('guinav_ls_input_oc_label'));
$field->setAttribute("maxlength", "63");

// exclude articles
$field = $form->addLinkListField('exclude');
$field->setLabel(rex_i18n::msg('guinav_ls_input_exclude_label'));


$content .= $form->get();

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $title);
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;



?>