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
include "header.php";
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mytree = new XoopsTree($xoopsDB->prefix("flashgames_games"),"lid");
$xoopsOption['template_main'] = 'flashgames_highscores.html';
include XOOPS_ROOT_PATH."/header.php";
//generates top 10 charts by rating and hits for each main category

if(isset($rate)){
	$sort = _MD_RATING;
	$sortDB = "rating";
}else{
	$sort = _MD_HITS;
	$sortDB = "hits";
}


if ( $xoopsUser ){
 $uid = $xoopsUser->uid();
 $result=$xoopsDB->query("SELECT uname FROM ".$xoopsDB->prefix("users")." WHERE uid = $uid") or die("Error");
 $myrow = $xoopsDB->fetchArray($result);
 $playername = $myrow['uname'];
}



$xoopsTpl->assign('lang_sortby' ,$sort);
$xoopsTpl->assign('lang_rank' , _MD_RANK);
$xoopsTpl->assign('lang_title' , _MD_HIGHSCORE);
$xoopsTpl->assign('lang_name' , _MD_PLAYER);
$xoopsTpl->assign('lang_score' ,_MD_SCORE);
$xoopsTpl->assign('lang_info' ,_MD_INFO);
$xoopsTpl->assign('lang_noscore' ,_MD_NOSCORE);



$arr=array();
$result=$xoopsDB->query("select lid, title, gametype from ".$xoopsDB->prefix("flashgames_games")." where status=1 and (gametype >0)");

$e = 0;
$count = 1;
$rankings = array();
while(list($lid, $title, $gametype)=$xoopsDB->fetchRow($result)){




$pfad = 'games/';
// Check if screenshot is in gif format
$filename = "$pfad$lid.gif";
	

if (file_exists($filename)) 
{
$rankings[$e]['image'] = '<a href="'.XOOPS_URL.'/modules/flashgames/game.php?lid='.$lid.'" target="_self"><img src="'.XOOPS_URL.'/modules/flashgames/games/'.$lid.'.gif" width="24" height="24" /></a>';
}

// Check if screenshot is in jpg format
$filename = "$pfad$lid.jpg";
if (file_exists($filename)) 
{
$rankings[$e]['image'] = '<a href="'.XOOPS_URL.'/modules/flashgames/game.php?lid='.$lid.'" target="_self"><img src="'.XOOPS_URL.'/modules/flashgames/games/'.$lid.'.jpg" width="24" height="24" /></a>';
}

if (strlen($title) >= 19) {
$title = $myts->makeTboxData4Show(substr($title,0,(19 -1)))."...";
}

$rankings[$e]['title'] = '<a href="'.XOOPS_URL.'/modules/flashgames/game.php?lid='.$lid.'" target="_self">'.$title.'</a>';

	$query = "select lid, name, score, date from ".$xoopsDB->prefix("flashgames_score")." where  (lid=$lid";
	// get all child cat ids for a given cat id
	$arr=$mytree->getAllChildId($lid);
	$size = count($arr);
	for($i=0;$i<$size;$i++){
		$query .= " or lid=".$arr[$i]."";
	}


// lowest score on top
if ($gametype == 4 or $gametype == 2)
{
	$query .= ") order by score ASC";
  }
else
{
  $query .= ") order by score DESC";
     
}

 $rankings[$e]['count'] = $count;

 
 $lastscore = -99999999999;
 $rank = 0;
 $result2 = $xoopsDB->query($query,$flashgames_scoresave,0);
	
  while(list($lid,$name,$score,$date)=$xoopsDB->fetchRow($result2)){


if ( $gametype == 3  or  $gametype == 4 ) {
 //This is a time based score, so format it accordingly.  All time based scores are stored in seconds
 $timestamp = mktime(0, 0, $score);
 $score = strftime("%H:%M:%S", $timestamp);
}



if ($name == $playername)
{
  $name = "<strong>".$name."</strong>"; 
}


           if ($lastscore != $score) 
           {
        	$rank++;
        
		}
                         

            $lastscore = $score;


		$rankings[$e]['scores'][] = array('lid' => $lid, 'rank' => $rank, 'name' => $name , 'score' =>                                                   $score, 'date' => $date, 'count' => $count);
		
	}


if ($rank == 0)
{
$name = 'no score';
		
$rankings[$e]['scores'][] = array('lid' => $lid, 'rank' => $rank, 'name' => $name , 'score' =>                                                   $score, 'date' => $date, 'count' => $count);

}

	$e++;
        $count++;   

}
$xoopsTpl->assign('rankings', $rankings);
include XOOPS_ROOT_PATH.'/footer.php';
?>
