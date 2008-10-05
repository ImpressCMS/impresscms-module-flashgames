<?php
// register globals=off fix
extract($_POST,EXTR_SKIP); 
extract($_GET);


// ------------------------------------------------------------------------- //
//                      flashgames - XOOPS game album                          //
//                     <http://bluetopia.homeip.net/>                        //
// ------------------------------------------------------------------------- //
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

// $Id: functions.php,v 1.3 2005/09/20 14:15:19 leason_sk Exp $ 



function mainheader($mainlink=1) {
    	echo "<br /><p><div align=\"center\">";
	echo "<a href=\"".XOOPS_URL."/modules/flashgames/index.php\"><img src=\"".XOOPS_URL."/modules/flashgames/images/logo.gif\" border=\"0\" /></a>";
    	echo "</div></p><br />";
}

function createThumb($imagePath, $name, $ext) {
	global $flashgames_shotwidth;
    $image_stats = GetImageSize($imagePath); 
    if ($image_stats[2] == 1) { // no gif support, big thumbs!
        copy($imagePath, XOOPS_ROOT_PATH."/modules/flashgames/games/thumbs/$name.$ext");
        return;
    } else if ($image_stats[2] == 2) { 
        $src_img = ImageCreateFromJPEG($imagePath);
    } else if ($image_stats[2] == 3) {
        $sourceImg = ImageCreateFromPNG($imagePath);
    } else die();
    
	$imagewidth = $image_stats[0]; 
	$imageheight = $image_stats[1]; 
	$new_w = $flashgames_shotwidth;
	$scale = ($imagewidth / $new_w); 
    $new_h = round($imageheight / $scale);

    $gd2 = true;
    $dst_img = @ImageCreateTrueColor($new_w,$new_h);
    if ( $dst_img == "") {
        $gd2 = false;
       	$dst_img = ImageCreate($new_w,$new_h);
        if ( $dst_img == "") {
            // fixme: report GD error.            
        }
    }

    if ($gd2) {
        ImageCopyResampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));;    
    } else {
       	imagecopyresized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
    }
    
    if ($image_stats[2] == 2) { 
        Imagejpeg($dst_img, XOOPS_ROOT_PATH."/modules/flashgames/games/thumbs/$name.$ext");
    } else if ($image_stats[2] == 3) {
        Imagepng($dst_img, XOOPS_ROOT_PATH."/modules/flashgames/games/thumbs/$name.$ext");
    }
	imagedestroy($src_img);
	imagedestroy($dst_img);
}

function newlinkgraphic($time, $status) {
	$count = 7;
	$new = '';
	$startdate = (time()-(86400 * $count));
	if ($startdate < $time) {
		if($status==1){
			$new = "&nbsp;<img src=\"".XOOPS_URL."/modules/flashgames/images/newred.gif\" alt=\""._MD_NEWTHISWEEK."\" />";
		}elseif($status==2){
			$new = "&nbsp;<img src=\"".XOOPS_URL."/modules/flashgames/images/update.gif\" alt=\""._MD_UPTHISWEEK."\" />";
		}
	}
	return $new;
}

function popgraphic($hits) {
	global $flashgames_popular;
	
     if ($hits >= $flashgames_popular) {
		return "&nbsp;<img src=\"".XOOPS_URL."/modules/flashgames/images/pop.gif\" alt=\""._MD_POPULAR."\" />";
	}
	return '';
}


//Reusable Link Sorting Functions
function convertorderbyin($orderby) {
    	if ($orderby == "titleA")                        $orderby = "title ASC";
    	if ($orderby == "dateA")                        $orderby = "date ASC";
    	if ($orderby == "hitsA")                        $orderby = "hits ASC";
    	if ($orderby == "ratingA")                        $orderby = "rating ASC";
    	if ($orderby == "titleD")                        $orderby = "title DESC";
    	if ($orderby == "dateD")                        $orderby = "date DESC";
    	if ($orderby == "hitsD")                        $orderby = "hits DESC";
    	if ($orderby == "ratingD")                        $orderby = "rating DESC";
    	return $orderby;
}


