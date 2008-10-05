<?php

include("header.php");
include(XOOPS_ROOT_PATH."/header.php");
$q = "SELECT l.title from ".$xoopsDB->prefix("flashgames_games")." l, ".$xoopsDB->prefix("flashgames_text")." t WHERE l.lid=$item_id and l.lid=t.lid and status>0";
$result=$xoopsDB->query($q);
list($ltitle)=$xoopsDB->fetchRow($result);
$subject = $ltitle;
$pid = 0;
OpenTable();
include(XOOPS_ROOT_PATH."/include/commentform.inc.php");
CloseTable();
include(XOOPS_ROOT_PATH."/footer.php");
?>
