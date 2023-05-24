<?php

class testRob {

    public $spielername;
    public $hp;
    public $energie;
    public $credits;
    public $schild;
    public $arme;
    public $kostenBewegung;
    public $kostenSchlagen;
    public $machSchaden;
    public $bekommeSchaden;
    public $rostschaden;
    public $ausgesetzteRunden = 0;
    public $creditRegeneration = false;
    public $energieRegeneration = false;


    function __construct($name) {

        $this->spielername  = $name;
        $this->hp           = 500;
        $this->energie      = 500;
        $this->credits      = 5;
        $this->schild       = 0;
        $this->arme         = 0;

    }

    function steckbrief($spieler) {

        echo "Charakterübersicht: $spieler"."\r\n";
        echo "---------------------------------"."\r\n";
        echo "Name              : $this->spielername"."\r\n";
        echo "Lebenspunkte      : $this->hp"."\r\n";
        echo "Energie           : $this->energie"."\r\n";
        echo "Credits           : $this->credits"."\r\n";
        echo "Schilde           : $this->schild"."\r\n";
        echo "Arme              : $this->arme"."\r\n"."\r\n";

    }

    function kostenBewegung ($schritte) {

        $this->kostenBewegung = $schritte + ($this->arme * 5 * $schritte) + ($this->schild * 2 * $schritte);

        if ($this->kostenBewegung <= $this->energie) {

            $this->energie = $this->energie - $this->kostenBewegung;
            echo "=> Kosten: $this->kostenBewegung Energie."."\r\n";
            sleep(1);
            echo "=> Verbleibende Energie: $this->energie"."\r\n";
            sleep(1);
            return true;

        } else {

            echo "=> Nicht genügend Energie!"."\r\n"."\r\n";
            sleep(1);
            return false;

        }

    }

    function kostenSchlagen() {

        $this->kostenSchlagen = $this->arme * 2;

        if ($this->kostenSchlagen <= $this->energie) {

            $this->energie = $this->energie - $this->kostenSchlagen;
            return true;

        } else {

            echo "=> Nicht genügend Energie"."\r\n"."\r\n";
            sleep(1);
            return false;

        }

    }

    function machSchaden() {

        $this->machSchaden = $this->arme * 5;

    }

    function bekommeSchaden($schaden) {

        if ($this->energieRegeneration == true) {

            $this->bekommeSchaden = ($schaden - (($schaden / 100) * ($this->schild * 5))) * 2;
            $this->hp = $this->hp - $this->bekommeSchaden;

        } else {

            $this->bekommeSchaden = $schaden - (($schaden / 100) * ($this->schild * 5));
            $this->hp = $this->hp - $this->bekommeSchaden;

        }

    }

    function genArm() {

        if ($this->credits >= 1) {

            $this->credits = $this->credits - 1;
            $this->arme = $this->arme + 1;
            return true;

        } else {

            echo "=> Nicht genügend Credits!"."\r\n"."\r\n";
            sleep(1);
            return false;

        }

    }

    function genSchild() {

        if ($this->credits >= 1) {

            if ($this->schild < 16) {

                $this->credits = $this->credits - 1;
                $this->schild = $this->schild + 1;

                return true;

            } else {

                echo "=> Maximale Anzahl an Schilden erreicht!"."\r\n"."\r\n";
                sleep(1);

                return false;

            }

        } else {

            echo "=> Nicht genügend Credits!"."\r\n"."\r\n";
            sleep(1);
            return false;

        }

    }

    function genCredits() {

        if ($this->credits < 5) {

            $this->creditRegeneration = true;

            return true;

        } else {

            echo "=> Maximale Anzahl an Credits erreicht!"."\r\n"."\r\n";
            sleep(1);

            return false;

        }

    }

    function genEnergie() {

        if ($this->energie <= 400) {

            $this->energieRegeneration = true;

            return true;

        } else {

            echo "=> Maximale Menge an Energie erreicht!"."\r\n"."\r\n";
            sleep(1);
            return false;

        }

    }

    function gutschriftCredits() {

        echo "=> ".$this->spielername." regeneriert 1 Credit"."\r\n";
        sleep(1);
        $this->credits = $this->credits + 1;
        $this->creditRegeneration = false;

    }

