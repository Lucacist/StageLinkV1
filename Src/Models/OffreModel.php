<?php
class OffreModel {
    private $db;
    
    public function __construct() {
        require_once ROOT_PATH . '/src/Models/Database.php';
        $this->db = Database::getInstance();
    }
    
    public function getAllOffres() {
        $sql = "SELECT o.*, e.nom as entreprise_nom 
                FROM Offres o
                JOIN Entreprises e ON o.entreprise_id = e.id
                ORDER BY o.date_debut DESC";
        $stmt = $this->db->query($sql);
        $stmt->execute();
        
        $offres = [];
        while ($row = $stmt->fetch()) {
            $row['competences'] = $this->getOffreCompetences($row['id']);
            $offres[] = $row;
        }
        
        return $offres;
    }
    
    public function getOffreById($id) {
        $sql = "SELECT o.*, 
                e.nom AS entreprise_nom, 
                e.email AS entreprise_email,
                e.telephone AS entreprise_telephone,
                COUNT(c.id) as nombre_candidatures
                FROM Offres o
                JOIN Entreprises e ON o.entreprise_id = e.id
                LEFT JOIN Candidatures c ON o.id = c.offre_id
                WHERE o.id = ?
                GROUP BY o.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        $row = $stmt->fetch();
        if ($row) {
            $row['competences'] = $this->getOffreCompetences($id);
            return $row;
        }
        
        return null;
    }
    
    public function createOffre($entrepriseId, $titre, $description, $baseRemuneration, $dateDebut, $dateFin, $competences = []) {
        $sql = "INSERT INTO Offres (entreprise_id, titre, description, base_remuneration, date_debut, date_fin) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        try {
            $stmt->execute([$entrepriseId, $titre, $description, $baseRemuneration, $dateDebut, $dateFin]);
            $offreId = $this->db->getLastInsertId();
            error_log("Offre créée avec l'ID: " . $offreId);
            
            if (!empty($competences) && $offreId) {
                foreach ($competences as $competenceId) {
                    $this->addOffreCompetence($offreId, $competenceId);
                }
            }
            
            return $offreId;
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'offre: " . $e->getMessage());
            return false;
        }
    }
    
    public function addOffreCompetence($offreId, $competenceId) {
        $sql = "INSERT INTO Offres_Competences (offre_id, competence_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        
        try {
            $stmt->execute([$offreId, $competenceId]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de la compétence $competenceId à l'offre $offreId: " . $e->getMessage());
            return false;
        }
    }
    
    public function addOffreCompetences($offreId, $competenceIds) {
        error_log("addOffreCompetences - offreId: $offreId, competenceIds: " . json_encode($competenceIds));
        
        if (empty($competenceIds)) {
            error_log("Aucune compétence à ajouter");
            return true;
        }
        
        $this->deleteOffreCompetences($offreId);
        
        $sql = "INSERT INTO Offres_Competences (offre_id, competence_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Erreur préparation requête: " . $this->db->getConnection()->errorInfo()[2]);
            return false;
        }
        
        $success = true;
        foreach ($competenceIds as $competenceId) {
            error_log("Ajout compétence $competenceId à l'offre $offreId");
            try {
                $stmt->execute([$offreId, $competenceId]);
            } catch (PDOException $e) {
                error_log("Erreur lors de l'ajout de la compétence $competenceId: " . $e->getMessage());
                $success = false;
            }
        }
        
        return $success;
    }
    
    public function deleteOffreCompetences($offreId) {
        $sql = "DELETE FROM Offres_Competences WHERE offre_id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([$offreId]);
    }
    
    public function updateOffre($id, $entrepriseId, $titre, $description, $baseRemuneration, $dateDebut, $dateFin) {
        $sql = "UPDATE Offres SET entreprise_id = ?, titre = ?, description = ?, base_remuneration = ?, date_debut = ?, date_fin = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([$entrepriseId, $titre, $description, $baseRemuneration, $dateDebut, $dateFin, $id]);
    }
    
    public function deleteOffre($id) {
        $sql = "DELETE FROM Offres WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([$id]);
    }
    
    public function getOffreCompetences($offreId) {
        $sql = "SELECT c.* 
                FROM Competences c
                JOIN Offres_Competences oc ON c.id = oc.competence_id
                WHERE oc.offre_id = ?
                ORDER BY c.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$offreId]);
        
        return $stmt->fetchAll();
    }
    
    
    public function isOffreLiked($offreId, $utilisateurId) {
        $sql = "SELECT COUNT(*) as count 
                FROM WishList 
                WHERE offre_id = ? AND utilisateur_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$offreId, $utilisateurId]);
        $row = $stmt->fetch();
        
        return $row['count'] > 0;
    }
    
    public function toggleLike($offreId, $utilisateurId) {
        if ($this->isOffreLiked($offreId, $utilisateurId)) {
            $sql = "DELETE FROM WishList WHERE offre_id = ? AND utilisateur_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$offreId, $utilisateurId]);
        } else {
            $sql = "INSERT INTO WishList (offre_id, utilisateur_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$offreId, $utilisateurId]);
        }
    }

    public function getAllCompetences() {
        $sql = "SELECT * FROM Competences ORDER BY nom ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function countAllOffres() {
        $sql = "SELECT COUNT(*) as total FROM Offres";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
    
    public function getOffresWithPagination($limit, $offset) {
        $sql = "SELECT o.*, e.nom as entreprise_nom 
                FROM Offres o
                JOIN Entreprises e ON o.entreprise_id = e.id
                ORDER BY o.date_debut DESC
                LIMIT :offset, :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $offres = [];
        while ($row = $stmt->fetch()) {
            $row['competences'] = $this->getOffreCompetences($row['id']);
            $offres[] = $row;
        }
        
        return $offres;
    }
    
}
?>