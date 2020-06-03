<?php

function actionCategory($twig, $db) {
    $form = array();
    $category = new Category($db);
    $listeC = $category->selectCategory();
$listeDisabledC = $category->selectDisabledCategory();

    if (isset($_GET['deleteCategory'])) {
        $exec = $category->delete($_GET['deleteCategory']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Catégorie';
        } else {
            $form['valide'] = true;
            $form['message'] = 'La catégorie a été supprimée avec succès.';
            header("Refresh:0; url=index.php?page=category");
        }
    }

    if (isset($_GET['activateCategory'])) {
        $exec = $category->activate($_GET['activateCategory']);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Catégorie';
        } else {
            $form['valide'] = true;
            $form['message'] = 'La catégorie a été supprimée avec succès.';
            header("Refresh:0; url=index.php?page=category");
        }
    }

    echo $twig->render('category.html.twig', array('form' => $form, 'listeC' => $listeC, 'listeDisabledC' => $listeDisabledC));
}

function actionCategoryArticle($twig, $db) {
    $form = array();
    $category = new Category($db);
    empty($listeArticle);
    if (isset($_GET['idCategory'])) {
        $listeCategories = $category->selectById($_GET['idCategory']);
        $listeArticle = $category->selectCategoryArticle($_GET['idCategory']);
        if ($listeCategories != null) {
            $form['category'] = $listeCategories[1];
        } else {
            $form['message'] = 'Catégorie incorrecte';
        }
    }

    if (isset($_GET['deleteArticle'])) {
        $exec = $category->delete($_GET['deleteArticle']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Article';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'article a été supprimé avec succès.';
            header("Refresh:0; url=index.php?page=category");
        }
    }

    echo $twig->render('categoryarticle.html.twig', array('form' => $form, 'listeArticle' => $listeArticle));
}

function actionCategoryAdd($twig, $db) {
    $form = array();
    $category = new Category($db);

    if (isset($_POST['btAdd'])) {
        $labelCategory = htmlspecialchars($_POST['inputLabelCategory']);


        $exec = $category->insert($labelCategory);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table contact';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'insertion s\'est bien déroulée !';
        }
    }



    echo $twig->render('category_add.html.twig', array('form' => $form));
}

function actionCategoryModify($twig, $db) {
    $form = array();

    if (isset($_GET['idCategory'])) {
        $category = new Category($db);
        $aCategory = $category->selectById($_GET['idCategory']);
        if ($aCategory != null) {
            $form['category'] = $aCategory;
        } else {
            $form['message'] = 'Catégorie incorrecte';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $category = new Category($db);
            $idCategory = $_POST['idCategory'];
            $labelCategory = htmlspecialchars($_POST['inputLabelCategory']);

            try {
                $exec = $category->update($idCategory, $labelCategory,);
                if (!$success) {
                    $error = "You cannot delete this row";
                    throw new Exception($error);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    echo $twig->render('category_modify.html.twig', array('form' => $form));
}
