<?php



$func = rex_request('func', 'string');

$content = "";

$id = rex_request('id', 'int');
$brid = rex_post('base_id_root', 'string');


$form =  rex_form::factory(rex::getTablePrefix() . 'guinavigation', rex_i18n::msg('guinav_stat_form_headline'), "id=" . $id, "post", false);

$form->addParam('id', $id);

$c = "checked";

if ($func == 'edit') {

    $form->setEditMode(true);
    $title = rex_i18n::msg('guinav_field_fieldset_edit');
    $dpc = $dhlc = $dd = null;
    $add = false;

} else {

    $title = rex_i18n::msg('guinav_stat_field_fieldset_create');
    $dpc = 'current';
    $dhlc = 'active';
    $dd = 1;
    $add = true;

}

$field = $form->addHiddenField('nav_type', 'static');

$field = $form->addTextField('nav_name');
$field->setLabel(rex_i18n::msg('guinav_input_name_label'));

// unit
$field = $form->addSelectField('nav_unit');
$field->setLabel(rex_i18n::msg('guinav_input_unit_label'));
$select = $field->getSelect();
$select->setSize('1');
$select->addOptions(array("cat" => rex_i18n::msg('guinav_categories'), "art" => rex_i18n::msg('guinav_articles')));


// startpunkt
$field = $form->addSelectField('base_id');
$field->setLabel(rex_i18n::msg('guinav_input_start_label'));
$category_select = new rex_category_select(false, false, true);
$category_select->setSize('1');
$field->setSelect($category_select);

// startpunkt mit anzeigen
$field = $form->addCheckboxField('list_starting_point');
$field->setLabel(rex_i18n::msg('guinav_input_lsp_label'));
$field->addOption(rex_i18n::msg('guinav_input_lsp_text'), 1);

// tiefe
$field = $form->addTextField('depth', $dd);
$field->setAttribute('class', 'form-control nav-d3');
$field->setLabel(rex_i18n::msg('guinav_input_depth_label'));
$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_input_depth_text') . "</div>\n");

//home
$field = $form->addSelectField('home');
$field->setLabel(rex_i18n::msg('guinav_input_home_label'));
$select = $field->getSelect();
$select->setSize('1');
$select->addOptions( array("" => rex_i18n::msg('guinav_input_home_nope'), 
    "start" => rex_i18n::msg('guinav_input_home_start'),
    "end" => rex_i18n::msg('guinav_input_home_end') ));

// link on self
$field = $form->addCheckboxField('link_on_self');
$field->setLabel(rex_i18n::msg('guinav_input_los_label'));
$field->addOption('', 1);

// mark parent - current class
$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_input_cc_text') . "</div>\n");
$field = $form->addTextField('current_class', $dpc);
$field->setLabel(rex_i18n::msg('guinav_input_cc_label'));

// active link class
$field = $form->addTextField('active_link_class', $dhlc);
$field->setLabel(rex_i18n::msg('guinav_input_alc_label'));

// individual id
$field = $form->addCheckboxField('individual_id');
$field->setLabel(rex_i18n::msg('guinav_input_ii_label'));
$field->addOption(rex_i18n::msg('guinav_input_ii_text'), 1);

// exclude articles
$field = $form->addLinkListField('exclude');
$field->setLabel(rex_i18n::msg('guinav_input_exclude_label'));
// erklärung exclude
$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_input_exclude_text') . "</div>\n");

$field = $form->addRawField("<div class='rex-form-group form-group' style='border:solid 1px #999; border-left: none; border-right:none; line-height:3rem;'>" . rex_i18n::msg('guinav_txt_category_options') . "</div>\n");

// link first subcategory
$field = $form->addLinkListField('link_first');
$field->setLabel(rex_i18n::msg('guinav_stat_input_lf_label'));
// erklärung link first 
$field = $form->addRawField("<div class='rex-form-group form-group'>" . rex_i18n::msg('guinav_stat_input_lf_text') . "</div>\n");

$content .= $form->get();

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $title);
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;
