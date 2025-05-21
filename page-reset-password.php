
<?php 
/**
 * Template Name: Account Reset-Password
 * Description: A custom page template for special layouts.
 */

get_header(); ?>
<div class="container"><?php echo do_shortcode('[user_registration_lost_password]'); ?></div>



<!--  <section class="dashboard-section">
        <div class="container-custom mb-2">
            <div class="container profile-section">
                <div class="combo-profile-section">
                <div class="sidebar">
                    <a href="#" class="active"><i class="fa-solid fa-user"></i><span class="my-profile-text">My
                            Profile</span>
                    </a>
                    <a href="#"><i class="fa-solid fa-location-dot"></i><span class="my-adddress-text"> My Address</span></a>
                    <a href="#"><i class="fa-solid fa-location-dot"></i><span class="my-adddress-text"> My Orders</span></a>
                    <a href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i><span class="logout-text">Logout</span> </a>
                </div>
                
                <div class="profile-card">
                    <div class="profile-card-inner-section">
                    <h4 class="user-name-text">Krishna Sharma</h4>
                </div>
                    <div class="prodile-card-body-section">
                        <div class="text-center mb-4 profile-image">
                            <img id="profilePic" src="<?php echo get_template_directory_uri(); ?>/photos/user-icon.png" alt="Profile" style="width: 100px; height: 100px; border-radius: 50%;">
                            <label for="imageUpload" class="edit-icon">
                          <i class="fas fa-pencil-alt"></i>  
                            </label>
                            <input type="file" id="imageUpload" accept="image/*" style="display: none;">
                        </div>
                        
                    <div class="personal-detail">
                        <h5 class="personal-contact-detail">Personal & Contact Details</h5>
                        <form>
                            <label class="full-name">Full Name</label>
                            <input type="text" class="form-control mb-3 name-text" value="Krishna Sharma">
                            <label class="email-id">Email ID</label>
                            <input type="email" class="form-control mb-3 email-label-field"
                                value="krishna.sharma@XXXX.com">
                            <label class="mobile-number">Mobile Number</label>
                            <input type="text" class="form-control mb-3 mobile-number-lable-field" value="70115XXXXX">
                            <label class="mobile-number">Mobile Number</label>
                            <input type="text" class="form-control mb-3 mobile-number-lable-field" value="70115XXXXX">
                            <label class="mobile-number">Mobile Number</label>
                            <input type="text" class="form-control mb-3 mobile-number-lable-field" value="70115XXXXX">

                            <div class="save-button-dashboard">
                                <button type="submit" class="btn save-btn save-button-profile"><span
                                        class="save-button">
                                        Save
                                    </span></button>
                            </div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>

    </section>
  <script>
        document.addEventListener("DOMContentLoaded", function () {
            const profileBtn = document.querySelector(".profile-btn");
            const dropdown = document.querySelector(".profile-dropdown");

            profileBtn.addEventListener("click", function (event) {
                event.stopPropagation();
                dropdown.classList.toggle("active");
            });

            document.addEventListener("click", function (event) {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove("active");
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".sidebar a");

            sidebarLinks.forEach(link => {
                link.addEventListener("click", function () {
                    // Remove 'active' class from all links
                    sidebarLinks.forEach(l => l.classList.remove("active"));

                    // Add 'active' class to the clicked link
                    this.classList.add("active");
                });
            });
        });
   // image upload javascript start
   document.getElementById('imageUpload').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePic').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

//  end image upload javsript
    </script>
    <style>
    .dashboard-section .profile-image img {
    width: 150px !important;
    height: 150px !important;}
</style> -->
    <?php get_footer(); ?>
   