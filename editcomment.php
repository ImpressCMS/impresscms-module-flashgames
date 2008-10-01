<?php


include("header.php");
include_once(XOOPS_ROOT_PATH."/class/xoopscomments.php");
include(XOOPS_ROOT_PATH."/header.php");
$pollcomment = new XoopsComments($xoopsDB->prefix("flashgames_comments"),$comment_id);
$nohtml = $pollcomment->getVar("nohtml");
$nosmiley = $pollcomment->getVar("nosmiley");
$icon = $pollcomment->getVar("icon");
$item_id=$pollcomment->getVar("item_id");
$subject=$pollcomment->getVar("subject", "E");
$message=$pollcomment->getVar("comment", "E");
OpenTable();
include(XOOPS_ROOT_PATH."/include/commentform.inc.php");
CloseTable();
include(XOOPS_ROOT_PATH."/footer.php");
?>
