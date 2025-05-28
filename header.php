<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('customToggleBtn');
        const navCollapse = document.getElementById('customNavbarNav');
     
        toggleBtn.addEventListener('click', function () {
          navCollapse.classList.toggle('active');
        });
      });
    </script>
</head>

<body <?php body_class(); ?>>

<div class="container custom-nav-bar-sections">
  <nav class="custom-navbar">
    <div class="custom-container">
      <div class="custom-section">
        <a class="custom-navbar-brand d-flex align-items-center" href="<?php echo home_url(); ?>">
          <?php if (has_custom_logo()) {
            the_custom_logo(); ?>
            <span class="ms-2 fw-bold text-dark book-a-travel-text">Book A Travel</span>
          <?php } else { ?>
            <img src="<?php echo get_template_directory_uri(); ?>/photos/logo.png" alt="<?php bloginfo('name'); ?>">
          <?php } ?>
        </a>
        <button class="custom-navbar-toggler" id="customToggleBtn" aria-label="Toggle Menu">
          ☰
        </button>
      </div>
      <div class="custom-navbar-collapse" id="customNavbarNav">

     

        <?php
        wp_nav_menu(array(
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'custom-navbar-nav',
          'fallback_cb'    => false,
          'walker'         => new Bootstrap_Navwalker()
        ));
        ?>
     
        <div class="custom-right-section d-flex align-items-center gap-3">
          <div class="currency-selector">
            <img src="<?php echo get_template_directory_uri(); ?>/photos/clogo.png" alt="UK Flag">
            <span class="usd-flag">USD</span>
          </div>
          <div id="login-logout-btn">
            <?php
              $current_user = wp_get_current_user();
              if (is_user_logged_in() && in_array('subscriber', (array) $current_user->roles)) : ?>
              <div class="profile-dropdown">
                <button class="profile-btn">
                  <i class="fas fa-user person-icon"></i>
                </button>
                <div class="dropdown-menu">
                  <div class="mb-2">
                    <a class="setting" href="<?php echo home_url('/my-account/edit-profile/'); ?>">Profile</a>
                  </div>
                  <a class="my-account" href="<?php echo esc_url(wp_logout_url(home_url('/my-account'))); ?>">Logout</a>
                </div>
              </div>
              <?php else : ?>
              <button class="btn btn-primary login-button-home-page" data-bs-toggle="modal" data-bs-target="#loginModal">
                <span>Login or Signup</span>
              </button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Login/Signup Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog  travel-login-user-form-section">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <!-- Login Form -->
                  <div id="loginFormContainer">
                      <?php echo do_shortcode('[user_registration_login]'); ?>
                      <p class="mt-3 login-page-create-account">Don't have an account? <a href="#" id="showRegister">Register here</a></p>
                  </div>

                  <!-- Registration Form -->
                  <div id="registerFormContainer" class="d-none">
                      <?php echo do_shortcode('[user_registration_form id="214"]'); ?>
                      <p class="mt-3 login-page-paragraph">Already have an account? <a href="#" id="showLogin">Login here</a></p>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>