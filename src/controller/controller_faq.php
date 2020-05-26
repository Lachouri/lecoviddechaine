<?php

    //  $pretty = function($v='',$c="&nbsp;&nbsp;&nbsp;&nbsp;",$in=-1,$k=null)use(&$pretty){$r='';if(in_array(gettype($v),array('object','array'))){$r.=($in!=-1?str_repeat($c,$in):'').(is_null($k)?'':"$k: ").'<br>';foreach($v as $sk=>$vl){$r.=$pretty($vl,$c,$in+1,$sk).'<br>';}}else{$r.=($in!=-1?str_repeat($c,$in):'').(is_null($k)?'':"$k: ").(is_null($v)?'&lt;NULL&gt;':"<strong>$v</strong>");}return$r;};
    // echo $pretty($listeAA);

function actionFaqList($twig, $db) {
    $form = array();
    $faq = new Faq($db);
    $category = new Category($db);

    $listeF = $faq->selectAllFaq();
    $listeC = $category->selectCategory();
    
        if (isset($_GET['deleteFaq'])) {
        if (isset($_GET['idCategory'])) {
            $exec = $faq->delete($_GET['deleteFaq'], $_GET['idCategory']);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table Faq';
            } else {

                $form['valide'] = true;
                $form['message'] = 'La Faq a été supprimée avec succès.';
                header("Refresh:0; url=index.php?page=faq_list");
            }
        }
    }
    
    echo $twig->render('faq_list.html.twig', array('form' => $form, 'listeF' => $listeF, 'listeC' => $listeC));
}

function actionDisabledFaq($twig, $db) {
    $form = array();
    $faq = new Faq($db);
    $category = new Category($db);
     $listeC = $category->selectCategory();
    $listeFD = $faq->selectAllDisabled();


    if (isset($_GET['activateFaq'])) {
        if (isset($_GET['idCategory'])) {
            $exec = $faq->activate($_GET['activateFaq'], $_GET['idCategory']);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table Faq';
            } else {

                $form['valide'] = true;
                $form['message'] = 'La Faq a été supprimée avec succès.';
                header("Refresh:0; url=index.php?page=faq_list_disabled");
            }
        }
    }

    echo $twig->render('faq_list_disabled.html.twig', array('form' => $form, 'listeFD' => $listeFD, 'listeC' => $listeC));
}

function actionFaqDroit($twig, $db) {
    $form = array();
    $faq = new Faq($db);
    $listeF = $faq->selectFaq(2);
    if ($listeF != null) {
        $form['faq'] = $listeF[0]['idFaq'];
    } else {
        $form['message'] = 'incorrect';
    }

    if (isset($_GET['deleteFaq'])) {
        $exec = $faq->delete($_GET['deleteFaq']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Catégorie';
        } else {
            $form['valide'] = true;
            $form['message'] = 'La catégorie a été supprimée avec succès.';
            header("Refresh:0; url=index.php?page=faq_Droit");
        }
    }

    echo $twig->render('faq_Droit.html.twig', array('form' => $form, 'listeF' => $listeF));
}

function actionFaqEco($twig, $db) {
    $form = array();
    $faq = new Faq($db);
    $listeF = $faq->selectFaq(1);
    if ($listeF != null) {
        $form['faq'] = $listeF[0]['idFaq'];
    } else {
        $form['message'] = 'incorrect';
    }

    if (isset($_GET['deleteFaq'])) {
        $exec = $faq->delete($_GET['deleteFaq']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Catégorie';
        } else {
            $form['valide'] = true;
            $form['message'] = 'La catégorie a été supprimée avec succès.';
            header("Refresh:0; url=index.php?page=faq_Economie");
        }
    }
    echo $twig->render('faq_Economie.html.twig', array('form' => $form, 'listeF' => $listeF));
}

function actionFaqMana($twig, $db) {
    $form = array();
    $faq = new Faq($db);
    $listeF = $faq->selectFaq(3);
    if ($listeF != null) {
        $form['faq'] = $listeF[0]['idFaq'];
    } else {
        $form['message'] = 'incorrect';
    }

    if (isset($_GET['deleteFaq'])) {
        $exec = $faq->delete($_GET['deleteFaq']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Catégorie';
        } else {
            $form['valide'] = true;
            $form['message'] = 'La catégorie a été supprimée avec succès.';
            header("Refresh:0; url=index.php?page=faq_Management");
        }
    }
    echo $twig->render('faq_Management.html.twig', array('form' => $form, 'listeF' => $listeF));
}

function actionFaqEtude($twig, $db) {
    $form = array();
    $faq = new Faq($db);
    $listeF = $faq->selectFaq(4);
    if ($listeF != null) {
        $form['faq'] = $listeF[0]['idFaq'];
    } else {
        $form['message'] = 'incorrect';
    }

    if (isset($_GET['deleteFaq'])) {
        $exec = $faq->delete($_GET['deleteFaq']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Catégorie';
        } else {
            $form['valide'] = true;
            $form['message'] = 'La catégorie a été supprimée avec succès.';
            header("Refresh:0; url=index.php?page=faq_Etude");
        }
    }

    echo $twig->render('faq_etude.html.twig', array('form' => $form, 'listeF' => $listeF));
}

function actionFaqAdd($twig, $db) {
    $form = array();
    $faq = new Faq($db);

    $category = new Category($db);
    $listeC = $category->selectCategory();
    if (isset($_POST['btAdd'])) {
        $titleFaq = $_POST['inputTitleFaq'];
        $answerFaq = $_POST['inputAnswerFaq'];
        $idCategory = htmlspecialchars($_POST['inputIdCategory']);


        $exec = $faq->insert($titleFaq, $answerFaq, $idCategory);
        var_dump($titleFaq, $answerFaq, $idCategory);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Faq';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'insertion s\'est bien déroulée !';
        }
    }

    echo $twig->render('faq_add.html.twig', array('form' => $form, 'listeC' => $listeC));
}

function actionFaqModify($twig, $db) {
    $form = array();
    $category = new Category($db);
    $listeC = $category->selectCategory();

    if (isset($_GET['idFaq'])) {
        $faq = new Faq($db);
        $aFaq = $faq->selectById($_GET['idFaq']);
        if ($aFaq != null) {
            $form['faq'] = $aFaq;
        } else {
            $form['message'] = 'Faq incorrecte';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $faq = new Faq($db);
            $idFaq = $_POST['idFaq'];
            $titleFaq = $_POST['inputTitleFaq'];
            $answerFaq = $_POST['inputAnswerFaq'];
            $idCategory = htmlspecialchars($_POST['inputIdCategory']);

            $exec = $faq->update($idFaq, $titleFaq, $answerFaq, $idCategory);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table Faq';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Faq non précisé';
        }
    }

    echo $twig->render('faq_modify.html.twig', array('form' => $form, 'listeC' => $listeC));
}
