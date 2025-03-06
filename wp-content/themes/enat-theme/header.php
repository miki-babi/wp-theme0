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
        <li><a href="<?php echo home_url(); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Home</a></li>
        <li><a href="<?php echo home_url('/about-us'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">About Us</a></li>
        <li><a href="<?php echo home_url('/services'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Services</a></li>
        <li><a href="<?php echo home_url('/resources'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Resources</a></li>
        <li><a href="<?php echo home_url('/careers'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Careers</a></li>
        <li><a href="<?php echo home_url('/contact-us'); ?>" style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 12px; line-height: 20px; letter-spacing: 2px; text-transform: uppercase;">Contact Us</a></li>
    </ul>
    <a href="#" class="search-icon">
    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M22.2099 20.79L18.4999 17.11C19.94 15.3144 20.6374 13.0353 20.4487 10.7413C20.26 8.4473 19.1996 6.31278 17.4854 4.77664C15.7713 3.2405 13.5337 2.41951 11.2328 2.48247C8.93194 2.54543 6.74263 3.48756 5.11505 5.11514C3.48747 6.74272 2.54534 8.93203 2.48237 11.2329C2.41941 13.5338 3.24041 15.7714 4.77655 17.4855C6.31269 19.1997 8.44721 20.2601 10.7412 20.4488C13.0352 20.6375 15.3143 19.9401 17.1099 18.5L20.7899 22.18C20.8829 22.2737 20.9935 22.3481 21.1153 22.3989C21.2372 22.4497 21.3679 22.4758 21.4999 22.4758C21.6319 22.4758 21.7626 22.4497 21.8845 22.3989C22.0063 22.3481 22.1169 22.2737 22.2099 22.18C22.3901 21.9935 22.4909 21.7443 22.4909 21.485C22.4909 21.2257 22.3901 20.9765 22.2099 20.79ZM11.4999 18.5C10.1154 18.5 8.76206 18.0895 7.61091 17.3203C6.45977 16.5511 5.56256 15.4579 5.03275 14.1788C4.50293 12.8997 4.36431 11.4922 4.63441 10.1344C4.9045 8.7765 5.57119 7.52922 6.55016 6.55025C7.52912 5.57128 8.77641 4.9046 10.1343 4.6345C11.4921 4.3644 12.8996 4.50303 14.1787 5.03284C15.4578 5.56265 16.551 6.45986 17.3202 7.611C18.0894 8.76215 18.4999 10.1155 18.4999 11.5C18.4999 13.3565 17.7624 15.137 16.4497 16.4497C15.1369 17.7625 13.3564 18.5 11.4999 18.5Z" fill="#ED1E26"/>
</svg>

    </a>

    <a href="<?php echo home_url('/contact-us'); ?>" class="contact-btn">Contact</a>
</div>
