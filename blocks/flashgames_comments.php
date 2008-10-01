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

function b_flashgames_comments_show($options) {
	global $xoopsDB;
	
	$myts =& MyTextSanitizer::getInstance();
    $block = array();
    $block['title'] = _ALBM_NEWBB_RECENT;
    
	// BB    	
    $query="SELECT t.topic_id, t.topic_title, t.topic_time, t.topic_views,
    	t.topic_replies, t.forum_id, f.forum_name FROM 
    	".$xoopsDB->prefix("bb_topics")." t, 
    	".$xoopsDB->prefix("bb_forums")." f WHERE (f.forum_id=t.forum_id) 
    	AND (f.forum_type <> '1') ORDER BY t.topic_time DESC";
    if(!$result=$xoopsDB->query($query,$options[0],0)){
		echo "ERROR";
	}
	while ( $arr = $xoopsDB->fetchArray($result) ) {
		$all[] = $arr['topic_time'];
		$module = "<a href='".XOOPS_URL."/modules/newbb/viewforum.php?forum={$arr['forum_id']}'>{$arr['forum_name']}</a>"; 
		$topic = "<a href='".XOOPS_URL."/modules/newbb/viewtopic.php?topic_id={$arr["topic_id"]}&amp;forum={$arr["forum_id"]}'>{$arr['topic_title']}</a>";
		$all_mod[] = array($module, $topic, $arr['topic_replies'], $arr['topic_views']);
	}

	// flashgames
    $query="SELECT c.item_id, c.date, p.lid, p.cid, p.hits, p.title, p.comments FROM 
    	".$xoopsDB->prefix("flashgames_comments")." c, 
    	".$xoopsDB->prefix("flashgames_games")." p WHERE (c.item_id=p.lid) 
    	ORDER BY c.date DESC";
    if(!$result2=$xoopsDB->query($query,$options[0],0)){
		echo "ERROR";
	}
	while ( $arr = $xoopsDB->fetchArray($result2) ) {
		if(!in_array($arr['item_id'],$ids)) {
			$all[] = $arr['date'];
			$module = "<a href='".XOOPS_URL."/modules/flashgames/viewcat.php?cid={$arr['cid']}'>"._ALBM_FLASHGAMES_NAME."</a>"; 
			$topic = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid={$arr["lid"]}'>{$arr['title']}</a>";
			$all_mod[] = array($module, $topic, $arr['comments'], $arr['hits']);
		}	
		$ids[] = $arr['item_id'];
	}

	unset($ids);

	// Polls
    $query="SELECT c.item_id, c.date, c.user_id, p.poll_id, p.question, p.votes FROM 
    	".$xoopsDB->prefix("xoopspollcomments")." c, 
    	".$xoopsDB->prefix("xoopspoll_desc")." p WHERE (c.item_id=p.poll_id) 
    	ORDER BY c.date DESC";
    if(!$result2=$xoopsDB->query($query,$options[0],0)){
		echo "ERROR";
	}
	while ( $arr = $xoopsDB->fetchArray($result2) ) {
		if(!in_array($arr['item_id'],$ids)) {
			$all[] = $arr['date'];
			$module = "<a href='".XOOPS_URL."/modules/xoopspoll/'>"._ALBM_XOOPSPOLL."</a>"; 
			$topic = "<a href='".XOOPS_URL."/modules/xoopspoll/?poll_id={$arr["poll_id"]}'>{$arr['question']}</a>";
			$all_mod[] = array($module, $topic, '+1', $arr['votes']);
		}	
		$ids[] = $arr['item_id'];
	}

	unset($ids);

	// News
    $query="SELECT c.item_id, c.date, c.user_id, p.storyid, p.title, p.counter FROM 
    	".$xoopsDB->prefix("comments")." c, 
    	".$xoopsDB->prefix("stories")." p WHERE (c.item_id=p.storyid) 
    	ORDER BY c.date DESC";
    if(!$result2=$xoopsDB->query($query,$options[0],0)){
		echo "ERROR";
	}
	while ( $arr = $xoopsDB->fetchArray($result2) ) {
		if(!in_array($arr['item_id'],$ids)) {
			$all[] = $arr['date'];
			$module = "<a href='".XOOPS_URL."/modules/news/'>"._ALBM_NEWS."</a>"; 
			$topic = "<a href='".XOOPS_URL."/modules/news/article.php?storyid={$arr["storyid"]}'>{$arr['title']}</a>";
			$all_mod[] = array($module, $topic, '+1', $arr['counter']);
		}	
		$ids[] = $arr['item_id'];
	}

	arsort($all);

//    if ($xoopsDB->getRowsNum($result) > 0) {
		$block['content'] = "<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%' class='bg2'><tr><td>\n";
		$block['content'] .= "<table width='100%' border='0' cellpadding='4' cellspacing='1'>\n";
		$block['content'] .= "<tr class='bg3'>";
		if ( $options[1] != 0 ) {
    			$block['content'] .= "<td><b>" ._ALBM_NEWBB_FORUM."</b></td>";
		}
		$block['content'] .= "<td><b>" ._ALBM_NEWBB_TOPIC."</b></td><td align='center'><b>" ._ALBM_NEWBB_RPLS."</b></td><td align='center'><b>" ._ALBM_NEWBB_VIEWS."</b></td>";
		if ( $options[1] != 0 ) {
			$block['content'] .= "<td align='right'><b>" ._ALBM_NEWBB_LPOST."</b></td>";
		}
		$block['content'] .= "</tr>";
    	//while ( $arr = $xoopsDB->fetchArray($result) ) {
    	$count = 0;
		while (list ($i, $time) = each ($all) and $count < $options[0]) {
			$block['content'] .= "<tr class='bg1'>";
			if ( $options[1] != 0 ) {
				$block['content'] .= "<td>{$all_mod[$i][0]}</td>";
			}
			$block['content'] .= "<td>{$all_mod[$i][1]}</td>";
			$block['content'] .= "<td align='center'><span class='fg2'>{$all_mod[$i][2]}</span></td>";
			$block['content'] .= "<td align='center'>{$all_mod[$i][3]}</td>";
			if ( $options[1] != 0 ) {
				$block['content'] .= "<td align='right'>".formatTimestamp($time,"m"). "</td>";
			}
			$count++;
		}
		$block['content'] .= "</tr></table></td></tr><tr><td align='right'>";
		$block['content'] .= "</td></tr></table>";
/*	} else {
		$block = false;
	}
*/	
    	return $block;
}

function b_flashgames_comments_edit($options) {
	$inputtag = "<input type='text' name='options[]' value='".$options[0]."' />";
	$form = sprintf(_ALBM_NEWBB_DISPLAY,$inputtag);
	$form .= "<br />"._ALBM_NEWBB_DISPLAYF."&nbsp;<input type='radio' id='options[]' name='options[]' value='1'";
	if ( $options[1] == 1 ) {
		$form .= " checked='checked'";
	}
	$form .= " />&nbsp;"._YES."<input type='radio' id='options[]' name='options[]' value='0'";
	if ( $options[1] == 0 ) {
		$form .= " checked='checked'";
	}
	$form .= " />&nbsp;"._NO."";
	return $form;
}

?>
