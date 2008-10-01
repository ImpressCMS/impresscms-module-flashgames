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

/******************************************************************************
 * Function: b_flashgames_top_show
 * Input   : $options[0] = date for the most recent links
 *                    hits for the most popular links
 *           $block['content'] = The optional above content
 *           $options[1]   = How many reviews are displayes
 * Output  : Returns the desired most recent or most popular links
 ******************************************************************************/
function b_flashgames_game_show($options) {
	global $xoopsDB;
	$block = array();
	$block['content'] = "<center><br />";
	$myts =& MyTextSanitizer::getInstance();
	$result = $xoopsDB->query("SELECT lid,ext FROM ".$xoopsDB->prefix("flashgames_games")." WHERE status > 0 ORDER by RAND() limit 1");
	$myrow = $xoopsDB->fetchArray($result);
	$block['title'] = _ALBM_FLASHGAMES_TITLE3;	
	$img = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid=".$myrow['lid']."'>
	<img width=140 src='".XOOPS_URL."/modules/flashgames/games/thumbs/".$myrow['lid'].".".$myrow['ext']."'>";
// fixme    
//	$block['content'] .= "<table width=1% border=0 cellspacing=0 cellpadding=0>
//	<tr bgcolor=black><td colspan=3 height=1><img src='".XOOPS_URL."/modules/myalbum/images/pixel_trans.gif' width=1 height=1></td></tr><tr><td bgcolor=black width=1><img src='".XOOPS_URL."/modules/myalbum/images/pixel_trans.gif' width=1 height=1></td><td><center>$img</td><td bgcolor=black width=1><img src='".XOOPS_URL."/modules/myalbum/images/pixel_trans.gif' width=1 height=1></td></tr><tr bgcolor=black><td colspan=3 height=1><img src='".XOOPS_URL."/modules/myalbum/images/pixel_trans.gif'></td></tr>
//	</table>";
   	$block['content'] .= "$img";
   	$block['content'] .= "</center><br />";
	return $block;
}

function b_flashgames_game_edit($options) {
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
?>
