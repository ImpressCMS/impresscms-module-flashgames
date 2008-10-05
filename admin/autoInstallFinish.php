<?PHP
include("admin_header.php");

include("../cache/config.php");
include("../include/functions.php");
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
include_once(XOOPS_ROOT_PATH."/class/module.errorhandler.php");
include_once(XOOPS_ROOT_PATH."/class/xoopscomments.php");
include_once(XOOPS_ROOT_PATH."/class/xoopslists.php");

?>
<style>
.ItemBlock {
	border: 1px dashed blue;
	padding: 5px;
	margin: 5px;
}
.ItemBlockFocus {
	background-color: #EFEFEF;
	border: 2px solid red;
	padding: 5px;
	margin: 5px;
}
.ItemBlock #ItemName {
	font-size: 16px;
	font-weight: bold;
	letter-spacing: 1px;
}
.ItemBlock #StatusTitle {
	font-weight: bold;
	font-size: 12px;
}
</style>
<script language="JavaScript">
<!--
function pausecomp(Amount) {
	d = new Date() //today's date
	while (1)
	{
		mill=new Date() // Date Now
		diff = mill-d //difference in milliseconds
		if( diff > Amount ) {break;}
	}
}


function UpdateStatus( Div, Status ) {
	Elem = document.getElementById(Div);
	Elem.innerHTML = Status;
}
var OldBlock = false;
function UpdateCurrentBlock ( NewBlockID ) {
	if(OldBlock){
		OldBlock.className = "ItemBlock";
	}
	NewBlock = document.getElementById( NewBlockID );
	
	NewBlock.className = "ItemBlockFocus";
	
	OldBlock = NewBlock;
}


function ResetBlock ( BlockID ) {
	NewBlock = document.getElementById( BlockID );
	
	NewBlock.className = "ItemBlock";
}
-->
</script>
<img src="../images/logo.gif">
<h2>Automatic Installation Service Results</h2>
<h3>Please be patient and do not close your browser during installation</h3>
<?PHP


$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;
$mytree = new XoopsTree($xoopsDB->prefix("flashgames_cat"),"cid","pid");

global $xoopsDB;
//xoops_cp_header();
//OpenTable();

function UpdateStatus($Div, $Status){
	// Set Status text
	print "
	<script language='JavaScript'>
		<!--
	";	// Open the script tag so we can update the status quickly
	print "UpdateStatus('$Div', '$Status');
	";
	// Close the script tag
	print "
		-->
	</script>
	";

	ob_flush();
	flush();	// Output what we got so far
}

function UpdateCurrentBlock($Div){
	// Set Status text
	print "
	<script language='JavaScript'>
		<!--
	";	// Open the script tag so we can update the status quickly
	print "UpdateCurrentBlock('$Div');
	";
	// Close the script tag
	print "
		-->
	</script>
	";

	ob_flush();
	flush();	// Output what we got so far
}

function ResetBlock($Div){
	// Set Status text
	print "
	<script language='JavaScript'>
		<!--
	";	// Open the script tag so we can update the status quickly
	print "ResetBlock('$Div');
	";
	// Close the script tag
	print "
		-->
	</script>
	";

	ob_flush();
	flush();	// Output what we got so far
}

function InstallGame($gameinfo, $licensekey, $cid, $uid, $localgamefile, $localgamepic){
	if(!is_array($gameinfo)){
		return false;
	}
	
	global $xoopsDB;

  $xoopsDB =& Database::getInstance();

	if(empty($gameinfo['gameType'])){
		$gameinfo['gameType'] = 1;
	}
	
    $sql = "INSERT INTO ".$xoopsDB->prefix("flashgames_games")."
    		SET cid=$cid,
    			title='".addslashes($gameinfo['name'])."',
    			res_x=$gameinfo[width],
    			res_y=$gameinfo[height],
    			bgcolor='$gameinfo[bgcolor]',
    			submitter='$uid',
    			date=".strtotime("today").",
    			gametype='".$gameinfo['gameType']."',
    			status=1,
    			ext='swf',
    			license='$licensekey'";

    $xoopsDB->queryF($sql);
	$newid = $xoopsDB->getInsertId();

	if($newid != 0){
		$sql = "INSERT INTO ".$xoopsDB->prefix("flashgames_text")." SET lid=$newid, description='".addslashes($gameinfo['description'])."'";
		$result = $xoopsDB->queryF($sql);
		
		if($result === false){
			// There was an error installing the file!
	    	$output .= "$sql<br>".$xoopsDB->error();
			
	    	//Remove the game record and files?
	    	$xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("flashgames_games")." WHERE lid=$newid");
	    	unlink($localgamefile);
	    	unlink($localgamepic);
	    	
	    	return false;
		
		}else{
		
		    // Rename the image and game file accordingly
		    $gamefileinfo = pathinfo($localgamefile);
		    $newgamename = $gamefileinfo['dirname']."/".$newid.".swf";
		    
		    $picfileinfo = pathinfo($localgamepic);
		    $newpicname = $picfileinfo['dirname']."/".$newid.".".$picfileinfo['extension'];
		    
		    rename($localgamefile, $newgamename);
		    rename($localgamepic, $newpicname);

		}

		return $newid;
	}else{
		// SQL error installing game
		return false;
	}
}

