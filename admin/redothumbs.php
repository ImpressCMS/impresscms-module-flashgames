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
include("../../../mainfile.php");
include(XOOPS_ROOT_PATH."/modules/myalbum/cache/config.php");
include(XOOPS_ROOT_PATH."/modules/myalbum/include/functions.php");

$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object
include_once(XOOPS_ROOT_PATH."/class/module.errorhandler.php");

global $xoopsDB;
xoops_cp_header();

if(isset($_POST['submit']) && $_POST['submit'] != "") {
    OpenTable();
	if(!$xoopsUser) {
		redirect_header(XOOPS_URL."/user.php",2,_ALBM_MUSTREGFIRST);
		exit();
	} 

	if(!isset($_POST['submitter']) || $_POST['submitter']=="") {
    	$submitter = $xoopsUser->uid();
    } else {
		$submitter = $_POST['submitter'];
	}
	// Check if size exist
    if ($_POST['size']=="") {
        redirect_header(XOOPS_URL."/modules/myalbum/admin/redothumbs.php",2,_ALBM_MUSTREGFIRST);
	}
    
    $result=$xoopsDB->query("SELECT lid,ext from ".$xoopsDB->prefix("myalbum_photos")." ORDER BY lid LIMIT $i,$size") or die("Error");
    while($myrow = $xoopsDB->fetchArray($result)) {
        print _ALBM_REDOING.$myrow['lid'].".".$myrow['ext']."<br>\n";
        createThumb(XOOPS_ROOT_PATH."/modules/myalbum/photos/".$myrow['lid'].".".$myrow['ext'], $myrow['lid'], $myrow['ext']);
    }    
    CloseTable();
}    

OpenTable();
include_once("../../../class/xoopsformloader.php");

$form = new XoopsThemeForm(_ALBM_REDOTHUMBS, "batchupload", "redothumbs.php");	
$label = new XoopsFormLabel("", _ALBM_REDOTHUMBSINFO);
$form->setExtra("enctype='multipart/form-data'");

$question_text = new XoopsFormText(_ALBM_REDOTHUMBSNUMBER, "size", 50, 255, 10);

$op_hidden = new XoopsFormHidden("op", "submit");
if ($_POST['i']=="") {
    $op_hidden = new XoopsFormHidden("i", "0");
} else {
    $op_hidden = new XoopsFormHidden("i", $_POST['i'] + $_POST['size']);
}

$submit_button = new XoopsFormButton("", "submit", _ALBM_NEXT, "submit");

$form->addElement($label);
$form->addElement($question_text);
$form->addElement($op_hidden);
$form->addElement($submit_button);
$form->display();
CloseTable();

xoops_cp_footer();

include("footer.php");
?>
