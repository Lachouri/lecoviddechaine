<?php

class Category {

    private $db;
    private $insert;
    private $selectCategory;
        private $selectDisabledCategory;

    private $selectCategoryArticle;
    private $selectById;
    private $delete;
    private $update;
    private $activate;

    public function __construct($db) {
        $this->db = $db;

        $this->insert = $db->prepare("INSERT INTO Category(labelCategory) VALUES(:labelCategory)");

        $this->selectCategory = $db->prepare("SELECT idCategory, labelCategory FROM Category WHERE activated = 1");
        $this->selectDisabledCategory = $db->prepare("SELECT idCategory, labelCategory FROM Category WHERE activated = 0");

        $this->selectById = $db->prepare("SELECT idCategory, labelCategory FROM Category WHERE idCategory =:idCategory ");

        $this->selectCategoryArticle = $db->prepare("SELECT c.idCategory, idArticle, titleArticle, headlineArticle, contentArticle,
                                                            dateArticle, sourceArticle, imageArticle, 
                                                            c.labelCategory, u.nicknameUser 
                                      FROM Article a 
                                      INNER JOIN Category c ON c.idCategory=a.idCategory 
                                      INNER JOIN User u ON u.idUser=a.idUser 
                                      WHERE c.idCategory =:idCategory
                                      AND a.activated = 1
                                      ORDER BY dateArticle desc");

        $this->update = $db->prepare("UPDATE Category SET labelCategory=:labelCategory
           WHERE idCategory=:idCategory");

        $this->delete = $db->prepare("BEGIN;
            UPDATE Article SET activated = 0 WHERE idCategory =:idCategory;
        UPDATE Category SET activated = 0 WHERE idCategory =:idCategory;
        UPDATE Faq SET activated = 0 WHERE idCategory  =:idCategory;
        COMMIT;");

        $this->activate = $db->prepare("BEGIN;
            UPDATE Article SET activated = 1 WHERE idCategory =:idCategory;
            UPDATE Category SET activated = 1 WHERE idCategory =:idCategory;
             UPDATE Faq SET activated = 1 WHERE idCategory =:idCategory;
            COMMIT;");
    }

    public function insert($labelCategory) {
        $r = true;
        $this->insert->execute(array(':labelCategory' => $labelCategory));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectDisabledCategory() {
        $this->selectDisabledCategory->execute();
        if ($this->selectDisabledCategory->errorCode() != 0) {
            print_r($this->selectDisabledCategory->errorInfo());
        }
        return $this->selectDisabledCategory->fetchAll();
    }
    
        public function selectCategory() {
        $this->selectCategory->execute();
        if ($this->selectCategory->errorCode() != 0) {
            print_r($this->selectCategory->errorInfo());
        }
        return $this->selectCategory->fetchAll();
    }

    public function selectCategoryArticle($idCategory) {
        $this->selectCategoryArticle->execute(array(':idCategory' => $idCategory));
        if ($this->selectCategoryArticle->errorCode() != 0) {
            print_r($this->selectCategoryArticle->errorInfo());
        }
        return $this->selectCategoryArticle->fetchAll();
    }

    public function selectById($idCategory) {
        $this->selectById->execute(array(':idCategory' => $idCategory));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function delete($idCategory) {
        $r = true;
        $this->delete->execute(array(':idCategory' => $idCategory));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function activate($idCategory) {
        $r = true;
        $this->activate->execute(array(':idCategory' => $idCategory));
        if ($this->activate->errorCode() != 0) {
            print_r($this->activate->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function update($idCategory, $labelCategory) {
        $r = true;
        $this->update->execute(array(':idCategory' => $idCategory, ':labelCategory' => $labelCategory));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

}
