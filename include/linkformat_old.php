<?php
echo "<tr>";
if ( $mylinks_useshots ) {

	$tablewidth=$mylinks_shotwidth+120;
	if ( $big == 1 ) {
		echo "<td align='center'><center>";		
		$img = "<a href='".XOOPS_URL."/modules/myalbum/photo.php?lid=$lid&full=1'><img src='".XOOPS_URL."/modules/myalbum/photos/$lid.$ext' width='$res_x' height='$res_y' alt='' /></a>";		
	} else {
		echo "<td width='$tablewidth' align='center'>";
//		$img = "<a href='".XOOPS_URL."/modules/myalbum/photo.php?lid=$lid'><img src='".XOOPS_URL."/modules/myalbum/photos/thumbs/$lid.$ext' width='$mylinks_shotwidth' alt='' /></a>";
	

$pfad = 'photos/';
// Check if screenshot is in gif format
$filename = "$pfad$lid.gif";
	

if (file_exists($filename)) 
{
$img = "<a href='".XOOPS_URL."/modules/myalbum/photo.php?lid=$lid'><img src='".XOOPS_URL."/modules/myalbum/photos/$lid.gif' width='$mylinks_shotwidth' alt='' /></a>";
$imag = 'yes';
}

// Check if screenshot is in jpg format
$filename = "$pfad$lid.jpg";
if (file_exists($filename)) 
{
$img = "<a href='".XOOPS_URL."/modules/myalbum/photo.php?lid=$lid'><img src='".XOOPS_URL."/modules/myalbum/photos/$lid.jpg' width='$mylinks_shotwidth' alt='' /></a>";
$imag = 'yes';
}

// no image available
if ($imag != "yes" )
{
$img = "<a href='".XOOPS_URL."/modules/myalbum/photo.php?lid=$lid'><img src='".XOOPS_URL."/modules/myalbum/photos/noimage.gif' width='$mylinks_shotwidth' alt='' /></a>";
}


	}
	
	

	
/*  fixme!  
	print "<table width=1% border=0 cellspacing=0 cellpadding=0>
	<tr bgcolor=black><td colspan=3 height=1><img src='".XOOPS_URL."/modules/myalbum/images/pixel_trans.gif' width=1 height=1></td></tr><tr><td bgcolor=black width=1><img src='".XOOPS_URL."/modules/myalbum/images/pixel_trans.gif' width=1 height=1></td><td><center>$img</center></td><td bgcolor=black width=1><img src='".XOOPS_URL."/modules/myalbum/images/pixel_trans.gif' width=1 height=1></td></tr><tr bgcolor=black><td colspan=3 height=1><img src='".XOOPS_URL."/modules/myalbum/images/pixel_trans.gif'></td></tr>
	</table>";
*/
	print "<br><center>$img</center><br>";

	if ( $big == 1 ) {
		print "</td></tr><tr><td>";		
	} else {
		print "</td><td>";
	}

//	echo "</td>";

} else {
	echo "<td>";
}

global $xoopsUser;

if ( $xoopsUser ) {
//echo $xoopsUser->uid();

	if ( $xoopsUser->uid() == $submitter or $xoopsUser->isAdmin($xoopsModule->mid()) ) {
		echo "<a href=\"".XOOPS_URL."/modules/myalbum/editphoto.php?lid=$lid\"><img src=\"".XOOPS_URL."/modules/myalbum/images/editicon.gif\" border=\"0\" alt=\""._ALBM_EDITTHISLINK."\" /></a>  ";
	}
}
// echo "<br>";
echo "<a name=\"$lid\"></a><a href=\"".XOOPS_URL."/modules/myalbum/photo.php?lid=".$lid."\"><b>$ltitle</b></a>";
newlinkgraphic($time, $status);
echo "<br>";
popgraphic($hits);
 echo "<br>";
echo "<b>"._ALBM_DESCRIPTIONC."</b>$description";


// ShowSubmitter($submitter);
			
echo "<br><b>"._ALBM_LASTUPDATEC."</b>$datetime";
echo "<br><b>"._ALBM_HITSC."</b>$hits ";
//voting & comments stats
        
if ($rating!="0" || $rating!="0.0") {
	if ($votes == 1) {
		$votestring = _ALBM_ONEVOTE;
	} else {
		$votestring = sprintf(_ALBM_NUMVOTES,$votes);
	}
	echo "<br><b>"._ALBM_RATINGC."</b>$rating ($votestring)";
}
if ($comments != 0) {
	if ($comments == 1) {
		$poststring = _ALBM_ONEPOST;
	} else {
		$poststring = sprintf(_ALBM_NUMPOSTS,$comments);
	}
	echo "<b>"._ALBM_COMMENTSC."</b>$poststring";
}
echo "<br><a href=\"".XOOPS_URL."/modules/myalbum/ratephoto.php?lid=".$lid."\">"._ALBM_RATETHISPHOTO."</a>";
//echo " | <a href=\"".XOOPS_URL."/modules/myalbum/modlink.php?lid=".$lid."\">"._ALBM_MODIFY."</a>";
// Oka delete
//echo " | <a target='_top' href='mailto:?subject=".sprintf(_ALBM_INTRESTLINK,$xoopsConfig['sitename'])."&body=".sprintf(_ALBM_INTLINKFOUND,$xoopsConfig['sitename']).":  ".XOOPS_URL."/modules/myalbum/photo.php?lid=".$lid."'>"._ALBM_TELLAFRIEND."</a>";
//echo " | <a href>"._ALBM_VSCOMMENTS."</a>";

echo "</td></tr>";

?>
