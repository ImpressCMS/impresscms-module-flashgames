<?php
include_once XOOPS_ROOT_PATH.'/modules/flashgames/language/'.$xoopsConfig['language'].'/main.php';



function b_flashgames_random_show( $options )
{
	global $xoopsDB;


$myts =& MyTextSanitizer::getInstance() ;
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";

$mytree = new XoopsTree($xoopsDB->prefix("flashgames_games"),"lid");	
	
	$block = array() ;
	
     
	// Get number of game
	$result = $xoopsDB->query( "SELECT count(lid) FROM  ".$xoopsDB->prefix("flashgames_games")." WHERE status>0" ) ;
	list( $numrows ) = $xoopsDB->fetchRow( $result ) ;

	if( $numrows < 1 ) return $block ;


	if( $numrows <= 1 ) {

		$result = $xoopsDB->query( "SELECT lid , cid , title , status FROM ".$xoopsDB->prefix("flashgames_games")." WHERE status>0" ) ;


	} else {
		$result = $xoopsDB->query( "SELECT lid FROM ".$xoopsDB->prefix("flashgames_games")." WHERE status>0" ) ;
		$lids = array() ;
		$sel_lids = array() ;
		while( list( $lid ) = $xoopsDB->fetchRow( $result ) ) $lids[] = $lid ;
		srand( (float) microtime() * 10000000 ) ;
		$sel_lids = array_rand( $lids , 1 ) ;

		if( is_array( $sel_lids ) ) {
			$whr_lid = '' ;
			foreach( $sel_lids as $key ) $whr_lid .= $lids[ $key ] . "," ;
			$whr_lid = substr( $whr_lid , 0 , -1 ) ;
		} else {
			$whr_lid = $lids[ $sel_lids ] ;
		}
		
      			
	        

		$result = $xoopsDB->query( "SELECT lid , cid , title FROM ".$xoopsDB->prefix("flashgames_games")." WHERE status>0 AND lid IN ($whr_lid)" ) ;
	}
	


	while( $rgame = $xoopsDB->fetchArray( $result ) ) {
if (strlen($rgame['title']) >= 21) {
$rgame['title'] = $myts->makeTboxData4Show(substr($rgame['title'],0,(21 -1)))."...";
}

                $whr_lid = $rgame['lid']; 
		$block['title'] = $myts->makeTboxData4Show( $rgame['title'] ) ;
		$block['lid'] = $whr_lid;
		
	}




// score table
$result=$xoopsDB->query("select lid, title, gametype from ".$xoopsDB->prefix("flashgames_games")." where status=1  AND lid IN ($whr_lid)");
$rankings = array();
while(list($lid, $title, $gametype)=$xoopsDB->fetchRow($result)){

$pfad = XOOPS_ROOT_PATH.'/modules/flashgames/games/';
// Check if screenshot is in gif format
$filename = "$pfad$lid.gif";
		
	

if (file_exists($filename)) 
{
		$block['ext'] = 'gif' ;
}



// Check if screenshot is in jpg format
$filename = "$pfad$lid.jpg";
if (file_exists($filename)) 
{
		$block['ext'] = 'jpg' ;

}

$block['lang_playanother'] = _ALBM_PLAYANOTHER ;
$block['showranks']        =  $options[1] ;
$block['width']          =  $options[0] ;


$gtype = $gametype;

$query = "select lid, name, score, date from ".$xoopsDB->prefix("flashgames_score")." where  (lid=$whr_lid";

// lowest score on top
if ($gametype == 4 or $gametype == 2)
{
	$query .= ") order by score ASC";
  }
else
{
  $query .= ") order by score DESC";
     
}


$lastscore = -999999999999999;
$result2 = $xoopsDB->query($query,$options[1],0);
$rank = 0;
while(list($lid,$name,$score,$date)=$xoopsDB->fetchRow($result2)){


if ( $gametype == 3  or  $gametype == 4 ) {
 //This is a time based score, so format it accordingly.  All time based scores are stored in seconds
 $timestamp = mktime(0, 0, $score);
 $score = strftime("%H:%M:%S", $timestamp);
}

           if ($lastscore != $score) 
           {
        	$rank++;
        
		}
                         

            $lastscore = $score;



		
		$rgame['rank'] =  $rank;
		$rgame['name'] =  $name;
		$rgame['score'] = $score; 
		
		$block['rgame'][] = $rgame ;
		
	}
}






// no highscores available
if ( $gtype  <> 0  and $rank == '0' )
{
$rgame['noscores'] =  1;


$rgame['lang_noscores'] =  _ALBM_NOSCORES;
$rgame['lang_setscores'] =  _ALBM_SETSCORES;


$block['rgame'][] = $rgame ;
}


	return $block ;
}


function b_flashgames_random_edit( $options )
{
	$form = _ALBM_TEXT_BLOCK_WIDTH."&nbsp;
		<input type='text' size='6' name='options[]' value='".$options[0]."' />&nbsp;pixel <br />
		"._ALBM_TEXT_DISP . "&nbsp; <input type='text' size='3' name='options[1]' value='{$options[1]}' />
		\n" ;

	return $form ;
}
?>