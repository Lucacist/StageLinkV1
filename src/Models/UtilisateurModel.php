<?php
class utilisateurmodel {
    private $db;
    
    public function __construct() {
        require_once ROOT_PATH . '/Src/Models/Database.php';
        $this->db = database::getInstance();
    }
    
    public function getUserById($userId) {
        $sql = "SELECT * FROM utilisateurs WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetch() ?: null;
    }
    
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        
        $row = $stmt->fetch();
        
        if ($row) {
            if (password_verify($password, $row['mot_de_passe'])) {
                return $row;
            } 
            else if ($password === $row['mot_de_passe']) {
                $this->updatePasswordHash($row['id'], $password);
                return $row;
            }
        }
        
        return null;
    }
    
    public function updatePasswordHash($userId, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$hashedPassword, $userId]);
    }
    
    public function getUserRole($userId) {
        $sql = "SELECT r.code as role_code, r.nom as role_nom
                FROM utilisateurs u
                JOIN roles r ON u.role_id = r.id
                WHERE u.id = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetch() ?: null;
    }
    
    public function getUserPermissions($userId) {
        $sql = "SELECT DISTINCT p.code
                FROM utilisateurs u
                JOIN roles r ON u.role_id = r.id
                JOIN role_permissions rp ON r.id = rp.role_id
                JOIN permissions p ON rp.permission_id = p.id
                WHERE u.id = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        
        $permissions = [];
        while ($row = $stmt->fetch()) {
            $permissions[] = $row['code'];
        }
        
        return $permissions;
    }
    
    public function hasPermission($userId, $permissionCode) {
        $sql = "SELECT COUNT(*) as count 
                FROM utilisateurs u
                JOIN roles r ON u.role_id = r.id
                JOIN role_permissions rp ON r.id = rp.role_id
                JOIN permissions p ON rp.permission_id = p.id
                WHERE u.id = ? AND p.code = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $permissionCode]);
        $row = $stmt->fetch();
        
        return $row['count'] > 0;
    }
    
    public function createUser($nom, $prenom, $email, $mot_de_passe, $role_id) {
        $sql = "SELECT COUNT(*) as count FROM utilisateurs WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        
        if ($row['count'] > 0) {
            return ['success' => false, 'message' => 'Un utilisateur avec cet email existe déjà.'];
        }
        
        $hashedPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        try {
            $stmt->execute([$nom, $prenom, $email, $hashedPassword, $role_id]);
            $insertId = $this->db->getLastInsertId();
            return ['success' => true, 'id' => $insertId];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la création de l\'utilisateur: ' . $e->getMessage()];
        }
    }
    
    public function getAllRoles() {
        $sql = "SELECT * FROM roles";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getRoleIdByCode($roleCode) {
        $sql = "SELECT id FROM roles WHERE code = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roleCode]);
        
        $row = $stmt->fetch();
        return $row ? $row['id'] : null;
    }
}
