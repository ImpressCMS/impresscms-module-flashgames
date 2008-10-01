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

if(isset($HTTP_POST_VARS['submit']) && $HTTP_POST_VARS['submit'] != "") {
    OpenTable();
	if(!$xoopsUser) {
		redirect_header(XOOPS_URL."/user.php",2,_ALBM_MUSTREGFIRST);
		exit();
	} 

//------------

	include '../../../mainfile.php';
	include '../../../class/xoopscomments.php';
	include '../../../include/comment_constants.php';
	$module_handler =& xoops_gethandler('module');
//	$old_commentd_mods = array('myalbum' => 'myalbum_comments');
	$title = _INSTALL_L147;
	$content = '';
   
    $module = 'myalbum';
    $com_table = 'myalbum_comments';
    
	$moduleobj =& $module_handler->getByDirname($module);
	if (is_object($moduleobj)) {
		$content .= '<h5>'.$moduleobj->getVar('name').'</h5>';
		$comment_handler =& xoops_gethandler('comment');
		$criteria = new CriteriaCompo();
		$criteria->setOrder('DESC');
		$criteria->setSort('com_id');
		$criteria->setLimit(1);
		$last_comment =& $comment_handler->getObjects($criteria);
		$offset = (is_array($last_comment) && count($last_comment) > 0) ? $last_comment[0]->getVar('com_id') : 0;
		$xc = new XoopsComments($xoopsDB->prefix($com_table));
		$top_comments =& $xc->getAllComments(array('pid=0'));

    	$xoopsDB->query('DELETE FROM '.$xoopsDB->prefix('xoopscomments').' WHERE com_modid = '.$moduleobj->getVar('mid'));
		foreach ($top_comments as $tc) {
			$sql = sprintf("INSERT INTO %s (com_id, com_pid, com_modid, com_icon, com_title, com_text, com_created, com_modified, com_uid, com_ip, com_sig, com_itemid, com_rootid, com_status, dohtml, dosmiley, doxcode, doimage, dobr) VALUES (%u, %u, %u, '%s', '%s', '%s', %u, %u, %u, '%s', %u, %u, %u, %u, %u, %u, %u, %u, %u)", $xoopsDB->prefix('xoopscomments'), $tc->getVar('comment_id') + $offset, 0, $moduleobj->getVar('mid'), '', addslashes($tc->getVar('subject', 'n')), addslashes($tc->getVar('comment', 'n')), $tc->getVar('date'), $tc->getVar('date'), $tc->getVar('user_id'), $tc->getVar('ip'), 0, $tc->getVar('item_id'), $tc->getVar('comment_id') + $offset, XOOPS_COMMENT_ACTIVE, 0, 1, 1, 1, 1);

			if (!$xoopsDB->query($sql)) {
				$content .= _NGIMG.sprintf("_INSTALL_L146", $tc->getVar('comment_id') + $offset).'<br />';
			} else {
				$content .= _OKIMG.sprintf("_INSTALL_L145", $tc->getVar('comment_id') + $offset).'<br />';
				$child_comments = $tc->getCommentTree();
				foreach ($child_comments as $cc) {
					$sql = sprintf("INSERT INTO %s (com_id, com_pid, com_modid, com_icon, com_title, com_text, com_created, com_modified, com_uid, com_ip, com_sig, com_itemid, com_rootid, com_status, dohtml, dosmiley, doxcode, doimage, dobr) VALUES (%u, %u, %u, '%s', '%s', '%s', %u, %u, %u, '%s', %u, %u, %u, %u, %u, %u, %u, %u, %u)", $xoopsDB->prefix('xoopscomments'), $cc->getVar('comment_id') + $offset, $cc->getVar('pid') + $offset, $moduleobj->getVar('mid'), '', addslashes($cc->getVar('subject', 'n')), addslashes($cc->getVar('comment', 'n')), $cc->getVar('date'), $cc->getVar('date'), $cc->getVar('user_id'), $cc->getVar('ip'), 0, $cc->getVar('item_id'), $tc->getVar('comment_id') + $offset, XOOPS_COMMENT_ACTIVE, 0, 1, 1, 1, 1);
					if (!$xoopsDB->query($sql)) {
						$content .= _NGIMG.sprintf("_INSTALL_L146", $cc->getVar('comment_id') + $offset).'<br />';
					} else {
						$content .= _OKIMG.sprintf("_INSTALL_L145", $cc->getVar('comment_id') + $offset).'<br />';
					}
				}
			}
		}
	}
	$xoopsDB->query('ALTER TABLE '.$xoopsDB->prefix('xoopscomments').' CHANGE com_id com_id mediumint(8) unsigned NOT NULL auto_increment PRIMARY KEY');
	

// --------------


    
    CloseTable();
}    

OpenTable();
include_once("../../../class/xoopsformloader.php");

$form = new XoopsThemeForm(_ALBM_IMPORTCOMMENTS, "batchupload", "upgrade2xoops2.php");	
$form->setExtra("enctype='multipart/form-data'");

$op_hidden = new XoopsFormHidden("op", "submit");

$submit_button = new XoopsFormButton("", "submit", _ALBM_NEXT, "submit");

$form->addElement($op_hidden);
$form->addElement($submit_button);
$form->display();
CloseTable();

xoops_cp_footer();

include("footer.php");
?>
