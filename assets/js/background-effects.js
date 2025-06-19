// background-effects.js
export function initializeBackgroundEffects() {
    // Create floating orbs
    const orb1 = document.createElement('div');
    orb1.className = 'floating-orb orb-1';
    document.body.appendChild(orb1);
    
    const orb2 = document.createElement('div');
    orb2.className = 'floating-orb orb-2';
    document.body.appendChild(orb2);
    
    // Create grid pattern
    const grid = document.createElement('div');
    grid.className = 'grid-pattern';
    document.body.appendChild(grid);
    
    // Dynamic glow effect on auth container
    const authContainer = document.querySelector('.auth-container');
    if (authContainer) {
        authContainer.addEventListener('mousemove', (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            authContainer.style.setProperty('--mouse-x', x);
            authContainer.style.setProperty('--mouse-y', y);
            
            const glowX = (x - 0.5) * 20;
            const glowY = (y - 0.5) * 20;
            
            authContainer.style.boxShadow = `
                ${glowX}px ${glowY}px 30px rgba(0, 240, 255, 0.2),
                0 8px 32px rgba(0, 0, 0, 0.3)
            `;
        });
        
        authContainer.addEventListener('mouseleave', () => {
            authContainer.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.3)';
        });
    }
}