    function gutschriftEnergie() {

        echo "=> $this->spielername regeneriert 100 Energie."."\r\n";
        sleep(1);
        $this->energie = $this->energie + 100;
        $this->energieRegeneration = false;

    }

    function rostschaden() {

        $this->ausgesetzteRunden = $this->ausgesetzteRunden + 1;

        if ($this->ausgesetzteRunden > 3) {

            if ($this->energieRegeneration == true) {

                $this->rostschaden = 20;
                $this->hp = $this->hp - $this->rostschaden;

            } else {

                $this->rostschaden = 10;
                $this->hp = $this->hp - $this->rostschaden;

            }

            return true;

        } else {

            return false;

        }

    }

    function resetAussetzen() {

        $this->ausgesetzteRunden = 0;

    }

    function tod() {

        if ($this->hp <= 0) {

            return true;

        } else {

            return false;

        }

    }


}


class spielbrett {

    public $felder;
    public $positionSpieler1;
    public $positionSpieler2;
    public $amZug;
    public $nichtamZug;
    public $robTest1;
    public $robTest2;
    public $spielende = false;
    public $zugRobo;
    public $position;

    function __construct($robTest1, $robTest2, $brettgroesse) {

        $this->robTest1 = $robTest1;
        $this->robTest2 = $robTest2;
        $this->felder = $brettgroesse;

        $this->positionSpieler1 = rand(1, $brettgroesse);
        echo "=> ".$robTest1->spielername. " spawnt auf Feld $this->positionSpieler1."."\r\n";
        sleep(2);

        $this->positionSpieler2 = rand(1, $brettgroesse);
        echo "=> ".$robTest2->spielername. " spawnt auf Feld $this->positionSpieler2."."\r\n"."\r\n";
        sleep(2);

        $range          = range($this->positionSpieler1, $this->positionSpieler2);
        $abstandSpieler = count($range);

        while (($abstandSpieler-1) < 10) {

            echo "Die Roboter sind zu nah beieinander gespawnt."."\r\n";
            sleep(1);
            echo "Neuer Spawnpunkt für Spieler 2 wird genereiert."."\r\n"."\r\n";
            sleep(2);

            $this->positionSpieler2 = rand(1, $brettgroesse);
            $range          = range($this->positionSpieler1, $this->positionSpieler2);
            $abstandSpieler = count($range);

            echo "=> Neuer Spawnpunkt für Spieler 2: Feld $this->positionSpieler2."."\r\n"."\r\n";
            sleep(1);

        }

    }

    function steuerung() {

        echo "Steuerung:"."\r\n"."\r\n";
        echo "Links                 : !bewegen('links','Felder')"."\r\n";
        echo "Rechts                : !bewegen('rechts','Felder')"."\r\n";
        echo "Schlagen              : !schlagen"."\r\n";
        echo "Energie generieren    : !genEnergie"."\r\n";
        echo "Credits generieren    : !genCredits"."\r\n";
        echo "Arme generieren       : !genArm"."\r\n";
        echo "Schilde generieren    : !genSchild"."\r\n";
        echo "Passen                : !passen"."\r\n";
        echo "Stats ansehen         : !stats"."\r\n";
        echo "Hilfe                 : !help"."\r\n"."\r\n"."\r\n";

    }

    function aktion($aktion) {

        switch ($aktion) {

            case "!bewegen":

                $this->bewegen();
                break;

            case "!schlagen":

                $this->schlagen();
                break;

            case "!genEnergie":

                $this->genEnergie();
                break;

            case "!genCredits":

                $this->genCredits();
                break;

            case "!genArm":

                $this->genArm();
                break;

            case "!genSchild":

                $this->genSchild();
                break;

            case "!passen":

                $this->passen();
                break;

            case "!stats":

                sleep(2);
                if ($this->amZug == $this->robTest1->spielername) {

                    $this->robTest1->steckbrief($this->robTest1->spielername);

                } elseif ($this->amZug == $this->robTest2->spielername) {

                    $this->robTest2->steckbrief($this->robTest2->spielername);

                }

                break;

            case "!positionen":

                echo "=> ".$this->robTest1->spielername.": Feld ".$this->positionSpieler1."\r\n";
                echo "=> ".$this->robTest2->spielername.": Feld ".$this->positionSpieler2."\r\n"."\r\n";
                break;

            case "!help":

                sleep(2);
                $this->steuerung();
                break;

        }

    }

