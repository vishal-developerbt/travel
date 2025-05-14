<?php 
/**
 * Template Name: Front Page
 * Description: A custom page template for special layouts.
 */

get_header(); ?>
   
    
    <?php get_template_part('booking-section'); ?>
    <?php get_template_part('special-offer-section'); ?>
    <?php // get_template_part('hotel-section'); ?>
    <?php get_template_part('trending-section'); ?>
    <?php get_template_part('crypto-section'); ?>
    <?php get_template_part('faq-section'); ?>
   
   

    <div class="footer-bg">
        <img src="./photos/bg.svg.png" alt="">
    </div>
    <?php get_footer(); ?>

    <script>
      function showSearchTab(tabId, event) {
      
    // Prevent default behavior
    event.preventDefault();

    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('d-none');
    });

    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active-tab');
    });

    // Show the selected tab content
    document.getElementById(tabId).classList.remove('d-none');

    // Add active class to the clicked button
    event.currentTarget.classList.add('active-tab');
}
  
      </script>
 
   
