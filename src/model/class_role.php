<?php

class Role {

    private $db;
    private $insert;
    private $select;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO Role(labelRole) VALUES(:labelRole)");
        $this->select = $db->prepare("SELECT idRole, labelRole FROM Role");
        $this->delete = $db->prepare("DELETE FROM Role WHERE idRole=:idRole");
    }

    public function insert($labelRole) {
        $r = true;
        $this->insert->execute(array(':labelRole' => $labelRole));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function delete($idRole) {
        $r = true;
        $this->delete->execute(array(':idRole' => $idRole));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
