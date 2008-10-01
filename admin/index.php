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
include("admin_header.php");

include("../cache/config.php");
include("../include/functions.php");
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once(XOOPS_ROOT_PATH."/class/module.errorhandler.php");
include_once(XOOPS_ROOT_PATH."/class/xoopscomments.php");
include_once(XOOPS_ROOT_PATH."/class/xoopslists.php");


$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;
$mytree = new XoopsTree($xoopsDB->prefix("flashgames_cat"),"cid","pid");

function flashgames() {
   	global $xoopsDB;
	xoops_cp_header();
	OpenTable();

	$result3 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("flashgames_games")." where status=0");
    	list($totalnewlinks) = $xoopsDB->fetchRow($result3);
	if($totalnewlinks>0){
		$totalnewlinks = "<font color=\"#ff0000\"><b>$totalnewlinks</b></font>";
	}
	echo " - <a href=index.php?op=flashgamesConfigAdmin>"._ALBM_GENERALSET."</a>";
	echo "<br><br>";
	echo " - <a href=index.php?op=linksConfigMenu>"._ALBM_ADDMODDELETE."</a>";
	echo "<br><br>";
	echo " - <a href=index.php?op=listNewLinks>"._ALBM_LINKSWAITING." ($totalnewlinks)</a>";
	// OKa Begin
	//echo "<br><br>";
	//echo " - <a href=batch.php>"._ALBM_BATCHUPLOAD."</a>";
	//echo "<br><br>";
	//echo " - <a href=redothumbs.php>"._ALBM_REDOTHUMBS2."</a>";
	//echo "<br><br>";
	//echo " - <a href=upgrade2xoops2.php>"._ALBM_IMPORTCOMMENTS."</a>";
    // Oka End
	
	$result=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("flashgames_games")." where status>0");
        list($numrows) = $xoopsDB->fetchRow($result);
	echo "<br><br><div align=\"center\">";
	printf(_ALBM_THEREAREADMIN1,$numrows);	echo "</div>";
    	CloseTable();
        echo "<br><br>";
        print GetFooter();
      	xoops_cp_footer();
}

function listNewLinks(){
	global $xoopsDB, $xoopsConfig, $myts, $eh, $mytree;
	$mytree = new XoopsTree($xoopsDB->prefix("flashgames_cat"),"cid","pid");
  	$result = $xoopsDB->query("select lid, cid, title, submitter from ".$xoopsDB->prefix("flashgames_games")." where status=0 order by date DESC");
   	$numrows = $xoopsDB->getRowsNum($result);
	xoops_cp_header();

   	echo "<h4>"._ALBM_LINKSWAITING."&nbsp;($numrows)</h4><br>";
   	if ($numrows>0) {
		while(list($lid, $cid, $title, $submitterid) = $xoopsDB->fetchRow($result)) {
			OpenTable();
			$result2 = $xoopsDB->query("select description from ".$xoopsDB->prefix("flashgames_text")." where lid=$lid");
			list($description) = $xoopsDB->fetchRow($result2);
			$title = $myts->makeTboxData4Edit($title);
			$description = $myts->makeTareaData4Edit($description);
			$submitter = XoopsUser::getUnameFromId($submitterid);
			$cat = $mytree->getNicePathFromId($cid, "title", "../viewcat.php?");
			print "<b>Title:</b> <a href='../editgame.php?lid=$lid'>$title</a><br />
			<b>Description:</b> $description<br />
			<b>Category:</b> $cat<br />
			<b>Submitter:</b> $submitter";			
			CloseTable();
	//		echo myTextForm("index.php?op=delNewLink&lid=$lid",_ALBM_DELETE);
		}
   	} else {
		echo ""._ALBM_NOSUBMITTED."";
	}
	xoops_cp_footer();
}


function linksConfigMenu(){
	global $xoopsDB,$xoopsConfig, $myts, $eh, $mytree;
// Add a New Main Category
	xoops_cp_header();
    	OpenTable();
   	echo "<form method=post action=index.php>\n";
    	echo "<h4>"._ALBM_ADDMAIN."</h4><br>"._ALBM_TITLEC."<input type=text name=title size=30 maxlength=50><br>";
	echo ""._ALBM_IMGURL."<br><input type=\"text\" name=\"imgurl\" size=\"100\" maxlength=\"150\" value=\"http://\"><br><br>";
	echo "<input type=hidden name=cid value=0>\n";
	echo "<input type=hidden name=op value=addCat>";
	echo "<input type=submit value="._ALBM_ADD."><br></form>";
    	CloseTable();
    	echo "<br>";
// Add a New Sub-Category
    	$result=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("flashgames_cat")."");
	list($numrows)=$xoopsDB->fetchRow($result);
    	if ($numrows>0) {
		OpenTable();
    		echo "<form method=post action=index.php>";
    		echo "<h4>"._ALBM_ADDSUB."</h4><br />"._ALBM_TITLEC."<input type=text name=title size=30 maxlength=50>&nbsp;"._ALBM_IN."&nbsp;";
		$mytree->makeMySelBox("title", "title");
#		echo "<br>"._ALBM_IMGURL."<br><input type=\"text\" name=\"imgurl\" size=\"100\" maxlength=\"150\">\n";
    		echo "<input type=hidden name=op value=addCat><br><br>";
		echo "<input type=submit value="._ALBM_ADD."><br></form>";
		CloseTable();
		echo "<br>";

// Modify Category
    		OpenTable();
    		echo "
    		</center><form method=post action=index.php>
    		<h4>"._ALBM_MODCAT."</h4><br>";
    		echo _ALBM_CATEGORYC;
    		$mytree->makeMySelBox("title", "title");
    		echo "<br><br>\n";
    		echo "<input type=hidden name=op value=modCat>\n";
    		echo "<input type=submit value="._ALBM_MODIFY.">\n";
		echo "</form>";
		CloseTable();
		echo "<br>";
    	}
// Modify Game
    	$result2 = mysql_query("SELECT lid, title FROM ".$xoopsDB->prefix("flashgames_games")." ORDER BY title");
    	//list($numrows2) = $xoopsDB->fetchRow($result2);
		OpenTable();
		echo "<form method=get action=\"../editgame.php\">\n";
		echo "<h4>"._ALBM_MODLINK."</h4><br>\n";
    	echo _ALBM_LINKID."<select name=lid>";
    	while($game = mysql_fetch_assoc($result2)){
    		echo "<option value='{$game[lid]}'>{$game[title]}</option>";
    	}
    	echo "</select>";
		echo "<input type=hidden name=fct value=flashgames>\n";
		echo "<input type=hidden name=op value=modLink><br><br>\n";
		echo "<input type=submit value="._ALBM_MODIFY."></form>\n";
		CloseTable();
// Automatic Installation Service
		echo "<br>";
		OpenTable();
		echo "<h4>"._FG_AUTOMATICINSTALLATIONSERVICE."</h4><br>\n";
		echo "<a href=\"automaticinstallationservice.php\">"._FG_AUTOMATICINSTALLATIONSERVICELINK."</a>\n";
		CloseTable();
		xoops_cp_footer();		
}

