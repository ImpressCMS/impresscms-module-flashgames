<?php
// Module Info

// The name of this module
define("_ALBM_FLASHGAMES_NAME","Flashgames");

// A brief description of this module
define("_ALBM_FLASHGAMES_DESC","Flashgames Modul mit Highscoresupport.");

// Names of blocks for this module (Not all module has blocks)
define("_ALBM_BNAME_RECENT","Aktuelle Spiele");
define("_ALBM_BNAME_HITS","Top Spiele");
define("_ALBM_BNAME_RANDOM","Zufallsspiel");

// Names of blocks for this module (Not all module has blocks)
define("_ALBM_MYLINKS_BNAME1","Neueste Spiele");
define("_ALBM_MYLINKS_BNAME2","Top Games");
define("_ALBM_MYLINKS_BNAME3","Top Spieler");
define("_ALBM_MYGAME_ALBUM","Spiele Album");
define("_ALBM_MYGAME_COMMENTS","Neueste Kommentare");
define("_ALBM_MYGAME_RANDOM","Zufallsspiel");
define("_ALBM_MYGAME_LATEST","Neuestes Spiel");

// Config Items
define( "_ALBM_CFG_GAMESPATH" , "Pfad zu den Spielen" ) ;
define( "_ALBM_CFG_DESCGAMESPATH" , "Pfad vom XOOPS Verzeichnis.<br />(Der erste Buchstabe muß '/' sein. Der letzte Buchstabe darf nicht '/' sein.)<br />CMOD in Unix  777 oder 707." ) ;
define( "_ALBM_CFG_THUMBSPATH" , "Pfad zu den Vorschaubildern" ) ;
define( "_ALBM_CFG_DESCTHUMBSPATH" , "Den selben Pfad wie für die Spieler verwenden." ) ;
define( "_ALBM_CFG_USEIMAGICK" , "ImageMagick zur Erstellung der Vorschaubilder benutzen" ) ;
define( "_ALBM_CFG_DESCIMAGICK" , "Die Auswahl von NEIN bedeutet die Benutzung von GD. Falls m&ouml;glich sollte ImageMagick eingesetzt werden" ) ;
define( "_ALBM_CFG_IMAGICKPATH" , "Pfad zu ImageMagick" ) ;
define( "_ALBM_CFG_DESCIMAGICKPATH" , "Vollständiger Pfad zu 'convert'<br />(Der letzte Buchstabe darf nicht '/'sein.)" ) ;
define( "_ALBM_CFG_POPULAR" , "Aufrufe um popul&auml;r zu werden" ) ;
define( "_ALBM_CFG_NEWDAYS" , "Anzahl der Tage an denen die icons'new'&'update' gezeigt werden." ) ;
define( "_ALBM_CFG_NEWGAMES" , "Anzahl der Spiele die auf der Hauptseite angezeigt werden sollen" ) ;
define( "_ALBM_CFG_PERPAGE" , "Anzahl der Bilder pro Seite" ) ;
define( "_ALBM_CFG_MAKETHUMB" , "Vorschaubild erzeugen" ) ;
define( "_ALBM_CFG_THUMBWIDTH" , "Breite Vorschaubild" ) ;
define( "_ALBM_CFG_DESCMAKETHUMB" , "Beim Wechsel von 'Nein' zu 'Ja', wird empfohlen die 'Vorschaubilder neu zu erstellen'." ) ;
define( "_ALBM_CFG_WIDTH" , "Max. Bildbreite" ) ;
define( "_ALBM_CFG_DESCWIDTH" , "Bei der Benutzung von ImageMagick, wird die Breite angepaßt.<br />Andernfalls ist die Breite limitiert." ) ;
define( "_ALBM_CFG_HEIGHT" , "Max. Bildh&ouml;he" ) ;
define( "_ALBM_CFG_DESCHEIGHT" , "Wie maximale Breite." ) ;
define( "_ALBM_CFG_FSIZE" , "Max. Dateigr&ouml;&szlig;e" ) ;
define( "_ALBM_CFG_DESCFSIZE" , "Limit für die Größe hochgeladener Dateien." ) ;
define( "_ALBM_CFG_NEEDADMIT" , "Freigabe f&uuml;r neue Spiele" ) ;
define( "_ALBM_CFG_ADDPOSTS" , "Zahl die zur Anzahl der eingeschickten Spiele bei neuen Spielen addiert wird." ) ;
define( "_ALBM_CFG_DESCADDPOSTS" , "Normalerweise, 0 oder 1. Kleiner als 0 bedeutet 0" ) ;


// Sub menu titles
define("_ALBM_MYLINKS_SMNAME1","Einschicken");
define("_ALBM_MYLINKS_SMNAME2","Popul&auml;r");
define("_ALBM_MYLINKS_SMNAME3","Top bewertet");
define("_ALBM_MYLINKS_SMNAME4","Highscores");
define("_ALBM_MYLINKS_SMNAME5","Bestenliste");


// Names of admin menu items
define("_ALBM_FLASHGAMES_ADMENU0","Eingeschickte Spiele");
define("_ALBM_FLASHGAMES_ADMENU1","Einstellungen");
define("_ALBM_FLASHGAMES_ADMENU2","Hinzuf&uuml;gen/Bearbeiten von Kategorien");
define("_ALBM_FLASHGAMES_ADMENU3","Eingeschickte Spiele");
define("_ALBM_FLASHGAMES_ADMENU4","Stapelupload");
define("_ALBM_FLASHGAMES_ADMENU5","Vorschaubilder neu anlegen");
define("_ALBM_MAXRANKSAVE","Max. Anzahl zu speichernder Ränge");
define("_ALBM_MAXRANKSHOW","Max. Anzahl anzuzeigender Ränge");
define("_ALBM_FLASHGAMES_AISERVICE","Automatischer Installations Service");



?>