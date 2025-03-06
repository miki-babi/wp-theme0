<footer>
    <div class="newsletter">
        <h2>Newsletter</h2>
        <p>Get the latest updates, financial tips, and exclusive offers straight to your inbox.</p>
        <form action="#" method="post">
            <input type="email" placeholder="Your email" required>
            <button type="submit">Subscribe</button>
        </form>
    </div>

    <div class="footer-content">
        <div class="footer-section">
            <h3>About the company</h3>
            <p>Empowering communities and supporting financial growth with innovative banking solutions tailored to your needs.</p>
            <div class="social-icons">
                <a href="#"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/facebook.svg'); ?>" alt="Facebook"></a>
                <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube.svg" alt="YouTube"></a>
                <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/linkedin.svg" alt="LinkedIn"></a>
                <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram.svg" alt="Instagram"></a>
            </div>
        </div>

        <div class="footer-section">
            <h3>Products</h3>
            <ul>
                <li><a href="#">Loan</a></li>
                <li><a href="#">Saving</a></li>
                <li><a href="#">Women Financing</a></li>
                <li><a href="#">Digital Banking</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>About</h3>
            <ul>
                <li><a href="#">Mission & Vision</a></li>
                <li><a href="#">Historical Milestones</a></li>
                <li><a href="#">Our Unique Approaches</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3><span class="phone-icon">📞</span> 507074</h3>
            <ul>
                <li><a href="#">Branches</a></li>
                <li><a href="#">Feedback</a></li>
                <li><a href="#">Contacts info</a></li>
            </ul>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>