    function bewegen() {


        echo "Richtung: ";
        $richtung = trim(fgets(STDIN));
        sleep(1);
        echo "Schritte: ";
        $schritte = trim(fgets(STDIN));
        sleep(1);

        while ($schritte < 1 || $schritte > 3) {

            echo "=> Maximal 1-3 Schritte möglich!"."\r\n"."\r\n";
            sleep(1);
            echo "Schritte: ";
            global $schritte;
            $schritte = trim(fgets(STDIN));

        }

        echo "\r\n";
        sleep(1);
        echo "...Berechne Energieverbrauch..."."\r\n";
        sleep(2);
        echo " ...Checke Energieguthaben..."."\r\n";
        sleep(2);
        echo "     ...Checke Umfeld..."."\r\n"."\r\n";
        sleep(2);

        $this->robZuweisung();


        switch ($richtung) {

            case "links":

                if (($this->position - $schritte) > 0) {

                    if ($this->zugRobo->kostenBewegung($schritte) == true) {

                        echo "=> $this->amZug bewegt sich $schritte Schritte nach $richtung." . "\r\n";
                        sleep(1);

                        echo "=> Neue Position: Feld " . ($this->position - $schritte) . "." . "\r\n";
                        sleep(1);

                        $this->position = $this->position - $schritte;

                        if ($this->zugRobo->spielername == $this->robTest1->spielername) {

                            $this->positionSpieler1 = $this->position;

                        } else {

                            $this->positionSpieler2 = $this->position;

                        }

                        echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                        sleep(2);

                        $this->amZug = $this->nichtamZug;
                        $this->nichtamZug = $this->zugRobo->spielername;

                        echo "=> $this->amZug ist nun am Zug" . "\r\n";
                        sleep(2);

                        $this->zugRobo->resetAussetzen();
                        $this->robZuweisung();

                        if ($this->zugRobo->creditRegeneration == true) {

                            $this->zugRobo->gutschriftCredits();
                            $this->rosten();
                            $this->amZug = $this->nichtamZug;
                            $this->nichtamZug = $this->zugRobo->spielername;

                        } elseif ($this->zugRobo->energieRegeneration == true) {

                            $this->zugRobo->gutschriftEnergie();
                            $this->rosten();
                            $this->amZug = $this->nichtamZug;
                            $this->nichtamZug = $this->zugRobo->spielername;

                        }

                    }

                    break;

                } else {

                    echo "=> Du stehst zu nah am Spielfeldrand!"."\r\n"."\r\n";
                    sleep(1);
                    break;

                }



            case "rechts":

                if (($this->position + $schritte) <= $this->felder) {

                    if ($this->zugRobo->kostenBewegung($schritte) == true) {

                        echo "=> $this->amZug bewegt sich $schritte Schritte nach $richtung." . "\r\n";
                        sleep(1);

                        echo "=> Neue Position: Feld " . ($this->position + $schritte) . "." . "\r\n";
                        sleep(1);

                        $this->position = $this->position + $schritte;

                        if ($this->zugRobo->spielername == $this->robTest1->spielername) {

                            $this->positionSpieler1 = $this->position;

                        } else {

                            $this->positionSpieler2 = $this->position;

                        }

                        echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                        sleep(2);

                        $this->amZug = $this->nichtamZug;
                        $this->nichtamZug = $this->zugRobo->spielername;

                        echo "=> $this->amZug ist nun am Zug" . "\r\n";
                        sleep(2);

                        $this->zugRobo->resetAussetzen();
                        $this->robZuweisung();

                        if ($this->zugRobo->creditRegeneration == true) {

                            $this->zugRobo->gutschriftCredits();
                            $this->rosten();
                            $this->amZug = $this->nichtamZug;
                            $this->nichtamZug = $this->zugRobo->spielername;

                        } elseif ($this->zugRobo->energieRegeneration == true) {

                            $this->zugRobo->gutschriftEnergie();
                            $this->rosten();
                            $this->amZug = $this->nichtamZug;
                            $this->nichtamZug = $this->zugRobo->spielername;

                        }

                    }

                    break;

                } else {

                    echo "=> Du stehst zu nah am Spielfeldrand!"."\r\n"."\r\n";
                    sleep(1);
                    break;

                }

        }

    }