$installDir = XOOPS_ROOT_PATH."/modules/flashgames/games/";

$output = "";
$postkeys = array_keys($_POST);
$installGames = array();	//keep track of what games to install here...

foreach($postkeys as $postvar){
	$flag = strpos($postvar, "_installflag");

	if($flag !== false && ($_POST[$postvar] == "on" || $_POST[$postvar] == 1)){
		// This is an install flag, the user wants to install this game so we need to get the ID
		$id = substr($postvar, 0, $flag);
		
		//Add this game's id to the array so we can install it later
		$installGames[] = $id;
	}
}

$gamelist = pnFlashGames_getNewGamesList(true);
$gamelist = $gamelist["gamelist"];
$blocksize = 2048;
$numinstallgames = count($installGames);

//print_r($gamelist);
foreach($installGames as $currgame){
	$gameinfo = $gamelist[$currgame];
	$writeflag = true;	// This is used to make sure the game file and pic gets written without error...
	$updateflag = false; // This is used to show if a game was updated or installed as new
	
	$StatusDiv = $gameinfo["id"]."_Status";
	$PercentDiv = $gameinfo["id"]."_Percentage";
	$ItemDiv = $gameinfo["id"]."_Block";
	
	print "<div class='ItemBlock' id='$ItemDiv'>";
	print "<div id='ItemName'>".$gameinfo['name']."</div>";
	print "<span id='StatusTitle'>Status: </span><span id='$StatusDiv'>Waiting in queue...</span> <span id='$PercentDiv'></span>";
	print "</div>"; //Close ItemBlock
}

reset($installGames);

