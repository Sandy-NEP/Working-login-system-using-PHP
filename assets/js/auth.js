
// auth.js
import { initializePasswordToggle } from './password-toggle.js';
import { initializeFormValidation } from './form-validation.js';
import { initializePasswordStrength } from './password-strength.js';
import { initializeFormAnimations } from './animations.js';
import { initializeButtonLoading } from './button-loading.js';
import { initializeBackgroundEffects } from './background-effects.js';
import { createParticles } from './particles.js';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initializePasswordToggle();
    initializeFormValidation();
    initializePasswordStrength();
    initializeFormAnimations();
    initializeButtonLoading();
    initializeBackgroundEffects();
    createParticles();
});