    function schlagen() {

        $range = range($this->positionSpieler1, $this->positionSpieler2);
        $count = count($range);

        if ($this->robTest1->spielername == $this->amZug) {

            $this->zugRobo =& $this->robTest1;
            $opfer =& $this->robTest2;
            $this->nichtamZug = $this->robTest2->spielername;

        } else {

            $this->zugRobo =& $this->robTest2;
            $opfer =& $this->robTest1;
            $this->nichtamZug = $this->robTest1->spielername;

        }

        if ($count == 2) {

            if ($this->zugRobo->kostenSchlagen() == true) {

                echo "=> $this->amZug schlägt mit " . $this->zugRobo->arme . " Armen zu" . "\r\n";
                sleep(1);

                $this->zugRobo->machSchaden();
                $opfer->bekommeSchaden($this->zugRobo->machSchaden);

                echo "=> Kosten: ".$this->zugRobo->kostenSchlagen ." Energie."."\r\n";
                sleep(1);
                echo "=> Verbleibende Energie: ".$this->zugRobo->energie."."."\r\n";
                sleep(1);

                echo "=> " . $this->zugRobo->spielername . " fügt " . $opfer->spielername . " " . $opfer->bekommeSchaden . " Schaden zu." . "\r\n";
                sleep(1);
                echo "=> " . $opfer->spielername . " hat noch " . $opfer->hp . " Lebenspunkte." . "\r\n";
                sleep(1);
                echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                sleep(2);

                if ($opfer->tod() == true) {

                    $this->spielende = true;

                    echo "=> $this->nichtamZug ist gestorben."."\r\n";
                    sleep(1);
                    echo "=> Damit ist das Spiel beendet!"."\r\n"."\r\n";
                    sleep(1);
                    echo "=> Sieger: $this->amZug";
                    exit();

                } else {

                    $this->rosten();
                    $this->spielende();
                    $this->amZug = $this->nichtamZug;
                    $this->nichtamZug = $this->zugRobo->spielername;

                    echo "=> $this->amZug ist nun am Zug" . "\r\n";
                    sleep(2);

                    $this->robZuweisung();

                    $this->checkCreditRegeneration();
                    $this->checkEnergieRegeneration();

                }

            }

        } else {

            if ($this->zugRobo->kostenSchlagen() == true) {

                echo "=> $this->amZug schlägt mit " . $this->zugRobo->arme . " Armen zu" . "\r\n";
                sleep(1);
                echo "=> Der Gegner ist zu weit entfernt!"."\r\n";
                sleep(1);
                echo "=> Kosten: ".$this->zugRobo->kostenSchlagen ." Energie."."\r\n";
                sleep(1);
                echo "=> Verbleibende Energie: ".$this->zugRobo->energie."."."\r\n";
                sleep(1);
                echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                sleep(2);

                $this->rosten();
                $this->spielende();
                $this->amZug = $this->nichtamZug;
                $this->nichtamZug = $this->zugRobo->spielername;

                echo "=> $this->amZug ist nun am Zug" . "\r\n";
                sleep(2);

                $this->robZuweisung();

                $this->checkCreditRegeneration();
                $this->checkEnergieRegeneration();

            }

        }

    }

