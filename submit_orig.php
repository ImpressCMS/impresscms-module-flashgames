<?php
// ------------------------------------------------------------------------- //
//                      myAlbum - XOOPS photo album                          //
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

// $Id: submit_orig.php,v 1.1.1.1 2005/03/23 13:45:42 source Exp $ 
  
include("header.php");
$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once(XOOPS_ROOT_PATH."/class/module.errorhandler.php");
include(XOOPS_ROOT_PATH."/modules/myalbum/class/upload.class.php");

global $myalbum_managed;

$eh = new ErrorHandler; //ErrorHandler object
$mytree = new XoopsTree($xoopsDB->prefix("myalbum_cat"),"cid","pid");

if(isset($HTTP_POST_VARS['submit']) && $HTTP_POST_VARS['submit'] != ""){
	if(!$xoopsUser){
		redirect_header(XOOPS_URL."/user.php",2,_ALBM_MUSTREGFIRST);
		exit();
	} 

    	if(!isset($HTTP_POST_VARS['submitter']) || $HTTP_POST_VARS['submitter']=="") {
                $submitter = $xoopsUser->uid();
    	}else{
		$submitter = $HTTP_POST_VARS['submitter'];
	}
// Check if Title exist
    	if ($HTTP_POST_VARS["title"]=="") {
        	$eh->show("1001");
    	}
// Check if Photo exist
	$file = $HTTP_POST_VARS["uploadFileName"][0];
    	if ($file=="" || !isset($file)) {
        	$eh->show("7000");
    	}	
// Check if Description exist
    	if ($HTTP_POST_VARS['description']=="") {
        	$eh->show("1008");
    	}
	if ( !empty($HTTP_POST_VARS['cid']) ) {
    		$cid = $HTTP_POST_VARS['cid'];
	} else {
		$cid = 0;
	}

	$newid = $xoopsDB->genId($xoopsDB->prefix("myalbum_photos")."_lid_seq");

	$field = $GLOBALS['uploadFileName'][0];
//	echo "<h1>".$GLOBALS['uploadFileName'][0]."<br />".$HTTP_POST_FILES[$field]['tmp_name']."<br />".$HTTP_POST_FILES[$field]['name']."<br />".$xoopsConfig['avatar_allow_upload']."</h1>";
	if ($HTTP_POST_FILES[$field]['tmp_name'] != "") {
		$upload = new Upload();
		$upload->setAllowedMimeTypes(array("image/gif","image/pjpeg","image/jpeg","image/x-png"));
		$upload->setMaxImageSize($myalbum_width, $myalbum_heigth);
		$upload->setMaxFileSize($myalbum_fsize);
		$tmp_name = 'tmp_'.rand();
		$upload->setUploadFileNamesArrName("xoops_upload_file");
		$upload->setDestinationFileName("$tmp_name");
		$upload->setUploadPath(XOOPS_ROOT_PATH."/modules/myalbum/photos");
		if ( $upload->doUpload() ) {
			$url = $myts->makeTboxData4Save($url);
			$title = $myts->makeTboxData4Save($HTTP_POST_VARS["title"]);
   			$email = $myts->makeTboxData4Save($HTTP_POST_VARS["email"]);
   			$description = $myts->makeTareaData4Save($HTTP_POST_VARS["description"]);
			$date = time();
			$ext = preg_replace("/^.+\.([^.]+)$/sU", "\\1", $HTTP_POST_FILES[$field]['name']);
//            print XOOPS_ROOT_PATH."/modules/myalbum/photos/$tmp_name.$ext<<<<br>";
            if(!file_exists(XOOPS_ROOT_PATH."/modules/myalbum/photos/$tmp_name.$ext")) {
                print "<br>strlow";
                $ext = strtolower($ext);
            }                
//            print XOOPS_ROOT_PATH."/modules/myalbum/photos/$tmp_name.$ext<<<<br>";
			$dim = GetImageSize(XOOPS_ROOT_PATH."/modules/myalbum/photos/$tmp_name.$ext");
			$q = "INSERT INTO ".$xoopsDB->prefix("myalbum_photos")." (lid, cid, title, ext, res_x, res_y, submitter, status, date, hits, rating, votes, comments) VALUES ($newid, $cid, '$title', '$ext', '$dim[0]', '$dim[1]', $submitter, $myalbum_managed, $date, 0, 0, 0, 0)";
    			$xoopsDB->query($q) or $eh->show("0013");
			if($newid == 0){
				$newid = $xoopsDB->getInsertId();
			}
            rename(XOOPS_ROOT_PATH."/modules/myalbum/photos/$tmp_name.$ext",
       			XOOPS_ROOT_PATH."/modules/myalbum/photos/$newid.$ext");
            
			createThumb(XOOPS_ROOT_PATH."/modules/myalbum/photos/$newid.$ext", $newid, $ext);
    		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myalbum_text")." (lid, description) VALUES ($newid, '$description')") or $eh->show("0013");
			redirect_header("index.php",2,_ALBM_RECEIVED."");
			exit();
		} else {
			$errors = $upload->getUploadErrors();
			include(XOOPS_ROOT_PATH."/header.php");
			OpenTable();
			mainheader();
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
			include(XOOPS_ROOT_PATH."/footer.php");
			exit();
		}
	}
	
	redirect_header("submit.php",2,_ALBM_FILEERROR);

// daniel
	
}else{
	if(!$xoopsUser){
		redirect_header(XOOPS_URL."/user.php",2,_ALBM_MUSTREGFIRST);
		exit();
	}
	$result = $xoopsDB->query("SELECT count(*) as count FROM ".$xoopsDB->prefix("myalbum_cat")."",$mylinks_newlinks,0);
    list($count) = $xoopsDB->fetchRow($result);
	if($count == 0){
		redirect_header(XOOPS_URL."/modules/myalbum",2,_ALBM_MUSTADDCATFIRST);
		exit();
	}

	include(XOOPS_ROOT_PATH."/header.php");
   	OpenTable();
   	mainheader();

	include_once("../../class/xoopsformloader.php");
	$form = new XoopsThemeForm(_ALBM_PHOTOUPLOAD, "uploadavatar", "submit.php");	
	$pixels_label = new XoopsFormLabel(_ALBM_MAXPIXEL, $myalbum_width." x ".$myalbum_heigth);
	$size_label = new XoopsFormLabel(_ALBM_MAXSIZE, $myalbum_fsize);
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
	$file_form = new XoopsFormFile(_ALBM_SELECTFILE, "avatarfile", $myalbum_fsize);
	$op_hidden = new XoopsFormHidden("op", "submit");
	$upload_hidden = new XoopsFormHidden("uploadFileName[0]", "avatarfile");
	$counter_hidden = new XoopsFormHidden("fieldCounter", 1);
	$submit_button = new XoopsFormButton("", "submit", _SUBMIT, "submit");
	$form->addElement($pixels_label);
	$form->addElement($size_label);
	$form->addElement($question_text);
	$form->addElement($desc_tarea);
	$form->addElement($cat);
	$form->addElement($file_form);
	$form->addElement($upload_hidden);
	$form->addElement($counter_hidden);
	$form->addElement($op_hidden);
	$form->addElement($submit_button);
	$form->setRequired($file_form);
	$form->display();
   	CloseTable();
}

include("footer.php");
?>
