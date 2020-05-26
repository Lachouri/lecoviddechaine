<?php

function actionArticle($twig, $db) {
    $form = array();
    $article = new Article($db);
    $listeA = $article->selectAll();
    //  $pretty = function($v='',$c="&nbsp;&nbsp;&nbsp;&nbsp;",$in=-1,$k=null)use(&$pretty){$r='';if(in_array(gettype($v),array('object','array'))){$r.=($in!=-1?str_repeat($c,$in):'').(is_null($k)?'':"$k: ").'<br>';foreach($v as $sk=>$vl){$r.=$pretty($vl,$c,$in+1,$sk).'<br>';}}else{$r.=($in!=-1?str_repeat($c,$in):'').(is_null($k)?'':"$k: ").(is_null($v)?'&lt;NULL&gt;':"<strong>$v</strong>");}return$r;};
    // echo $pretty($listeAA);

    if (isset($_GET['deleteArticle'])) {
        $exec = $article->delete($_GET['deleteArticle']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Article';
        } else {

            $form['valide'] = true;
            $form['message'] = 'L\'article a été supprimé avec succès.';
            header("Refresh:0; url=index.php?page=article_list");
        }
    }
    echo $twig->render('article_list.html.twig', array('form' => $form, 'listeA' => $listeA));
}

function actionDisabledArticle($twig, $db) {
    $form = array();
    $category = new Category($db);
    $listeC = $category->selectCategory();
    $article = new Article($db);
    $listeAD = $article->selectAllDisabled();
    //  $pretty = function($v='',$c="&nbsp;&nbsp;&nbsp;&nbsp;",$in=-1,$k=null)use(&$pretty){$r='';if(in_array(gettype($v),array('object','array'))){$r.=($in!=-1?str_repeat($c,$in):'').(is_null($k)?'':"$k: ").'<br>';foreach($v as $sk=>$vl){$r.=$pretty($vl,$c,$in+1,$sk).'<br>';}}else{$r.=($in!=-1?str_repeat($c,$in):'').(is_null($k)?'':"$k: ").(is_null($v)?'&lt;NULL&gt;':"<strong>$v</strong>");}return$r;};
    // echo $pretty($listeAA);

    if (isset($_GET['activateArticle'])) {
        if (isset($_GET['idCategory'])) {
            $exec = $article->activate($_GET['activateArticle'], $_GET['idCategory']);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table Article';
            } else {

                $form['valide'] = true;
                $form['message'] = 'L\'article a été supprimé avec succès.';
                header("Refresh:0; url=index.php?page=article_list_disabled");
            }
        }
    }

    echo $twig->render('article_list_disabled.html.twig', array('form' => $form, 'listeAD' => $listeAD, 'listeC' => $listeC));
}

function actionSelectedArticle($twig, $db) {
    $form = array();
    $article = new Article($db);
    if (isset($_GET['idArticle'])) {
        $listeA = $article->selectArticle($_GET['idArticle']);
        array_push($listeA, substr($listeA["contentArticle"], 0, 100)); //Only send 100 caracters
        array_push($listeA, 100 - round(strlen($listeA[10]) * 100 / strlen($listeA[3]))); //Calculate de percentage of article left


        if ($listeA != null) {
            $form['article'] = $listeA;
        } else {
            $form['message'] = 'Article incorrect';
        }
    }



    echo $twig->render('article.html.twig', array('form' => $form, 'listeA' => $listeA));
}