    function genEnergie() {

        $this->robZuweisung();

        if ($this->zugRobo->genEnergie() == true) {

            sleep(2);
            echo "=> $this->amZug wird in den Standby Modus versetzt."."\r\n";
            sleep(1);
            echo "=> Energie wird aufgeladen und in der nächsten Runde gutgeschrieben."."\r\n";
            sleep(1);
            echo "=> Du erleidest während dieser Phase doppelten Schaden!"."\r\n";
            sleep(1);
            echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
            sleep(2);

            $this->rosten();
            $this->spielende();
            $this->amZug = $this->nichtamZug;
            $this->nichtamZug = $this->zugRobo->spielername;

            echo "=> $this->amZug ist nun am Zug" . "\r\n";
            sleep(2);

            $this->robZuweisung();

            if ($this->zugRobo->energieRegeneration == true) {

                $this->zugRobo->gutschriftEnergie();

                echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                sleep(2);

                $this->rosten();
                $this->spielende();
                $this->amZug = $this->nichtamZug;
                $this->nichtamZug = $this->zugRobo->spielername;

                echo "=> $this->amZug ist nun am Zug" . "\r\n";
                sleep(2);

                $this->robZuweisung();

                if ($this->zugRobo->energieRegeneration == true) {

                    $this->zugRobo->gutschriftEnergie();

                    echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                    sleep(2);

                    $this->rosten();
                    $this->spielende();
                    $this->amZug = $this->nichtamZug;
                    $this->nichtamZug = $this->zugRobo->spielername;

                    echo "=> $this->amZug ist nun am Zug" . "\r\n";
                    sleep(2);

                }

            } elseif ($this->zugRobo->creditRegeneration == true) {

                $this->zugRobo->gutschriftCredits();

                echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                sleep(2);

                $this->rosten();
                $this->spielende();
                $this->amZug = $this->nichtamZug;
                $this->nichtamZug = $this->zugRobo->spielername;

                echo "=> $this->amZug ist nun am Zug" . "\r\n";
                sleep(2);

                $this->robZuweisung();

                if ($this->zugRobo->creditRegeneration == true) {

                    $this->zugRobo->gutschriftCredits();

                    echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                    sleep(2);

                    $this->rosten();
                    $this->spielende();
                    $this->amZug = $this->nichtamZug;
                    $this->nichtamZug = $this->zugRobo->spielername;

                    echo "=> $this->amZug ist nun am Zug" . "\r\n";
                    sleep(2);

                }

            }

        }

    }

    function genCredits() {

        $this->robZuweisung();

        if ($this->zugRobo->genCredits() == true) {

            sleep(2);
            echo "=> $this->amZug wird in den Standby Modus versetzt."."\r\n";
            sleep(1);
            echo "=> Credits werden generiert und in der nächsten Runde gutgeschrieben."."\r\n";
            sleep(1);
            echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
            sleep(2);

            $this->rosten();
            $this->spielende();
            $this->amZug = $this->nichtamZug;
            $this->nichtamZug = $this->zugRobo->spielername;

            echo "=> $this->amZug ist nun am Zug" . "\r\n";
            sleep(2);

            $this->robZuweisung();

            if ($this->zugRobo->creditRegeneration == true) {

                $this->zugRobo->gutschriftCredits();

                echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                sleep(2);

                $this->rosten();
                $this->spielende();
                $this->amZug = $this->nichtamZug;
                $this->nichtamZug = $this->zugRobo->spielername;

                echo "=> $this->amZug ist nun am Zug" . "\r\n";
                sleep(2);

                $this->robZuweisung();

                if ($this->zugRobo->creditRegeneration == true) {

                    $this->zugRobo->gutschriftCredits();

                    echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                    sleep(2);

                    $this->rosten();
                    $this->spielende();
                    $this->amZug = $this->nichtamZug;
                    $this->nichtamZug = $this->zugRobo->spielername;

                    echo "=> $this->amZug ist nun am Zug" . "\r\n";
                    sleep(2);

                }

            } elseif ($this->zugRobo->energieRegeneration == true) {

                $this->zugRobo->gutschriftEnergie();

                echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                sleep(2);

                $this->rosten();
                $this->spielende();
                $this->amZug = $this->nichtamZug;
                $this->nichtamZug = $this->zugRobo->spielername;

                echo "=> $this->amZug ist nun am Zug" . "\r\n";
                sleep(2);

                $this->robZuweisung();

                if ($this->zugRobo->energieRegeneration == true) {

                    $this->zugRobo->gutschriftEnergie();

                    echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
                    sleep(2);

                    $this->rosten();
                    $this->spielende();
                    $this->amZug = $this->nichtamZug;
                    $this->nichtamZug = $this->zugRobo->spielername;

                    echo "=> $this->amZug ist nun am Zug" . "\r\n";
                    sleep(2);

                }

            }

        }

    }

