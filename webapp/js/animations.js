/**
 * Animatsiyalar - Yulduzchalar va Effektlar
 */

// Sahifa yuklanganda ishga tushadi
document.addEventListener('DOMContentLoaded', function() {
    createStars();
});

/**
 * Orqa fonda animatsiyali yulduzchalar yaratish
 */
function createStars() {
    const starsContainer = document.getElementById('starsBackground');
    const starCount = 50; // Yulduzchalar soni
    
    for (let i = 0; i < starCount; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        
        // Tasodifiy pozitsiya
        star.style.left = Math.random() * 100 + '%';
        star.style.top = Math.random() * 100 + '%';
        
        // Tasodifiy animatsiya davomiyligi
        const duration = 15 + Math.random() * 10; // 15-25 soniya
        star.style.animationDuration = duration + 's';
        
        // Tasodifiy animatsiya kechikishi
        star.style.animationDelay = Math.random() * 5 + 's';
        
        // Tasodifiy o'lcham
        const size = 2 + Math.random() * 3; // 2-5px
        star.style.width = size + 'px';
        star.style.height = size + 'px';
        
        // Tasodifiy yorqinlik
        const opacity = 0.3 + Math.random() * 0.7; // 0.3-1.0
        star.style.opacity = opacity;
        
        starsContainer.appendChild(star);
    }
}

/**
 * Smooth scroll animation
 */
function smoothScrollTo(element) {
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Neon pulse effektini elementga qo'shish
 */
function addNeonPulse(element, color = 'green') {
    const colors = {
        green: '#00ff00',
        red: '#ff0040',
        yellow: '#ffff00',
        blue: '#00d4ff'
    };
    
    const pulseColor = colors[color] || colors.green;
    
    element.style.animation = 'neonPulse 0.5s ease-in-out';
    element.style.boxShadow = `0 0 30px ${pulseColor}`;
    
    setTimeout(() => {
        element.style.animation = '';
    }, 500);
}

/**
 * Shake animatsiyasi (xato uchun)
 */
function shakeElement(element) {
    element.style.animation = 'shake 0.5s';
    
    setTimeout(() => {
        element.style.animation = '';
    }, 500);
}

// Shake animatsiya keyframes CSS ga qo'shish
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
        20%, 40%, 60%, 80% { transform: translateX(10px); }
    }
`;
document.head.appendChild(style);

/**
 * Confetti effekti (muvaffaqiyatli xarid uchun)
 */
function showConfetti() {
    const colors = ['#00ff00', '#ff0040', '#ffff00', '#00d4ff', '#b400ff'];
    const confettiCount = 50;
    
    for (let i = 0; i < confettiCount; i++) {
        const confetti = document.createElement('div');
        confetti.style.position = 'fixed';
        confetti.style.left = Math.random() * 100 + '%';
        confetti.style.top = '-10px';
        confetti.style.width = '10px';
        confetti.style.height = '10px';
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.zIndex = '10000';
        confetti.style.pointerEvents = 'none';
        confetti.style.borderRadius = '50%';
        
        document.body.appendChild(confetti);
        
        // Animatsiya
        const fallDuration = 2000 + Math.random() * 1000;
        const fallDistance = window.innerHeight + 100;
        const sway = (Math.random() - 0.5) * 200;
        
        confetti.animate([
            { 
                transform: 'translateY(0) translateX(0) rotate(0deg)',
                opacity: 1
            },
            { 
                transform: `translateY(${fallDistance}px) translateX(${sway}px) rotate(${Math.random() * 720}deg)`,
                opacity: 0
            }
        ], {
            duration: fallDuration,
            easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        }).onfinish = () => {
            confetti.remove();
        };
    }
}

/**
 * Loading animation ko'rsatish/yashirish
 */
function showLoading() {
    const loading = document.getElementById('loadingSpinner');
    if (loading) {
        loading.style.display = 'flex';
    }
}

function hideLoading() {
    const loading = document.getElementById('loadingSpinner');
    if (loading) {
        loading.style.display = 'none';
    }
}

/**
 * Toast notification (xabar ko'rsatish)
 */
function showToast(message, type = 'info') {
    const colors = {
        success: '#00ff00',
        error: '#ff0040',
        info: '#00d4ff',
        warning: '#ffff00'
    };
    
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.left = '50%';
    toast.style.transform = 'translateX(-50%)';
    toast.style.padding = '15px 30px';
    toast.style.backgroundColor = '#1a1a1a';
    toast.style.border = `2px solid ${colors[type]}`;
    toast.style.borderRadius = '10px';
    toast.style.color = colors[type];
    toast.style.boxShadow = `0 0 20px ${colors[type]}`;
    toast.style.zIndex = '10000';
    toast.style.fontSize = '16px';
    toast.style.fontWeight = 'bold';
    toast.style.textAlign = 'center';
    toast.style.maxWidth = '90%';
    
    document.body.appendChild(toast);
    
    // Animate in
    toast.animate([
        { opacity: 0, transform: 'translateX(-50%) translateY(-20px)' },
        { opacity: 1, transform: 'translateX(-50%) translateY(0)' }
    ], {
        duration: 300,
        easing: 'ease-out'
    });
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.animate([
            { opacity: 1, transform: 'translateX(-50%) translateY(0)' },
            { opacity: 0, transform: 'translateX(-50%) translateY(-20px)' }
        ], {
            duration: 300,
            easing: 'ease-in'
        }).onfinish = () => {
            toast.remove();
        };
    }, 3000);
}

// Global export
window.animations = {
    addNeonPulse,
    shakeElement,
    showConfetti,
    showLoading,
    hideLoading,
    showToast,
    smoothScrollTo
};
