<?php

function actionSignIn($twig, $db) {
    $form = array();
    $role = new Role($db);
    $listeR = $role->select();
    if (isset($_POST['btSignin'])) {

        $nicknameUser = htmlspecialchars($_POST['nicknameUser']);
        $passwordUser1 = htmlspecialchars($_POST['passwordUser1']);
        $passwordUser2 = htmlspecialchars($_POST['passwordUser2']);
        $idRoleUser = 1;

        $form['valide'] = true;

        $user = new User($db);
        if ($passwordUser1 != $passwordUser2) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        } else {
            $exec = $user->insert($nicknameUser, password_hash($passwordUser1, PASSWORD_DEFAULT), $idRoleUser);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Veuillez vérifier les informations saisies.';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';
            }
        }
        $form['nicknameUser'] = $nicknameUser;
    }
    echo $twig->render('signin.html.twig', array('form' => $form, 'session' => $_SESSION, 'listeR' => $listeR));
}

function actionLogIn($twig, $db) {
    $form = array();

    $user = new User($db);
    if (isset($_POST['btLogin'])) {
        $nicknameUser = filter_input(INPUT_POST, 'nicknameUser', FILTER_SANITIZE_SPECIAL_CHARS);
        $passwordUser = filter_input(INPUT_POST, 'passwordUser', FILTER_SANITIZE_SPECIAL_CHARS);
        $anUser = $user->connect($nicknameUser);
        if ($anUser != null) {
            if ($anUser['activated'] == 0) {
                $form['valide'] = false;
                $form['message'] = 'Votre compte est désactivé';
            } elseif (!password_verify($passwordUser, $anUser['passwordUser'])) {
                $form['valide'] = false;
                $form['message'] = 'Les mots de passe sont différents';
            } else {
                $form['valide'] = true;
                $_SESSION['idUser'] = $anUser['idUser'];
                $_SESSION['nicknameUser'] = $nicknameUser;
                $_SESSION['passwordUser'] = $passwordUser;
                $_SESSION['idRoleUser'] = $anUser['idRole'];
                header("Location: ../web/");
            }
        } else {
            $form['valide'] = false;
            $form['message'] = 'Ce compte n\'existe pas';
        }
    }
    echo $twig->render('login.html.twig', array('form' => $form));
}

function actionLogOut() {
    session_unset();
    session_destroy();
    header("Location: ../web/");
}

function actionAccount($twig, $db) {
    $user = new User($db);
    if (isset($_GET['idUser'])) {
        $exec = $user->unsubscribe($_GET['idUser']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Utilisateur';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'utilisateur a été supprimé avec succès.';
            header("Refresh:0; url=index.php?page=index");
            actionLogOut();
        }
    }
    echo $twig->render('account.html.twig', array());
}

function actionAccountModify($twig, $db) {
    $form = array();

    if (isset($_GET['idUser'])) {
        $user = new User($db);
        $aUser = $user->selectById($_GET['idUser']);

        if ($aUser != null) {
            $form['user'] = $aUser;
            $role = new Role($db);
            $liste = $role->select();
            $form['roles'] = $liste;
        } else {
            $form['message'] = 'Utilisateur incorrect.';
        }
    } else {
        if ($_SESSION['idRoleUser'] == 2) {
            return header("Refresh:0; url=index.php?page=account");
            ;
        } else {
            if (isset($_POST['btModifier'])) {
                $user = new User($db);

                $idUser = $_POST['idUser'];
                $inputNickname = $_POST['inputNickname'];
                $inputPassword = $_POST['inputPassword'];
                $inputPassword2 = $_POST['inputPassword2'];

                if ($inputPassword != $inputPassword2) {
                    $form['valide'] = false;
                    $form['message'] = 'Les adresses emails ou les mots de passe sont différents.';
                } else {
                    $user = new User($db);
                    $exec = $user->update($idUser, $inputNickname, password_hash($inputPassword, PASSWORD_DEFAULT));
                    if (!$exec) {
                        $form['valide'] = false;
                        $form['message'] = 'Problème d\'insertion dans la table utilisateur';
                    } else {
                        $form['valide'] = true;
                        $form['message'] = 'Modification réussie';
                    }
                }
            } else {
                $form['message'] = 'Utilisateur non précisé.';
            }
        }
    }

    echo $twig->render('account_modify.html.twig', array('form' => $form));
}

function actionUserList($twig, $db) {
    $form = array();
    $user = new User($db);
    $listeU = $user->select();

    if (isset($_GET['idUser']) && $_GET['idUser'] != "") {
        if ($_GET['idUser'] != 2) {
            $exec = $user->delete($_GET['idUser']);
            $form['valide'] = true;
            $form['message'] = 'L\'utilisateur a été supprimé avec succès.';
            header("Refresh:0; url=index.php?page=user_list");
        } else {
            $form['valide'] = false;
            $form['message'] = 'Vous ne pouvez pas désactiver votre propre compte';
        }
    }

    echo $twig->render('user_list.html.twig', array('form' => $form, 'listeU' => $listeU));
}

function actionDisabledUserList($twig, $db) {
    $form = array();
    $user = new User($db);
    $listeUD = $user->selectDisabled();

    if (isset($_GET['idUser']) && $_GET['idUser'] != "") {
        if ($_GET['idUser'] != 2) {
            $exec = $user->activate($_GET['idUser']);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table Utilisateur';
            } else {
                $form['valide'] = true;
                $form['message'] = 'L\'utilisateur a été supprimé avec succès.';
                header("Refresh:0; url=index.php?page=user_list_disabled");
            }
        }
    }

    echo $twig->render('user_list_disabled.html.twig', array('form' => $form, 'listeUD' => $listeUD));
}