function modLink() {
   	global $xoopsDB, $HTTP_GET_VARS, $myts, $eh, $mytree, $xoopsConfig;
    	$lid = $HTTP_GET_VARS['lid'];
		xoops_cp_header();
    	OpenTable();
    	$result = $xoopsDB->query("select cid, title, url, email, logourl from ".$xoopsDB->prefix("flashgames_links")." where lid=$lid") or $eh->show("0013");
    	echo "<h4>"._ALBM_MODLINK."</h4><br>";
    	list($cid, $title, $url, $email, $logourl) = $xoopsDB->fetchRow($result); 
    	$title = $myts->makeTboxData4Edit($title);
    	$url = $myts->makeTboxData4Edit($url);
//   	$url = urldecode($url);
    	$email = $myts->makeTboxData4Edit($email);
    	$logourl = $myts->makeTboxData4Edit($logourl);
//  	$logourl = urldecode($logourl);
    	$result2 = $xoopsDB->query("select description from ".$xoopsDB->prefix("flashgames_text")." where lid=$lid"); 
    	list($description)=$xoopsDB->fetchRow($result2);
    	$description = $myts->makeTareaData4Edit($description);
	echo "<table>";
    	echo "<form method=post action=index.php>";
    	echo "<tr><td>"._ALBM_LINKID."</td><td><b>$lid</b></td></tr>";
    	echo "<tr><td>"._ALBM_SITETITLE."</td><td><input type=text name=title value=\"$title\" size=50 maxlength=100></input></td></tr>\n";
    	echo "<tr><td>"._ALBM_SITEURL."</td><td><input type=text name=url value=\"$url\" size=50 maxlength=100></input></td></tr>\n";
    	echo "<tr><td>"._ALBM_EMAILC."</td><td><input type=text name=email value=\"$email\" size=50 maxlength=60></input></td></tr>\n";
    	echo "<tr><td valign=\"top\">"._ALBM_DESCRIPTIONC."</td><td><textarea name=description cols=60 rows=5>$description</textarea></td></tr>";
    	echo "<tr><td>"._ALBM_CATEGORYC."</td><td>";
    	$mytree->makeMySelBox("title", "title", $cid);
	echo "</td></tr>\n";
	echo "<tr><td>"._ALBM_SHOTIMAGE."</td><td><input type=text name=logourl value=\"$logourl\" size=\"50\" maxlength=\"60\"></input></td></tr>\n";
	$shotdir = "<b>".XOOPS_URL."/modules/flashgames/images/shots/</b>";
	echo "<tr><td></td><td>";
	printf(_ALBM_SHOTMUST,$shotdir);
	echo "</td></tr>\n";
	echo "</table>";
    	echo "<br><BR><input type=hidden name=lid value=$lid></input>\n";
    	echo "<input type=hidden name=op value=modLinkS><input type=submit value="._ALBM_MODIFY.">";
		// echo "&nbsp;<input type=button value="._ALBM_DELETE." onclick=\"javascript:location='index.php?op=delLink&lid=".$lid."'\">";
		//echo "&nbsp;<input type=button value="._ALBM_CANCEL." onclick=\"javascript:history.go(-1)\">";
		echo "</form>\n";

		echo "<table><tr><td>\n";
		echo myTextForm("index.php?op=delLink&lid=".$lid , _ALBM_DELETE);
		echo "</td><td>\n";
		echo myTextForm("index.php?op=linksConfigMenu", _ALBM_CANCEL);
		echo "</td></tr></table>\n";
    	echo "<hr>";

    	$result5=$xoopsDB->query("SELECT count(*) FROM ".$xoopsDB->prefix("flashgames_votedata")." WHERE lid = $lid");
    	list($totalvotes) = $xoopsDB->fetchRow($result5);
    	echo "<table valign=top width=100%>\n";
    	echo "<tr><td colspan=7><b>";
	printf(_ALBM_TOTALVOTES,$totalvotes);
	echo "</b><br><br></td></tr>\n";
        // Show Registered Users Votes
    	$result5=$xoopsDB->query("SELECT ratingid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("flashgames_votedata")." WHERE lid = $lid AND ratinguser >0 ORDER BY ratingtimestamp DESC");
    	$votes = $xoopsDB->getRowsNum($result5);
    	echo "<tr><td colspan=7><br><br><b>";
	printf(_ALBM_USERTOTALVOTES,$votes);
	echo "</b><br><br></td></tr>\n";
    	echo "<tr><td><b>" ._ALBM_USER."  </b></td><td><b>" ._ALBM_IP."  </b></td><td><b>" ._ALBM_RATING."  </b></td><td><b>" ._ALBM_USERAVG."  </b></td><td><b>" ._ALBM_TOTALRATE."  </b></td><td><b>" ._ALBM_DATE."  </b></td><td align=\"center\"><b>" ._ALBM_DELETE."</b></td></tr>\n";
    	if ($votes == 0){
	 	echo "<tr><td align=\"center\" colspan=\"7\">" ._ALBM_NOREGVOTES."<br></td></tr>\n";
    	}
    	$x=0;
    	$colorswitch="dddddd";
    	while(list($ratingid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5)) {
    	//	$ratingtimestamp = formatTimestamp($ratingtimestamp);
    	//Individual user information
    		$result2=$xoopsDB->query("SELECT rating FROM ".$xoopsDB->prefix("flashgames_votedata")." WHERE ratinguser = '$ratinguser'");
        	$uservotes = $xoopsDB->getRowsNum($result2);
        	$useravgrating = 0;
        	while(list($rating2) = $xoopsDB->fetchRow($result2)){
			$useravgrating = $useravgrating + $rating2;
		}
        	$useravgrating = $useravgrating / $uservotes;
        	$useravgrating = number_format($useravgrating, 1);
		$ratingusername = XoopsUser::getUnameFromId($ratinguser);
        	echo "<tr><td bgcolor=\"".$colorswitch."\">".$ratingusername."</td><td bgcolor=\"$colorswitch\">".$ratinghostname."</td><td bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">".$useravgrating."</td><td bgcolor=\"$colorswitch\">".$uservotes."</td><td bgcolor=\"$colorswitch\">".$ratingtimestamp."</td><td bgcolor=\"$colorswitch\" align=\"center\"><b><a href=index.php?op=delVote&lid=$lid&rid=$ratingid>X</a></b></td></tr>\n";
    		$x++;
    		if ($colorswitch=="dddddd"){ 
			$colorswitch="ffffff";
    		} else {
			$colorswitch="dddddd";
		}
    	}
	// Show Unregistered Users Votes
    	$result5=$xoopsDB->query("SELECT ratingid, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("flashgames_votedata")." WHERE lid = $lid AND ratinguser = 0 ORDER BY ratingtimestamp DESC");
    	$votes = $xoopsDB->getRowsNum($result5);
    	echo "<tr><td colspan=7><b><br><br>";
	printf(_ALBM_ANONTOTALVOTES,$votes);
	echo "</b><br><br></td></tr>\n";
    	echo "<tr><td colspan=2><b>" ._ALBM_IP."  </b></td><td colspan=3><b>" ._ALBM_RATING."  </b></td><td><b>" ._ALBM_DATE."  </b></b></td><td align=\"center\"><b>" ._ALBM_DELETE."</b></td><br></tr>";
    	if ($votes == 0) {
		echo "<tr><td colspan=\"7\" align=\"center\">" ._ALBM_NOUNREGVOTES."<br></td></tr>";
    	}
    	$x=0;
    	$colorswitch="dddddd";
    	while(list($ratingid, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5)) {
    		$formatted_date = formatTimestamp($ratingtimestamp);
        	echo "<td colspan=\"2\" bgcolor=\"$colorswitch\">$ratinghostname</td><td colspan=\"3\" bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" aling=\"center\"><b><a href=index.php?op=delVote&lid=$lid&rid=$ratingid>X</a></b></td></tr>";
    		$x++;
    		if ($colorswitch=="dddddd") {
			$colorswitch="ffffff";
    		} else {
			$colorswitch="dddddd";
		}
    	}
    	echo "<tr><td colspan=\"6\">&nbsp;<br></td></tr>\n";
    	echo "</table>\n";
    	CloseTable();
    	xoops_cp_footer();
}

function delVote() {
    	global $xoopsDB, $HTTP_GET_VARS, $eh;
    	$rid = $HTTP_GET_VARS['rid'];
    	$lid = $HTTP_GET_VARS['lid'];
    	$query = "delete from ".$xoopsDB->prefix("flashgames_votedata")." where ratingid=$rid";
    	$xoopsDB->query($query) or $eh->show("0013");
    	updaterating($lid);
    	redirect_header("index.php",1,_ALBM_VOTEDELETED);
    	exit();
}
function listBrokenLinks() {
    	global $xoopsDB, $eh;
    	$result = $xoopsDB->query("select * from ".$xoopsDB->prefix("flashgames_broken")." group by lid order by reportid DESC");
    	$totalbrokenlinks = $xoopsDB->getRowsNum($result);
	xoops_cp_header();
	OpenTable();
	echo "<h4>"._ALBM_BROKENREPORTS." ($totalbrokenlinks)</h4><br>";
    	
    	if ($totalbrokenlinks==0) {
    		echo _ALBM_NOBROKEN;
    	} else {
		echo "<center>
    "._ALBM_IGNOREDESC."<br>
    "._ALBM_DELETEDESC."</center><br><br><br>";
        	$colorswitch="dddddd";
		echo "<table align=\"center\" width=\"90%\">";
        	echo "
        	<tr>
          	<td><b>Link Name</b></td>
          	<td><b>" ._ALBM_REPORTER."</b></td>
          	<td><b>" ._ALBM_LINKSUBMITTER."</b></td>
          	<td><b>" ._ALBM_IGNORE."</b></td>
          	<td><b>" ._ALBM_DELETE."</b></td>
        	</tr>";
        	while(list($reportid, $lid, $sender, $ip)=$xoopsDB->fetchRow($result)){
    			$result2 = $xoopsDB->query("select title, url, submitter from ".$xoopsDB->prefix("flashgames_links")." where lid=$lid");
			if ($sender != 0) {
				$result3 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid=$sender");
				list($uname, $email)=$xoopsDB->fetchRow($result3);
			}
    			list($title, $url, $ownerid)=$xoopsDB->fetchRow($result2);
//			$url=urldecode($url);
    			$result4 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$ownerid'");
    			list($owner, $owneremail)=$xoopsDB->fetchRow($result4);
    			echo "<tr><td bgcolor=$colorswitch><a href=$url>$title</a></td>";
    			if ($email=='') { echo "<td bgcolor=\"".$colorswitch."\">".$sender." (".$ip.")"; 
			} else { 
				echo "<td bgcolor=\"".$colorswitch."\"><a href=\"mailto:".$email."\">".$uname."</a> (".$ip.")"; 
			}
    			echo "</td>";
    			if ($owneremail=='') { 
				echo "<td bgcolor=\"".$colorswitch."\">".$owner.""; 
			} else { echo "<td bgcolor=\"".$colorswitch."\"><a href=\"mailto:".$owneremail."\">".$owner."</a>"; 
			}

			echo "</td><td bgcolor='$colorswitch' align='center'>\n";
			echo myTextForm("index.php?op=ignoreBrokenLinks&lid=$lid" , "X");
			echo "</td>";
			echo "<td align='center' bgcolor='$colorswitch'>\n";
			echo myTextForm("index.php?op=delBrokenLinks&lid=$lid" , "X");
			echo "</td></tr>\n";

    			if ($colorswitch == "#dddddd") {
				$colorswitch="#ffffff";
			} else {
				$colorswitch="#dddddd";
			}
    		}
		echo "</table>";
    	}

	CloseTable();
	xoops_cp_footer();
}
function delBrokenLinks() {
    	global $xoopsDB, $HTTP_GET_VARS, $eh;
    	$lid = $HTTP_GET_VARS['lid'];
    	$query = "delete from ".$xoopsDB->prefix("flashgames_broken")." where lid=$lid";
    	$xoopsDB->query($query) or $eh->show("0013");
    	$query = "delete from ".$xoopsDB->prefix("flashgames_links")." where lid=$lid";
    	$xoopsDB->query($query) or $eh->show("0013");
    	redirect_header("index.php",1,_ALBM_LINKDELETED);
	exit();
}
function ignoreBrokenLinks() {
    	global $xoopsDB, $HTTP_GET_VARS, $eh;
    	$query = "delete from ".$xoopsDB->prefix("flashgames_broken")." where lid=".$HTTP_GET_VARS['lid']."";
    	$xoopsDB->query($query) or $eh->show("0013");
    	redirect_header("index.php",1,_ALBM_BROKENDELETED);
	exit();
}
function listModReq() {
    	global $xoopsDB, $myts, $eh, $mytree, $flashgames_shotwidth, $flashgames_useshots;
    	$result = $xoopsDB->query("select * from ".$xoopsDB->prefix("flashgames_mod")." order by requestid");
    	$totalmodrequests = $xoopsDB->getRowsNum($result);
	xoops_cp_header();
	OpenTable();
    	echo "<h4>"._ALBM_USERMODREQ." ($totalmodrequests)</h4><br>";
	if($totalmodrequests>0){
    		echo "<table width=95%><tr><td>";
    		while(list($requestid, $lid, $cid, $title, $url, $email, $logourl, $description, $submitterid)=$xoopsDB->fetchRow($result)) {
			$result2 = $xoopsDB->query("select cid, title, url, email, logourl, submitter from ".$xoopsDB->prefix("flashgames_links")." where lid=$lid");
			list($origcid, $origtitle, $origurl, $origemail, $origlogourl, $ownerid)=$xoopsDB->fetchRow($result2);
			$result2 = $xoopsDB->query("select description from ".$xoopsDB->prefix("flashgames_text")." where lid=$lid");
			list($origdescription) = $xoopsDB->fetchRow($result2);
			$result7 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$submitterid'");
			$result8 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$ownerid'");
			$cidtitle=$mytree->getPathFromId($cid, "title");
			$origcidtitle=$mytree->getPathFromId($origcid, "title");
			list($submitter, $submitteremail)=$xoopsDB->fetchRow($result7);
			list($owner, $owneremail)=$xoopsDB->fetchRow($result8);
			$title = $myts->makeTboxData4Show($title);
    			$url = $myts->makeTboxData4Show($url);
//			$url = urldecode($url);
    			$email = $myts->makeTboxData4Show($email);

// use original image file to prevent users from changing screen shots file
			$origlogourl = $myts->makeTboxData4Show($origlogourl);
    			$logourl = $origlogourl;

//			$logourl = urldecode($logourl);
    			$description = $myts->makeTareaData4Show($description);
    			$origurl = $myts->makeTboxData4Show($origurl);
//			$origurl = urldecode($origurl);
    			$origemail = $myts->makeTboxData4Show($origemail);
//			$origlogourl = urldecode($origlogourl);
    			$origdescription = $myts->makeTareaData4Show($origdescription);
    			if ($owner=="") { 
				$owner="administration"; 
			}
    			echo "<table border=1 bordercolor=black cellpadding=5 cellspacing=0 align=center width=450><tr><td>
    	   		<table width=100% bgcolor=dddddd>
    	     		<tr>
    	       		<td valign=top width=45%><b>"._ALBM_ORIGINAL."</b></td>
	       		<td rowspan=14 valign=top align=left><small><br>"._ALBM_DESCRIPTIONC."<br>$origdescription</small></td>
    	     		</tr>
    	     		<tr><td valign=top width=45%><small>"._ALBM_SITETITLE."$origtitle</small></td></tr>
    	     		<tr><td valign=top width=45%><small>"._ALBM_SITEURL."".$origurl."</small></td></tr>
	     		<tr><td valign=top width=45%><small>"._ALBM_CATEGORYC."$origcidtitle</small></td></tr>
	     		<tr><td valign=top width=45%><small>"._ALBM_EMAILC."$origemail</small></td></tr>
	     		<tr><td valign=top width=45%><small>"._ALBM_SHOTIMAGE."</small>";
			if ( $flashgames_useshots && !empty($origlogourl) ) {
				echo "<img src=\"".XOOPS_URL."/modules/flashgames/images/shots/".$origlogourl."\" width=\"".$flashgames_shotwidth."\" />";
			} else {
				echo "&nbsp;";
			}
			echo "</td></tr>
    	   		</table></td></tr><tr><td>
    	   		<table width=100%>
    	     		<tr>
    	       		<td valign=top width=45%><b>"._ALBM_PROPOSED."</b></td>
    	       		<td rowspan=14 valign=top align=left><small><br>"._ALBM_DESCRIPTIONC."<br>$description</small></td>
    	     		</tr>
    	     		<tr><td valign=top width=45%><small>"._ALBM_SITETITLE."$title</small></td></tr>
    	     		<tr><td valign=top width=45%><small>"._ALBM_SITEURL."".$url."</small></td></tr>
	     		<tr><td valign=top width=45%><small>"._ALBM_CATEGORYC."$cidtitle</small></td></tr>
			<tr><td valign=top width=45%><small>"._ALBM_EMAILC."$email</small></td></tr>
	     		<tr><td valign=top width=45%><small>"._ALBM_SHOTIMAGE."</small>";
			if ( $flashgames_useshots && !empty($logourl) ) {
				echo "<img src=\"".XOOPS_URL."/modules/flashgames/images/shots/".$logourl."\" width=\"".$flashgames_shotwidth."\" alt=\"/\" />";
			} else {
				echo "&nbsp;";
			}
			echo "</td></tr>
    	   		</table></td></tr></table>
    			<table align=center width=450>
    	  		<tr>";
    			if ($submitteremail=="") { 
				echo "<td align=left><small>"._ALBM_SUBMITTER."$submitter</small></td>"; 
			} else { 
				echo "<td align=left><small>"._ALBM_SUBMITTER."<a href=mailto:".$submitteremail.">".$submitter."</a></small></td>"; 
			}
    			if ($owneremail=="") { 
				echo "<td align=center><small>"._ALBM_OWNER."".$owner."</small></td>"; 
			} else { 
				echo "<td align=center><small>"._ALBM_OWNER."<a href=mailto:".$owneremail.">".$owner."</a></small></td>"; 
			}
			echo "<td align=right><small>\n";
			echo "<table><tr><td>\n";
			echo myTextForm("index.php?op=changeModReq&requestid=$requestid" , _ALBM_APPROVE);
			echo "</td><td>\n";
			echo myTextForm("index.php?op=ignoreModReq&requestid=$requestid", _ALBM_IGNORE);
			echo "</td></tr></table>\n";
			echo "</small></td></tr>\n";
    			echo "</table><br><br>";
    		}
    		echo "</td></tr></table>";
	}else {
		echo _ALBM_NOMODREQ;
	}
	CloseTable();
	xoops_cp_footer();
}
function changeModReq() {
    	global $xoopsDB, $HTTP_GET_VARS, $eh, $myts;
    	$requestid = $HTTP_GET_VARS['requestid'];
    	$query = "select lid, cid, title, url, email, logourl, description from ".$xoopsDB->prefix("flashgames_mod")." where requestid=".$requestid."";
    	$result = $xoopsDB->query($query);
    	while(list($lid, $cid, $title, $url, $email, $logourl, $description)=$xoopsDB->fetchRow($result)) {
	if (get_magic_quotes_runtime()) {
		$title = stripslashes($title);
    		$url = stripslashes($url);
    		$email = stripslashes($email);
    		$logourl = stripslashes($logourl);
    		$description = stripslashes($description);
	}
	$title = addslashes($title);
    	$url = addslashes($url);
    	$email = addslashes($email);
    	$logourl = addslashes($logourl);
    	$description = addslashes($description);
    	$xoopsDB->query("UPDATE ".$xoopsDB->prefix("flashgames_links")." SET cid='$cid',title='$title',url='$url',email='$email',logourl='$logourl', status=2, date=".time()." WHERE lid='$lid'")
      or $eh->show("0013");
	$xoopsDB->query("UPDATE ".$xoopsDB->prefix("flashgames_text")." SET description='$description' WHERE lid=".$lid."")
      or $eh->show("0013");
    	$xoopsDB->query("delete from ".$xoopsDB->prefix("flashgames_mod")." where requestid='$requestid'") or $eh->show("0013");
    	}
    	redirect_header("index.php",1,_ALBM_DBUPDATED);
	exit();
}
function ignoreModReq() {
    	global $xoopsDB, $HTTP_GET_VARS, $eh;
    	$query= "delete from ".$xoopsDB->prefix("flashgames_mod")." where requestid=".$HTTP_GET_VARS['requestid']."";
    	$xoopsDB->query($query) or $eh->show("0013");
    	redirect_header("index.php",1,_ALBM_MODREQDELETED);
	exit();
}

function modLinkS() {
    	global $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
    	$cid = $HTTP_POST_VARS["cid"];
    	if (($HTTP_POST_VARS["url"]) || ($HTTP_POST_VARS["url"]!="")) {
//		$url = $myts->formatURL($HTTP_POST_VARS["url"]);
//		$url = urlencode($url);
		$url = $myts->makeTboxData4Save($HTTP_POST_VARS["url"]);
	}
	$logourl = $myts->makeTboxData4Save($HTTP_POST_VARS["logourl"]);
    	$title = $myts->makeTboxData4Save($HTTP_POST_VARS["title"]);
    	$email = $myts->makeTboxData4Save($HTTP_POST_VARS["email"]);
    	
    	$description = $myts->makeTareaData4Save($HTTP_POST_VARS["description"]);
    	$xoopsDB->query("update ".$xoopsDB->prefix("flashgames_links")." set cid='$cid', title='$title', url='$url', email='$email', logourl='$logourl', status=2, date=".time()." where lid=".$HTTP_POST_VARS['lid']."")  or $eh->show("0013");
    	$xoopsDB->query("update ".$xoopsDB->prefix("flashgames_text")." set description='$description' where lid=".$HTTP_POST_VARS['lid']."")  or $eh->show("0013");
    	redirect_header("index.php",1,_ALBM_DBUPDATED);
	exit();
}
function delLink() {
   	global $xoopsDB, $HTTP_GET_VARS, $eh;
   	$query = "delete from ".$xoopsDB->prefix("flashgames_links")." where lid=".$HTTP_GET_VARS['lid']."";
   	$xoopsDB->query($query) or $eh->show("0013");
	$query = "delete from ".$xoopsDB->prefix("flashgames_text")." where lid=".$HTTP_GET_VARS['lid']."";
	$xoopsDB->query($query) or $eh->show("0013");
	$query = "delete from ".$xoopsDB->prefix("flashgames_votedata")." where lid=".$HTTP_GET_VARS['lid']."";
	$xoopsDB->query($query) or $eh->show("0013");
    	redirect_header("index.php",1,_ALBM_LINKDELETED);
	exit();
}
function modCat() {
    	global $xoopsDB, $HTTP_POST_VARS, $myts, $eh, $mytree;
    	$cid = $HTTP_POST_VARS["cid"];
	xoops_cp_header();
    	OpenTable();
    	echo "<h4>"._ALBM_MODCAT."</h4><br>";
	$result=$xoopsDB->query("select pid, title, imgurl from ".$xoopsDB->prefix("flashgames_cat")." where cid=$cid");
	list($pid,$title,$imgurl) = $xoopsDB->fetchRow($result);
	$title = $myts->makeTboxData4Edit($title);
	$imgurl = $myts->makeTboxData4Edit($imgurl);
	echo "<form action=index.php method=post>"._ALBM_TITLEC."<input type=text name=title value=\"$title\" size=51 maxlength=50><br><br>"._ALBM_IMGURLMAIN."<br><input type=text name=imgurl value=\"$imgurl\" size=100 maxlength=150><br><br>";
	echo _ALBM_PARENT."&nbsp;";
	$mytree->makeMySelBox("title", "title", $pid, 1, pid);
//	<input type=hidden name=pid value=\"$pid\">
	echo "<br><input type=\"hidden\" name=\"cid\" value=\"".$cid."\">
	<input type=\"hidden\" name=\"op\" value=\"modCatS\"><br>
	<input type=\"submit\" value=\""._ALBM_SAVE."\">
	<input type=\"button\" value=\""._ALBM_DELETE."\" onClick=\"location='index.php?pid=$pid&cid=$cid&op=delCat'\">";
	echo "&nbsp;<input type=\"button\" value=\""._ALBM_CANCEL."\" onclick=\"javascript:history.go(-1)\">";
	echo "</form>";
    	CloseTable();
	xoops_cp_footer();
}
function modCatS() {
    	global $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
    	$cid =  $HTTP_POST_VARS['cid'];
    	$pid =  $HTTP_POST_VARS['pid'];
    	$title =  $myts->makeTboxData4Save($HTTP_POST_VARS['title']);
	if (($HTTP_POST_VARS["imgurl"]) || ($HTTP_POST_VARS["imgurl"]!="")) {
		$imgurl = $myts->makeTboxData4Save($HTTP_POST_VARS["imgurl"]);
	}
	$xoopsDB->query("update ".$xoopsDB->prefix("flashgames_cat")." set pid=$pid, title='$title', imgurl='$imgurl' where cid=$cid") or $eh->show("0013");
    	redirect_header("index.php",1,_ALBM_DBUPDATED);
}
function delCat() {
    	global $xoopsDB, $HTTP_GET_VARS, $eh, $mytree;
    	$cid =  $HTTP_GET_VARS['cid'];
    	if($HTTP_GET_VARS['ok']){
    		$ok =  $HTTP_GET_VARS['ok'];
    	}
    	if($ok==1) {
		//get all subcategories under the specified category
		$arr=$mytree->getAllChildId($cid);
		for($i=0;$i<sizeof($arr);$i++){
			//get all links in each subcategory
			$result=$xoopsDB->query("select lid,ext from ".$xoopsDB->prefix("flashgames_games")." where cid=".$arr[$i]."") or $eh->show("0013");
			//now for each link, delete the text data and vote ata associated with the link
			while(list($lid, $ext)=$xoopsDB->fetchRow($result)){
				$delete = $lid;
				$q = "DELETE FROM ".$xoopsDB->prefix("flashgames_games")." WHERE lid = $delete";
				$xoopsDB->query($q) or $eh->show("0013");
				$q = "DELETE FROM ".$xoopsDB->prefix("flashgames_text")." WHERE lid = $delete";
				$xoopsDB->query($q) or $eh->show("0013");
				$q = "DELETE FROM ".$xoopsDB->prefix("flashgames_votedata")." WHERE lid = $delete";
				$xoopsDB->query($q) or $eh->show("0013");
				// delete comments for this game
				$com = new XoopsComments($xoopsDB->prefix("flashgames_comments"));
				$criteria = array("item_id=$delete", "pid=0");
				$commentsarray = $com->getAllComments($criteria);
				foreach($commentsarray as $comment){
					$comment->delete();
				}
				if(is_numeric($delete)) { // last security check
					unlink(XOOPS_ROOT_PATH."/modules/flashgames/games/$delete.$ext");
					unlink(XOOPS_ROOT_PATH."/modules/flashgames/games/thumbs/$delete.$ext");
				}
			}
			//all links for each subcategory is deleted, now delete the subcategory data
    	    $xoopsDB->query("delete from ".$xoopsDB->prefix("flashgames_cat")." where cid=".$arr[$i]."") or $eh->show("0013");
		}
		//all subcategory and associated data are deleted, now delete category data and its associated data
		$result=$xoopsDB->query("select lid, ext from ".$xoopsDB->prefix("flashgames_games")." where cid=".$cid."") or $eh->show("0013");
		while(list($lid, $ext)=$xoopsDB->fetchRow($result)){
			$delete = $lid;
			$q = "DELETE FROM ".$xoopsDB->prefix("flashgames_games")." WHERE lid = $delete";
			$xoopsDB->query($q) or $eh->show("0013");
			$q = "DELETE FROM ".$xoopsDB->prefix("flashgames_text")." WHERE lid = $delete";
			$xoopsDB->query($q) or $eh->show("0013");
			$q = "DELETE FROM ".$xoopsDB->prefix("flashgames_votedata")." WHERE lid = $delete";
			$xoopsDB->query($q) or $eh->show("0013");
			// delete comments for this game
			$com = new XoopsComments($xoopsDB->prefix("flashgames_comments"));
			$criteria = array("item_id=$delete", "pid=0");
			$commentsarray = $com->getAllComments($criteria);
			foreach($commentsarray as $comment){
				$comment->delete();
			}
			if(is_numeric($delete)) { // last security check
				unlink(XOOPS_ROOT_PATH."/modules/flashgames/games/$delete.$ext");
				unlink(XOOPS_ROOT_PATH."/modules/flashgames/games/thumbs/$delete.$ext");
			}
		}
	    $xoopsDB->query("delete from ".$xoopsDB->prefix("flashgames_cat")." where cid=$cid") or $eh->show("0013");
        redirect_header("index.php",1,_ALBM_CATDELETED);
		exit();
    	} else {
		xoops_cp_header();
		OpenTable();
		echo "<center>";
		echo "<h4><font color=\"#ff0000\">";
		echo _ALBM_WARNING."</font></h4><br>";
		echo "<table><tr><td>\n";
		echo myTextForm("index.php?op=delCat&cid=$cid&ok=1",_ALBM_YES);
		echo "</td><td>\n";
		echo myTextForm("index.php", _ALBM_NO);
		echo "</td></tr></table>\n";
    		CloseTable();
		xoops_cp_footer();
    	}
}
function delNewLink() {
    	global $xoopsDB, $HTTP_GET_VARS, $eh;
    	$query = "delete from ".$xoopsDB->prefix("flashgames_links")." where lid=".$HTTP_GET_VARS['lid']."";
    	$xoopsDB->query($query) or $eh->show("0013");
    	$query = "delete from ".$xoopsDB->prefix("flashgames_text")." where lid=".$HTTP_GET_VARS['lid']."";
    	$xoopsDB->query($query) or $eh->show("0013");
	redirect_header("index.php",1,_ALBM_LINKDELETED);
}
function addCat() {
    	global $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
    	$pid = $HTTP_POST_VARS["cid"];
    	$title = $HTTP_POST_VARS["title"];
    	if (($HTTP_POST_VARS["imgurl"]) || ($HTTP_POST_VARS["imgurl"]!="")) {
//		$imgurl = $myts->formatURL($HTTP_POST_VARS["imgurl"]);
//		$imgurl = urlencode($imgurl);
		$imgurl = $myts->makeTboxData4Save($HTTP_POST_VARS["imgurl"]);
	}
    	$title = $myts->makeTboxData4Save($title);
	$newid = $xoopsDB->genId($xoopsDB->prefix("flashgames_cat")."_cid_seq");
    	$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("flashgames_cat")." (cid, pid, title, imgurl) VALUES ($newid, $pid, '$title', '$imgurl')") or $eh->show("0013");
	redirect_header("index.php?op=linksConfigMenu",1,_ALBM_NEWCATADDED);
}

function addLink() {
    	global $xoopsConfig, $xoopsDB, $myts, $xoopsUser, $eh, $HTTP_POST_VARS;
	if (($HTTP_POST_VARS["url"]) || ($HTTP_POST_VARS["url"]!="")) {
	//	$url=$myts->formatURL($HTTP_POST_VARS["url"]);
//		$url = urlencode($url);
		$url = $myts->makeTboxData4Save($HTTP_POST_VARS["url"]);
	}
	$logourl = $myts->makeTboxData4Save($HTTP_POST_VARS["logourl"]);
    	$title = $myts->makeTboxData4Save($HTTP_POST_VARS["title"]);
    	$email = $myts->makeTboxData4Save($HTTP_POST_VARS["email"]);
    	$description = $myts->makeTareaData4Save($HTTP_POST_VARS["description"]);
    	$submitter = $xoopsUser->uid();
    	$result = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("flashgames_links")." where url='$url'");
    	list($numrows) = $xoopsDB->fetchRow($result);
	$errormsg = "";
	$error = 0;
    	if ($numrows>0) {
		$errormsg .= "<h4><font color=\"#ff0000\">";
		$errormsg .= _ALBM_ERROREXIST."</font></h4><br>";
		$error = 1;
    	}
// Check if Title exist
    	if ($title=="") {
		$errormsg .= "<h4><font color=\"#ff0000\">";
		$errormsg .= _ALBM_ERRORTITLE."</font></h4><br>";
    		$error =1;
    	}

// Check if Description exist
    	if ($description=="") {
		$errormsg .= "<h4><font color=\"#ff0000\">";
		$errormsg .= _ALBM_ERRORDESC."</font></h4><br>";
    		$error =1;
    	}
    	if($error == 1) {
		xoops_cp_header();
		echo $errormsg;
		xoops_cp_footer();
		exit();
    	}
    	if ( !empty($HTTP_POST_VARS['cid']) ) {
        	$cid = $HTTP_POST_VARS['cid'];
	} else {
		$cid = 0;
	}
	$newid = $xoopsDB->genId($xoopsDB->prefix("flashgames_links")."_lid_seq");
    	$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("flashgames_links")." (lid, cid, title, url, email, logourl, submitter, status, date, hits, rating, votes, comments) VALUES ($newid, $cid, '$title', '$url', '$email', '$logourl', $submitter, 1, ".time().", 0, 0, 0, 0)") or $eh->show("0013");
	if($newid == 0){
		$newid = $xoopsDB->getInsertId();
	}
    	$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("flashgames_text")." (lid, description) VALUES ($newid, '$description')") or $eh->show("0013");
    	redirect_header("index.php",1,_ALBM_NEWLINKADDED);
}
function approve(){
	global $xoopsConfig, $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
	$lid = $HTTP_POST_VARS['lid'];
	$title = $HTTP_POST_VARS['title'];
	$cid = $HTTP_POST_VARS['cid'];
	if ( empty($cid) ) {
		$cid = 0;
	}
	$email = $HTTP_POST_VARS['email'];
	$description = $HTTP_POST_VARS['description'];
	if (($HTTP_POST_VARS["url"]) || ($HTTP_POST_VARS["url"]!="")) {
//		$url=$myts->formatURL($HTTP_POST_VARS["url"]);
//		$url = urlencode($url);
		$url = $myts->makeTboxData4Save($HTTP_POST_VARS["url"]);
	}
	$logourl = $myts->makeTboxData4Save($HTTP_POST_VARS["logourl"]);
	$title = $myts->makeTboxData4Save($title);
	$email = $myts->makeTboxData4Save($email);
	$description = $myts->makeTareaData4Save($description);
	$query = "update ".$xoopsDB->prefix("flashgames_links")." set cid='$cid', title='$title', url='$url', email='$email', logourl='$logourl', status=1, date=".time()." where lid=".$lid."";
	$xoopsDB->query($query) or $eh->show("0013");
	$query = "update ".$xoopsDB->prefix("flashgames_text")." set description='$description' where lid=".$lid."";
	$xoopsDB->query($query) or $eh->show("0013");
	$result = $xoopsDB->query("select submitter from ".$xoopsDB->prefix("flashgames_links")." where lid=$lid");
	list($submitterid)=$xoopsDB->fetchRow($result);
	$submitter = XoopsUser::getUnameFromId($submitterid);
	$subject = sprintf(_ALBM_YOURLINK,$xoopsConfig['sitename']);
	$message = sprintf(_ALBM_HELLO,$submitter);
	$message .= "\n\n"._ALBM_WEAPPROVED."\n\n";
	$yourlinkurl = XOOPS_URL."/modules/flashgames/";
	$message .= sprintf(_ALBM_YOUCANBROWSE,$yourlinkurl);
	$message .= "\n\n"._ALBM_THANKSSUBMIT."\n\n".$xoopsConfig['sitename']."\n".XOOPS_URL."\n".$xoopsConfig['adminmail']."";
	$xoopsMailer =& getMailer();
	$xoopsMailer->useMail();
	$xoopsMailer->setToEmails($email);
	$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
	$xoopsMailer->setFromName($xoopsConfig['sitename']);
	$xoopsMailer->setSubject($subject);
	$xoopsMailer->setBody($message);
	$xoopsMailer->send();
    	redirect_header("index.php",1,_ALBM_NEWLINKADDED);
}

