<style>
    /* Footer Styles */
.site-footer {
    background-color: #003580;
    color: white;
    padding: 40px 0 0;
    margin-top: 50px;
}

.footer-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-section h3 {
    color: #d0e6f0;
    font-size: 1.2rem;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}

.footer-section h3::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 2px;
    background: #0071c2;
}

.footer-section p {
    margin-bottom: 20px;
    line-height: 1.6;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section li {
    margin-bottom: 10px;
}

.footer-section a {
    color: white;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-section a:hover {
    color: #d0e6f0;
}

.social-icons {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.social-icons img {
    width: 24px;
    height: 24px;
    transition: transform 0.3s;
}

.social-icons a:hover img {
    transform: translateY(-3px);
}

.contact-info img {
    width: 16px;
    margin-right: 8px;
    vertical-align: middle;
}

.footer-bottom {
    background-color: #002050;
    padding: 20px 0;
    text-align: center;
    margin-top: 40px;
}

.footer-bottom p {
    margin: 0;
    font-size: 0.9rem;
}

.legal-links {
    margin-top: 10px;
}

.legal-links a {
    color: #d0e6f0;
    margin: 0 10px;
    font-size: 0.8rem;
}

/* Responsive Footer */
@media (max-width: 768px) {
    .footer-container {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .footer-section {
        margin-bottom: 30px;
    }
}
</style>
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>HomeX Algérie</h3>
            <p>Votre agence immobilière digitale pour trouver la propriété parfaite à travers les 58 wilayas algériennes.</p>
            <div class="social-icons">
                <a href="#"><img src="icons/facebook.svg" alt="Facebook"></a>
                <a href="#"><img src="icons/instagram.svg" alt="Instagram"></a>
                <a href="#"><img src="icons/whatsapp.svg" alt="WhatsApp"></a>
            </div>
        </div>

        <div class="footer-section">
            <h3>Liens Rapides</h3>
            <ul>
                <li><a href="acheter.php">Acheter un bien</a></li>
                <li><a href="vendre.php">Vendre un bien</a></li>
                <li><a href="louer.php">Louer un bien</a></li>
                <li><a href="contact.php">Contactez-nous</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Wilayas Populaires</h3>
            <ul class="wilayas-list">
                <li><a href="?wilaya=16">Alger</a></li>
                <li><a href="?wilaya=31">Oran</a></li>
                <li><a href="?wilaya=25">Constantine</a></li>
                <li><a href="?wilaya=19">Sétif</a></li>
                <li><a href="?wilaya=9">Blida</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Contact</h3>
            <ul class="contact-info">
                <li><img src="icons/phone.svg" alt="Téléphone"> +213 XXX XX XX XX</li>
                <li><img src="icons/email.svg" alt="Email"> contact@homex.dz</li>
                <li><img src="icons/location.svg" alt="Adresse"> Rue Didouche Mourad, Alger</li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> HomeX Algérie. Tous droits réservés.</p>
        <div class="legal-links">
            <a href="confidentialite.php">Confidentialité</a>
            <a href="conditions.php">Conditions d'utilisation</a>
        </div>
    </div>
</footer>

<!-- Keep the CSS in styles.css -->