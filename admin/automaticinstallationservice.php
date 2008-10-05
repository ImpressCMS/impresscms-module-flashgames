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


//global $xoopsDB;

$xoopsDB =& Database::getInstance();


xoops_cp_header();
OpenTable();

echo "Welcome to the AI Service Start Page<br/>\n";	//LANG

echo "<div align='left'>"._FG_AUTOINSTALLINSTRUCTIONS."</div>";

$installDir = XOOPS_ROOT_PATH."/modules/flashgames/games/";

if(!is_writable($installDir)){
	echo "<br><br>";
	echo "<div align='center' style='color: #FF0000;'>"._PNFG_NOTWRITABLE1." ".$installDir." "._PNFG_NOTWRITABLE2."</div>";
}

echo "<br><br>";

$gameinfo = pnFlashGames_getNewGamesList();
$gamelist = $gameinfo["gamelist"];
$message = $gameinfo["message"];

$catlist = array();
$catlist_rs = $xoopsDB->query("SELECT cid, title FROM ".$xoopsDB->prefix("flashgames_cat")." ORDER BY title");
while($cat_row = $xoopsDB->fetchArray($catlist_rs)){
	$catlist[] = array("cid" => $cat_row["cid"],
					   "title" => $cat_row["title"]);	
}

// Print a message if any was given
if(!empty($message)){
	//$output->Linebreak();
	echo "<div align='left'>$message</div>";
	echo "<br><br>";
}

if($gamelist !== false && is_array($gamelist)){
	// Successfully got new game list - Parse into an XML object
	
	// Add the JS to select all
	?>
<SCRIPT language=JavaScript>
<!--
function selectAll(formObj, value){
	for (var i=0; i < formObj.elements.length; i++)
		formObj.elements[i].checked = value;
		
}


function loadPicWin(url){
	pnfg_gamepicwin = window.open(url,"pnfg_gamepic","toolbar=no,location=no,directories=no,status=no,scrollbars=no,resizable=no,copyhistory=no,width=100,height=100");
}


function showPic(picurl, divid){
	document.getElementById(divid).innerHTML = "<img src='" + picurl + "' width='100' height='100' border='0' onClick=\"hidePic('" + picurl + "', '" + divid + "');\">";
}


function hidePic(picurl, divid){
	document.getElementById(divid).innerHTML = "<div id='" + divid + "'><a href=\"javascript:showPic('" + picurl + "', '" + divid + "');\">Show Pic</a></div>";
}


function hidediv(divid, gamename){
	document.getElementById("container-" + divid).style.visibility = 'hidden';
	document.getElementById("container-" + divid).style.display = 'none';
	document.getElementById("header-" + divid).innerHTML = "<i><a href=\"javascript:showdiv('" + divid + "', '" + gamename + "');\">Show " + gamename + "</i></a>";
	setCookie("container-" + divid + "-vis", "hidden");
}


function showdiv(divid, gamename){
	document.getElementById("container-" + divid).style.visibility = 'visible';
	document.getElementById("container-" + divid).style.display = 'block';
	document.getElementById("header-" + divid).innerHTML = "<i><a href=\"javascript:hidediv('" + divid + "', '" + gamename + "');\">Hide " + gamename + "</i></a>";
	setCookie("container-" + divid + "-vis", "visible");
}


function setCookie(name, value, expires, path, domain, secure)
{
    document.cookie= name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires.toGMTString() : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}


function getCookie(name)
{
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1)
    {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }
    else
    {
        begin += 2;
    }
    var end = document.cookie.indexOf(";", begin);
    if (end == -1)
    {
        end = dc.length;
    }
    return unescape(dc.substring(begin + prefix.length, end));
}


function deleteCookie(name, path, domain)
{
    if (getCookie(name))
    {
        document.cookie = name + "=" + 
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            "; expires=Thu, 01-Jan-70 00:00:01 GMT";
    }
}
-->
</SCRIPT>
<?PHP

// Compile a list of checksums of games installed on this server
$sql = "SELECT * FROM ".$xoopsDB->prefix("flashgames_games");
$rsGames = $xoopsDB->query($sql);
$mygames=array();
while($currgame = $xoopsDB->fetchArray($rsGames)){
	$mygames[] = array("gamefile" => "../games/".$currgame['lid'].".".$currgame['ext']);
}
$mychecksums = array();
if(count($mygames) > 0){
	foreach($mygames as $currgame){
		$contents = pnFlashGames_getChecksum($currgame['gamefile']);
		$mychecksums[] = ($contents);
	}
}

echo "<form name=\"autoInstaller\" action=\"autoInstallFinish.php\"	method=\"POST\">";

