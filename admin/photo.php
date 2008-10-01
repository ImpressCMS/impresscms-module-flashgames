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
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once(XOOPS_ROOT_PATH."/class/module.errorhandler.php");
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH."/class/xoopscomments.php");
include_once(XOOPS_ROOT_PATH."/class/xoopslists.php");
include(XOOPS_ROOT_PATH."/modules/myalbum/class/upload.class.php");

global $myts, $xoopsDB, $xoopsConfig, $xoopsModule;

$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object
$eh = new ErrorHandler; //ErrorHandler object
$mytree = new XoopsTree($xoopsDB->prefix("myalbum_cat"),"cid","pid");

function update($lid, $cid, $title, $desc, $valid, $ext = "", $x = "", $y = "") {
	global $myts, $xoopsDB, $xoopsConfig, $xoopsModule;

	if($ext == "") {	
		$xoopsDB->query("UPDATE ".$xoopsDB->prefix("myalbum_photos")." 
			SET cid='$cid',title='$title', status='$valid', date=".time()." 
			WHERE lid='$lid'");
	} else {
		$xoopsDB->query("UPDATE ".$xoopsDB->prefix("myalbum_photos")." 
			SET cid='$cid',title='$title', status='$valid', date=
			".time().", ext='$ext',res_x='$x',res_y='$y'   
			WHERE lid='$lid'");
	}
	$xoopsDB->query("UPDATE ".$xoopsDB->prefix("myalbum_text")." SET 
		description='$desc' WHERE lid=".$lid."");

	redirect_header("photo.php?lid=$lid",0);
}

if(isset($delete) and $delete != "") {

	$delete = $myts->makeTareaData4Save($delete);
	$result = $xoopsDB->query("SELECT l.lid, l.cid, l.title, l.ext, l.res_x, l.res_y, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description FROM ".$xoopsDB->prefix("myalbum_photos")." l, ".$xoopsDB->prefix("myalbum_text")." t where l.lid=t.lid and l.lid=$delete ORDER BY date DESC",$flashgames_newlinks,0);
	list($lid, $cid, $ltitle, $ext, $res_x, $res_y, $status, $time, $hits, $rating, $votes, $comments, $description)=$xoopsDB->fetchRow($result);

	$q = "DELETE FROM ".$xoopsDB->prefix("myalbum_photos")." WHERE lid = $delete";
	$xoopsDB->query($q) or $eh->show("0013");
	$q = "DELETE FROM ".$xoopsDB->prefix("myalbum_text")." WHERE lid = $delete";
	$xoopsDB->query($q) or $eh->show("0013");
	$q = "DELETE FROM ".$xoopsDB->prefix("myalbum_votedata")." WHERE lid = $delete";
	$xoopsDB->query($q) or $eh->show("0013");

	// delete comments for this photo
	$com = new XoopsComments($xoopsDB->prefix("myalbum_comments"));
	$criteria = array("item_id=$delete", "pid=0");
	$commentsarray = $com->getAllComments($criteria);
	foreach($commentsarray as $comment){
		$comment->delete();
	}

	if(is_numeric($delete)) { // last security check
		unlink(XOOPS_ROOT_PATH."/modules/myalbum/photos/$delete.$ext");
		unlink(XOOPS_ROOT_PATH."/modules/myalbum/photos/thumbs/$delete.$ext");
	}

	redirect_header("index.php?op=linksConfigMenu",2,_ALBM_DELETINGPHOTO);
	exit();
}

if(isset($HTTP_POST_VARS['submit']) && $HTTP_POST_VARS['submit'] != ""){
	if(!$xoopsUser){
		redirect_header(XOOPS_URL."/user.php",2,_ALBM_MUSTREGFIRST);
		exit();
	} 

   	if(!isset($HTTP_POST_VARS['submitter']) || $HTTP_POST_VARS['submitter']=="") {
   		$submitter = $xoopsUser->uid();
 	} else {
		$submitter = $HTTP_POST_VARS['submitter'];
	}

	if(isset($HTTP_POST_VARS['delete'])) {
		xoops_cp_header();
		OpenTable();
		echo "<h4 style='text-align:left;'>"._ALBM_PHOTODEL." $delete</h4>\n";
		echo "<table><tr><td>\n";
		echo myTextForm("photo.php?delete=$lid", _YES);
		echo "</td><td>\n";
		echo myTextForm("photo.php?lid=$lid", _NO);
		echo "</td></tr></table>\n";
		CloseTable();
		xoops_cp_footer();
		exit();
	}

// Check if Title exist
    if ($HTTP_POST_VARS["title"]=="") {
    	$eh->show("1001");
    }
// Check if Photo exist
//	$file = $HTTP_POST_VARS["uploadFileName"][0];
//   	if ($file=="" || !isset($file)) {
//        	$eh->show("7000");
//    	}	
// Check if Description exist
    if ($HTTP_POST_VARS['description']=="") {
       	$eh->show("1008");
    }
	if ( !empty($HTTP_POST_VARS['cid']) ) {
    	$cid = $HTTP_POST_VARS['cid'];
	} else {
		$cid = 0;
	}
	if(isset($HTTP_POST_VARS['valid'])) {
		$valid = 1;
	} else {
		$valid = 0;
	}
	
	$field = $GLOBALS['uploadFileName'][0];
//	echo "<h1>".$GLOBALS['uploadFileName'][0]."<br />".$HTTP_POST_FILES[$field]['tmp_name']."<br />".$HTTP_POST_FILES[$field]['name']."</h1>";
	if ( $HTTP_POST_FILES[$field]['tmp_name'] != "none" ) {
		$upload = new Upload();
		$upload->setAllowedMimeTypes(array("image/gif","image/pjpeg","image/jpeg","image/x-png"));
		$upload->setMaxImageSize($myalbum_width, $myalbum_heigth);
		$upload->setMaxFileSize($myalbum_fsize);
		$tmp_name = 'tmp_'.rand();
		$upload->setDestinationFileName("$tmp_name");
		$upload->setUploadPath(XOOPS_ROOT_PATH."/modules/myalbum/photos");
		if ( $upload->doUpload() ) {
			$title = $myts->makeTboxData4Save($HTTP_POST_VARS["title"]);
   			$description = $myts->makeTareaData4Save($HTTP_POST_VARS["description"]);
			$ext = preg_replace("/^.+\.([^.]+)$/sU", "\\1", $HTTP_POST_FILES[$field]['name']);
			$dim = GetImageSize(XOOPS_ROOT_PATH."/modules/myalbum/photos/$tmp_name.$ext");
			if(is_numeric($lid)) { // last security check
				unlink(XOOPS_ROOT_PATH."/modules/myalbum/photos/$lid.$ext");
				unlink(XOOPS_ROOT_PATH."/modules/myalbum/photos/thumbs/$lid.$ext");
			}	
			rename(XOOPS_ROOT_PATH."/modules/myalbum/photos/$tmp_name.$ext",
				XOOPS_ROOT_PATH."/modules/myalbum/photos/$lid.$ext");
			createThumb(XOOPS_ROOT_PATH."/modules/myalbum/photos/$lid.$ext", $lid, $ext);
			update($lid, $cid, $title, $description, $valid, $ext, $dim[0], $dim[1]);
			exit();
		} else {
			$errors = $upload->getUploadErrors();
			xoops_cp_header();
   			OpenTable();
    		echo "<p><strong>::Errors occured::</strong><br />\n";
			while(list($filename,$values) = each($errors)) {
				"File: " . print $filename . "<br />";
				$count = count($values);
				for($i=0; $i<$count; $i++) {
					echo "==>" . $values[$i] . "<br />";
				}
			}
			echo "</p>";
			CloseTable();
		    xoops_cp_footer();
       		exit();
		}
	} else {  //update without file upload
		$title = $myts->makeTboxData4Save($HTTP_POST_VARS["title"]);
		$description = $myts->makeTareaData4Save($HTTP_POST_VARS["description"]);
    	$cid = $HTTP_POST_VARS['cid'];
		update($lid, $cid, $title, $description, $valid);
	}

} else {

	if(!$xoopsUser){
		redirect_header(XOOPS_URL."/user.php",2,_ALBM_MUSTREGFIRST);
		exit();
	}
    
    if ($submitter == $xoopsUser->uid()) {

//        include_once("../../../header.php");

    } else {
    	xoops_cp_header();
    }
    
   	OpenTable();

	$result = $xoopsDB->query("SELECT l.lid, l.cid, l.title, l.ext, l.res_x, l.res_y, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description FROM ".$xoopsDB->prefix("myalbum_photos")." l, ".$xoopsDB->prefix("myalbum_text")." t where l.lid=t.lid and l.lid=$lid ORDER BY date DESC",$flashgames_newlinks,0);
	list($lid, $cid, $ltitle, $ext, $res_x, $res_y, $status, $time, $hits, $rating, $votes, $comments, $description)=$xoopsDB->fetchRow($result);
	include_once("../../../class/xoopsformloader.php");

	$form = new XoopsThemeForm(_ALBM_PHOTOUPLOAD, "uploadavatar", "photo.php?lid=$lid");	
	$form->setExtra("enctype='multipart/form-data'");
	$question_text = new XoopsFormText(_ALBM_PHOTOTITLE, "title", 50, 255,$ltitle);
	$cat = new XoopsFormSelect(_ALBM_PHOTOCAT, "cid", $cid);		
	$tree = $mytree->getChildTreeArray();
	foreach ($tree as $leaf ) {
		$leaf['prefix'] = substr($leaf['prefix'], 0, -1);
		$leaf['prefix'] = str_replace(".","--",$leaf['prefix']);
		$cat -> addOption($leaf['cid'],$leaf['prefix'].$leaf['title']);
	}
	$desc_tarea = new XoopsFormTextarea(_ALBM_PHOTODESC, "description", $description);
	$file_form = new XoopsFormFile(_ALBM_SELECTFILE, "avatarfile", $myalbum_fsize);
	$op_hidden = new XoopsFormHidden("op", "submit");
	$upload_hidden = new XoopsFormHidden("uploadFileName[0]", "avatarfile");
	$counter_hidden = new XoopsFormHidden("fieldCounter", 1);
	if($status == 1) 
		$status = "";
	$valid_box = new XoopsFormCheckBox(_ALBM_VALIDPHOTO, "valid", array($status));
	$valid_box -> addOption();	
	$delete_box = new XoopsFormCheckBox(_ALBM_DELETEPHOTO, "delete");	
	$delete_box -> addOption();
	$submit_button = new XoopsFormButton("", "submit", _SUBMIT, "submit");
	print "<center><img src=../photos/thumbs/$lid.$ext></center>";
	$form->addElement($question_text);
	$form->addElement($desc_tarea);
	$form->addElement($cat);
	$form->addElement($file_form);
	$form->addElement($upload_hidden);
	$form->addElement($counter_hidden);
	$form->addElement($op_hidden);
	$form->addElement($valid_box);	
	$form->addElement($delete_box);
	$form->addElement($submit_button);
//	$form->setRequired($file_form);
	$form->display();
   	CloseTable();
}

	xoops_cp_footer();
?>
