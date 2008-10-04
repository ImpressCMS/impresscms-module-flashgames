<?php
// register globals=off fix
extract($_POST,EXTR_SKIP); 
extract($_GET);

include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH.'/kernel/module.php';
include XOOPS_ROOT_PATH.'/include/cp_functions.php';

global $xoopsDB, $lid;
$lid = intval($lid);
$result = $xoopsDB->query("SELECT l.submitter FROM ".$xoopsDB->prefix('flashgames_games')." l, ".$xoopsDB->prefix('flashgames_text')." t where l.lid=$lid",0);
list($submitter) = $xoopsDB->fetchRow($result);

if($xoopsUser)
{
	$xoopsModule = XoopsModule::getByDirname('flashgames');
	if(!$xoopsUser->isAdmin($xoopsModule->mid()) and $submitter != $xoopsUser->uid()) {redirect_header(XOOPS_URL.'/',3,_NOPERM);}
}
else {redirect_header(XOOPS_URL.'/',3,_NOPERM);}

if(file_exists('../language/'.$xoopsConfig['language'].'/main.php')) {include '../language/'.$xoopsConfig['language'].'/main.php';}
else {include '../language/english/main.php';}
?>
