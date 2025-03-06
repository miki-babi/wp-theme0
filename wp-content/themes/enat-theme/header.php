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

  </style>
<div class="navbar">
    <a href="<?php echo home_url(); ?>">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="Logo" class="logo">
    </a>

    <ul class="nav-links">
        <li><a href="<?php echo home_url(); ?>">Home</a></li>
        <li><a href="<?php echo home_url('/about-us'); ?>">About Us</a></li>
        <li><a href="<?php echo home_url('/services'); ?>">Services</a></li>
        <li><a href="<?php echo home_url('/resources'); ?>">Resources</a></li>
        <li><a href="<?php echo home_url('/careers'); ?>">Careers</a></li>
        <li><a href="<?php echo home_url('/contact-us'); ?>">Contact Us</a></li>
    </ul>

    <a href="<?php echo home_url('/contact-us'); ?>" class="contact-btn">Contact</a>
</div>