    function genArm() {

        $this->robZuweisung();

        if ($this->zugRobo->genArm() == true) {

            echo "=> " . $this->zugRobo->spielername . " regeneriert 1 Arm." . "\r\n";
            sleep(1);
            echo "=> Kosten                 : 1 Credit" . "\r\n";
            sleep(1);
            echo "=> Verbleibende Credits   : " . $this->zugRobo->credits . "\r\n";
            sleep(1);
            echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
            sleep(2);

            $this->rosten();
            $this->spielende();
            $this->amZug = $this->nichtamZug;
            $this->nichtamZug = $this->zugRobo->spielername;

            echo "=> $this->amZug ist nun am Zug" . "\r\n";
            sleep(2);

            $this->robZuweisung();

            $this->checkCreditRegeneration();
            $this->checkEnergieRegeneration();

        }

    }


    function genSchild() {

        $this->robZuweisung();

        if ($this->zugRobo->genSchild() == true) {

            echo "=> " . $this->zugRobo->spielername . " regeneriert 1 Schild." . "\r\n";
            sleep(1);
            echo "=> Kosten                 : 1 Credit" . "\r\n";
            sleep(1);
            echo "=> Verbleibende Credits   : " . $this->zugRobo->credits . "\r\n";
            sleep(1);
            echo "=> Spielzug beeendet." . "\r\n" . "\r\n";
            sleep(2);

            $this->rosten();
            $this->spielende();
            $this->amZug = $this->nichtamZug;
            $this->nichtamZug = $this->zugRobo->spielername;

            echo "=> $this->amZug ist nun am Zug" . "\r\n";
            sleep(2);

            $this->robZuweisung();

            $this->checkCreditRegeneration();
            $this->checkEnergieRegeneration();

        }

    }

    function passen() {

        $this->robZuweisung();

        echo "=> $this->amZug setzt eine Runde aus."."\r\n";
        sleep(1);
        echo "=> Spielzug beeendet."."\r\n"."\r\n";
        sleep(2);

        $this->rosten();
        $this->spielende();
        $this->amZug = $this->nichtamZug;
        $this->nichtamZug = $this->zugRobo->spielername;

        echo "=> $this->amZug ist nun am Zug"."\r\n";
        sleep(2);

        $this->robZuweisung();

        $this->checkCreditRegeneration();
        $this->checkEnergieRegeneration();

    }

    function robZuweisung() {

        if ($this->robTest1->spielername == $this->amZug) {

            $this->zugRobo =& $this->robTest1;
            $this->nichtamZug = $this->robTest2->spielername;
            $this->position = $this->positionSpieler1;

        } else {

            $this->zugRobo =& $this->robTest2;
            $this->nichtamZug = $this->robTest1->spielername;
            $this->position = $this->positionSpieler2;

        }

    }

    function checkCreditRegeneration() {

        if ($this->zugRobo->creditRegeneration == true) {

            $this->zugRobo->gutschriftCredits();
            echo "=> Spielzug beeendet."."\r\n"."\r\n";
            sleep(2);
            $this->rosten();
            $this->amZug = $this->nichtamZug;
            $this->nichtamZug = $this->zugRobo->spielername;

            echo "=> $this->amZug ist nun am Zug"."\r\n";
            sleep(2);

        }

    }

    function checkEnergieRegeneration() {

        if ($this->zugRobo->energieRegeneration == true) {

            $this->zugRobo->gutschriftEnergie();
            echo "=> Spielzug beeendet."."\r\n"."\r\n";
            sleep(2);
            $this->rosten();
            $this->amZug = $this->nichtamZug;
            $this->nichtamZug = $this->zugRobo->spielername;

            echo "=> $this->amZug ist nun am Zug"."\r\n";
            sleep(2);

        }

    }

    function rosten() {

        if ($this->zugRobo->rostschaden() == true) {

            echo "=> $this->amZug fängt an zu rosten."."\r\n";
            sleep(1);
            echo "=> Rostschaden: ".$this->zugRobo->rostschaden. " Lebenspunkte!"."\r\n";
            sleep(1);
            echo "=> Verbleibende Lebenspunkte: ".$this->zugRobo->hp."\r\n"."\r\n";
            sleep(1);

        } else {

            echo "=> Achtung! Wenn du dich mehr als 3x hintereinander nicht bewegst, fängst du an zu rosten. (".$this->zugRobo->ausgesetzteRunden."/3)"."\r\n"."\r\n";
            sleep(2);

        }

    }

