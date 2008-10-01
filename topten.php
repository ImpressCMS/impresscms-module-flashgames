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
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
$mytree = new XoopsTree($xoopsDB->prefix("flashgames_cat"),"cid","pid");

include(XOOPS_ROOT_PATH."/header.php");
//generates top 10 charts by rating and hits for each main category
// OpenTable();
echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 0px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";


mainheader();
if(isset($rate)){
	$sort = _ALBM_RATING;
	$sortDB = "rating";
}else{
	$sort = _ALBM_HITS;
	$sortDB = "hits";
}
$arr=array();
$result=$xoopsDB->query("select cid, title from ".$xoopsDB->prefix("flashgames_cat")." where pid=0");
while(list($cid,$ctitle)=$xoopsDB->fetchRow($result)){
	$boxtitle = "<big>";
	$boxtitle .= sprintf(_ALBM_TOP10,$ctitle);
	$boxtitle .= " (".$sort.")</big>";
	$thing = "<table width='100%' border='0'><tr><td width='10%' class='bg3'><b>"._ALBM_RANK."</b></td><td width='30%' class='bg3'><b>"._ALBM_TITLE."</b></td><td width='40%' class='bg3'><b>"._ALBM_CATEGORY."</b></td><td width='12%' class='bg3' align='center'><b>"._ALBM_HITS."</b></td><td width='12%' class='bg3' align='center'><b>"._ALBM_RATING."</b></td><td width='8%' class='bg3' align='right'><b>"._ALBM_VOTE."</b></td></tr>";
	$query = "select lid, cid, title, hits, rating, votes from ".$xoopsDB->prefix("flashgames_games")." where status>0 and (cid=$cid";
	// get all child cat ids for a given cat id
	$arr=$mytree->getAllChildId($cid);
	$size = sizeof($arr);
	for($i=0;$i<$size;$i++){
		$query .= " or cid=".$arr[$i]."";
	}
	$query .= ") order by ".$sortDB." DESC"; 
	$result2 = $xoopsDB->query($query,10,0);
	$rank = 1;
	while(list($lid,$lcid,$ltitle,$hits,$rating,$votes)=$xoopsDB->fetchRow($result2)){
		$rating = number_format($rating, 2);
		if($hit){
			$hits = "<span class='fg2'>$hits</span>";
		} elseif($rate) {
			$rating = "<span class='fg2'>$rating</span>";
		}else{
		}
		$catpath = $mytree->getPathFromId($lcid, "title");
		$catpath= substr($catpath, 1);
		$catpath = str_replace("/"," <span class='fg2'>&raquo;&raquo;</span> ",$catpath);
		$thing .= "<tr><td>$rank</td>";
		$thing .= "<td><a href='game.php?lid=$lid'>$ltitle</a></td>";
		$thing .= "<td>$catpath</td>";
		$thing .= "<td align='center'>$hits</td>";
		$thing .= "<td align='center'>$rating</td><td align='right'>$votes</td></tr>";
		$rank++;
	}
	$thing .= "</table>";
	themecenterposts($boxtitle,$thing);
	echo "<br />";
}
CloseTable();

include("footer.php");

?>
