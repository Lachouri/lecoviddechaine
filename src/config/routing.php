<?php

function getPage($db) {

    //Pages de base
    $lesPages['index'] = "actionIndex;0";
    $lesPages['contact'] = "actionContact;0";
    $lesPages['mentionslegales'] = "actionMentionsLegales;0";
    $lesPages['cgu'] = "actionCGU;0";


    // Pages utilisateurs
    $lesPages['signin'] = "actionSignIn;0";
    $lesPages['login'] = "actionLogIn;0";
    $lesPages['logout'] = "actionLogOut;0";
    $lesPages['account'] = "actionAccount;0";
    $lesPages['accountmodify'] = "actionAccountModify;1";



    $lesPages['article'] = "actionSelectedArticle;0";
    $lesPages['article_list'] = "actionArticle;0";



    //Catégories
    $lesPages['category'] = "actionCategory;0";
    $lesPages['categoryarticle'] = "actionCategoryArticle;0";

    //FAQs
    $lesPages['faq_list'] = "actionFaqList;0";
    $lesPages['faq_Droit'] = "actionFaqDroit;0";
    $lesPages['faq_Economie'] = "actionFaqEco;0";
    $lesPages['faq_Management'] = "actionFaqMana;0";
    $lesPages['faq_Etude'] = "actionFaqEtude;0";



    // Page d'administration
    $lesPages['user_list'] = "actionUserList;2";
    $lesPages['articleadd'] = "actionArticleAdd;2";
    $lesPages['articlemodify'] = "actionArticleModify;2";
    $lesPages['categoryadd'] = "actionCategoryAdd;2";
    $lesPages['categorymodify'] = "actionCategoryModify;2";
    $lesPages['faqmodify'] = "actionFaqModify;2";
    $lesPages['faqadd'] = "actionFaqAdd;2";
    $lesPages['article_list_disabled'] = "actionDisabledArticle;2";
    $lesPages['user_list_disabled'] = "actionDisabledUserList;2";
    $lesPages['faq_list_disabled'] = "actionDisabledFaq;2";






    if ($db != null) {
        if (isset($_GET['page'])) {
            // Nous mettons dans la variable $page, la valeur qui a été passée dans le lien
            $page = $_GET['page'];
        } else {
            // S'il n'y a rien en mémoire, nous lui donnons la valeur « accueil » afin de lui afficher une page
            //par défaut
            $page = 'index';
        }

        if (!isset($lesPages[$page])) {
            // Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
            $page = 'index';
        }

        $explose = explode(";", $lesPages[$page]);
        $role = $explose[1];

        // Si le rôle nécessite de contrôler les droits
        if ($role != 0) {
            // Nous vérifions que la personne est connectée
            if (isset($_SESSION['nicknameUser'])) {
                //Nous vérifions qu'elle a un rôle
                if (isset($_SESSION['idRoleUser'])) {

                    if ($role != $_SESSION['idRoleUser']) {
                        //Nous redigeons la personne vers la page d'acccueil car elle n'a pas le bon rôle 
                        $contenu = 'actionIndex';
                    } else {
                        // La personne est autorisée à récupérer  
                        $contenu = $explose[0];
                    }
                } else {
                    // Dans la session le rôle n'existe pas donc on va sur la page d'accueil 
                    $contenu = 'actionIndex';
                }
            } else {
                // La personne n'est pas connectée, donc on va sur la page d'accueil  
                $contenu = 'actionIndex';
            }
        } else {
            // Nous donnons du contenu non protégé  
            $contenu = $explose[0];
        }
    } else {
        // La base de données n'est pas accessible
        $contenu = 'actionMaintenance';
    }
// La fonction envoie le contenu
    return $contenu;
}

?>