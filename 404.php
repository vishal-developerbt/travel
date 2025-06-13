<?php get_header(); ?>

<div class="error-404 not-found" style="text-align: center; padding: 100px 20px;">
    <h1 style="font-size: 72px; color: #e74c3c;">404</h1>
    <h2 style="font-size: 32px;">Oops! Page Not Found</h2>
    <p style="font-size: 18px;">The page you are looking for doesn't exist or has been moved.</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" style="display: inline-block; margin-top: 20px; background-color: #3498db; color: #fff; padding: 10px 20px; border-radius: 4px;">Go Back to Home</a>
</div>

<?php get_footer(); ?>