$buttons = '
	<INPUT 
	  TYPE=button 
	  VALUE="'._FG_SELECTALL.'" 
	  ONCLICK="selectAll(this.form,true)">
	<INPUT 
	  TYPE=button 
	  VALUE="'._FG_CLEARALL.'" 
	  ONCLICK="selectAll(this.form,false)"> ';

echo "<div align='left'>$buttons</div><br>";

// Iterate through the list of games from pnFlashGames.com and display the ones not installed here
foreach($gamelist as $remotegame){
	if(!in_array($remotegame['checksum'], $mychecksums)){
		// This game is not installed here -- add an addition form for it
		
		if(!empty($remotegame["license"])){
			// This is a commercial game
			echo "<div><div align='left' width='75%' style='background-color: #ECF1DC;'>";
		}else{
			//This is a free game
			// Put the surrounding div for show/hide ability
			if($_COOKIE["container-{$remotegame[id]}-vis"] == "hidden") {
				// Hide this div by default
				echo "<div align='center' id='header-{$remotegame[id]}'><i><a href='javascript:showdiv(\"{$remotegame[id]}\", \"{$remotegame[name]}\");'>Show {$remotegame[name]}</a></i></div>";
				echo "<div id='container-{$remotegame[id]}' style='visibility: hidden; display: none;'>";
			}else{
				echo "<div align='center' id='header-{$remotegame[id]}'><i><a href='javascript:hidediv(\"{$remotegame[id]}\", \"{$remotegame[name]}\");'>Hide {$remotegame[name]}</a></i></div>";
				echo "<div id='container-{$remotegame[id]}'>";
			}
			echo "<div align='left'>";
		}
		echo "<strong>".$remotegame["name"]."</strong>";
		if(!empty($remotegame["license"])){
			// This is a commercial game
			echo "&nbsp;&nbsp;&nbsp;<i>( Commercial Game )</i>";
			// Show image?
			echo "<Br><br>";
			echo "<img src='{$remotegame['gamepic']}' width='100' height='100'>";
		}else{
			// Show a link to see this games thumbnail
			echo "<br><Br>";
			//$output->Text("<a href='javascript:loadPicWin(\"{$remotegame[gamepic]}\");'>View Thumbnail</a>");
			echo "<div id='{$remotegame[id]}-pic'><a href='javascript:showPic(\"{$remotegame[gamepic]}\", \"{$remotegame[id]}-pic\");'>Show Pic</a></div>";
		}				

		echo "<br>";
		//$output->FormCheckbox($remotegame['id']."_installflag", false);
		echo "<input type=\"checkbox\" name='".$remotegame['id']."_installflag' id='".$remotegame['id']."_installflag' />";
		echo "&nbsp;&nbsp;<label for='".$remotegame['id']."_installflag'>"._FG_INSTALL."</label>";
		echo "<br><br>";
		echo _FG_AUTHOR.": {$remotegame[author]}<br>";
		echo $remotegame["description"];
		echo "<br>";
//		echo _FG_RATING.": ".number_format($remotegame['rating']." / 5", 2)." / 5<br>";
		echo _FG_FILESIZE.": ".$remotegame['gamesize']."<br>";
		echo _FG_NUMTIMESPLAYED.": ".number_format($remotegame[timesPlayed])."<br>";
		echo "<a href=\"{$remotegame[previewurl]}\" target=\"_blank\">"._FG_PREVIEWGAME."</a><br>";
		if(!empty($remotegame["license"])){
			// This is a commercial game
			echo _FG_LICENSEKEY.": ";
			//$output->FormText($remotegame['id']."_license", "", 50, 255);
			echo "<input type=\"textbox\" size=\"50\" maxsize=\"255\" name=\"".$remotegame['id']."_license"."\">";
			echo "<br>";
		}				
		echo _FG_CATEGORY.": ";
		//$output->FormSelectMultiple($remotegame['id']."_initcat", $catoptions); 
		//echo $mytree->makeMySelBox("title","title");
		echo "<select name='".$remotegame['id']."_initcat"."'>";
		foreach($catlist as $cat){
			echo "<option value='".$cat["cid"]."'>".$cat["title"]."<option>";	
		}
		echo "</select>";
		echo "</div>";
		
		// Close the master container
		echo "</div>";
		
		echo "<hr width='100%' size='1' align='left' noshade>";
	}else{
		// This game is already installed here
	}
}
	echo "<div align='left'>$buttons</div>";
	echo "<br>";
	echo "<input type=\"submit\" value=\"Install Games\">";
	
	echo "</form>";
}else{
	// Error getting new games
	echo "<div align='left' style='color: #FF0000;'>$gamelist</div>";
}


CloseTable();


echo "<br><br>";
print GetFooter();
xoops_cp_footer();
?>