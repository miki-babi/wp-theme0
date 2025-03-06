<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <?php wp_head(); ?>
  
</head>

<body <?php body_class(); ?>>
  <style>
    /* Navbar Styling */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 15px 50px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Logo */
.logo {
    height: 60px;
}

/* Navigation Links */
.nav-links {
    list-style: none;
    display: flex;
    gap: 25px;
    margin: 0;
    padding: 0;
}

.nav-links li {
    display: inline;
}

.nav-links a {
    text-decoration: none;
    color: #333;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    transition: color 0.3s;
}

.nav-links a:hover {
    color: #d32f2f; /* Red color on hover */
}

/* Contact Button */
.contact-btn {
    background: #d32f2f;
    color: white;
    padding: 10px 18px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    transition: background 0.3s;
}

.contact-btn:hover {
    background: #b71c1c;
}

/* Responsive Design */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        text-align: center;
    }

    .nav-links {
        flex-direction: column;
        gap: 10px;
    }

    .contact-btn {
        margin-top: 10px;
    }
}
/* Footer Styling */
footer {
    background: #fff;
    color: #333;
    padding: 50px 20px;
    font-family: Arial, sans-serif;
}

/* Newsletter Section */
.newsletter {
    background: #fff5f5;
    text-align: center;
    padding: 30px 20px;
    border-radius: 10px;
    margin-bottom: 30px;
}

.newsletter h2 {
    font-size: 24px;
    font-weight: bold;
    color: #222;
}

.newsletter p {
    color: #666;
    font-size: 14px;
    margin-bottom: 20px;
}

.newsletter form {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.newsletter input {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 250px;
    font-size: 14px;
}

.newsletter button {
    background: #d32f2f;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}

.newsletter button:hover {
    background: #b71c1c;
}

/* Footer Content Section */
.footer-content {
    display: flex;
    justify-content: space-between;
    gap: 40px;
    max-width: 1100px;
    margin: 0 auto;
    flex-wrap: wrap;
}

/* Footer Sections */
.footer-section {
    flex: 1;
    min-width: 200px;
}

.footer-section h3 {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 15px;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section ul li a {
    text-decoration: none;
    color: #666;
    font-size: 14px;
    transition: color 0.3s;
}

.footer-section ul li a:hover {
    color: #d32f2f;
}

/* Social Icons */
.social-icons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.social-icons a img {
    width: 24px;
    height: 24px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        text-align: center;
    }

    .newsletter form {
        flex-direction: column;
        gap: 10px;
    }

    .newsletter input {
        width: 100%;
    }

    .newsletter button {
        width: 100%;
    }
}

.search-container {
    position: relative;
}

.search-form-container {
    position: absolute;
    top: 40px;  /* Adjust based on where you want the search form */
    left: 0;
    right: 0;
    background-color: white;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    display: none;
}

.search-form-container .search-form {
    display: flex;
}

.search-form-container .search-form input[type="search"] {
    width: 100%;
    padding: 5px;
}

.search-form-container .search-form button {
    padding: 5px;
    cursor: pointer;
}

  </style>
  <script>
    document.getElementById('search-icon').addEventListener('click', function() {
    var searchFormContainer = document.getElementById('search-form-container');
    if (searchFormContainer.style.display === 'none' || searchFormContainer.style.display === '') {
        searchFormContainer.style.display = 'block';
    } else {
        searchFormContainer.style.display = 'none';
    }
});

  </script>
<div class="navbar">
    <a href="<?php echo home_url(); ?>">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="Logo" class="logo">
    </a>

    <ul class="nav-links">
        <li><a href="<?php echo home_url(); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Home</a></li>
        <li><a href="<?php echo home_url('/about-us'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">About Us</a></li>
        <li><a href="<?php echo home_url('/services'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Services</a></li>
        <li><a href="<?php echo home_url('/resources'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Resources</a></li>
        <li><a href="<?php echo home_url('/careers'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Careers</a></li>
        <li><a href="<?php echo home_url('/contact-us'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Contact Us</a></li>
    </ul>
    <div class="search-container">
    <!-- SVG Search Icon -->
    <button id="search-icon" class="search-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="search-icon-svg">
            <path fill-rule="evenodd" d="M15.743 13.257a8 8 0 1 0-1.486 1.486l4.743 4.743a1 1 0 0 0 1.414-1.414l-4.743-4.743zM16 8a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" clip-rule="evenodd"/>
        </svg>
    </button>

    <!-- Hidden Search Form -->
    <div id="search-form-container" class="search-form-container" style="display: none;">
        <?php get_search_form(); ?>
    </div>
</div>


    <a href="<?php echo home_url('/contact-us'); ?>" class="contact-btn">Contact</a>
</div>
