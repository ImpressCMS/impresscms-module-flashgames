Flashgames Module V1.0.1 for Xoops 2.0.x 

Author:   Oliver Kaufhold (aka gripsmaker)  / http://www.tipsmitgrips.de (german Supportsite)
Partner:  Lee Eason                         / http://pnflashgames.com   (english Supportsite)

README DEUTSCH  (english see below)            |
--------------------------------
Beschreibung:
Dieses Xoops-Modul basiert auf dem Galeriemodul myalbum. Statt Bilder, werden jedoch Flashanwendungen unterstützt;
In erster Linie Flashspiele. Ein Highlight des Moduls ist die Unterstützung von Highscores, d.h. wenn ein Spiel Highscores unterstützt, wird nach Spielende automatisch der Username und der Score in einer Highscoretabelle gespeichert und ausgegeben (vorausgesetzt der User ist eingeloggt!)

Neben einer Vielzahl an Spielen zum Kostenlosen Download, gibt es auch eine Reihe
an qualitativ hochwertigen kommerziellen Spielen, die für ein geringes Entgelt bezogen 
werden können.

Sowohl kostenlose als auch kommerzielle Spiele, sind auf meiner Partnerseite
"pnflashgames.com" erhältlich !
Alle diese Spiele speichern den Highscore ab!
   

Weitere Infos und Support unter: www.tipsmitgrips.de und 
                                 pnflashgames.com 




Installationsanweisungen       |
--------------------------------

Den Ordner "Flashgames" in das Modules Verzeichnis kopieren und über das Admin Menü installieren
Die Ordner /games und /cache benötigen Schreibrechte (chmod 777)

Deinstallationsanweisungen       |
--------------------------------

Das Modul über das Admin Modul deinstallieren. Anschließend die Spiele und Bilder (1.swf,1.gif...) aus dem Ordner /games löschen.



Installation von Spielen
-------------------------------|
- Zunächst eine Kategorie in der Administration anlegen

Titel:           eingeben
Beschreibung:    eingeben
Spiel Breite:    änderbar (Vorgabewert 300)
Spiel Höhe:      änderbar (Vorgabewert 300)
Kategorie:       auswählen
Hintergrundfarbe: Hintergrundfarbe des Spiels (leer lassen für Standardfarbe)
Bild:            Vorschaubild auswählen zum jeweiligen Spiel, unterstützt wird 'jpg' und 'gif'
Spiel auswählen: Spiel oder Flashanwendung auswählen mit der Endung 'swf' 
                 oder Java Spiele (zip oder jar) 
Java Klasse:     Bei Java Spielen, muß die class-Datei angegeben werden z.B. Frozenbubble.class
Highscoretyp:    für Spiele, die Highscores unterstützen, muß ein Highscoretyp ausgewählt werden
                 (auf Spiele ohne Highscore hat die Auswahl keine Auswirkung)

Beispiel: Der Highscoretyp für Pacman ist 'numerisch-Höchster Score gewinnt'


Lizenzschlüssel:  Für kommerzielle Spiele wird ein Lizenzschlüssel benötigt. Dieser wird zusammen mit dem 
                  Spiel beim Kauf ausgehändigt (Spiele können auf pnflashgames.com bezogen werden)
                  Beim Kauf muß eine Domain angegeben werden, auf der das Spiel laufen soll. Der                   ausgegbene Schlüssel funktioniert dann nur mit dieser Domain !

Nur für Mitglieder:  falls gesetzt, muß Spieler muß eingeloggt sein, um ZUgriff auf das Spiel zu erhalten

                 
Achtung: Vorschaubild und Spiel können später nicht mehr geändert werden.
         Wollt Ihr dies dennoch, müßt Ihr das Spiel löschen und anschließend neu hinzufügen. 


Ändern von Spielen
-------------------------------|
Titel:               kann geändert werden
Beschreibung:        kann geändert werden
Spiel Breite:        kann geändert werden
Spiel Höhe:          kann geändert werden
Hintergrundfarbe:    kann geändert werden
Kategorie:           kann geändert werden
Highscoretyp:        kann geändert werden
Lizenzschlüssel:     kann geändert werden
Gültig:              falls gesetzt, ist Spiel aktiv
Nur für Mitglieder:  falls gesetzt,muß Spieler eingeloggt sein, um ZUgriff auf das Spiel zu erhalten
Tagesdatum setzen:   falls gesetzt, wird das Tagesdatum gesetzt
Löschen:             Das Spiel und evtl. Highscores werden komplett gelöscht !!!
Score löschen:       Nur die Highscores zum Spiel werden gelöscht.



-------------------------------------------

 Changelog:
 ==========

1.0.1
- AI Service fixed 

1.0 Alpha 1 (Testversion)
- Neue Blöcke: Zufallsspiel, Neuestes Spiel, Topspieler
- Topspieler Übersicht
- Index Seite templatebasiert (flashgames_index.html, flashgames_viewcat.html)
  Eigene Layouts sind jetzt wesentlich einfacher zu realisieren
