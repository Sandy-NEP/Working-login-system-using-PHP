document.addEventListener('DOMContentLoaded', function() {
    // Profile icon click handler
    const profileIcon = document.getElementById('profile-icon');
    if (profileIcon) {
        profileIcon.addEventListener('click', function() {
            window.location.href = '../profile/edit.php';
        });
        
        // Add hover effect
        profileIcon.style.cursor = 'pointer';
        profileIcon.style.transition = 'transform 0.3s ease';
        
        profileIcon.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        
        profileIcon.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
    
    // Confirm before delete
    const deleteButtons = document.querySelectorAll('.btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete your account? This cannot be undone!')) {
                e.preventDefault();
            }
        });
    });
});