function flashgamesConfigAdmin() {

	global $xoopsConfig;
	global $flashgames_perpage, $flashgames_popular, $flashgames_newlinks, $flashgames_sresults, $flashgames_useshots, $flashgames_shotwidth;
	global $flashgames_width, $flashgames_heigth, $flashgames_fsize, $flashgames_managed;
    global $flashgames_scoresave, $flashgames_scoreshow, $flashgames_usersubmit, $flashgames_playershow;  // OKa 


	xoops_cp_header();
	OpenTable();
	echo "<h4>" . _ALBM_GENERALSET . "</h4><br>";
	echo "<form action=\"index.php\" method=\"post\">";
    echo "
    <table width=100% border=0><tr><td nowrap>
    "._ALBM_LINKSPERPAGE."</td><td width=100%>
        <select name=xflashgames_perpage>
        <option value=$flashgames_perpage selected>$flashgames_perpage</option>
        <option value=10>10</option>
        <option value=15>15</option>
        <option value=20>20</option>
        <option value=25>25</option>
        <option value=30>30</option>
        <option value=50>50</option>
    </select>
    </td></tr><tr><td nowrap>
    "._ALBM_HITSPOP."</td><td>
        <select name=xflashgames_popular>
        <option value=$flashgames_popular selected>$flashgames_popular</option>
        <option value=10>10</option>
        <option value=20>20</option>
        <option value=50>50</option>
        <option value=100>100</option>
        <option value=500>500</option>
        <option value=1000>1000</option>
    </select>
    </td></tr><tr><td nowrap>
    "._ALBM_LINKSNEW."</td><td>
        <select name=xflashgames_newlinks>
        <option value=$flashgames_newlinks selected>$flashgames_newlinks</option>
        <option value=10>10</option>
        <option value=15>15</option>
        <option value=20>20</option>
        <option value=25>25</option>
        <option value=30>30</option>
        <option value=50>50</option>
    </select>";
    echo "</td></tr>";
	echo "<tr><td nowrap>" . _ALBM_USESHOTS . "</td><td>";
	if ($flashgames_useshots==1) {
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_useshots\" VALUE=\"1\" CHECKED>&nbsp;" ._ALBM_YES."&nbsp;</INPUT>";
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_useshots\" VALUE=\"0\" >&nbsp;" ._ALBM_NO."&nbsp;</INPUT>";
	} else {
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_useshots\" VALUE=\"1\">&nbsp;" ._ALBM_YES."&nbsp;</INPUT>";
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_useshots\" VALUE=\"0\" CHECKED>&nbsp;" ._ALBM_NO."&nbsp;</INPUT>";
	}
	
	echo "</td></tr>";
	echo "<tr><td nowrap>" . _ALBM_IMGWIDTH . "</td><td>"; 
	if($flashgames_shotwidth!=""){
		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xflashgames_shotwidth\" VALUE=\"$flashgames_shotwidth\"></INPUT>";
	}else{
		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xflashgames_shotwidth\" VALUE=\"140\"></INPUT>";
	}
	echo "</td></tr>";
// Oka not used
	

//	echo "<tr><td nowrap>" . _ALBM_MAXWIDTH . "</td><td>"; 
//	if($myalbum_width!=""){
//		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmyalbum_width\" VALUE=\"$myalbum_width\"></INPUT>";
//	}else{
//		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmyalbum_width\" VALUE=\"800\"></INPUT>";
//	}
//	echo "</td></tr>";
//	echo "<tr><td nowrap>" . _ALBM_MAXHEIGTH . "</td><td>"; 
//	if($myalbum_heigth!=""){
//		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmyalbum_heigth\" VALUE=\"$myalbum_heigth\"></INPUT>";
//	}else{
//		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmyalbum_heigth\" VALUE=\"600\"></INPUT>";
//	}
//	echo "</td></tr>";
//	echo "<tr><td nowrap>" . _ALBM_MAXSIZE . "</td><td>"; 
//	if($myalbum_fsize!=""){
//		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmyalbum_fsize\" VALUE=\"$myalbum_fsize\"></INPUT>";
//	}else{
//		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xmyalbum_fsize\" VALUE=\"100000\"></INPUT>";
//	}
//	echo "</td></tr>";
	
	// OKa Begin
	// Max. number of highscore ranks to save
	echo "<tr><td nowrap>" ._ALBM_MAXRANKSAVE. "</td><td>"; 
	if($flashgames_scoresave!=""){
		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xflashgames_scoresave\" VALUE=\"$flashgames_scoresave\"></INPUT>";
	}else{
		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xflashgames_scoresave\" VALUE=\"15\"></INPUT>";
	}
	echo "</td></tr>";
	// Max. Number of highscore ranks to show
	echo "<tr><td nowrap>" ._ALBM_MAXRANKSHOW. "</td><td>"; 

	if($flashgames_scoreshow!=""){
		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xflashgames_scoreshow\" VALUE=\"$flashgames_scoreshow\"></INPUT>";
	}else{
		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xflashgames_scoreshow\" VALUE=\"15\"></INPUT>";
	}

	// Max. Number of top player ranks to show

	echo "<tr><td nowrap>" ._ALBM_MAXPLAYERSHOW. "</td><td>"; 
	if($flashgames_playershow!=""){
		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xflashgames_playershow\" VALUE=\"$flashgames_playershow\"></INPUT>";
	}else{
		echo "<INPUT TYPE=\"text\" size=\"10\" NAME=\"xflashgames_playershow\" VALUE=\"50\"></INPUT>";
	}


	 echo "</td></tr>";
	// OKa Ende
	
   
	echo "<tr><td nowrap>" . _ALBM_MANAGED . "</td><td>";
	if ($flashgames_managed==1) {
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_managed\" VALUE=\"1\" CHECKED>&nbsp;" ._ALBM_YES."&nbsp;</INPUT>";
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_managed\" VALUE=\"0\" >&nbsp;" ._ALBM_NO."&nbsp;</INPUT>";
	} else {
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_managed\" VALUE=\"1\">&nbsp;" ._ALBM_YES."&nbsp;</INPUT>";
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_managed\" VALUE=\"0\" CHECKED>&nbsp;" ._ALBM_NO."&nbsp;</INPUT>";
	}
	
// Oka begin

echo "<tr><td nowrap>" . _ALBM_USERSUBMIT . "</td><td>";
	if ($flashgames_usersubmit==1) {
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_usersubmit\" VALUE=\"1\" CHECKED>&nbsp;" ._ALBM_YES."&nbsp;</INPUT>";
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_usersubmit\" VALUE=\"0\" >&nbsp;" ._ALBM_NO."&nbsp;</INPUT>";
	} else {
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_usersubmit\" VALUE=\"1\">&nbsp;" ._ALBM_YES."&nbsp;</INPUT>";
		echo "<INPUT TYPE=\"RADIO\" NAME=\"xflashgames_usersubmit\" VALUE=\"0\" CHECKED>&nbsp;" ._ALBM_NO."&nbsp;</INPUT>";
	}
// oka end



	echo "</td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
   	echo "</table>";
   	echo "<input type=\"hidden\" name=\"op\" value=\"flashgamesConfigChange\">";
   	echo "<input type=\"submit\" value=\""._ALBM_SAVE."\">";
	echo "&nbsp;<input type=\"button\" value=\""._ALBM_CANCEL."\" onclick=\"javascript:history.go(-1)\">";
   	echo "</form>";
   	CloseTable();
	xoops_cp_footer();

}

