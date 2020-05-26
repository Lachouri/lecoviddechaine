<?php

class Faq {

    private $db;
    private $insert;
    private $selectFaq;
    private $selectById;
    private $selectAllFaq;
    private $selectAllDisabled;
    private $delete;
    private $activate;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO Faq(titleFaq,answerFaq,idCategory) VALUES(:titleFaq,:answerFaq,:idCategory)");
        $this->selectAllFaq = $db->prepare("SELECT f.idFaq, f.titleFaq, f.answerFaq, f.activated, c.idCategory, c.labelCategory
                                      FROM Faq f 
                                      INNER JOIN Category c ON c.idCategory=f.idCategory
                                      WHERE f.activated = 1
                                       ORDER BY f.idCategory");
        $this->selectAllDisabled = $db->prepare("SELECT f.idFaq, f.titleFaq, f.answerFaq, f.activated,  c.idCategory, c.labelCategory
                                      FROM Faq f 
                                      INNER JOIN Category c ON c.idCategory=f.idCategory
                                      WHERE f.activated = 0
                                       ORDER BY f.idCategory");
        $this->selectById = $db->prepare("SELECT idFaq, titleFaq, answerFaq, f.idCategory, c.labelCategory
                FROM Faq f INNER JOIN Category c ON c.idCategory = f.idCategory WHERE idFaq = :idFaq");
        $this->selectFaq = $db->prepare("SELECT f.idFaq, f.titleFaq, f.answerFaq, f.activated, c.labelCategory
            FROM Faq f
            INNER JOIN Category c ON c.idCategory = f.idCategory
            WHERE c.idCategory = :idCategory
            AND f.activated = 1");
        $this->update = $db->prepare("UPDATE Faq SET idFaq =:idFaq, titleFaq =:titleFaq, answerFaq = :answerFaq, idCategory = :idCategory
              WHERE idFaq = :idFaq");
        $this->delete = $db->prepare("UPDATE Faq SET activated = 0 WHERE idFaq = :idFaq");
        $this->activate = $db->prepare("BEGIN;
            UPDATE Faq SET activated = 1 WHERE idFaq =:idFaq;
            UPDATE Category SET activated = 1 WHERE idCategory=:idCategory;
            COMMIT;");
    }

    public function insert($titleFaq, $answerFaq, $idCategory) {
        $r = true;
        $this->insert->execute(array(':titleFaq' => $titleFaq, ':answerFaq' => $answerFaq, ':idCategory' => $idCategory));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectAllFaq() {
        $this->selectAllFaq->execute();
        if ($this->selectAllFaq->errorCode() != 0) {
            print_r($this->selectAllFaq->errorInfo());
        }
        return $this->selectAllFaq->fetchAll();
    }
    
        public function selectAllDisabled() {
        $this->selectAllDisabled->execute();
        if ($this->selectAllDisabled->errorCode() != 0) {
            print_r($this->selectAllDisabled->errorInfo());
        }
        return $this->selectAllDisabled->fetchAll();
    }

    public function selectFaq($idCategory) {
        $this->selectFaq->execute(array(':idCategory' => $idCategory));
        if ($this->selectFaq->errorCode() != 0) {
            print_r($this->selectFaq->errorInfo());
        }
        return $this->selectFaq->fetchAll();
    }

    public function selectById($idFaq) {
        $this->selectById->execute(array(':idFaq' => $idFaq));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function delete($idFaq) {
        $r = true;
        $this->delete->execute(array(':idFaq' => $idFaq));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function activate($idFaq, $idCategory) {
        $r = true;
        $this->activate->execute(array(':idFaq' => $idFaq, ':idCategory' => $idCategory));
        if ($this->activate->errorCode() != 0) {
            print_r($this->activate->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function update($idFaq, $titleFaq, $answerFaq, $idCategory) {
        $r = true;
        $this->update->execute(array(':idFaq' => $idFaq, ':titleFaq' => $titleFaq, ':answerFaq' => $answerFaq, ':idCategory' => $idCategory));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

}