function actionArticleAdd($twig, $db) {
    $form = array();
    $article = new Article($db);

    $category = new Category($db);
    $listeC = $category->selectCategory();

    if (isset($_POST['btAdd'])) {
        $titleArticle = $_POST['inputTitleArticle'];
        $headlineArticle = $_POST['inputHeadlineArticle'];
        $contentArticle = $_POST['inputContentArticle'];
        $dateArticle = date('Y/m/d', time());
        $sourceArticle = htmlspecialchars($_POST['inputSourceArticle']);
        $idCategory = htmlspecialchars($_POST['inputIdCategory']);
        $idUser = $_SESSION['idUser'];
        $imageArticle = NULL;

        if (isset($_FILES['imageArticle'])) {
            if (!empty($_FILES['imageArticle']['name'])) {

                $extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
                $taille_max = 10000000;
                $dest_dossier = dirname(__DIR__, 2) . '/web/img/articles/';

                if (!in_array(substr(strrchr($_FILES['imageArticle']['name'], '.'), 1), $extensions_ok)) {
                    echo 'Veuillez sélectionner un fichier de type png, gif ou jpg.';
                } else {
                    if (file_exists($_FILES['imageArticle']['tmp_name']) && (filesize($_FILES['imageArticle']['tmp_name'])) > $taille_max) {
                        echo 'Votre fichier doit faire moins de 10 Mo !';
                    } else {
                        $imageArticle = basename($_FILES['imageArticle']['name']);
                        $imageArticle = strtr($imageArticle, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                        $imageArticle = preg_replace('/([^.a-z0-9]+)/i', '_', $imageArticle);
                        move_uploaded_file($_FILES['imageArticle']['tmp_name'], $dest_dossier . $imageArticle);
                    }
                }
            }
        }


        $exec = $article->insert($titleArticle, $headlineArticle, $contentArticle, $dateArticle, $sourceArticle, $imageArticle, $idCategory, $idUser);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table article';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'insertion s\'est bien déroulée !';
        }
    }


    $listeA = $article->select();
    echo $twig->render('article_add.html.twig', array('form' => $form, 'listeA' => $listeA, 'listeC' => $listeC));
}

function actionArticleModify($twig, $db) {
    $form = array();
    $category = new Category($db);
    $listeC = $category->selectCategory();

    if (isset($_GET['idArticle'])) {
        $article = new Article($db);
        $anArticle = $article->selectArticle($_GET['idArticle']);

        if ($anArticle != null) {
            $form['article'] = $anArticle;
        } else {
            $form['message'] = 'Article incorrect';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $article = new Article($db);
            $idArticle = $_POST['idArticle'];
            $titleArticle = $_POST['inputTitleArticle'];
            $headlineArticle = $_POST['inputHeadlineArticle'];
            $contentArticle = $_POST['inputContentArticle'];
            $sourceArticle = htmlspecialchars($_POST['inputSourceArticle']);
            $idCategory = htmlspecialchars($_POST['inputIdCategory']);
            $imageArticle = NULL;

            if (isset($_FILES['imageArticle']) && $_FILES['imageArticle']["name"] != "") {
                if (!empty($_FILES['imageArticle']['name'])) {
                    $extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
                    $taille_max = 10000000;
                    $dest_dossier = dirname(__DIR__, 2) . '/web/img/articles/';

                    if (!in_array(substr(strrchr($_FILES['imageArticle']['name'], '.'), 1), $extensions_ok)) {

                        echo 'Veuillez sélectionner un fichier de type png, gif ou jpg.';
                    } else {

                        if (file_exists($_FILES['imageArticle']['tmp_name']) && (filesize($_FILES['imageArticle']['tmp_name'])) > $taille_max) {

                            echo 'Votre fichier doit faire moins de 10 Mo !';
                        } else {
                            $imageArticle = basename($_FILES['imageArticle']['name']);
                            $imageArticle = strtr($imageArticle, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                            $imageArticle = preg_replace('/([^.a-z0-9]+)/i', '_', $imageArticle);
                            move_uploaded_file($_FILES['imageArticle']['tmp_name'], $dest_dossier . $imageArticle);
                        }
                    }
                }
                $exec = $article->update($idArticle, $titleArticle, $headlineArticle, $contentArticle, $sourceArticle, $imageArticle, $idCategory);
            } else {
                $exec = $article->updateWithoutImage($idArticle, $titleArticle, $headlineArticle, $contentArticle, $sourceArticle, $idCategory);
            }


            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table article';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Article non précisé';
        }
    }

    echo $twig->render('article_modify.html.twig', array('form' => $form, 'listeC' => $listeC,));
}