function flashgamesConfigChange() {
	global $xoopsConfig, $HTTP_POST_VARS;

	$xflashgames_popular = $HTTP_POST_VARS['xflashgames_popular'];
	$xflashgames_newlinks = $HTTP_POST_VARS['xflashgames_newlinks'];
	$xflashgames_perpage = $HTTP_POST_VARS['xflashgames_perpage'];
	$xflashgames_useshots = $HTTP_POST_VARS['xflashgames_useshots'];
	$xflashgames_shotwidth = $HTTP_POST_VARS['xflashgames_shotwidth'];
	$xflashgames_width = $HTTP_POST_VARS['xflashgames_width'];
	$xflashgames_heigth = $HTTP_POST_VARS['xflashgames_heigth'];
	$xflashgames_fsize = $HTTP_POST_VARS['xflashgames_fsize'];		
	$xflashgames_managed = $HTTP_POST_VARS['xflashgames_managed'];		
// OKa begin
        $xflashgames_scoresave = $HTTP_POST_VARS['xflashgames_scoresave'];		
	$xflashgames_scoreshow = $HTTP_POST_VARS['xflashgames_scoreshow'];
        $xflashgames_playershow = $HTTP_POST_VARS['xflashgames_playershow'];        
        $xflashgames_usersubmit = $HTTP_POST_VARS['xflashgames_usersubmit'];


$xflashgames_width = 1102400;
$xflashgames_heigth = 1102400;
$xflashgames_fsize = 1000000000;

// OKa end

	$filename = XOOPS_ROOT_PATH."/modules/flashgames/cache/config.php";

	$file = fopen($filename, "w");
	$content = "";
	$content .= "<?PHP\n";
	$content .= "\n";
	$content .= "###############################################################################\n";
	$content .= "# Flashgames v0.9                                                                #\n";
	$content .= "#                                                                              #\n";
	$content .= "# \$flashgames_popular:	The number of hits required for a game to be popular. Default = 20      #\n";
	$content .= "# \$lashgames_newlinks:	The number of games that appear on the front page as latest games. Default = 10  #\n";
	$content .= "# \$flashgames_perpage:    	The number of games that appear for each page. Default = 10 #\n";
	$content .= "# \$flashgames_useshots:    	Use screenshots? Default = 1 (Yes) #\n";
	$content .= "# \$flashgames_shotwidth:    	Screenshot Image Width (Default = 140) #\n";
	$content .= "###############################################################################\n";
	$content .= "\n";
	$content .= "\$flashgames_popular = $xflashgames_popular;\n";
	$content .= "\$flashgames_newlinks = $xflashgames_newlinks;\n";
	$content .= "\$flashgames_perpage = $xflashgames_perpage;\n";
	$content .= "\$flashgames_useshots = $xflashgames_useshots;\n";
	$content .= "\$flashgames_shotwidth = $xflashgames_shotwidth;\n";
	$content .= "\$flashgames_width = $xflashgames_width;\n";
	$content .= "\$flashgames_heigth = $xflashgames_heigth;\n";
	$content .= "\$flashgames_fsize = $xflashgames_fsize;\n";	
	// OKa begin
    $content .= "\$flashgames_scoresave = $xflashgames_scoresave;\n";
	$content .= "\$flashgames_scoreshow = $xflashgames_scoreshow;\n";
	$content .= "\$flashgames_playershow = $xflashgames_playershow;\n";
	$content .= "\$flashgames_managed    = $xflashgames_managed;\n";	
	$content .= "\$flashgames_usersubmit = $xflashgames_usersubmit;\n";	

	// Oka end
	
	$content .= "\n";
	$content .= "?>\n";

	fwrite($file, $content);
    	fclose($file);

	redirect_header("index.php",1,_ALBM_CONFUPDATED);
}