    function spielende() {

        if ($this->zugRobo->tod() == true) {

            $this->spielende = true;

            echo "=> $this->amZug ist gestorben."."\r\n";
            sleep(1);
            echo "=> Damit ist das Spiel beendet!"."\r\n"."\r\n";
            sleep(1);
            echo "=> Sieger: $this->nichtamZug";
            exit();

        }

    }

}

echo "*****************************"."\r\n";
echo "* Willkommen bei Robo-Wars! *"."\r\n";
echo "*****************************"."\r\n"."\r\n";
sleep(2);

echo "Zuerst müssen Sie sich einen Roboter konfigurieren:"."\r\n"."\r\n";
sleep(2);

echo "Achtung!"."\r\n"."\r\n";

echo "Pro Schild: -5% Schaden | +2 Energieverbrauch/Feld."."\r\n";
echo "Pro Arm: +5 Schaden | +5 Energieverbrauch/Feld"."\r\n"."\r\n";
sleep(2);

echo "Spieler 1, wählen Sie einen Namen: ";
$name1 = trim(fgets(STDIN));
sleep(1);
echo "Spieler 2, wählen Sie einen Namen: ";
$name2 = trim(fgets(STDIN));
sleep(1);
$robTest1 = new testRob($name1);
echo "\r\n";
$robTest2 = new testRob($name2);
echo "\r\n";

echo "Es stehen sich folgende Spieler im Kampf gegenüber:"."\r\n"."\r\n";
sleep(2);

$robTest1->steckbrief($robTest1->spielername);
echo "\r\n";
sleep(2);
$robTest2->steckbrief($robTest2->spielername);
sleep(2);

echo "     ...Spielbrett wird erstellt..."."\r\n";
sleep(3);
echo "...Spieler nehmen Ihre Positionen ein..."."\r\n"."\r\n";
sleep(3);
$brett = new spielbrett($robTest1, $robTest2, 50);

echo "*************"."\r\n";
echo "* Anleitung *"."\r\n";
echo "*************"."\r\n"."\r\n";
sleep(2);

echo "=> Sie können sich pro Zug 1-3 Felder nach links/rechts bewegen."."\r\n";
sleep(2);
echo "=> Sie können zuschlagen sobald der Gegner auf dem Feld neben Ihnen steht."."\r\n";
sleep(2);
echo "=> Sie können Ihren Zug aussetzen."."\r\n";
sleep(2);
echo "=> Sie können Arme/Schilde/Credits/Energie wieder genereieren."."\r\n"."\r\n";
sleep(2);
echo "=> Beachten Sie: Jede Aktion kostet Energie/Credits!"."\r\n"."\r\n";
sleep(2);
echo "  - Bewegung:"."\r\n";
echo "      Grundkosten : 1 Energie"."\r\n";
echo "      Arme        : Anzahl der Arme * 5 Energie/Arm * Anzahl Felder"."\r\n";
echo "      Schilde     : Anzahl der Schilde * 2 Energie/Schild * Anzahl Felder"."\r\n";
sleep(2);
echo "  - Schlagen:"."\r\n";
echo "      Grundkosten : Anzahl der Arme * 5 Energie/Arm"."\r\n";
sleep(2);
echo "  - Schild generieren:"."\r\n";
echo "      Grundkosten : 1 Credit"."\r\n";
sleep(2);
echo "  - Arm generieren:"."\r\n";
echo "      Grundkosten : 1 Credit"."\r\n";
sleep(2);
echo "  - Credits/Energie generieren:"."\r\n";
echo "      Grundkosten : 1 Zug aussetzen"."\r\n"."\r\n";
sleep(2);

function steuerung() {

    echo "Steuerung:"."\r\n"."\r\n";
    echo "Links                 : !bewegen('links','Felder')"."\r\n";
    echo "Rechts                : !bewegen('rechts','Felder')"."\r\n";
    echo "Schlagen              : !schlagen"."\r\n";
    echo "Energie generieren    : !genEnergie"."\r\n";
    echo "Credits generieren    : !genCredits"."\r\n";
    echo "Arme generieren       : !genArm"."\r\n";
    echo "Schilde generieren    : !genSchild"."\r\n";
    echo "Passen                : !passen"."\r\n";
    echo "Stats ansehen         : !stats"."\r\n";
    echo "Positionen            : !positionen"."\r\n";
    echo "Hilfe                 : !help"."\r\n"."\r\n"."\r\n";

}

