<?php

class User {

    private $db;
    private $insert;
    private $connect;
    private $select;
        private $selectDisabled;

    private $selectById;
    private $update;
    private $delete;
    private $unsubscribe;
    private $activate;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO User(nicknameUser, passwordUser, idRole) VALUES(:nicknameUser, :passwordUser, :idRole)");
        $this->connect = $db->prepare("SELECT idUser, nicknameUser, idRole, activated, passwordUser FROM User WHERE nicknameUser=:nicknameUser");
        $this->select = $db->prepare("SELECT idUser, nicknameUser, labelRole, u.idRole FROM User u "
                . "INNER JOIN Role r 
                ON r.idRole = u.idRole
                WHERE u.activated = 1
                AND u.idRole = 1
                ORDER BY nicknameUser");
        $this->selectDisabled = $db->prepare("SELECT idUser, nicknameUser, labelRole, u.idRole FROM User u "
                . "INNER JOIN Role r 
                ON r.idRole = u.idRole
                WHERE u.activated = 0
                AND u.idRole = 1
                ORDER BY nicknameUser");
        $this->selectById = $db->prepare("SELECT idUser, nicknameUser, labelRole FROM User u "
                . "INNER JOIN Role r ON r.idRole = u.idRole
                WHERE idUser = :idUser
                ORDER BY nicknameUser");
        $this->update = $db->prepare("UPDATE User SET nicknameUser = :nicknameUser, passwordUser = :passwordUser"
                . "WHERE idUser = :idUser");
        $this->delete = $db->prepare("UPDATE User SET activated = 0 WHERE idUser=:idUser");
        $this->activate = $db->prepare("UPDATE User SET activated = 1 WHERE idUser=:idUser");
        $this->unsubscribe = $db->prepare("DELETE FROM User WHERE idUser=:idUser");
    }

    public function insert($nicknameUser, $passwordUser, $idRole) {
        $r = true;
        $this->insert->execute(array(':nicknameUser' => $nicknameUser, ':passwordUser' => $passwordUser, ':idRole' => $idRole));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function connect($nicknameUser) {
        $aUser = $this->connect->execute(array(':nicknameUser' => $nicknameUser));
        if ($this->connect->errorCode() != 0) {
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }

    public function selectDisabled() {
        $this->selectDisabled->execute();
        if ($this->selectDisabled->errorCode() != 0) {
            print_r($this->selectDisabled->errorInfo());
        }
        return $this->selectDisabled->fetchAll();
    }
    
        public function select() {
        $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($idUser) {
        $this->selectById->execute(array(':idUser' => $idUser));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($idUser, $nicknameUser, $passwordUser) {
        $r = true;
        $this->update->execute(array(':idUser' => $idUser, ':nicknameUser' => $nicknameUser, ':passwordUser' => $passwordUser));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idUser) {
        $r = true;
        $this->delete->execute(array(':idUser' => $idUser));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function activate($idUser) {
        $r = true;
        $this->activate->execute(array(':idUser' => $idUser));
        if ($this->activate->errorCode() != 0) {
            print_r($this->activate->errorInfo());
            $r = false;
        }
        return $r;
    }

        public function unsubscribe($idUser) {
        $r = true;
        $this->unsubscribe->execute(array(':idUser' => $idUser));
        if ($this->unsubscribe->errorCode() != 0) {
            print_r($this->unsubscribe->errorInfo());
            $r = false;
        }
        return $r;
    }

    
}
?>

