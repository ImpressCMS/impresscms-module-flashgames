<?php
// register globals=off fix
extract($_POST,EXTR_SKIP); 
extract($_GET);


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

$modcheck = $_POST["module"];
$funccheck = $_POST["func"];

if($modcheck == "pnFlashGames" && !empty($xoopsUser)){
	$uid= $xoopsUser->getVar('uid');
	if($uid == 0){
		print "&opSuccess=Not Logged In&endvar=true";
		return;
	}
	if(!isset($xoopsDB)){
		print "&opSuccess=DB Error&endvar=true";
		return;
	}
	
	$gid = $_POST["gid"];
	
	// Get current player's username
	$uid = $xoopsUser->uid();
	$result=$xoopsDB->query("SELECT uname FROM ".$xoopsDB->prefix("users")." WHERE uid = $uid") or die("Error getting username");
	$myrow = $xoopsDB->fetchArray($result);
	$player_name = $myrow['uname'];
	
	// Determine what the game is asking us to do
	switch($funccheck){
		case "storeScore":
			$player_score = $_POST['score'];
			$player_ip = $_SERVER['REMOTE_ADDR'];
			
			$result = pnFlashGames_storeScore($xoopsDB, $gid, $player_name, $player_score, $player_ip);
			if($result){
				print "&opSuccess=true&endvar=true";
			}else{
				print "&opSuccess=Error&endvar=true";
			}
			break;		
		
		case "saveGame":
			$gameData = $_POST["gameData"];
			
			$result = pnFlashGames_saveGame($xoopsDB, $gid, $player_name, $gameData);
			if($result){
				print "&opSuccess=true&endvar=true";
			}else{
				print "&opSuccess=false&endvar=true";
			}
			break;
		
		case "loadGame":
			$gameData = pnFlashGames_loadGame($xoopsDB, $gid, $player_name);
			
			if($gameData != false){
				//Return true
				print "&opSuccess=true&gameData=$gameData&endvar=1"; //send endvar to keep opSuccess separate from all other output from PostNuke
			}else{
				print "&opSuccess=false&error=Error&endvar=1";
			}
			break;
		case "loadGameScores":
			$scores = pnFlashGames_loadGameScores($xoopsDB, $gid);
			
			if($scores != false){
				//Return true
				print "&opSuccess=true&gameScores=$scores&endvar=1"; //send endvar to keep opSuccess separate from all other output from PostNuke
			}else{
				print "&opSuccess=false&error=Error&endvar=1";
			}
			break;
	}
}

# Error handler
function error_msg($msg) {
	exit("success=0&errorMsg=$msg");
}


$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
$mytree = new XoopsTree($xoopsDB->prefix("flashgames_cat"),"cid","pid");
$xoopsOption['template_main'] = 'flashgames_index.html';  // Oka

if($xoopsConfig['startpage'] == "flashgames"){
	$xoopsOption['show_rblock'] =1;
	include(XOOPS_ROOT_PATH."/header.php");
	make_cblock();
	echo "<br />";
}else{
	$xoopsOption['show_rblock'] =0;
	include(XOOPS_ROOT_PATH."/header.php");
}
$result=$xoopsDB->query("SELECT cid, title, imgurl FROM ".$xoopsDB->prefix("flashgames_cat")." WHERE pid = 0 ORDER BY title") or die("Error");

// Begin OKa
$count = 1;
while($myrow = $xoopsDB->fetchArray($result)) {
	$imgurl = '';
	if ($myrow['imgurl'] && $myrow['imgurl'] != "http://"){
		$imgurl = $myts->makeTboxData4Edit($myrow['imgurl']);
	}
	$totallink = getTotalItems($myrow['cid'], 1);
	// get child category objects
	$arr = array();
	$arr = $mytree->getFirstChild($myrow['cid'], "title");
	$space = 0;
	$chcount = 0;
	$subcategories = '';
	foreach($arr as $ele){
		$chtitle = $myts->makeTboxData4Show($ele['title']);
		if ($chcount > 5) {
			$subcategories .= "...";
			break;
		}
		if ($space>0) {
			$subcategories .= ", ";
		}
		$subcategories .= "<a href=\"".XOOPS_URL."/modules/flashgames/viewcat.php?cid=".$ele['cid']."\">".$chtitle."</a>";
		$space++;
		$chcount++;
	}
	$xoopsTpl->append('categories', array('image' => $imgurl, 'id' => $myrow['cid'], 'title' => $myts->makeTboxData4Show($myrow['title']), 'subcategories' => $subcategories, 'totallink' => $totallink, 'count' => $count));
	$count++;
}
list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select count(*) from ".$xoopsDB->prefix("flashgames_games")." where status>0"));


if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
	$isadmin = true;
} else {
	$isadmin = false;
}

