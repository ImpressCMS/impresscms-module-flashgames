Flashgames Module V1.0.1 for Xoops 2.0.x 

Author:   Oliver Kaufhold (aka gripsmaker)  / http://www.tipsmitgrips.de (german Supportsite)
Partner:  Lee Eason                         / http://pnflashgames.com   (english Supportsite)

README DEUTSCH  (english see below)            |
--------------------------------
Beschreibung:
Dieses Xoops-Modul basiert auf dem Galeriemodul myalbum. Statt Bilder, werden jedoch Flashanwendungen unterst�tzt;
In erster Linie Flashspiele. Ein Highlight des Moduls ist die Unterst�tzung von Highscores, d.h. wenn ein Spiel Highscores unterst�tzt, wird nach Spielende automatisch der Username und der Score in einer Highscoretabelle gespeichert und ausgegeben (vorausgesetzt der User ist eingeloggt!)

Neben einer Vielzahl an Spielen zum Kostenlosen Download, gibt es auch eine Reihe
an qualitativ hochwertigen kommerziellen Spielen, die f�r ein geringes Entgelt bezogen 
werden k�nnen.

Sowohl kostenlose als auch kommerzielle Spiele, sind auf meiner Partnerseite
"pnflashgames.com" erh�ltlich !
Alle diese Spiele speichern den Highscore ab!
   

Weitere Infos und Support unter: www.tipsmitgrips.de und 
                                 pnflashgames.com 




Installationsanweisungen       |
--------------------------------

Den Ordner "Flashgames" in das Modules Verzeichnis kopieren und �ber das Admin Men� installieren
Die Ordner /games und /cache ben�tigen Schreibrechte (chmod 777)

Deinstallationsanweisungen       |
--------------------------------

Das Modul �ber das Admin Modul deinstallieren. Anschlie�end die Spiele und Bilder (1.swf,1.gif...) aus dem Ordner /games l�schen.



Installation von Spielen
-------------------------------|
- Zun�chst eine Kategorie in der Administration anlegen

Titel:           eingeben
Beschreibung:    eingeben
Spiel Breite:    �nderbar (Vorgabewert 300)
Spiel H�he:      �nderbar (Vorgabewert 300)
Kategorie:       ausw�hlen
Hintergrundfarbe: Hintergrundfarbe des Spiels (leer lassen f�r Standardfarbe)
Bild:            Vorschaubild ausw�hlen zum jeweiligen Spiel, unterst�tzt wird 'jpg' und 'gif'
Spiel ausw�hlen: Spiel oder Flashanwendung ausw�hlen mit der Endung 'swf' 
                 oder Java Spiele (zip oder jar) 
Java Klasse:     Bei Java Spielen, mu� die class-Datei angegeben werden z.B. Frozenbubble.class
Highscoretyp:    f�r Spiele, die Highscores unterst�tzen, mu� ein Highscoretyp ausgew�hlt werden
                 (auf Spiele ohne Highscore hat die Auswahl keine Auswirkung)

Beispiel: Der Highscoretyp f�r Pacman ist 'numerisch-H�chster Score gewinnt'


Lizenzschl�ssel:  F�r kommerzielle Spiele wird ein Lizenzschl�ssel ben�tigt. Dieser wird zusammen mit dem 
                  Spiel beim Kauf ausgeh�ndigt (Spiele k�nnen auf pnflashgames.com bezogen werden)
                  Beim Kauf mu� eine Domain angegeben werden, auf der das Spiel laufen soll. Der                   ausgegbene Schl�ssel funktioniert dann nur mit dieser Domain !

Nur f�r Mitglieder:  falls gesetzt, mu� Spieler mu� eingeloggt sein, um ZUgriff auf das Spiel zu erhalten

                 
Achtung: Vorschaubild und Spiel k�nnen sp�ter nicht mehr ge�ndert werden.
         Wollt Ihr dies dennoch, m��t Ihr das Spiel l�schen und anschlie�end neu hinzuf�gen. 


�ndern von Spielen
-------------------------------|
Titel:               kann ge�ndert werden
Beschreibung:        kann ge�ndert werden
Spiel Breite:        kann ge�ndert werden
Spiel H�he:          kann ge�ndert werden
Hintergrundfarbe:    kann ge�ndert werden
Kategorie:           kann ge�ndert werden
Highscoretyp:        kann ge�ndert werden
Lizenzschl�ssel:     kann ge�ndert werden
G�ltig:              falls gesetzt, ist Spiel aktiv
Nur f�r Mitglieder:  falls gesetzt,mu� Spieler eingeloggt sein, um ZUgriff auf das Spiel zu erhalten
Tagesdatum setzen:   falls gesetzt, wird das Tagesdatum gesetzt
L�schen:             Das Spiel und evtl. Highscores werden komplett gel�scht !!!
Score l�schen:       Nur die Highscores zum Spiel werden gel�scht.



-------------------------------------------

 Changelog:
 ==========

1.0.1
- AI Service fixed 

1.0 Alpha 1 (Testversion)
- Neue Bl�cke: Zufallsspiel, Neuestes Spiel, Topspieler
- Topspieler �bersicht
- Index Seite templatebasiert (flashgames_index.html, flashgames_viewcat.html)
  Eigene Layouts sind jetzt wesentlich einfacher zu realisieren
- register globals (quick fix)
  Modul sollte nun auch mit register globals = off laufen (nicht vollst�ndig getestet!)
- Highscorer�nge
  Spieler mit gleichem Score erhalten nun den gleichen Highscorerang und die selbe Punktzahl
  in der Topspieler-Liste
- Java Spiele Support
  Java Spiele werden nun unterst�tzt, jedoch werden bei highscorebasierten Javaspielen die Scores 
  nicht gespeichert!
- Automatischer Installations Service
  Spiele k�nnen nun automatisch installiert werden. Dieser kostenpflichtige Service 
  kann im pnflashgames.com Shop aktiviert werden.
- Templates fixed
  Dank an DevoteeM f�r die �berarbeitung der Templates  

0.9 RC1 (Release Kandidat 1)
- Unterst�tzung von kommerziellen Spielen
- Save/Load Funktion von Spielen 
- technisch auf dem Stand des pnflashgames Moduls (wichtig f�r sp�tere Erweiterungen) !!

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
  Thanks to DevoteeM f�r fixing and revising the Templates


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