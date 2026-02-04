
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération avec vérification (Null Coalescing Operator)
    $name         = $_POST['name'] ?? '';
    $email        = $_POST['email'] ?? '';
    $telephone    = $_POST['phone'] ?? '';
    $niveauenfant = $_POST['child-section'] ?? '';
    $subject      = $_POST['subject'] ?? '';
    $message      = $_POST['message'] ?? '';
    $acceptTerms  = isset($_POST['consent']) ? 'Oui' : 'Non';

    $to = "contact@noumidia.ma";

    // Validation simple
    if (empty($name) || empty($email) || empty($message)) {
        echo "Erreur : Veuillez remplir tous les champs obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Erreur : L'adresse email n'est pas valide.";
    } else {
        // Hna t9der t-sift l-email
        echo "Succès : Votre message a été envoyé avec succès !";
    }
}
?>
