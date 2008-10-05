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

//include XOOPS_ROOT_PATH.'/modules/flashgames/header.php';
include_once XOOPS_ROOT_PATH.'/modules/flashgames/language/'.$xoopsConfig['language'].'/main.php';

/******************************************************************************
 * Function: b_flashgames_top_show
 * Input   : $options[0] = date for the most recent links
 *                    hits for the most popular links
 *           $block['content'] = The optional above content
 *           $options[1]   = How many reviews are displayes
 * Output  : Returns the desired most recent or most popular links
 ******************************************************************************/
function b_flashgames_top_show($options) {
	global $xoopsDB;
	$myts =& MyTextSanitizer::getInstance();
	$block = array();
	$sql = "SELECT lid, cid, title, date, hits  FROM ".$xoopsDB->prefix("flashgames_games")." WHERE status >0 ORDER BY ".$options[0]." DESC";
	
	$result = $xoopsDB->query($sql,$options[1],0);
	while ( $myrow = $xoopsDB->fetchArray($result) ) {
		$news = array();
		$title = $myts->makeTboxData4Show($myrow["title"]);
	///	if ( !XOOPS_USE_MULTIBYTES ) {
	//		if (strlen($myrow['title']) >= $options[2]) {
	//			$title = $myts->makeTboxData4Show(substr($myrow['title'],0,($options[2] -1)))."..";
	//		}
	//	}
		$news['title'] = $title;


		$news['lid'] = $myrow['lid'];
		if ( $options[0] == "date" ) {
			$news['date'] = formatTimestamp($myrow['date'],"s");

		} elseif ( $options[0] == "hits" ) {
			$news['hits'] = $myrow['hits'];
		}
		$block['games'][] = $news;
	}
	return $block;
}






function b_flashgames_topplayer_show($options) {

global $xoopsDB, $playername, $num, $xoopsUser;
	$myts =& MyTextSanitizer::getInstance();

	$block = array();



include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mytree = new XoopsTree($xoopsDB->prefix("flashgames_games"),"lid");

	$sort = _MD_HITS;
	$sortDB = "hits";



if ( $xoopsUser ){
 $uid = $xoopsUser->uid();
 $result=$xoopsDB->query("SELECT uname FROM ".$xoopsDB->prefix("users")." WHERE uid = $uid") or die("Error");
 $myrow = $xoopsDB->fetchArray($result);
 $playername = $myrow['uname'];
}


$arr=array();
$result=$xoopsDB->query("SELECT lid, title, gametype from ".$xoopsDB->prefix("flashgames_games")." WHERE status=1 and  (gametype >0)");
$e = 0;
$count = 1;
$rankings = array();

// Begin Oka new
$players = array();   
$orderedlist = array();   

	//Manually assign the weights to each score position for now
	$firstPlace = 10;
	$secondPlace = 9;
	$thirdPlace = 8;
	$fourthPlace = 7;
	$fifthPlace = 6;
	$sixthPlace = 5;
	$seventhPlace = 4;
	$eighthPlace = 3;
	$ninthPlace = 2;
	$tenthPlace = 1;

// End Oka new




while(list($lid, $title, $gametype)=$xoopsDB->fetchRow($result)){

	$query = "SELECT lid, name, score, date from ".$xoopsDB->prefix("flashgames_score")." WHERE  (lid=$lid";
	// get all child cat ids for a given cat id
	$arr=$mytree->getAllChildId($lid);
	$size = count($arr);
	for($i=0;$i<$size;$i++){
		$query .= " or lid=".$arr[$i]."";
	}


// lowest score on top
if ($gametype == 4 or $gametype == 2)
{
	$query .= ") ORDER BY score ASC";
  }
else
{
  $query .= ") ORDER BY score DESC";
     
}

$lastscore = -999999999999999;

	$result2 = $xoopsDB->query($query,10,0);
	$rank = 0;
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



// Begin Oka
switch($rank){
                	case 1:
                		$weight = $firstPlace;
                		break;
                	case 2:
                		$weight = $secondPlace;
                		break;
                	case 3:
                		$weight = $thirdPlace;
                		break;
                	case 4:
                		$weight = $fourthPlace;
                		break;
                	case 5:
                		$weight = $fifthPlace;
                		break;
                	case 6:
                		$weight = $sixthPlace;
                		break;
                	case 7:
                		$weight = $seventhPlace;
                		break;
                	case 8:
                		$weight = $eighthPlace;
                		break;
                	case 9:
                		$weight = $ninthPlace;
                		break;
                	case 10:
                		$weight = $tenthPlace;
                		break;
                }


              $players[$name] += $weight  ;
           
                $num++;



// End Oka



	}
	$e++;
        $count++;   

}


$lastscore = -999999999999999;

arsort($players);
	reset($players);

       $rank = 0;
        $counter = 1; 

	while ((list ($key, $val) = each ($players)) && $counter <= $options[0]) {
	   
 if ($lastscore != $val) 
              {
        	$rank++;
        
		}
                         

            $lastscore = $val;


                            $orderedList[] = array('name' => $key,
	    					   'weight' => $val,
                                               'rank' => $rank );
	    
      $counter++;
	

}	
	


$block = $orderedList;
$block[1]['lang_listall'] =  _ALBM_LISTALL ;




       

	return $block;
}


function b_flashgames_top_edit($options) {
	$form = ""._ALBM_MYLINKS_DISP."&nbsp;";
	$form .= "<input type='hidden' name='options[]' value='";
	if($options[0] == "date"){
		$form .= "date'";
	}else {
		$form .= "hits'";
	}
	$form .= " />";
	$form .= "<input type='text' name='options[]' value='".$options[1]."' />&nbsp;"._ALBM_MYLINKS_LINKS."";
	return $form;
}



function b_flashgames_topplayer_edit($options) {
	$form = ""._ALBM_MYLINKS_DISP."&nbsp;";
//	$form .= "<input type='hidden' name='options[]' value='";
//	if($options[0] == "date"){
//		$form .= "date'";
//	}else {
//		$form .= "hits'";
//	}
//	$form .= " />";
	$form .= "<input type='text' name='options[]' value='".$options[0]."' />&nbsp;"._ALBM_MYLINKS_PLAYER."";
	return $form;
}
?>







