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
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once(XOOPS_ROOT_PATH."/class/module.errorhandler.php");
include(XOOPS_ROOT_PATH."/modules/myalbum/class/upload.class.php");

$eh = new ErrorHandler; //ErrorHandler object
$mytree = new XoopsTree($xoopsDB->prefix("myalbum_cat"),"cid","pid");

if(isset($_POST['submit']) && $_POST['submit'] != "") {
	if(!$xoopsUser) {
		redirect_header(XOOPS_URL."/user.php",2,_ALBM_MUSTREGFIRST);
		exit();
	} 
	if(!isset($_POST['submitter']) || $_POST['submitter']=="") {
    	$submitter = $xoopsUser->uid();
    } else {
		$submitter = $_POST['submitter'];
	}
	// Check if Description exist
    if ($_POST['description']=="") {
    	$eh->show("1008");
	}
	if ( !empty($_POST['cid']) ) {
    		$cid = $_POST['cid'];
	} else {
		$cid = 0;
	}
	
	$newid = $xoopsDB->genId($xoopsDB->prefix("myalbum_photos")."_lid_seq");

	$url = $myts->makeTboxData4Save($url);
	$title = $myts->makeTboxData4Save($_POST["title"]);
   	$description = $myts->makeTareaData4Save($_POST["description"]);
   	$dir = $myts->makeTboxData4Save($_POST["dir"]);

   	chdir($dir);
   	$dir_name = "$dir";
   	$dir_h = opendir(".");
	xoops_cp_header();
	$filecount = 1;
   	while ($file_name = readdir($dir_h)) {
   		if (($file_name != "." && $file_name != "..")) {
   			$ext = preg_replace("/^.+\.([^.]+)$/sU", "\\1", $file_name);
   			$name = str_replace(".$ext", "", $file_name);
   			if ($_POST["title"]=="") {
   				$nameDB = $name;
   			} else {
   				$nameDB = $_POST["title"]." ".$filecount;
   				$filecount++;
   			}	
   			if(is_file($file_name) and in_array(strtolower($ext),array("jpg","png","gif"))) {
				OpenTable();						
				$date = time();
				$newid = '\'\'';
				$dim = GetImageSize("$dir/$name.$ext");
				$q = "INSERT INTO ".$xoopsDB->prefix("myalbum_photos")." (lid, cid, title, ext, res_x, res_y, submitter, status, date, hits, rating, votes, comments) VALUES ($newid, $cid, '$nameDB', '$ext', '$dim[0]', '$dim[1]', $submitter, 1, $date, 0, 0, 0, 0)";
   			$xoopsDB->queryF($q) or $eh->show("0013");
				$newid = $xoopsDB->getInsertId();
	   			print "&nbsp;&nbsp;<a href='photo.php?lid=$newid'>$name</a>\n";
				copy("$dir/$name.$ext",
					XOOPS_ROOT_PATH."/modules/myalbum/photos/$newid.$ext");
				createThumb(XOOPS_ROOT_PATH."/modules/myalbum/photos/$newid.$ext", $newid, $ext);
    			$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("myalbum_text")." (lid, description) VALUES ($newid, '$description')") or $eh->show("0013");
    			print "Done!<br></center>\n";
				CloseTable();
	   		} 
   		}
  	}
   	closedir($dir_h);
	xoops_cp_footer();
	
} else {
	if(!$xoopsUser){
		redirect_header(XOOPS_URL."/user.php",2,_ALBM_MUSTREGFIRST);
		exit();
	}

   	global $xoopsDB;
   	xoops_cp_header();
   	OpenTable();
	include_once("../../../class/xoopsformloader.php");

	$form = new XoopsThemeForm(_ALBM_PHOTOUPLOAD, "batchupload", "batch.php");	
	$label = new XoopsFormLabel("", _ALBM_BATCHBLANK);
	$form->setExtra("enctype='multipart/form-data'");

	$question_text = new XoopsFormText(_ALBM_PHOTOTITLE, "title", 50, 255);
	$cat = new XoopsFormSelect(_ALBM_PHOTOCAT, "cid", $cid);		
	$tree = $mytree->getChildTreeArray();
	foreach ($tree as $leaf ) {
		$leaf['prefix'] = substr($leaf['prefix'], 0, -1);
		$leaf['prefix'] = str_replace(".","--",$leaf['prefix']);
		$cat -> addOption($leaf['cid'],$leaf['prefix'].$leaf['title']);
	}
	$desc_tarea = new XoopsFormTextarea(_ALBM_PHOTODESC, "description");
	$dir = new XoopsFormText(_ALBM_PHOTOPATH, "dir", 50, 255);
	
	$op_hidden = new XoopsFormHidden("op", "submit");
	$submit_button = new XoopsFormButton("", "submit", _SUBMIT, "submit");
	$form->addElement($label);
	$form->addElement($question_text);
	$form->addElement($desc_tarea);
	$form->addElement($cat);
	$form->addElement($dir);
	$form->addElement($op_hidden);
	$form->addElement($submit_button);
    $form->setRequired($desc_tarea);
    $form->setRequired($dir);
	$form->display();
   	CloseTable();

    xoops_cp_footer();
}

include("footer.php");
?>