steuerung();
sleep(5);

echo "Skills:"."\r\n"."\r\n";
echo "Arme  : 5 Schaden pro Arm"."\r\n";
echo "Schild: 5% Schadensreduzierung pro Schild"."\r\n"."\r\n";
sleep(2);

echo "...Das Spiel beginnt!..."."\r\n"."\r\n";
sleep(3);

echo "=> $robTest1->spielername: Feld $brett->positionSpieler1"."\r\n";
sleep(2);
echo "=> $robTest2->spielername: Feld $brett->positionSpieler2"."\r\n"."\r\n";
sleep(2);

echo "Der Würfel entscheidet welcher Spieler anfängt."."\r\n"."\r\n";
sleep(2);

wuerfeln($robTest1, $robTest2, $brett);

while ($brett->spielende == false) {

    spielzug($brett);

}

function spielzug($brett) {

    echo "$brett->amZug, wähle deinen Zug: ";
    $aktion = trim(fgets(STDIN));
    echo "\r\n";
    $brett->aktion($aktion);

}

function wuerfeln($robTest1, $robTest2, $brett) {

    echo "$robTest1->spielername, bitte würfeln (!wurf): ";
    $wurf1 = trim(fgets(STDIN));
    $wurf1Zahl = rand(1,6);
    sleep(2);

    while ($wurf1 != "!wurf") {

        echo "$robTest1->spielername, bitte würfeln (!wurf): ";
        $wurf1 = trim(fgets(STDIN));

    }

    echo "=> $robTest1->spielername würfelt eine $wurf1Zahl!"."\r\n"."\r\n";

    echo "$robTest2->spielername, bitte würfeln (!wurf): ";
    $wurf2 = trim(fgets(STDIN));
    $wurf2Zahl = rand(1,6);
    sleep(2);

    while ($wurf2 != "!wurf") {

        echo "$robTest2->spielername, bitte würfeln (!wurf): ";
        $wurf2 = trim(fgets(STDIN));

    }

    echo "=> $robTest2->spielername würfelt eine $wurf2Zahl!"."\r\n"."\r\n";

    while ($wurf1Zahl == $wurf2Zahl) {

        echo "=> Die Zahlen sind gleich!"."\r\n";
        echo "=> Es wird erneut gewürfelt"."\r\n"."\r\n";

        echo "$robTest1->spielername, bitte würfeln (!wurf): ";
        $wurf1 = trim(fgets(STDIN));
        $wurf1Zahl = rand(1,6);
        sleep(2);

        while ($wurf1 != "!wurf") {

            echo "$robTest1->spielername, bitte würfeln (!wurf): ";
            $wurf1 = trim(fgets(STDIN));

        }

        echo "=> $robTest1->spielername würfelt eine $wurf1Zahl!"."\r\n"."\r\n";

        echo "$robTest2->spielername, bitte würfeln (!wurf): ";
        $wurf2 = trim(fgets(STDIN));
        $wurf2Zahl = rand(1,6);
        sleep(2);

        while ($wurf2 != "!wurf") {

            echo "$robTest2->spielername, bitte würfeln (!wurf): ";
            $wurf2 = trim(fgets(STDIN));

        }

        echo "=> $robTest2->spielername würfelt eine $wurf2Zahl!"."\r\n"."\r\n";

    }

    if ($wurf1Zahl > $wurf2Zahl) {

        $brett->amZug = $robTest1->spielername;
        $brett->nichtamZug = $robTest2->spielername;
        echo "=> $brett->amZug beginnt"."\r\n";
        sleep(2);

    } elseif ($wurf1Zahl < $wurf2Zahl) {

        $brett->amZug = $robTest2->spielername;
        $brett->nichtamZug = $robTest1->spielername;
        echo "=> $brett->amZug beginnt"."\r\n";
        sleep(2);

    }

}
