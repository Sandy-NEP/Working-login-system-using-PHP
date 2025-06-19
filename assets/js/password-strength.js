// password-strength.js
export function initializePasswordStrength() {
    document.querySelectorAll('input[type="password"]').forEach(input => {
        // Create strength meter if it doesn't exist
        if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('password-strength')) {
            const strengthMeter = document.createElement('div');
            strengthMeter.className = 'password-strength';
            strengthMeter.innerHTML = '<div class="strength-meter"></div>';
            input.parentNode.insertBefore(strengthMeter, input.nextSibling);
        }
        
        input.addEventListener('input', function() {
            const strengthMeter = this.nextElementSibling.querySelector('.strength-meter');
            const strength = checkPasswordStrength(this.value);
            
            strengthMeter.className = 'strength-meter';
            strengthMeter.classList.add(strength.class);
            strengthMeter.style.width = strength.width;
        });
    });
}

function checkPasswordStrength(password) {
    let strength = 0;
    
    // Length check
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // Character variety checks
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    // Determine strength class
    if (strength <= 2) {
        return { class: 'weak', width: '30%' };
    } else if (strength <= 4) {
        return { class: 'medium', width: '60%' };
    } else {
        return { class: 'strong', width: '100%' };
    }
}