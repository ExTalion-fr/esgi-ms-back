<?php
class Pins {
    private $connexion;

    public $pinsId;
    public $pinsName;
    public $pinsCitation;
    public $pinsUserId;
    public $pinsCreatedAt;

    public function __construct($db) {
        $this->connexion = $db;
    }

    public function time_to_str($time,$precision=6){
        if($time=abs(intval($time))) {
            $s=['an'=>31556926,'mois'=>2629743,'semaine'=>604800,'jour'=>86400,'heure'=>3600,'minute'=>60,'seconde'=>1];
            // $s=['a'=>31556926,'m'=>2629743,'s'=>604800,'j'=>86400,'h'=>3600,'min'=>60];
            $d=0;
            foreach($s as $a=>$b) {
                if($time>=$b && $c=$time/$b) {
                    $c=intval($c);
                    $time-=$b*$c;
                    // $r[]="$c $a".($c>1?($a=='mois'?'':'s'):'');
                    $r[]="$c $a";
                    if(++$d==$precision) {
                        break;
                    }
                }
            }
            if (count($r) == 1) {
                return $r[0];
            } else {
                $aaa = array_slice($r, 0, -1);
                $yyy = array_slice($r, -1, 1);
                $bbb = array_shift($yyy);
                return implode(' ', $aaa) . ' et ' . $bbb;
            }
            return count($r)==1?$r[0]:(implode(' ',array_slice($r,0,-1)).' et '.array_shift(array_slice($r,-1,1)));
        }
        return 'Aucun temps';
    }

    public function getAllPins() {
        $sql = "SELECT * FROM pins ORDER BY pinsId ASC";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }

    public function getPinsById() {
        $sql = "SELECT * FROM pins WHERE pinsId = ? LIMIT 0,1";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(1, $this->pinsId);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $this->pinsName = $row['pinsName'];
        $this->pinsCitation = $row['pinsCitation'];
        $this->pinsUserId = $row['pinsUserId'];
        $this->pinsCreatedAt = $row['pinsCreatedAt'];
    }

    public function editPins($key, $value, $pinsId) {
        $sql = 'UPDATE pins SET ' . $key . '="' . $value . '" WHERE pinsId = :pinsId';
        $query = $this->connexion->prepare($sql);
        $query->bindParam(':pinsId', $pinsId);
        if($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function addPins($pinsName, $pinsCitation, $pinsUserId) {
        $sql = 'INSERT INTO pins (`pinsName`, `pinsCitation`, `pinsUserId`) VALUES(?, ?, ?)';
        $query = $this->connexion->prepare($sql);
        $query->execute(array($pinsName, $pinsCitation, $pinsUserId));
        $this->pinsId = $this->lastInsertId();
        $this->pinsName = $pinsName;
        $this->pinsCitation = $pinsCitation;
        $this->pinsUserId = $pinsUserId;
        $this->pinsCreatedAt = new DateTime('now', new DateTimeZone('Europe/Paris'));
        return [
            "id" => $this->pinsId,
            "name" => $this->pinsName,
            "citation" => $this->pinsCitation,
            "userId" => $this->pinsUserId,
            "createdAt" => ""
        ];
    }

    public function removePins($pinsId) {
        $sql = 'DELETE FROM pins WHERE pinsId = :pinsId';
        $query = $this->connexion->prepare($sql);
        $query->bindParam(':pinsId', $pinsId);
        $query->execute();
    }

    public function lastInsertId() {
        $sql = "SELECT pinsId FROM pins ORDER BY pinsId DESC LIMIT 1";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['pinsId'];
    }

}