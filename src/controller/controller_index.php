<?php

function actionIndex($twig, $db) {
    $form = array();
    $article = new Article($db);
    $listeA = $article->select();
    echo $twig->render('index.html.twig', array('form' => $form, 'listeA' => $listeA));
}

function actionContact($twig, $db) {
    $form = array();

    if (isset($_POST['btSendForm'])) {
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $subject = htmlspecialchars($_POST['subject']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        if (isset($email) && !empty($email) && isset($message) && !empty($message) &&
                isset($firstName) && !empty($firstName) && isset($lastName) && !empty($lastName) && isset($subject) && !empty($subject)) {
            //Envoie d'un mail//
            $header = "MIME-Version: 1.0\r\n";
            $header .= "From:'$email'<$email>" . "\n";
            $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';

            $contactForm = "
                    <html>
                        <body>
                        <img id=\"logo-main\" src=\"http://serveur1.arras-sio.com/symfony4-4020/lecoviddechaine/web/images/coviddechaine-email.png\"   class=\"desktop\"  alt=\"Covid déchaîné\">

                                    <div  style=\"width: 500px; margin: 0 auto;\">
            <p  style=\"text-align: center;\">Mail provenant de : $firstName $lastName - $email</p>
            <div >     <p>Object du mail : $subject</p>
                <p>\" $message \"</p>
            </div>
        </div>
                        </body>
                    </html>
                   ";

            mail("ludivine.achouri@orange.fr", "Un lecteur a besoin d'assistance", $contactForm, $header);
//fin d'envoie du mail//
            $form['valide'] = true;
            $form['message'] = "Le webmaster a bien reçu votre mail, il vous répondra sous peu.";
        } else {
            $form['valide'] = false;
            $form['message'] = "Une erreur s'est produite. Réessayez dans quelques minutes...";
        }
    }

    echo $twig->render('contact.html.twig', array('form' => $form));
}

function actionMentionsLegales($twig, $db) {
    echo $twig->render('mentionslegales.html.twig', array());
}

function actionCGU($twig, $db) {
    echo $twig->render('cgu.html.twig', array());
}
