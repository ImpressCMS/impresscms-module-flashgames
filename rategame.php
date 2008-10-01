<?php
// ------------------------------------------------------------------------- //
//                      flashgames                                           //
//                     <http://www.tipsmitgrips.de>                          //
// ------------------------------------------------------------------------- //
// based on                                                                  //
// Xoops module "myalbum"          - http://bluetopia.homeip.net/            //
// Postnuke module "pnflashgames"  - http://www.pnflashgames.com             //
// Mainly based on:                                                          //
// XOOPS PHP Content Management System - http://www.xoops.org/               //
// and:                                                                      //
// myPHPNUKE Web Portal System - http://myphpnuke.com/                       //
// PHP-NUKE Web Portal System - http://phpnuke.org/                          //
// Thatware - http://thatware.org/                                           //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //

include("header.php");
include_once(XOOPS_ROOT_PATH."/class/module.errorhandler.php");
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
if($HTTP_POST_VARS['submit']) {
	$eh = new ErrorHandler; //ErrorHandler object
	if(!$xoopsUser){
		$ratinguser = 0;
	}else{
		$ratinguser = $xoopsUser->uid();
	}
    	//Make sure only 1 anonymous from an IP in a single day.
    	$anonwaitdays = 1;
    	$ip = getenv("REMOTE_ADDR");
	$lid = $HTTP_POST_VARS['lid'];
	$rating = $HTTP_POST_VARS['rating'];
    	// Check if Rating is Null
    	if ($rating=="--") {
		redirect_header("rategame.php?lid=".$lid."",4,_ALBM_NORATING);
		exit();
    	}
	$lid = $HTTP_POST_VARS['lid'];
    	// Check if Link POSTER is voting (UNLESS Anonymous users allowed to post)
    	if ($ratinguser != 0) {
        	$result=$xoopsDB->query("select submitter from ".$xoopsDB->prefix("flashgames_games")." where lid=$lid");
        	while(list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
        		if ($ratinguserDB == $ratinguser) {
				redirect_header("index.php",4,_ALBM_CANTVOTEOWN);
				exit();
                	}
        	}

    	// Check if REG user is trying to vote twice.
    		$result=$xoopsDB->query("select ratinguser from ".$xoopsDB->prefix("flashgames_votedata")." where lid=$lid");
        	while(list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
        		if ($ratinguserDB == $ratinguser) {
				redirect_header("index.php",4,_ALBM_VOTEONCE2);
				exit();
                	}
        	}
    	}
     	// Check if ANONYMOUS user is trying to vote more than once per day.
    	if ($ratinguser == 0){
    		$yesterday = (time()-(86400 * $anonwaitdays));
        	$result=$xoopsDB->query("select count(*) FROM ".$xoopsDB->prefix("flashgames_votedata")." WHERE lid=$lid AND ratinguser=0 AND ratinghostname = '$ip' AND ratingtimestamp > $yesterday");
    		list($anonvotecount) = $xoopsDB->fetchRow($result);
    		if ($anonvotecount > 0) {
			redirect_header("index.php",4,_ALBM_VOTEONCE2);
			exit();
        	}
    	}
	if($rating > 10){
		$rating = 10;
	}
        //All is well.  Add to Line Item Rate to DB.
	$newid = $xoopsDB->genId($xoopsDB->prefix("flashgames_votedata")."_ratingid_seq");
	$datetime = time();
    	$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("flashgames_votedata")." (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES ($newid, $lid, $ratinguser, $rating, '$ip', $datetime)") or $eh->show("0013");
        //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
        updaterating($lid);
	$ratemessage = _ALBM_VOTEAPPRE."<br>".sprintf(_ALBM_THANKURATE,$xoopsConfig[sitename]);
	redirect_header("index.php",2,$ratemessage);
	exit();
} else {
    	include(XOOPS_ROOT_PATH."/header.php");
	
    	OpenTable();
    	mainheader();
    	$result=$xoopsDB->query("select title from ".$xoopsDB->prefix("flashgames_games")." where lid=$lid");
	list($title) = $xoopsDB->fetchRow($result);
	
	$title = $myts->makeTboxData4Show($title);
    	echo "
    	<hr size=1 noshade>
	<table border=0 cellpadding=1 cellspacing=0 width=\"80%\"><tr><td>
    	<h4><center>$title</center></h4>
    	<UL>
     	<LI>"._ALBM_VOTEONCE."
     	<LI>"._ALBM_RATINGSCALE."
     	<LI>"._ALBM_BEOBJECTIVE."
     	<LI>"._ALBM_DONOTVOTE."";
    	echo "
     	</UL>
     	</td></tr>
     	<tr><td align=\"center\">
     	<form method=\"POST\" action=\"rategame.php\">
     	<input type=\"hidden\" name=\"lid\" value=\"".$lid."\">
     	<select name=\"rating\">
     	<option>--</option>";
     	for($i=10;$i>0;$i--){
		echo "<option value=\"".$i."\">".$i."</option>\n";
	}
     	echo "</select>&nbsp;&nbsp;<input type=\"submit\" name=\"submit\" value=\""._ALBM_RATEIT."\">\n";
	echo "&nbsp;<input type=button value="._ALBM_CANCEL." onclick=\"javascript:history.go(-1)\">\n";
    	echo "</form></td></tr></table>\n";
    	CloseTable();    	
	
}

include("footer.php");

?>