foreach($installGames as $currgame){
	$gameinfo = $gamelist[$currgame];
	$writeflag = true;	// This is used to make sure the game file and pic gets written without error...
	$updateflag = false; // This is used to show if a game was updated or installed as new
	
	$StatusDiv = $gameinfo["id"]."_Status";
	$PercentDiv = $gameinfo["id"]."_Percentage";
	$ItemDiv = $gameinfo["id"]."_Block";
	
	// Attempt to get and write the game file
	$output .= "Opening {$gameinfo[gamefile]} for reading...<br>";
	UpdateCurrentBlock($ItemDiv);
	
	$fp = fopen($gameinfo['gamefile'], "r");
	if($fp !== false){
		$gamecontents = '';
		
		$loopcount = 0;
		
		// Set Status text
		UpdateStatus($StatusDiv, 'Copying .swf file...');
				
		while (!feof($fp)) {
			$gamecontents .= fread($fp, $blocksize);
			
			// Update the progress bar...
			$loopcount++;
			$percent = round( (($loopcount * $blocksize) / $gameinfo['gamesize_real']) * 100 );
			UpdateStatus($PercentDiv, "[ $percent % ]");
		}
		fclose($fp);
		
	}else{
		$output .= "Error reading gamefile...<br>";
		$writeflag = false;
	}
	
	UpdateStatus($StatusDiv, 'Writing .swf contents');
	
	$localgamefile = $installDir . basename($gameinfo['gamefile']);
	if(file_exists($localgamefile)){
		$updateflag = true;
		if(is_writable($localgamefile)){
			// Overwriting loca file
			$fp = fopen($localgamefile, "wb");
			if($fp !== false){
				$output .= "Updating $localgamefile...<br>";
				fwrite($fp, $gamecontents);
				fclose($fp);
				chmod($localgamefile, 0755);
			}else{
				$output .= "Error updating $localgamefile... Error ER001<br>";
				$writeflag = false;
			}
			
		}else{
			// Local file is not writable
			$output .= "Error updating $localgamefile... <i>File already exists but is not writable! (ER003)</i><br>";
			$writeflag = false;
		}
	}else{
		// No local file, this is a new install
		$fp = fopen($localgamefile, "wb");
		if($fp !== false){
			$output .= "Writing $localgamefile...<br>";
			fwrite($fp, $gamecontents);
			fclose($fp);
			chmod($localgamefile, 0755);
		}else{
			$output .= "Error writing $localgamefile... ER002<br>";
			$writeflag = false;
		}
	}

	UpdateStatus($StatusDiv, 'Copying thumbnail');
	
	// Attempt to get and write the game thumbnail
	$output .= "Opening {$gameinfo[gamepic]} for reading...<br>";
	$fp = fopen($gameinfo['gamepic'], "r");
	if($fp !== false){
		$piccontents = '';
		
		$loopcount = 0;
		
		while (!feof($fp)) {
		    $piccontents .= fread($fp, $blocksize);
			
			// Update the progress bar...
			$loopcount++;
			$percent = round( (($loopcount * $blocksize) / $gameinfo['gamesize_real']) * 100 );
			UpdateStatus($PercentDiv, "[ $percent % ]");
		}
		fclose($fp);
		
	}else{
		$output .= "Error reading game pic...<br>";
		$writeflag = false;
	}

	UpdateStatus($StatusDiv, 'Writing thumbnail');
	
	$localgamepic = $installDir . basename($gameinfo['gamepic']);
	if(file_exists($localgamepic)){
		if(is_writable($localgamepic)){
			// Updating local picture
			$fp = fopen($localgamepic, "wb");
			if($fp !== false){
				$output .= "Updating $localgamepic...<br>";
				fwrite($fp, $piccontents);
				fclose($fp);
				chmod($localgamepic, 0755);
			}else{
				$output .= "Error updating $localgamepic...<br>";
				$writeflag = false;
			}
		}else{
			// Local pic not writable
			$output .= "Error updating $localgamepic... <i>File exists but is not writable!</i><br>";
			$writeflag = false;
		}
	}else{
		// Not updating pic, copying new file
		$fp = fopen($localgamepic, "wb");
		if($fp !== false){
			$output .= "Writing $localgamepic...<br>";
			fwrite($fp, $piccontents);
			fclose($fp);
			chmod($localgamepic, 0755);
		}else{
			$output .= "Error writing $localgamepic...<br>";
			$writeflag = false;
		}
	}
	
	UpdateStatus($StatusDiv, 'Installing game');
	// Now we invoke the API function and actually install the game...
	if($writeflag && !$updateflag){
		// Looks like the files got copied okay, so lets go ahead and install the game
		$output .= "Installing {$gameinfo[name]}...<br/>";
	    $licensekey = ( ($_POST[$currgame."_license"]=="" || empty($_POST[$currgame."_license"]) || !isset($_POST[$currgame."_license"]) ) ? "" : $_POST[$currgame."_license"]);
		
	    $newid = InstallGame($gameinfo, $licensekey, $_POST[$currgame.'_initcat'], $xoopsUser->uid(), $localgamefile, $localgamepic);
	    
		if($newid){
			
			UpdateStatus($StatusDiv, 'Installation complete');
		
		}else{
			// There was an error installing the file!
			UpdateStatus($StatusDiv, 'Error Installing game');
			$output .= "<pre>$sql\n".$xoopsDB->error()."</pre>";
	    	$output .= "Error installing game!<br>";
		}
	}else if($updateflag){
		// Game file was updated... check to update license key or anything like that?
		UpdateStatus($StatusDiv, 'Game updated successfully');
	}
	
	UpdateStatus($PercentDiv, '');
	$output .= "<hr width='75%' align='center'>";

	ResetBlock($ItemDiv);
} // end installation loop

// remove the gamelist.xml file...
unlink($gamelist);

// Update main progress to finished	
print "<h3>Installation Complete</h3>";
print "<a href='".XOOPS_URL."/modules/flashgames/admin/index.php'><strong>Continue...</strong></a>";

// Uncomment this to show more detailed debugging output (Such as any mysql errors or file transfer problems
//print "<br>".$output;

?>