function convertorderbytrans($orderby) {
    	if ($orderby == "hits ASC")   $orderbyTrans = ""._ALBM_POPULARITYLTOM."";
    	if ($orderby == "hits DESC")    $orderbyTrans = ""._ALBM_POPULARITYMTOL."";
    	if ($orderby == "title ASC")    $orderbyTrans = ""._ALBM_TITLEATOZ."";
   	if ($orderby == "title DESC")   $orderbyTrans = ""._ALBM_TITLEZTOA."";
    	if ($orderby == "date ASC") $orderbyTrans = ""._ALBM_DATEOLD."";
    	if ($orderby == "date DESC")   $orderbyTrans = ""._ALBM_DATENEW."";
    	if ($orderby == "rating ASC")  $orderbyTrans = ""._ALBM_RATINGLTOH."";
    	if ($orderby == "rating DESC") $orderbyTrans = ""._ALBM_RATINGHTOL."";
    	return $orderbyTrans;
}


function convertorderbyout($orderby) {
    	if ($orderby == "title ASC")            $orderby = "titleA";
    	if ($orderby == "date ASC")            $orderby = "dateA";
    	if ($orderby == "hits ASC")          $orderby = "hitsA";
    	if ($orderby == "rating ASC")        $orderby = "ratingA";
    	if ($orderby == "title DESC")              $orderby = "titleD";
    	if ($orderby == "date DESC")            $orderby = "dateD";
    	if ($orderby == "hits DESC")          $orderby = "hitsD";
    	if ($orderby == "rating DESC")        $orderby = "ratingD";
    	return $orderby;
}



//updates rating data in itemtable for a given item
function updaterating($sel_id){
	global $xoopsDB;
	$query = "SELECT rating FROM ".$xoopsDB->prefix("flashgames_votedata")." WHERE lid = ".$sel_id."";
	//echo $query;
	$voteresult = $xoopsDB->query($query);
    	$votesDB = $xoopsDB->getRowsNum($voteresult);
	$totalrating = 0;
    	while(list($rating)=$xoopsDB->fetchRow($voteresult)){
		$totalrating += $rating;
	}
	$finalrating = $totalrating/$votesDB;
	$finalrating = number_format($finalrating, 4);
	$query =  "UPDATE ".$xoopsDB->prefix("flashgames_games")." SET rating=$finalrating, votes=$votesDB WHERE lid = $sel_id";
	//echo $query;
    	$xoopsDB->queryF($query) or die();
}

//returns the total number of items in items table that are accociated with a given table $table id
function getTotalItems($sel_id, $status=""){
	global $xoopsDB, $mytree;
	$count = 0;
	$arr = array();
	$query = "SELECT count(*) from ".$xoopsDB->prefix("flashgames_games")." WHERE cid=".$sel_id."";
	if($status!=""){
		$query .= " and status>=$status";
	}
	$result = $xoopsDB->query($query);
	list($thing) = $xoopsDB->fetchRow($result);
	$count = $thing;
	$arr = $mytree->getAllChildId($sel_id);
	$size = sizeof($arr);
	for($i=0;$i<$size;$i++){
		$query2 = "SELECT count(*) from ".$xoopsDB->prefix("flashgames_games")." WHERE cid=".$arr[$i]."";
		if($status!=""){
			$query2 .= " and status>=$status";
		}
		$result2 = $xoopsDB->query($query2);
		list($thing) = $xoopsDB->fetchRow($result2);
		$count += $thing;
	}
	return $count;
}

