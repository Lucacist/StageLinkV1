<?php
class EntrepriseModel {
    private $db;
    
    public function __construct() {
        require_once ROOT_PATH . '/src/Models/Database.php';
        $this->db = Database::getInstance();
    }

    public function countAllEntreprises() {
        $sql = "SELECT COUNT(*) as total FROM Entreprises";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function getEntreprisesWithPaginationAndRatings($limit, $offset, $userId = null) {
        $sql = "SELECT e.*, 
                COALESCE(AVG(ev.note), 0) as note_moyenne,
                COUNT(ev.id) as nombre_avis,
                user_eval.note as user_note
                FROM Entreprises e
                LEFT JOIN Evaluations ev ON e.id = ev.entreprise_id
                LEFT JOIN (
                    SELECT entreprise_id, note 
                    FROM Evaluations 
                    WHERE utilisateur_id = :userId
                ) user_eval ON e.id = user_eval.entreprise_id
                GROUP BY e.id
                ORDER BY e.nom
                LIMIT :offset, :limit";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll();
    }

    
    public function getAllEntreprises() {
        $sql = "SELECT * FROM Entreprises ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getAllEntreprisesWithRatings($userId = null) {
        $sql = "SELECT e.*, 
                COALESCE(AVG(ev.note), 0) as note_moyenne,
                COUNT(ev.id) as nombre_avis,
                user_eval.note as user_note
                FROM Entreprises e
                LEFT JOIN Evaluations ev ON e.id = ev.entreprise_id
                LEFT JOIN (
                    SELECT entreprise_id, note 
                    FROM Evaluations 
                    WHERE utilisateur_id = :userId
                ) user_eval ON e.id = user_eval.entreprise_id
                GROUP BY e.id
                ORDER BY e.nom";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getEntrepriseById($id) {
        $sql = "SELECT * FROM Entreprises WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch() ?: null;
    }
    
    public function getEntrepriseWithRatingsById($id, $userId = null) {
        $sql = "SELECT e.*, 
                COALESCE(AVG(ev.note), 0) as note_moyenne,
                COUNT(ev.id) as nombre_evaluations,
                user_eval.note IS NOT NULL as user_has_rated,
                user_eval.note as user_note
                FROM Entreprises e
                LEFT JOIN Evaluations ev ON e.id = ev.entreprise_id
                LEFT JOIN (
                    SELECT entreprise_id, note 
                    FROM Evaluations 
                    WHERE utilisateur_id = :userId
                ) user_eval ON e.id = user_eval.entreprise_id
                WHERE e.id = :id
                GROUP BY e.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch() ?: null;
    }
    
    public function createEntreprise($nom, $description, $email, $telephone) {
        $sql = "SELECT COUNT(*) as count FROM Entreprises WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        
        if ($row['count'] > 0) {
            return false;
        }
        
        $sql = "INSERT INTO Entreprises (nom, description, email, telephone) 
                VALUES (:nom, :description, :email, :telephone)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    public function updateEntreprise($id, $nom, $description, $email, $telephone) {
        $checkSql = "SELECT id FROM Entreprises WHERE email = :email AND id != :id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            return false;
        }
        
        $sql = "UPDATE Entreprises 
                SET nom = :nom, description = :description, email = :email, telephone = :telephone 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function deleteEntreprise($id) {
        $this->db->getConnection()->beginTransaction();
        
        try {
            $checkSql = "SELECT id FROM Entreprises WHERE id = :id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() === 0) {
                error_log("Tentative de suppression d'une entreprise inexistante (ID: $id)");
                return false;
            }
            
            $sqlEvaluations = "DELETE FROM Evaluations WHERE entreprise_id = :id";
            $stmtEvaluations = $this->db->prepare($sqlEvaluations);
            $stmtEvaluations->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtEvaluations->execute();
            error_log("Évaluations supprimées pour l'entreprise ID: $id");
            
            $sqlCandidatures = "DELETE FROM Candidatures WHERE offre_id IN (SELECT id FROM Offres WHERE entreprise_id = :id)";
            $stmtCandidatures = $this->db->prepare($sqlCandidatures);
            $stmtCandidatures->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtCandidatures->execute();
            error_log("Candidatures supprimées pour l'entreprise ID: $id");
            
            $sqlOffres = "DELETE FROM Offres WHERE entreprise_id = :id";
            $stmtOffres = $this->db->prepare($sqlOffres);
            $stmtOffres->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtOffres->execute();
            error_log("Offres supprimées pour l'entreprise ID: $id");
            
            $sqlEntreprise = "DELETE FROM Entreprises WHERE id = :id";
            $stmtEntreprise = $this->db->prepare($sqlEntreprise);
            $stmtEntreprise->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmtEntreprise->execute();
            
            if (!$result) {
                error_log("Erreur lors de la suppression de l'entreprise ID: $id - " . $this->db->getConnection()->errorInfo()[2]);
                $this->db->getConnection()->rollBack();
                return false;
            }
            
            $this->db->getConnection()->commit();
            error_log("Entreprise ID: $id supprimée avec succès");
            return true;
        } catch (PDOException $e) {
            error_log("Exception lors de la suppression de l'entreprise ID: $id - " . $e->getMessage());
            $this->db->getConnection()->rollBack();
            return false;
        }
    }
    
    public function rateEntreprise($entrepriseId, $utilisateurId, $note)
    {
        try {
            $sql = "SELECT id FROM Evaluations WHERE entreprise_id = :entrepriseId AND utilisateur_id = :utilisateurId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':entrepriseId', $entrepriseId, PDO::PARAM_INT);
            $stmt->bindParam(':utilisateurId', $utilisateurId, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $noteId = $row['id'];
                $sql = "UPDATE Evaluations SET note = :note WHERE id = :noteId";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':note', $note, PDO::PARAM_INT);
                $stmt->bindParam(':noteId', $noteId, PDO::PARAM_INT);
                return $stmt->execute();
            } else {
                $sql = "INSERT INTO Evaluations (entreprise_id, utilisateur_id, note) VALUES (:entrepriseId, :utilisateurId, :note)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':entrepriseId', $entrepriseId, PDO::PARAM_INT);
                $stmt->bindParam(':utilisateurId', $utilisateurId, PDO::PARAM_INT);
                $stmt->bindParam(':note', $note, PDO::PARAM_INT);
                return $stmt->execute();
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getEvaluations($entrepriseId) {
        // Requête SQL simplifiée avec alias clairs
        $sql = "SELECT 
                ev.id as id, 
                ev.entreprise_id, 
                ev.utilisateur_id, 
                ev.note, 
                u.prenom, 
                u.nom
                FROM Evaluations ev
                JOIN Utilisateurs u ON ev.utilisateur_id = u.id
                WHERE ev.entreprise_id = :entrepriseId
                ORDER BY ev.id DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':entrepriseId', $entrepriseId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOffresEntreprise($entrepriseId) {
        $sql = "SELECT o.*, 
                COUNT(c.id) as nombre_candidatures 
                FROM Offres o 
                LEFT JOIN Candidatures c ON o.id = c.offre_id
                WHERE o.entreprise_id = :entrepriseId
                GROUP BY o.id
                ORDER BY o.date_debut DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':entrepriseId', $entrepriseId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getTotalCandidaturesEntreprise($entrepriseId) {
        $sql = "SELECT COUNT(c.id) as total_candidatures
                FROM Candidatures c
                JOIN Offres o ON c.offre_id = o.id
                WHERE o.entreprise_id = :entrepriseId";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':entrepriseId', $entrepriseId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        
        return $row ? $row['total_candidatures'] : 0;
    }
}
