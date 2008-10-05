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

include 'header.php';
$myts =& MyTextSanitizer::getInstance();
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';

$mytree = new XoopsTree($xoopsDB->prefix('flashgames_cat'),'cid','pid');

$cid = intval($_GET['cid']);
$lid = intval($_GET['lid']);
$xoopsOption['template_main'] = 'flashgames_viewcat.html';
include XOOPS_ROOT_PATH.'/header.php';

if($_GET['show']!='') {$show = intval($_GET['show']);}
else {$show = $flashgames_perpage;}
if(!isset($_GET['min'])) {$min = 0;}
else {$min = intval($_GET['min']);}
if(!isset($max)) {$max = $min + $show;}
if(isset($_GET['orderby'])) {$orderby = convertorderbyin($_GET['orderby']);}
else {$orderby = 'title ASC';}

// neue routine
$pathstring = "<a href='index.php'>"._ALBM_HOME."</a>&nbsp;:&nbsp;";
$pathstring .= $mytree->getNicePathFromId($cid, 'title', 'viewcat.php?op=');
$xoopsTpl->assign('category_path', $pathstring);
$xoopsTpl->assign('category_id', $cid);
// get child category objects
$arr=array();
$arr=$mytree->getFirstChild($cid, 'title');
if(count($arr) > 0)
{
	$scount = 1;
	foreach($arr as $ele)
	{
		$sub_arr=array();
		$sub_arr=$mytree->getFirstChild($ele['cid'], 'title');
		$space = 0;
		$chcount = 0;
		$infercategories = '';
		foreach($sub_arr as $sub_ele)
		{
			$chtitle=$myts->makeTboxData4Show($sub_ele['title']);
			if($chcount>5)
			{
				$infercategories .= "...";
				break;
			}
			if($space>0) {$infercategories .= ", ";}
			$infercategories .= "<a href=\"".XOOPS_URL."/modules/flashgames/viewcat.php?cid=".$sub_ele['cid']."\">".$chtitle."</a>";
			$space++;
			$chcount++;
		}
   		$xoopsTpl->append('subcategories', array('title' => $myts->makeTboxData4Show($ele['title']), 'id' => $ele['cid'], 'infercategories' => $infercategories, 'totallinks' => getTotalItems($ele['cid'], 1), 'count' => $scount));
		$scount++;
	}
}