function ShowSubmitter($submitter) {

	$poster = new XoopsUser($submitter);
	if ( $poster ) {
		if ( $allow_sig == 1 && $this->attachsig() != "" && $poster->attachsig() == 1 ) {
			$myts =& Mytextsanitizer::getInstance();
			$text .= "<p><br />_________________<br />". $myts->makeTareaData4Show($poster->getVar("user_sig", "N"),0,1,1)."</p>";
		}

//		$reg_date = _JOINED;
//		$reg_date .= formatTimestamp($poster->user_regdate(),"s");
//		$posts = _POSTS;
//		$posts .= $poster->posts();
//		$user_from = _FROM;
//		$user_from = $poster->user_from();

		$rank = $poster->rank();
		if ( $rank['image'] != "" ) {
			$rank['image'] = "<img src='".XOOPS_URL."/images/ranks/".$rank['image']."' alt='' />";
		}
		$avatar_image = "<img width=\"50\" src='".XOOPS_URL."/images/avatar/".$poster->user_avatar()."' alt='' />";
		if ( $poster->isOnline() ) {
			$online_image = "<span style='color:#ee0000;font-weight:bold;'>"._ONLINE."</span>";
		} else {
			$online_image = "";
		}
		$profile_image = "<a href='".XOOPS_URL."/userinfo.php?uid=".$poster->uid()."'><img src='".XOOPS_URL."/images/icons/profile.gif' alt='"._PROFILE."' /></a>";
		if ( $xoopsUser ) {
			$pm_image =  "<a href=\"javascript:openWithSelfMain('".XOOPS_URL."/pmlite.php?send2=1&amp;to_userid=".$poster->uid()."','pmlite',360,300);\"><img src='".XOOPS_URL."/images/icons/pm.gif' alt='".sprintf(_SENDPMTO,$poster->uname())."' /></a>";
		} else {
			$pm_image = "";
		}
			if ( $poster->user_viewemail() ) {
			$email_image = "<a href='mailto:".$poster->email()."'><img src='".XOOPS_URL."/images/icons/email.gif' alt='".sprintf(_SENDEMAILTO,$poster->uname())."' /></a>";
		} else {
			$email_image = "";
		}
		$posterurl = $poster->url();
			if ( $poster->url() != "" ) {
			$www_image = "<a href='$posterurl' target='_blank'><img src='".XOOPS_URL."/images/icons/www.gif' alt='"._VISITWEBSITE."' target='_blank' /></a>";
		} else {
			$www_image = "";
		}
			if ( $poster->user_icq() != "" ) {
			$icq_image = "<a href='http://wwp.icq.com/scripts/search.dll?to=".$poster->user_icq()."'><img src='".XOOPS_URL."/images/icons/icq_add.gif' alt='"._ADDTOLIST."' /></a>";
		} else {
			$icq_image = "";
		}
		if ( $poster->user_aim() != "" ) {
			$aim_image = "<a href='aim:goim?screenname=".$poster->user_aim()."&message=Hi+".$poster->user_aim()."+Are+you+there?'><img src='".XOOPS_URL."/images/icons/aim.gif' alt='aim' /></a>";
		} else {
			$aim_image;
		}
		if ( $poster->user_yim() != "" ) {
			$yim_image = "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$poster->user_yim()."&.src=pg'><img src='".XOOPS_URL."/images/icons/yim.gif' alt='yim' /></a>";
		} else {
			$yim_image = "";
		}
		if ( $poster->user_msnm() != '' ) {
			$msnm_image = "<a href='".XOOPS_URL."/userinfo.php?uid=".$poster->uid()."'><img src='".XOOPS_URL."/images/icons/msnm.gif' alt='msnm' /></a>";
		} else {
			$msnm_image = "";
		}
		echo "<b>"._ALBM_SUBMITTER."</b>&nbsp;";
//		echo $avatar_image."&nbsp;";
		echo "<a href='".XOOPS_URL."/userinfo.php?uid=".$poster->uid()."'>".$poster->uname()."</a>";
        echo " - <a href='".XOOPS_URL."/modules/flashgames/viewcat.php?uid=".$poster->uid()."'>";
        printf(_ALBM_MOREGAMES."</a>",$poster->uname());
//		echo $online_image."&nbsp;";
//		echo $rank['image']."&nbsp;";
//		echo "<BR>";
//		echo $profile_image;
//		echo $pm_image;
//		echo $email_image;
//		echo $www_image;
//		echo $icq_image;
//		echo $aim_image;
//		echo $yim_image;
//		echo $msnm_image;
//		echo "<BR>";
	}
}


