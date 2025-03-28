<?php
class EntrepriseModel {
    private $db;
    
    public function __construct() {
        require_once ROOT_PATH . '/src/Models/Database.php';
        $this->db = Database::getInstance();
    }

    public function countAllEntreprises() {
        $sql = "SELECT COUNT(*) as total FROM Entreprises";
        $stmt = $this->db->query($sql);
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
                    WHERE utilisateur_id = ?
                ) user_eval ON e.id = user_eval.entreprise_id
                GROUP BY e.id
                ORDER BY e.nom
                LIMIT ?, ?";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $offset, $limit]);
    
        return $stmt->fetchAll();
    }

    
    public function getAllEntreprises() {
        $sql = "SELECT * FROM Entreprises ORDER BY nom";
        $stmt = $this->db->query($sql);
        
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
                    WHERE utilisateur_id = ?
                ) user_eval ON e.id = user_eval.entreprise_id
                GROUP BY e.id
                ORDER BY e.nom";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll();
    }
    
    public function getEntrepriseById($id) {
        $sql = "SELECT * FROM Entreprises WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
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
                    WHERE utilisateur_id = ?
                ) user_eval ON e.id = user_eval.entreprise_id
                WHERE e.id = ?
                GROUP BY e.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $id]);
        
        return $stmt->fetch() ?: null;
    }
    
    public function createEntreprise($nom, $description, $email, $telephone) {
        $sql = "SELECT COUNT(*) as count FROM Entreprises WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        
        if ($row['count'] > 0) {
            return false;
        }
        
        $sql = "INSERT INTO Entreprises (nom, description, email, telephone) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $description, $email, $telephone]);
    }
    
    public function updateEntreprise($id, $nom, $description, $email, $telephone) {
        $checkSql = "SELECT id FROM Entreprises WHERE email = ? AND id != ?";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([$email, $id]);
        
        if ($checkStmt->rowCount() > 0) {
            return false;
        }
        
        $sql = "UPDATE Entreprises 
                SET nom = ?, description = ?, email = ?, telephone = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nom, $description, $email, $telephone, $id]);
    }
    
    public function deleteEntreprise($id) {
        $this->db->getConnection()->beginTransaction();
        
        try {
            $checkSql = "SELECT id FROM Entreprises WHERE id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$id]);
            
            if ($checkStmt->rowCount() === 0) {
                error_log("Tentative de suppression d'une entreprise inexistante (ID: $id)");
                return false;
            }
            
            $sqlEvaluations = "DELETE FROM Evaluations WHERE entreprise_id = ?";
            $stmtEvaluations = $this->db->prepare($sqlEvaluations);
            $stmtEvaluations->execute([$id]);
            error_log("Évaluations supprimées pour l'entreprise ID: $id");
            
            $sqlCandidatures = "DELETE FROM Candidatures WHERE offre_id IN (SELECT id FROM Offres WHERE entreprise_id = ?)";
            $stmtCandidatures = $this->db->prepare($sqlCandidatures);
            $stmtCandidatures->execute([$id]);
            error_log("Candidatures supprimées pour l'entreprise ID: $id");
            
            $sqlOffres = "DELETE FROM Offres WHERE entreprise_id = ?";
            $stmtOffres = $this->db->prepare($sqlOffres);
            $stmtOffres->execute([$id]);
            error_log("Offres supprimées pour l'entreprise ID: $id");
            
            $sqlEntreprise = "DELETE FROM Entreprises WHERE id = ?";
            $stmtEntreprise = $this->db->prepare($sqlEntreprise);
            $result = $stmtEntreprise->execute([$id]);
            
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
            $sql = "SELECT id FROM Evaluations WHERE entreprise_id = ? AND utilisateur_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$entrepriseId, $utilisateurId]);
            
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $noteId = $row['id'];
                $sql = "UPDATE Evaluations SET note = ? WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$note, $noteId]);
            } else {
                $sql = "INSERT INTO Evaluations (entreprise_id, utilisateur_id, note) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$entrepriseId, $utilisateurId, $note]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getEvaluations($entrepriseId) {
        $sql = "SELECT ev.*, u.prenom, u.nom
                FROM Evaluations ev
                JOIN Utilisateurs u ON ev.utilisateur_id = u.id
                WHERE ev.entreprise_id = ?
                ORDER BY ev.id DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$entrepriseId]);
        
        return $stmt->fetchAll();
    }
    
    public function getOffresEntreprise($entrepriseId) {
        $sql = "SELECT o.*, 
                COUNT(c.id) as nombre_candidatures 
                FROM Offres o 
                LEFT JOIN Candidatures c ON o.id = c.offre_id
                WHERE o.entreprise_id = ?
                GROUP BY o.id
                ORDER BY o.date_debut DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$entrepriseId]);
        
        return $stmt->fetchAll();
    }
    
    public function getTotalCandidaturesEntreprise($entrepriseId) {
        $sql = "SELECT COUNT(c.id) as total_candidatures
                FROM Candidatures c
                JOIN Offres o ON c.offre_id = o.id
                WHERE o.entreprise_id = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$entrepriseId]);
        $row = $stmt->fetch();
        
        return $row ? $row['total_candidatures'] : 0;
    }
}
