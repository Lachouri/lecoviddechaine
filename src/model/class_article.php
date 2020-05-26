<?php

class Article {

    private $db;
    private $insert;
    private $select;
    private $selectArticle;
    private $selectAllDisabled;
    private $selectAll;
    private $update;
    private $updateWithoutImage;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO Article(titleArticle, headlineArticle, contentArticle, dateArticle, sourceArticle, imageArticle, idCategory, idUser) VALUES(:titleArticle, :headlineArticle,:contentArticle, :dateArticle, :sourceArticle, :imageArticle, :idCategory, :idUser)");
        $this->select = $db->prepare("SELECT idArticle, titleArticle, headlineArticle, contentArticle, dateArticle, sourceArticle, imageArticle,a.activated, c.idCategory, c.labelCategory,c.activated, u.nicknameUser
                                      FROM Article a 
                                      INNER JOIN Category c ON c.idCategory=a.idCategory 
                                      INNER JOIN User u ON u.idUser=a.idUser
                                      WHERE a.activated = 1
                                      AND c.activated = 1
                                      ORDER BY dateArticle DESC LIMIT 3");
        $this->selectAll = $db->prepare("SELECT idArticle, titleArticle, headlineArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.activated ,c.idCategory, c.labelCategory, c.activated, u.nicknameUser FROM Article a 
                                      INNER JOIN Category c ON c.idCategory=a.idCategory 
                                      INNER JOIN User u ON u.idUser=a.idUser 
                                        WHERE a.activated = 1
                                        AND c.activated = 1
                                      ORDER BY dateArticle DESC");
        $this->selectAllDisabled = $db->prepare("SELECT idArticle, titleArticle, headlineArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.activated ,c.idCategory, c.labelCategory, c.activated, u.nicknameUser FROM Article a 
                                      INNER JOIN Category c ON c.idCategory=a.idCategory 
                                      INNER JOIN User u ON u.idUser=a.idUser 
                                        WHERE a.activated = 0
                                      ORDER BY dateArticle DESC");
        $this->selectArticle = $db->prepare("SELECT idArticle, titleArticle, headlineArticle, contentArticle, dateArticle, sourceArticle, imageArticle,c.idCategory,  c.labelCategory, u.nicknameUser FROM Article a 
                                      INNER JOIN Category c ON c.idCategory=a.idCategory 
                                      INNER JOIN User u ON u.idUser=a.idUser
                                      WHERE idArticle=:idArticle");

        $this->update = $db->prepare("UPDATE Article SET titleArticle=:titleArticle,headlineArticle=:headlineArticle, contentArticle=:contentArticle, sourceArticle=:sourceArticle, imageArticle=:imageArticle, idCategory=:idCategory 
                                      WHERE idArticle=:idArticle");

        $this->updateWithoutImage = $db->prepare("UPDATE Article SET titleArticle=:titleArticle,headlineArticle=:headlineArticle, contentArticle=:contentArticle, sourceArticle=:sourceArticle, idCategory=:idCategory 
                                      WHERE idArticle=:idArticle");

        $this->delete = $db->prepare("UPDATE  Article SET activated = 0 WHERE idArticle=:idArticle");

        $this->activate = $db->prepare("BEGIN;
            UPDATE Article SET activated = 1 WHERE idArticle =:idArticle;
            UPDATE Category SET activated = 1 WHERE idCategory=:idCategory;
            COMMIT;");
    }

    public function insert($titleArticle, $headlineArticle, $contentArticle, $dateArticle, $sourceArticle, $imageArticle, $idCategory, $idUser) {
        $r = true;
        $this->insert->execute(array(':titleArticle' => $titleArticle, ':headlineArticle' => $headlineArticle, ':contentArticle' => $contentArticle, ':dateArticle' => $dateArticle, ':sourceArticle' => $sourceArticle, ':imageArticle' => $imageArticle, ':idCategory' => $idCategory, ':idUser' => $idUser));
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

    public function selectAllDisabled() {
        $this->selectAllDisabled->execute();
        if ($this->selectAllDisabled->errorCode() != 0) {
            print_r($this->selectAllDisabled->errorInfo());
        }
        return $this->selectAllDisabled->fetchAll();
    }

    public function selectAll() {
        $this->selectAll->execute();
        if ($this->selectAll->errorCode() != 0) {
            print_r($this->selectAll->errorInfo());
        }
        return $this->selectAll->fetchAll();
    }

    public function selectArticle($idArticle) {
        $this->selectArticle->execute(array(':idArticle' => $idArticle));
        if ($this->selectArticle->errorCode() != 0) {
            print_r($this->selectArticle->errorInfo());
        }
        return $this->selectArticle->fetch();
    }

    public function update($idArticle, $titleArticle, $headlineArticle, $contentArticle, $sourceArticle, $imageArticle, $idCategory) {
        $r = true;
        $this->update->execute(array(':idArticle' => $idArticle, ':titleArticle' => $titleArticle, ':headlineArticle' => $headlineArticle, ':contentArticle' => $contentArticle, ':sourceArticle' => $sourceArticle, ':imageArticle' => $imageArticle, ':idCategory' => $idCategory));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateWithoutImage($idArticle, $titleArticle, $headlineArticle, $contentArticle, $sourceArticle, $idCategory) {
        $r = true;
        $this->updateWithoutImage->execute(array(':idArticle' => $idArticle, ':titleArticle' => $titleArticle, ':headlineArticle' => $headlineArticle, ':contentArticle' => $contentArticle, ':sourceArticle' => $sourceArticle, ':idCategory' => $idCategory));
        if ($this->updateWithoutImage->errorCode() != 0) {
            print_r($this->updateWithoutImage->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function activate($idArticle, $idCategory) {
        $r = true;
        $this->activate->execute(array(':idArticle' => $idArticle, ':idCategory' => $idCategory));
        if ($this->activate->errorCode() != 0) {
            print_r($this->activate->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idArticle) {
        $r = true;
        $this->delete->execute(array(':idArticle' => $idArticle));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
