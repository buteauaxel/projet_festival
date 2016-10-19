<?php
namespace modele\dao;

use modele\metier\Attribution;
use PDO;

class AttributionDAO implements IDAO {


    protected static function enregVersMetier($enreg) {
        $idEtab = $enreg['IDETAB'];
        $typeChambre = $enreg['IDTYPECHAMBRE'];
        $idGroupe = $enreg['IDGROUPE'];
        $nbChambres = $enreg['NOMBRECHAMBRES'];
        $uneAttribution = new Attribution($idEtab, $typeChambre, $idGroupe, $nbChambres);

        return $uneAttribution;
    }
    
    public static function getAll() {
        $lesObjets = array();
        $requete = "SELECT * FROM Attribution";
        $stmt = Bdd::getPdo()->prepare($requete);
        $ok = $stmt->execute();
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesObjets[] = self::enregVersMetier($enreg);
            }
        }
        return $lesObjets;
    }
    
    public static function getOneById($id) {
        $objetConstruit = null;
        $requete = "SELECT * FROM Attribution WHERE ID = :id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        // attention, $ok = true pour un select ne retournant aucune ligne
        if ($ok && $stmt->rowCount() > 0) {
            $objetConstruit = self::enregVersMetier($stmt->fetch(PDO::FETCH_ASSOC));
        }
        return $objetConstruit;
    }
    
     public static function insert($objet) {
        return false;
    }

    public static function update($id, $objet) {
        return false;
    }
    
    public static function delete($id) {
        return false;
    }
    
    public static function getAllByEtablissement($idEtab) {
        $lesGroupes = array();
        $requete = "SELECT * FROM Groupe
                    WHERE ID IN (
                    SELECT DISTINCT ID FROM Groupe g
                            INNER JOIN Attribution a ON a.IDGROUPE = g.ID 
                            WHERE IDETAB=:id
                    )";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $idEtab);
        $ok = $stmt->execute();
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesGroupes[] = self::enregVersMetier($enreg);
            }
        } 
        return $lesGroupes;
    }
    
    public static function getAllToHost() {
        $lesGroupes = array();
        $requete = "SELECT * FROM Groupe WHERE HEBERGEMENT='O' ORDER BY ID";
        $stmt = Bdd::getPdo()->prepare($requete);
        $ok = $stmt->execute();
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesGroupes[] = self::enregVersMetier($enreg);
            }
        }
        return $lesGroupes;
    }
    
}

