<?php
session_start();

// ===== CONFIGURATION =====
define('ADMIN_EMAIL', 'contact@noumidia.com');
define('SITE_NAME', 'Groupe Scolaire Noumidia');
define('MAX_MESSAGE_LENGTH', 5000);
define('MAX_NAME_LENGTH', 100);

// ===== FONCTIONS DE SÉCURITÉ =====

/**
 * Génère un token CSRF
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Nettoie et valide les données
 */
function sanitizeInput($data, $type = 'text') {
    $data = trim($data);
    
    switch ($type) {
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
        case 'text':
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        case 'phone':
            return preg_replace('/[^0-9\+\-\s]/', '', $data);
        default:
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Log des tentatives suspectes
 */
function logSecurityEvent($event, $details = '') {
    $logFile = __DIR__ . '/security.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $logMessage = "[$timestamp] IP: $ip | Event: $event | Details: $details\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// ===== TRAITEMENT DU FORMULAIRE =====

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Vérification du token CSRF
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrf_token)) {
        logSecurityEvent('CSRF_TOKEN_INVALID', 'Token CSRF invalide ou manquant');
        $msg = "Erreur de sécurité : Token invalide. Veuillez réessayer.";
        $type = "error";
    } else {
        // 2. Récupération et nettoyage des données
        $name          = sanitizeInput($_POST['name'] ?? '', 'text');
        $email         = sanitizeInput($_POST['email'] ?? '', 'email');
        $phone         = sanitizeInput($_POST['phone'] ?? '', 'phone');
        $subject_form  = sanitizeInput($_POST['subject'] ?? '', 'text');
        $section       = sanitizeInput($_POST['child-section'] ?? '', 'text');
        $message       = sanitizeInput($_POST['message'] ?? '', 'text');
        $consent       = $_POST['consent'] ?? '';
        
        // 3. Validation des données
        $errors = [];
        
        // Vérifier les champs obligatoires
        if (empty($name)) {
            $errors[] = "Le nom est obligatoire";
        } elseif (strlen($name) > MAX_NAME_LENGTH) {
            $errors[] = "Le nom ne doit pas dépasser " . MAX_NAME_LENGTH . " caractères";
        }
        
        if (empty($email)) {
            $errors[] = "L'email est obligatoire";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide";
        } elseif (strlen($email) > 254) {
            $errors[] = "L'email est trop long";
        }
        
        if (empty($phone)) {
            $errors[] = "Le téléphone est obligatoire";
        } elseif (strlen(preg_replace('/[^0-9]/', '', $phone)) < 8) {
            $errors[] = "Le téléphone doit contenir au moins 8 chiffres";
        }
        
        if (empty($subject_form)) {
            $errors[] = "Le sujet est obligatoire";
        }
        
        if (empty($section)) {
            $errors[] = "La section est obligatoire";
        }
        
        if (empty($message)) {
            $errors[] = "Le message est obligatoire";
        } elseif (strlen($message) > MAX_MESSAGE_LENGTH) {
            $errors[] = "Le message ne doit pas dépasser " . MAX_MESSAGE_LENGTH . " caractères";
        } elseif (strlen($message) < 10) {
            $errors[] = "Le message doit contenir au moins 10 caractères";
        }
        
        if (!isset($consent) || $consent !== 'on') {
            $errors[] = "Vous devez accepter la politique de confidentialité";
        }
        
        // 4. Validation de la liste blanche pour subject et section
        $validSubjects = ['inscription', 'visite', 'question', 'autre'];
        $validSections = ['maternelle', 'primaire', 'college'];
        
        if (!in_array($subject_form, $validSubjects)) {
            logSecurityEvent('INVALID_SUBJECT', "Sujet invalide: $subject_form");
            $errors[] = "Sujet invalide";
        }
        
        if (!in_array($section, $validSections)) {
            logSecurityEvent('INVALID_SECTION', "Section invalide: $section");
            $errors[] = "Section invalide";
        }
        
        // 5. Détection du spam simple (répétition de caractères)
        if (preg_match('/(.)\1{10,}/', $message)) {
            logSecurityEvent('SPAM_DETECTED', 'Caractères répétés détectés');
            $errors[] = "Message suspect détecté (spam)";
        }
        
        // 6. Traitement si pas d'erreurs
        if (empty($errors)) {
            // Préparation de l'email
            $to = ADMIN_EMAIL;
            $subject = "Nouveau message de contact : " . $subject_form;
            
            // Headers sécurisés
            $headers = [];
            $headers[] = "From: noreply@" . $_SERVER['HTTP_HOST'];
            $headers[] = "Reply-To: " . $email;
            $headers[] = "Content-Type: text/plain; charset=UTF-8";
            $headers[] = "X-Mailer: PHP/" . phpversion();
            $headers[] = "X-Priority: 3";
            
            $headers_str = implode("\r\n", $headers);
            
            // Corps de l'email formaté
            $body = <<<EOT
=================================================================
NOUVEAU MESSAGE DE CONTACT - GROUPE SCOLAIRE NOUMIDIA
=================================================================

INFORMATIONS de l'expéditeur:
--------------------------------
Nom: $name
Email: $email
Téléphone: $phone

INFORMATIONS du message:
--------------------------------
Sujet: $subject_form
Section enfant: $section

MESSAGE:
--------------------------------
$message

=================================================================
Date d'envoi: " . date('d/m/Y H:i:s') . "
Adresse IP: " . $_SERVER['REMOTE_ADDR'] . "
=================================================================
EOT;
            
            // Envoi de l'email
            if (mail($to, $subject, $body, $headers_str)) {
                logSecurityEvent('EMAIL_SENT_SUCCESS', "Email reçu de: $email");
                
                // Envoyer une confirmation à l'utilisateur
                $confirm_subject = "Confirmation : Votre message a été reçu";
                $confirm_body = <<<EOT
Bonjour $name,

Merci de nous avoir contacté! Nous avons bien reçu votre message et reviendrons vers vous au plus tôt.

Cordialement,
Groupe Scolaire Noumidia
+212 668-717445
contact@noumidia.com
EOT;
                
                $confirm_headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
                $confirm_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                
                mail($email, $confirm_subject, $confirm_body, $confirm_headers);
                
                $msg = "✓ Message envoyé avec succès! Nous vous contacterons très bientôt.";
                $type = "success";
            } else {
                logSecurityEvent('EMAIL_SEND_FAILED', "Erreur lors de l'envoi pour: $email");
                $msg = "Erreur : Problème technique lors de l'envoi. Veuillez réessayer plus tard.";
                $type = "error";
            }
        } else {
            // Affichage des erreurs
            $msg = "Erreur(s) détectée(s) :\n- " . implode("\n- ", $errors);
            $type = "error";
            logSecurityEvent('FORM_VALIDATION_FAILED', implode(" | ", $errors));
        }
    }
    
    // Affichage du résultat
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='../css/style.css'> 
    </head>
    <body>
        <script src='../js/script.js'></script> 
        <script>
            window.onload = function() {
                if (typeof showFormMessage === 'function') {
                    showFormMessage('" . addslashes($msg) . "', '$type');
                } else {
                    alert('" . addslashes($msg) . "');
                }
                setTimeout(function(){
                    window.location.href = '../page/contact.php'; 
                }, 4000);
            };
        </script>
    </body>
    </html>";
    exit;
}
?>



