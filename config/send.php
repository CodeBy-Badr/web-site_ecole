
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données
    $name         = $_POST['name'] ?? '';
    $email        = $_POST['email'] ?? '';
    $phone        = $_POST['phone'] ?? '';
    $subject_form = $_POST['subject'] ?? '';
    $section      = $_POST['child-section'] ?? '';
    $message      = $_POST['message'] ?? '';

    $to = "contact@noumidia.ma";
    $email_subject = "Nouveau message: " . $subject_form;

    // Vérification simple
    if (empty($name) || empty($email) || empty($message)) {
        $msg = "Erreur : Veuillez remplir tous les champs.";
        $type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Erreur : Email invalide.";
        $type = "error";
    } else {
        // Préparation de l'email
        $headers = "From: contact@noumidia.ma\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $body = "Nom: $name\n";
        $body .= "Email: $email\n";
        $body .= "Téléphone: $phone\n";
        $body .= "Sujet: $subject_form\n";
        $body .= "Section: $section\n";
        $body .= "Message: $message\n";

        if (mail($to, $email_subject, $body, $headers)) {
            $msg = "✓ Message envoyé avec succès! Nous vous contacterons très bientôt.";
            $type = "success";
        } else {
            $msg = "Erreur : Problème technique lors de l'envoi.";
            $type = "error";
        }
    }

    echo "<!DOCTYPE html>
    <html>
    <head>
        <link rel='stylesheet' href='../css/style.css'> <!-- T-akked mn l-path d CSS -->
    </head>
    <body>
        <script src='../js/script.js'></script> <!-- T-akked mn l-path d JS -->
        <script>
            window.onload = function() {
                if (typeof showFormMessage === 'function') {
                    showFormMessage('$msg', '$type');
                } else {
                    alert('$msg');
                }
                // Rejja3 l-user l-page d contact men be3d 3 tswani
                setTimeout(function(){
                    window.location.href = '../contact.html'; 
                }, 3000);
            };
        </script>
    </body>
    </html>";
}
?>



