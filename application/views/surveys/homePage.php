<?php

$data['templatedir'] = getTemplatePath(Yii::app()->getConfig("defaulttemplate"));
$data['templateurl'] = getTemplateURL(Yii::app()->getConfig("defaulttemplate")) . "/";
$data['templatename'] = Yii::app()->getConfig("defaulttemplate");
$data['sitename'] = Yii::app()->getConfig("sitename");
$data['languagechanger'] = makeLanguageChanger(App()->lang->langcode);

//A nice exit
sendCacheHeaders();
doHeader();

echo templatereplace(file_get_contents(getTemplatePath(Yii::app()->getConfig("defaulttemplate")) . "/startpage.pstpl"), array(), $data, 'survey[' . __LINE__ . ']');
echo templatereplace(file_get_contents(getTemplatePath(Yii::app()->getConfig("defaulttemplate")) . "/content.pstpl"), array(), $data, 'survey[' . __LINE__ . ']');
echo templatereplace(file_get_contents(getTemplatePath(Yii::app()->getConfig("defaulttemplate")) . "/endpage.pstpl"), array(), $data, 'survey[' . __LINE__ . ']');

doFooter();
?>