- register globals (quick fix)
  Modul sollte nun auch mit register globals = off laufen (nicht vollständig getestet!)
- Highscoreränge
  Spieler mit gleichem Score erhalten nun den gleichen Highscorerang und die selbe Punktzahl
  in der Topspieler-Liste
- Java Spiele Support
  Java Spiele werden nun unterstützt, jedoch werden bei highscorebasierten Javaspielen die Scores 
  nicht gespeichert!
- Automatischer Installations Service
  Spiele können nun automatisch installiert werden. Dieser kostenpflichtige Service 
  kann im pnflashgames.com Shop aktiviert werden.
- Templates fixed
  Dank an DevoteeM für die Überarbeitung der Templates  

0.9 RC1 (Release Kandidat 1)
- Unterstützung von kommerziellen Spielen
- Save/Load Funktion von Spielen 
- technisch auf dem Stand des pnflashgames Moduls (wichtig für spätere Erweiterungen) !!

-------------------------------------------



##########################################################################################################

README ENGLISH       |
--------------------------------
Description:
The module is based on an image gallery for Xoops called "myAlbum".
Instead of Pictures it supports Flash Applications, mainly of course Flashgames !
One highlight of the module is the highscoresupport, that means when you play a highscore supported
game, your name and score will be saved in a highscore table automatically after games end 
(provided that the player was logged in) 

Beside of many games for free download, there is also a series of high quality comercial games
you can buy for a little fee.

Free and comercial games you can get exclusively on my oficial partnersite "Pnflashgames.com"
All of these games have of course highscoresupport !

For further details and support please visit:  pnflashgames.com (english support)
                                               www.tipsmitgrips.de (german support)



Installation       |
--------------------------------
- Unzip in "modules" directory.
- chmod 777 (or 707) /games  and /cache directories (in unix systems).


Deinstallation       |
--------------------------------
Deinstall the module in the admin interface of xoops. After this you have to delete manually the games and pictures (1.swf,1.gif...) in /games  


Add a new game
-------------------------------|
- First create a category in the admin interface

Titel:            input
Description:      input
Game width:       changeable (default value 300)
Game height:      changeable (default value 300)
Category:         select one
Picture:          Preview image  of the game,  'jpg' and 'gif' are supported
Select game:      select Game or Flash Application with ending 'swf'
                  or Java Games (jar or zip files)   
Highscoretype:    for highscore based games you must select a highscoretype
                  ( for non highscore games this function is useless)
 
Example: The highscore type for pacman is 'numeric- highest scores wins'

License Key:  If you buy a comercial game from pnflashgames.com you get also a license key. This key only works on the domain you specify when buying. 

Members only:  if marked, user musr logged in to get access to the game


Notice: After submitting a game, you can't change the preview Image and game!
        If you want this anyway you must delete the game and add it again.


Edit a game
-------------------------------|
Titel:          changeable
Description:    changeable
Game width:     changeable
Game width:     changeable
Backgroun Color:changeable
Category:       changeable
Highscoretype:  changeable
License Key:    changeable
Valid:          If marked, the game is activ
Members only:   if marked, user musr logged in to get access to the game
set current date: if marked current date will be set.
delete ?:       The game and existing highscores will be deleted !!!
Clear scores:   Only the higscores of the game will be deleted.



-------------------------------------------

 Changelog:
 ==========

1.0.1
- AI Service fixed 

1.0 Alpha 1 (Testversion)
- New Blocks: Randomgame, Newest game, Topplayer
- Topplayer overview
- Index Page is now full template based
  I've made some new templates (flashgames_index.html, flashgames_viewcat.html) for the main page.
  Giving the module a new layout should be much easier now.
- register globals (quick fix / open)
  Modul should now work with register globals = off. 
  I'm not quite sure if this really works in all cases because some errors occur ("Undefined variables")
- Highscore ranking fix 
  If two or more players reach the same score they will now get the same rank (as in pnflashgames)
  and of course the same points in the "top player" list.
- Support for java games (open)
  The game should be sent as zip or jar file. In the submit form you have to enter the classfile within
  the game (e.g. FrozenBubble.class)
  Note: Highcore enabled java game from pnflashgames don't save scores yet.
- Automatischer Installations Service
  You can now install games automatically. For a small fee you can activate this service at   pnflashgames.com Shop
- Templates fixed
  Thanks to DevoteeM für fixing and revising the Templates


0.91 RC1 
- Supporting of comercial games
- Supporting of game data saving 
- important functions of pnflasgames added for later enhancements !!!
-------------------------------------------






Credits
--------
Oficial Partner
Postnuke module "pnflashgames"      - http://www.pnflashgames.com/ (Lee Eason)

Xoops module "myalbum"              - http://bluetopia.homeip.net/    
XOOPS PHP Content Management System - http://www.xoops.org/ 