switch ($op) {
		default:
			flashgames();
			break;
		case "delNewLink":
			delNewLink();
			break;
		case "approve":
			approve();
			break;
		case "addCat":
			addCat();
			break;
		case "addLink":
			addLink();
			break;
		case "listBrokenLinks":
			listBrokenLinks();
			break;
		case "delBrokenLinks":
			delBrokenLinks();
			break;
		case "ignoreBrokenLinks":
			ignoreBrokenLinks();
			break;
		case "listModReq":
			listModReq();
			break;
		case "changeModReq":
			changeModReq();
			break;
	        case "ignoreModReq":
			ignoreModReq();
			break;
		case "delCat":
			delCat();
			break;
		case "modCat":
			modCat();
			break;
		case "modCatS":
			modCatS();
			break;
		case "modLink":
			modLink();
			break;
		case "modLinkS":
			modLinkS();
			break;
		case "delLink":
			delLink();
			break;
		case "delVote":
			delVote();
			break;
		case "delComment":
			delComment($bid, $rid);
			break;
		case "flashgamesConfigAdmin":
			flashgamesConfigAdmin();
			break;
		case "flashgamesConfigChange":
			if (xoopsfwrite()) {
				flashgamesConfigChange();
			}
			break;
		case "linksConfigMenu":
			linksConfigMenu();
			break;
		case "listNewLinks":
			listNewLinks();
			break;
}

?>