function get_DateTime ($string) { 
   $yyyy = substr($string, 0,4); 
   $month = substr($string, 5,2); 
   $dd = substr($string, 8,2); 
   $hh = substr($string, 11,2); 
   $mm = substr($string, 14,2); 
   $ss = substr($string, 17,2); 
   $ftime = "$month/$dd/$yyyy $hh:$mm"; 
   return $ftime; 
} 


function GetFooter(){
	return "<br /><div align='center'><a href='http://www.tipsmitgrips.de'><b>Flashgames 1.0.1</b></a>" .
		   "<div align='center'><a href='http://www.pnflashgames.com/linkback.php?type=xoops' target='_blank'>Powered by www.pnFlashGames.com</a></div>" . 
		   "<div align='center'><a href='http://www.pnflashgames.com/linkback.php?type=xoops' target='_blank'><img src='".XOOPS_URL."/modules/flashgames/images/poweredByButton.gif' border='0'></a></div>";

}


function pnFlashGames_getDomain(){
    global $_SERVER;
    if(!empty($_SERVER['HTTP_HOST'])){
        $server = $_SERVER['HTTP_HOST'];
    }else{
        $server = $_SERVER['HTTP_HOST'];
    }
    $url = "http://".$server."/";
    
    // get host name from URL
    preg_match("/^(http:\/\/)?([^\/]+)/i",
    
    $url, $matches);
    $host = $matches[2];
    
    $host = str_replace("www.", "", $host);
    return $host;
}


function pnFlashGames_getChecksum($file){
	if($fp = fopen($file, 'r')){
		$filecontent = fread($fp, filesize($file));
		fclose($fp);
		return md5($filecontent);
	}else{
		return false;
	}
}


function pnFlashGames_storeScore($xoopsDB, $gid, $uname, $score, $ip){


  //$xoopsDB =& Database::getInstance();
	// Get the game's information
	$checksql = "SELECT * FROM ".$xoopsDB->prefix("flashgames_games")." WHERE lid = $gid";
	$result=$xoopsDB->query($checksql);
	$gameInfo = $xoopsDB->fetchArray($result);
	
    if($gameInfo['gameType'] == '3' || $gameInfo['gameType'] == '4'){
        //This game uses a time based scoring method
        if(strstr($score, ":") !== false){
            // a formated time string was passed... convert it to seconds

            $timestamp = strtotime($score);
            $formatedTime = strftime("%H:%M:%S", $timestamp);
            $hours = substr($formatedTime, 0, 2);
            $minutes = substr($formatedTime, 3, 2);
            $seconds = substr($formatedTime, 6, 2);
            $numSeconds = (($hours * 60) * 60) + ($minutes * 60) + $seconds;

            $score = $numSeconds;
        }else{
            // a straight up integer value was passed, store it straight up as a number of seconds.
        }
    }

    $scorestable = $xoopsDB->prefix("flashgames_score");
	$numscores = pnFlashGames_countrows($xoopsDB, $scorestable);
	
	// Configuration constants from cache/config.php
	$table_max = $flashgames_scoresave;	// in cache/config.php
	
    // First check to see if this user has stored a high score for this game yet.
    // Each user is allowed to store one score per game, so we check first to
    // make sure this is not below a previous score
    $checksql = "SELECT score FROM $scorestable
            	WHERE  name='$uname'
            	AND    lid=$gid";
    $check = $xoopsDB->query($checksql);
	
    if($xoopsDB->getRowsNum($check) < 1){
        //No rows found, this user has not stored a high score for this game yet
        $sql = "INSERT INTO $scorestable
                SET lid=$gid,
                    name='$uname',
                    score=$score,
					ip='$ip',
                    date=NOW()";
					
		// Check table size and prune if necessary
		if($numscores >= $table_max){
			switch($gameInfo['gametype']){
				case '1':
				case '3':
					$orderby = "DESC";
					break;
				case '2':
				case '4':
					$orderby = "ASC";
					break;			
			}
//			$query = $xoopsDB->query("SELECT name, score FROM $scorestable WHERE lid = '$gameid' ORDER BY score $orderby LIMIT 0, 1");
//			$row = mysql_fetch_row($query);
//			if ($good_score) mysql_query("DELETE FROM $scorestable WHERE name='{$row[0]}'");
		}
    }else{
		$oldscore = $xoopsDB->fetchArray($check);
		$oldscore = $oldscore['score'];
        switch($gameInfo['gametype']){
            case '1':
			case '3':
                if($oldscore < $score){
                    //Row found but the new score is higher, so update the old one
                    $sql = "UPDATE $scorestable
                            SET    score=$score,
								   ip='$ip',
                                   date=NOW()
                            WHERE  name='$uname'
                            AND    lid=$gid";
                }else{
                    //Row found but the new score is lower, so do nothing
                    $sql = "";
                }
                break;
            case '2':   //both 2 and 3 are lowest score wins type games, so store if the oldscore was higher
            case '4':   //at this point, even though 3 is time based, score has been converted to seconds, so this will still work fine
                if($oldscore > $score){
                    //Row found but the new score is higher, so update the old one
                    $sql = "UPDATE $scorestable
                            SET    score=$score,
								   ip='$ip',
                                   date=NOW()
                            WHERE  name='$uname'
                            AND    lid=$gid";
                }else{
                    //Row found but the new score is lower, so do nothing
                    $sql = "";
                }
        }
    }
    if($sql != ""){
        //Need to do something
		$xoopsDB->queryF($sql);
    }
	
    // Return true
    return true;
}