if(!empty($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {$isadmin = true;}
else {$isadmin = false;}

$fullcountresult=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("flashgames_games")." where cid=".$cid." and status>0");
list($numrows) = $xoopsDB->fetchRow($fullcountresult);

$page_nav = '';
if($numrows>0)
{
	$xoopsTpl->assign('lang_description', _ALBM_DESCRIPTIONC);
	$xoopsTpl->assign('lang_lastupdate', _ALBM_LASTUPDATEC);
	$xoopsTpl->assign('lang_hits', _ALBM_HITSC);
	$xoopsTpl->assign('lang_rating', _ALBM_RATINGC);
	$xoopsTpl->assign('lang_ratethissite', _ALBM_RATETHISSITE);
	$xoopsTpl->assign('lang_modify', _ALBM_MODIFY);
	$xoopsTpl->assign('lang_category', _ALBM_CATEGORYC);
	$xoopsTpl->assign('lang_visit', _ALBM_VISIT);
	$xoopsTpl->assign('show_links', true);
	$xoopsTpl->assign('lang_comments', _COMMENTS);
	$sql = "SELECT l.lid, l.cid, l.title, l.ext, l.res_x, l.res_y, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.members, t.description from ".$xoopsDB->prefix('flashgames_games')." l, ".$xoopsDB->prefix('flashgames_text')." t WHERE cid=$cid and l.lid=t.lid and status>0 ORDER BY $orderby";
	$result=$xoopsDB->query($sql,$show,$min);

	//if 2 or more items in result, show the sort menu
	if($numrows>1)
	{
		$xoopsTpl->assign('show_nav', true);
		$orderbyTrans = convertorderbytrans($orderby);
		$xoopsTpl->assign('lang_sortby', _ALBM_SORTBY);
		$xoopsTpl->assign('lang_title', _ALBM_TITLE);
		$xoopsTpl->assign('lang_date', _ALBM_DATE);
		$xoopsTpl->assign('lang_rating', _ALBM_RATINGC);
		$xoopsTpl->assign('lang_popularity', _ALBM_POPULARITY);
		$xoopsTpl->assign('lang_cursortedby', sprintf(_ALBM_CURSORTEDBY, convertorderbytrans($orderby)));
		$xoopsTpl->assign('lang_description', _ALBM_DESCRIPTIONC);
		$xoopsTpl->assign('lang_lastupdate', _ALBM_LASTUPDATEC);
		$xoopsTpl->assign('lang_hits', _ALBM_HITSC);
		$xoopsTpl->assign('lang_ratethisgame', _ALBM_RATETHISGAME);
		$xoopsTpl->assign('lang_latestlistings', _ALBM_LATESTLIST);
		$xoopsTpl->assign('lang_category', _ALBM_CATEGORYC);
		$xoopsTpl->assign('lang_visit', _ALBM_VISIT);
		$xoopsTpl->assign('lang_comments', _COMMENTS);
	}
	while(list($lid, $cid,$ltitle, $ext, $res_x, $res_y, $status, $time, $hits, $rating, $votes, $comments, $members,  $description) = $xoopsDB->fetchRow($result))
	{
		// using screenshots
		if($flashgames_useshots)
		{
			$dir = 'games/';
			// Check if screenshot is in gif format
			$filename = '$dir$lid.gif';
	
			if(file_exists($filename))
			{
				$img = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid=$lid'><img src='".XOOPS_URL."/modules/flashgames/games/$lid.gif' width='$flashgames_shotwidth' alt='' /></a>";
				$imag = 'yes';
			}

			// Check if screenshot is in jpg format
			$filename = '$dir$lid.jpg';
			if(file_exists($filename)) 
			{
				$img = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid=$lid'><img src='".XOOPS_URL."/modules/flashgames/games/$lid.jpg' width='$flashgames_shotwidth' alt='' /></a>";
				$imag = 'yes';
			}
			// no image available
			if($imag != 'yes')
			{
				$img = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid=$lid'><img src='".XOOPS_URL."/modules/flashgames/games/noimage.gif' width='$flashgames_shotwidth' alt='' /></a>";
			}
		}
		else {$img ='';}
	
		$xoopsTpl->assign('shotwidth', $flashgames_shotwidth);
		$xoopsTpl->assign('tablewidth', $flashgames_shotwidth+60);
		$xoopsTpl->assign('show_screenshot', true);
		$xoopsTpl->assign('lang_noscreenshot', _MD_NOSHOTS);
		$xoopsTpl->assign('lang_membersonly' , _ALBM_MEMBERSONLY);

		if($isadmin)
		{
			$adminlink = '<a href="'.XOOPS_URL.'/modules/flashgames/editgame.php?lid='.$lid.'"><img src="'.XOOPS_URL.'/modules/flashgames/images/editicon.gif" border="0" alt="'._ALBM_EDITTHISLINK.'" /></a>';
			$members = ' ';
		}
		else
		{
			$adminlink = '';
			if($members == 1) {$members = _ALBM_MEMBERSONLY;}
			else {$members = '';}
		}
		if($votes == 1) {$votestring = _ALBM_ONEVOTE;}
		else {$votestring = sprintf(_ALBM_NUMVOTES,$votes);}
	
		$path = $mytree->getPathFromId($cid, 'title');
		$path = substr($path, 1);
		$path = str_replace('/'," <img src='".XOOPS_URL."/modules/flashgames/images/arrow.gif' board='0' alt=''> ",$path);
		
		$new = newlinkgraphic($time, $status);
		$pop = popgraphic($hits);

		$title = $ltitle;

		$xoopsTpl->append('games', array('id' => $lid, 'cid' => $cid,  'rating' => number_format($rating, 2), 'title' => $title, 'ext' => $new.$pop, 'category' => $path, 'logourl' => $myts->makeTboxData4Show($logourl), 'updated' => formatTimestamp($time,'s'), 'description' => $myts->displayTarea($description), 'adminlink' => $adminlink, 'hits' => $hits, 'votes' => $votestring, 'comments' => $comments, 'members' => $members, 'image' => $img));
	}

	$orderby = convertorderbyout($orderby);
	//Calculates how many pages exist.  Which page one should be on, etc...
	$linkpages = ceil($numrows/$show);
	//Page Numbering
	if($linkpages!=1 && $linkpages!=0)
	{
		$cid = intval($_GET['cid']);
		$prev = $min-$show;
		if($prev>=0) {$page_nav .= "<a href='viewcat.php?cid=$cid&amp;min=$prev&amp;orderby=$orderby&amp;show=$show'><b><u>&laquo;</u></b></a>&nbsp;";}
		$counter = 1;
		$currentpage = ($max/$show);
		while($counter<=$linkpages)
		{
			$mintemp = ($show*$counter)-$show;
			if($counter == $currentpage) {$page_nav .= "<b>($counter)</b>&nbsp;";}
			else {$page_nav .= "<a href='viewcat.php?cid=$cid&amp;min=$mintemp&amp;orderby=$orderby&amp;show=$show'>$counter</a>&nbsp;";}
			$counter++;
		}
		if($numrows>$max)
		{
			$page_nav .= "<a href='viewcat.php?cid=$cid&amp;min=$max&amp;orderby=$orderby&amp;show=$show'>";
			$page_nav .= "<b><u>&raquo;</u></b></a>";
		}
	}
}
$xoopsTpl->assign('page_nav', $page_nav);
$xoopsTpl->assign('dontchange_copyright', GetFooter());

include XOOPS_ROOT_PATH.'/footer.php';
?>
