<?php

$title =  $this->i18n('guinav_info');

$be_lang_arr = explode("_", rex_i18n::getLocale() );
$be_lang = array_shift($be_lang_arr);

ob_start();
// check file
$fp =  __DIR__ . '/info_' . $be_lang . '.html';
if(file_exists($fp)) require $fp;
else require __DIR__ . '/info_de.html';
$content = ob_get_contents();
ob_end_clean();

$fragment = new rex_fragment();
$fragment->setVar('title', $title);
$fragment->setVar('body', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;