function pnFlashGames_countrows($xoopsDB, $table){

  //$xoopsDB =& Database::getInstance();
	$result = $xoopsDB->query("SELECT COUNT(1) as rowcount FROM $table");
	$count = $xoopsDB->fetchArray($result);
	return $count['rowcount'];
}


function pnFlashGames_saveGame($xoopsDB, $gid, $uname, $gameData){

  //$xoopsDB =& Database::getInstance();
	$savedgames = $xoopsDB->prefix("flashgames_savedGames");
    // First check to see if this user has stored game data for this game yet.
    // Each user is allowed to store one game data, so we check first to
    // make sure this is not below a previous game data
    $checksql = "SELECT COUNT(1) as rowcount FROM $savedgames
            	 WHERE  name='$uname'
            	 AND    lid=$gid";
    $check = $xoopsDB->query($checksql);
	$count = $xoopsDB->fetchArray($check);
	$count = $count["rowcount"];

    if($count < 1){
        //No rows found, this user has not stored a high score for this game yet
        $sql = "INSERT INTO $savedgames
                SET lid=$gid,
                    name='$uname',
                    gamedata='$gameData',
                    date=NOW()";
    }else{
        //old gameData found so replace it with the new one.
        $sql = "UPDATE $savedgames
                SET    gamedata='$gameData',
                       date=NOW()
                WHERE  name='$uname'
                AND    lid=$gid";
    }
    
    if($sql != ""){
        //Need to do something
        //print "$sql<br>";
		$xoopsDB->queryF($sql);
		//print mysql_error();
    }

    // Check for an error with the database code, and if so set an appropriate
    // error message and return
    if ($xoopsDB->errno() != 0) {
        return false;
    }

    // Return true
    return true;
}


