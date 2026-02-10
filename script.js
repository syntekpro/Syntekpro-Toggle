/**
 * Syntekpro-Toggle - Dark Mode Script
 * Handles dark mode toggle with localStorage persistence
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDarkModeToggle);
    } else {
        initDarkModeToggle();
    }

    function initDarkModeToggle() {
        const toggleBtn = document.getElementById('syntekpro-dark-mode-toggle');
        
        if (!toggleBtn) {
            return;
        }

        // Update button appearance based on current mode
        updateToggleButton();

        // Toggle dark mode on button click
        toggleBtn.addEventListener('click', function() {
            const isDarkMode = document.documentElement.classList.toggle('dark-mode');
            
            // Save preference to localStorage
            localStorage.setItem('syntekpro-dark-mode', isDarkMode);
            
            // Update button appearance
            updateToggleButton();
        });

        // Listen for OS preference changes
        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        darkModeMediaQuery.addEventListener('change', function(e) {
            // Only apply OS preference if user hasn't manually set a preference
            const savedMode = localStorage.getItem('syntekpro-dark-mode');
            if (savedMode === null) {
                if (e.matches) {
                    document.documentElement.classList.add('dark-mode');
                } else {
                    document.documentElement.classList.remove('dark-mode');
                }
                updateToggleButton();
            }
        });
    }

    function updateToggleButton() {
        const toggleBtn = document.getElementById('syntekpro-dark-mode-toggle');
        if (!toggleBtn) return;

        const isDarkMode = document.documentElement.classList.contains('dark-mode');
        const sunIcon = toggleBtn.querySelector('.syntekpro-icon-sun');
        const moonIcon = toggleBtn.querySelector('.syntekpro-icon-moon');

        if (isDarkMode) {
            toggleBtn.setAttribute('aria-label', 'Switch to Light Mode');
            if (sunIcon) sunIcon.style.display = 'block';
            if (moonIcon) moonIcon.style.display = 'none';
        } else {
            toggleBtn.setAttribute('aria-label', 'Switch to Dark Mode');
            if (sunIcon) sunIcon.style.display = 'none';
            if (moonIcon) moonIcon.style.display = 'block';
        }
    }
})();
