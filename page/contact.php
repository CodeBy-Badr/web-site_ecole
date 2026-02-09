<?php 
session_start();
// Générer le token CSRF s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Groupe Scolaire Noumidia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-logo">
                <i class="fas fa-graduation-cap"></i>
                <span>Groupe Scolaire Noumidia</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="../index.html" class="nav-link">Accueil</a></li>
                <li class="nav-item"><a href="about.html" class="nav-link">À Propos</a></li>
                <li class="nav-item"><a href="sections.html" class="nav-link">Nos Sections</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link active">Contact</a></li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="header-content" data-aos="fade-down">
            <h1>Nous Contacter</h1>
            <p>Nous sommes à votre écoute</p>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="contact-info">
        <div class="container">
            <div class="info-grid">
                <div class="info-card" data-aos="flip-left">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Adresse</h3>
                    <p>Avenue Ennakhil, Nador (En face du Total Jmidar)</p>
                    <p>Maroc</p>
                </div>

                <div class="info-card" data-aos="flip-left" data-aos-delay="100">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Téléphone</h3>
                    <p><a href="tel:+213XXX">+212 668-717445</a></p>
                    <p><strong>Lun-Ven: 8h-17h</strong></p>
                </div>

                <div class="info-card" data-aos="flip-left" data-aos-delay="200">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email</h3>
                    <p><a href="mailto:contact@noumidia.ma">contact@noumidia.ma</a></p>
                </div>

                <div class="info-card" data-aos="flip-left" data-aos-delay="300">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Heures d'Ouverture</h3>
                    <p><strong>Lun-Jeu:</strong> 8h-17h</p>
                    <p><strong>Vendredi:</strong> 8h-12h</p>
                    <p><strong>Samedi-Dimanche:</strong> Fermé</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="contact-form-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-down">Formulaire de Contact</h2>
            <div class="form-container" data-aos="fade-up">
                <form class="contact-form" method="post" action="../config/send.php" id="contactForm">
                    <div class="form-group">
                        <label for="name">Nom Complet *</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Téléphone *</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Sujet *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Sélectionnez un sujet</option>
                            <option value="inscription">Inscription</option>
                            <option value="visite">Demande de Visite</option>
                            <option value="question">Question Générale</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="child-section">Section de Votre Enfant *</label>
                        <select id="child-section" name="child-section" required>
                            <option value="">Sélectionnez une section</option>
                            <option value="maternelle">Maternelle</option>
                            <option value="primaire">Primaire</option>
                            <option value="college">Collège</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="consent" name="consent" required>
                        <label for="consent">J'accepte la politique de confidentialité</label>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">

                    <button type="submit" class="btn btn-primary btn-large">Envoyer le Message</button>
                </form>
                <div id="formMessage" class="form-message" style="display: none;"></div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <h2 class="section-title" data-aos="fade-down">Notre Localisation</h2>
        <div class="map-container" data-aos="zoom-in">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3261.9696563173775!2d-2.9722192000000103!3d35.1573775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd77a9000da6830b%3A0x7e92d68f4d632232!2sGroupe%20Scolaire%20Noumidia!5e0!3m2!1sfr!2sma!4v1770387962892!5m2!1sfr!2sma" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-down">Questions Fréquentes</h2>
            <div class="faq-grid">
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-header">
                        <h3>Quel est l'âge minimum pour l'inscription à la maternelle?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-content">
                        <p>L'âge minimum pour l'inscription à la maternelle est de 3 ans. Les enfants doivent être propres et avoir une autonomie minimale.</p>
                    </div>
                </div>

                <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="faq-header">
                        <h3>Quels sont les frais d'inscription?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-content">
                        <p>Les frais varient selon la section. Nous offrons des plans de paiement flexibles et des réductions pour les familles avec plusieurs enfants. Contactez-nous pour plus d'informations.</p>
                    </div>
                </div>

                <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="faq-header">
                        <h3>Proposez-vous le transport scolaire?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-content">
                        <p>Oui, nous proposons un service de transport scolaire avec des bus équipés et assuré. Des frais supplémentaires s'appliquent selon la zone.</p>
                    </div>
                </div>

                <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="faq-header">
                        <h3>Quelle est la durée de l'année scolaire?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-content">
                        <p>L'année scolaire débute en septembre et se termine en juin. Il y a 2 semestres avec des vacances entre chaque semestre.</p>
                    </div>
                </div>

                <div class="faq-item" data-aos="fade-up" data-aos-delay="400">
                    <div class="faq-header">
                        <h3>Y a-t-il des activités parascolaires?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-content">
                        <p>Oui, nous proposons une large gamme d'activités: sports, arts, musique, informatique, etc. Tous inclus dans les frais de scolarité.</p>
                    </div>
                </div>

                <div class="faq-item" data-aos="fade-up" data-aos-delay="500">
                    <div class="faq-header">
                        <h3>Comment puis-je visiter l'établissement?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-content">
                        <p>Vous pouvez prendre un rendez-vous en nous contactant par téléphone ou en remplissant notre formulaire de contact. Nous proposons des visites guidées les jours ouvrables.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section" data-aos="fade-up">
                <h3>Groupe Scolaire Noumidia</h3>
                <p>L'excellence éducative au service de votre enfant</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/GroupeScolaireNoumidia/"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/groupe_scolaire_noumidia/"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-section" data-aos="fade-up" data-aos-delay="100">
                <h4>Sections</h4>
                <ul>
                    <li><a href="sections.html#maternelle">Maternelle</a></li>
                    <li><a href="sections.html#primaire">Primaire</a></li>
                    <li><a href="sections.html#college">Collège</a></li>
                </ul>
            </div>

            <div class="footer-section" data-aos="fade-up" data-aos-delay="200">
                <h4>Liens Utiles</h4>
                <ul>
                    <li><a href="about.html">À Propos</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="#">Actualités</a></li>
                    <li><a href="#">Calendrier</a></li>
                </ul>
            </div>

            <div class="footer-section" data-aos="fade-up" data-aos-delay="300">
                <h4>Contact</h4>
                <p><i class="fas fa-map-marker-alt"></i> Avenue Ennakhil, Nador (En face du Total Jmidar)</p>
                <p><i class="fas fa-phone"></i> +212 668-717445</p>
                <p><i class="fas fa-envelope"></i> contact@noumidia.ma</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 Groupe Scolaire Noumidia. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