function pnFlashGames_loadGame($xoopsDB, $gid, $uname){
  //$xoopsDB =& Database::getInstance();
	$savedgames = $xoopsDB->prefix("flashgames_savedGames");
    
    $sql = "SELECT gamedata
            FROM $savedgames
            WHERE lid=$gid
            AND name='$uname'";

    $result = $xoopsDB->query($sql);
    
    if ($xoopsDB->errno() != 0) {
        return false;
    }

    if ($xoopsDB->getRowsNum($result) < 1){
        //No data for this game and user yet...
        return "";
    }
    
    $gameData = $xoopsDB->fetchArray($result);
    
    //Flash will unencode the data automatically, this way the data is sent back exactly as it came...
    return urlencode($gameData[0]);
}


function pnFlashGames_loadGameScores($xoopsDB, $gid){


  //$xoopsDB =& Database::getInstance();
	// Get the game's information
	$checksql = "SELECT * FROM ".$xoopsDB->prefix("flashgames_games")." WHERE lid = $gid";
	$result=$xoopsDB->query($checksql);
	$gameInfo = $xoopsDB->fetchArray($result);
	
	if($gameInfo['gametype'] == 1 || $gameInfo['gameType'] == 3){
		// sort asc
		$orderby = "ASC";
	}else{
		$orderby = "DESC";
	}
	
	$gamescores = $xoopsDB->prefix("flashgames_score");
    
    $sql = "SELECT score, name, date
            FROM $gamescores
            WHERE lid=$gid
			ORDER BY score $orderby";

    $result = $xoopsDB->query($sql);
	
	$output = "<scorelist>";
	
	if($xoopsDB->getRowsNum($result) > 0){
		//Found some highscores
		$dateInfo = getdate();
		$rank = 1;
		while($highscore = $xoopsDB->fetchArray($result)){
			$output .= "<score rank='".$rank++."' score='{$highscore[score]}' player='{$highscore[name]}' date='".get_DateTime($highscore['date'])."' />\n";
		}
	}
	$output .= "</scorelist>";
	$output = urlencode($output);
	
	return $output;
}

