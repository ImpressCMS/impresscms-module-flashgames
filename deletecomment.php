<?php

include("header.php");
include_once(XOOPS_ROOT_PATH."/class/xoopscomments.php");
if ( !$xoopsUser ) {
	include(XOOPS_ROOT_PATH."/header.php");
	echo "<h4>"._ALBM_DELNOTALLOWED."</h4>";
	echo "<br />";
	echo "<a href=\"javascript:history.go(-1)\">"._ALBM_GOBACK."</a>";
	include(XOOPS_ROOT_PATH."/footer.php");
	exit();
} else {
	if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
		include(XOOPS_ROOT_PATH."/header.php");
		echo "<h4>"._ALBM_DELNOTALLOWED."</h4>";
		echo "<br />";
		echo "<a href=\"javascript:history.go(-1)\">"._ALBM_GOBACK."</a>";
		include(XOOPS_ROOT_PATH."/footer.php");
		exit();
	}
}
if ( !empty($ok) ) {
	if ( !empty($comment_id) ) {
		$gamecomment = new XoopsComments($xoopsDB->prefix("flashgames_comments"),$comment_id);
		$deleted = $gamecomment->delete();
		$item_id = $gamecomment->getVar("item_id");
		list($numrows)=$xoopsDB->fetchRow($xoopsDB->query("select count(*) from ".$xoopsDB->prefix("flashgames_comments")." where item_id = $item_id"));
		$xoopsDB->queryF("update ".$xoopsDB->prefix("flashgames_games")." set comments=$numrows where lid=$item_id ");
	}
	redirect_header("game.php?lid=".$item_id."&amp;order=".$order."&amp;mode=".$mode."",2,_ALBM_COMMENTSDEL);
	exit();
} else {
	include(XOOPS_ROOT_PATH."/header.php");
	OpenTable();
	echo "<div align=\"center\">";
	echo "<h4 style='color:#ff0000;'>"._ALBM_AREYOUSURE."</h4>";
	echo "<table><tr><td>\n";
	echo myTextForm("deletecomment.php?comment_id=".$comment_id."&amp;mode=".$mode."&amp;order=".$order."&amp;ok=1", _YES);
	echo "</td><td>\n";
	echo myTextForm("javascript:history.go(-1)", _NO);
	echo "</td></tr></table>\n";
	echo "</div>";
	CloseTable();
}
include(XOOPS_ROOT_PATH."/footer.php");
?>