if (( $xoopsUser && $flashgames_usersubmit == 1) or ($isadmin)) {
$xoopsTpl->assign('lang_thereare', sprintf(_ALBM_THEREAREADMIN,$numrows));}
 else
{
$xoopsTpl->assign('lang_thereare', sprintf(_ALBM_THEREARE,$numrows));

}



$xoopsTpl->assign('lang_description', _ALBM_DESCRIPTIONC);
$xoopsTpl->assign('lang_lastupdate', _ALBM_LASTUPDATEC);
$xoopsTpl->assign('lang_hits', _ALBM_HITSC);
$xoopsTpl->assign('lang_rating', _ALBM_RATINGC);
$xoopsTpl->assign('lang_ratethisgame', _ALBM_RATETHISGAME);
$xoopsTpl->assign('lang_latestlistings' , _ALBM_LATESTLIST);
$xoopsTpl->assign('lang_category' , _ALBM_CATEGORYC);
$xoopsTpl->assign('lang_visit' , _ALBM_VISIT);
$xoopsTpl->assign('lang_comments' , _COMMENTS);
$xoopsTpl->assign('lang_membersonly' , _ALBM_MEMBERSONLY);


$result = $xoopsDB->query("SELECT l.lid, l.cid, l.title, l.ext, l.res_x, l.res_y, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.members, l.submitter, t.description FROM ".$xoopsDB->prefix("flashgames_games")." l, ".$xoopsDB->prefix("flashgames_text")." t where l.status>0 and l.lid=t.lid ORDER BY date DESC", $flashgames_newlinks, 0);


while(list($lid, $cid, $ltitle, $ext, $res_x, $res_y, $status, $time, $hits, $rating, $votes, $comments, $members, $submitter, $description) = $xoopsDB->fetchRow($result)) {
	
// using screenshots
if ( $flashgames_useshots ) {

$dir = 'games/';
// Check if screenshot is in gif format
$filename = "$dir$lid.gif";
	
if (file_exists($filename)) 
{
$img = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid=$lid'><img src='".XOOPS_URL."/modules/flashgames/games/$lid.gif' width='$flashgames_shotwidth' alt='' /></a>";
$imag = 'yes';
}

// Check if screenshot is in jpg format
$filename = "$dir$lid.jpg";
if (file_exists($filename)) 
{
$img = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid=$lid'><img src='".XOOPS_URL."/modules/flashgames/games/$lid.jpg' width='$flashgames_shotwidth' alt='' /></a>";
$imag = 'yes';
}

// no image available
if ($imag != "yes" )
{
$img = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid=$lid'><img src='".XOOPS_URL."/modules/flashgames/games/noimage.gif' width='$flashgames_shotwidth' alt='' /></a>";


}

	
	$xoopsTpl->assign('shotwidth', $flashgames_shotwidth);
	$xoopsTpl->assign('tablewidth', $flashgames_shotwidth+60);
	$xoopsTpl->assign('show_screenshot', true);
	$xoopsTpl->assign('lang_noscreenshot', _MD_NOSHOTS);
		}
		

	
	if ($isadmin) {
		$adminlink = '<a href="'.XOOPS_URL.'/modules/flashgames/editgame.php?lid='.$lid.'"><img src="'.XOOPS_URL.'/modules/flashgames/images/editicon.gif" border="0" alt="'._ALBM_EDITTHISLINK.'" /></a>';
	    $members = '';
	} else {
		$adminlink = '';
if ( $members == 1 ){ 
   $members = _ALBM_MEMBERSONLY;}
   else
   {    $members = ''; }
 		
		
	}
	if ($votes == 1) {
		$votestring = _ALBM_ONEVOTE;
	} else {
		$votestring = sprintf(_ALBM_NUMVOTES,$votes);
	}
	
		
	$path = $mytree->getPathFromId($cid, "title");
	$path = substr($path, 1);
	$path = str_replace("/"," <img src='".XOOPS_URL."/modules/flashgames/images/arrow.gif' board='0' alt=''> ",$path);
	$new = newlinkgraphic($time, $status);
	$pop = popgraphic($hits);
	
   // $title = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid=$lid'/>$ltitle</a>";
	 $title = $ltitle;
	 
	$xoopsTpl->append('games', array('id' => $lid, 'cid' => $cid, 'rating' => number_format($rating, 2), 'title' => $title, 'ext' => $new.$pop, 'category' => $path,  'updated' => formatTimestamp($time,"s"), 'description' => $myts->makeTareaData4Show($description), 'adminlink' => $adminlink, 'hits' => $hits, 'votes' => $votestring, 'comments' => $comments,  'members' => $members, 'image' => $img ));
}

$xoopsTpl->assign('dontchange_copyright', GetFooter() );

include XOOPS_ROOT_PATH.'/footer.php';



?>