function pnFlashGames_getNewGamesList($local = false){
	$installDir = XOOPS_ROOT_PATH."/modules/flashgames/games/";
	
	if($local && file_exists($installDir . "gamelist.xml")){
		$sync_location = $installDir . "gamelist.xml";
	}else{
		// We need to get a new list
		$domain = pnFlashGames_getDomain();
		$subscription = "76cac4898671959f228837d155b5383f";	//LANG --- REMOVE THIS
		$sync_location = "http://pnflashgames.com/gamelist.php";
		$vars = "domain=$domain&key=$subscription&type=xoops";
		$varsarr = array("domain" => $domain, "key" => $subscription);
	}
	
	// Include the XML Parser
	require_once("xmlparser.php");

	$method = "fopen";
	$debug = false;

	if($debug)
		print "Syncing with $sync_location...<br>Sending $vars<br>";
	
	if($local){
		// If local, use fopen to get the games list...
		$method = "fopen";
	}
	
	switch($method){
		case "curl":
			// CURL METHOD
			if($debug){
				print "Utilizing CURL method...<br>";
				print "Using cURL version: ".curl_version()."<br>";
			}
			$url = $sync_location; 
			$ch = curl_init($url);    // initialize curl handle 
			curl_setopt($ch, CURLOPT_POST, 1); // post information
			curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable 
			curl_setopt($ch, CURLOPT_VERBOSE, 1);	// verbose reporting
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	// follow Location: headers
			curl_setopt($ch, CURLOPT_HEADER, 0);	//strip the headers from the reply
			curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 10s 
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
			curl_setopt($ch, CURLOPT_ERRORBUFFER, $errorstr);
			$newfiles = curl_exec($ch); // run the whole process 
			
			if($debug){
				print "<pre>";
				print_r(curl_getinfo($ch));
				print "</pre><br>$errorstr";
			}
			
			if($newfiles === false){
				// Error executing CURL session
				if($debug)
					print "Error during CURL session:<BR>Error Number: ".curl_errno($ch)." -- ".curl_error($ch)."<br>$errorstr<br>";
			}
			curl_close($ch);
			break;

		case "fopen":
			// FOPEN METHOD
			if($debug)
				print "Utilizing FOPEN method...<br>";
			
			if($local){
				$fp = fopen($sync_location, "r");
			}else{
				$fp = fopen($sync_location."?".$vars, "r");
			}
			
			if($fp !== false){
				$newfiles = '';
				while (!feof($fp)) {
				  $newfiles .= fread($fp, 8192);
				}
				fclose($fp);
			}else{
				// Error opening location
				if($debug)
					print "Error opening $sync_location for reading...\n";
					
				$newfiles = false;
			}
			break;
		
		case "fsocket":
			// FSOCKET METHOD
			if($debug)
				print "Utilizing FSOCKET method...<br>";
				
			$newfiles = pnFlashGames_http_post("pnflashgames.com", 80, "/gamelist.php", $varsarr);
			break;
			
		case "filegetcontents":
			// FILE_GET_CONTENTS (only available on PHP >= 4.3)
			if($debug)
				print "Utilizing FSOCKET method...<br>";
				
			$newfiles = file_get_contents($sync_location);	//Only works with PHP >= 4.3
			break;
	}
	
	if($debug) {
		print "<pre>\nReturned from $sync_location:\n";
		print $newfiles;
		print "</pre>";
	}

	// Error checking...
	if(substr($newfiles, 0, 1) != "<"){
		// This is not XML data, it is an error or message report, return the raw data instead...
		return array("message" => "", "gamelist" => $newfiles);
	}

	if($newfiles !== false && $newfiles != ""){
		
		$xmlC = new XmlC();
		$xmlC->Set_XML_data($newfiles);
		
		//print "<pre>";
		//print_r($xmlC->obj_data);
		//print "</pre>";
		
		// Get the version info for this gamelist ?
		$pnfgversion = $xmlC->obj_data->gamelist[0]->pnfgversion;
		$listversion = $xmlC->obj_data->gamelist[0]->gamelistversion;
		$message = $xmlC->obj_data->gamelist[0]->message[0];
		
		// Put the data into a more simplified array...
		$gamelist = array();
		foreach($xmlC->obj_data->gamelist[0]->game as $game){
			$gamearr = array();
			$gamearr["id"] 			= urldecode($game->id[0]);
			$gamearr["name"] 		= urldecode($game->name[0]);
			$gamearr["description"] = urldecode($game->description[0]);
			$gamearr["width"] 		= urldecode($game->width[0]);
			$gamearr["height"] 		= urldecode($game->height[0]);
			$gamearr["bgcolor"] 	= urldecode($game->bgcolor[0]);
			$gamearr["gameType"] 	= urldecode($game->gameType[0]);
			$gamearr["author"] 		= urldecode($game->author[0]);
			$gamearr["checksum"] 	= urldecode($game->checksum[0]);
			$gamearr["license"] 	= urldecode($game->license[0]);
			$gamearr["timesPlayed"]	= urldecode($game->timesPlayed[0]);
			$gamearr["rating"] 		= urldecode($game->rating[0]);
			$gamearr["gamefile"] 	= urldecode($game->gamefile[0]);
			$gamearr["gamepic"] 	= urldecode($game->gamepic[0]);
			$gamearr["previewurl"] 	= urldecode($game->previewurl[0]);
			$gamearr["gamesize"] 	= urldecode($game->gamesize[0]);
			$gamearr["gamesize_real"] 	= urldecode($game->gamesize_real[0]);
			$gamearr["picsize_real"] 	= urldecode($game->picsize_real[0]);

			$gamelist[$gamearr["id"]] = $gamearr;
		}
		
		// Write the gamelist to a file for quicker parsing later?
		if(!$local){
			$fp = fopen($installDir . "gamelist.xml", "w");
			fwrite($fp, $newfiles);
			fclose($fp);
		}else{
			// If not local, destroy the gamelist
			unlink($installDir . "gamelist.xml");
		}
		
		return array("message" => $message, "gamelist" => $gamelist, "file" => $installDir."gamelist.xml");
	}else{
		return false;
	}
}

?>