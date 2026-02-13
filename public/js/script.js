/**
 * Syntekpro-Toggle - Dark Mode Script
 * Handles dark mode toggle with localStorage persistence and analytics tracking
 */

(function() {
    'use strict';

    // Get settings from admin panel
    var settings = window.syntekproToggleSettings || {
        defaultMode: 'auto',
        enableToggle: true
    };

    // Track page view on load (if analytics enabled)
    trackAnalyticsEvent('page_view');

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
            
            // Track toggle click
            trackAnalyticsEvent('toggle_click');
            
            // Track mode change
            trackAnalyticsEvent('mode_change', { mode: isDarkMode ? 'dark' : 'light' });
            
            // Update button appearance
            updateToggleButton();
        });

        // Listen for OS preference changes (only if auto mode is enabled)
        if (settings.defaultMode === 'auto') {
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

    /**
     * Track analytics event via AJAX
     */
    function trackAnalyticsEvent(eventType, eventData) {
        // Only track if AJAX URL is available (WordPress environment)
        if (typeof syntekproToggleAjax === 'undefined') {
            return;
        }

        var data = new FormData();
        data.append('action', 'syntekpro_track_analytics');
        data.append('nonce', syntekproToggleAjax.nonce);
        data.append('event_type', eventType);
        
        if (eventData) {
            data.append('event_data', JSON.stringify(eventData));
        }

        // Send non-blocking request
        if (navigator.sendBeacon) {
            navigator.sendBeacon(syntekproToggleAjax.ajax_url, data);
        } else {
            fetch(syntekproToggleAjax.ajax_url, {
                method: 'POST',
                body: data,
                keepalive: true
            }).catch(function() {
                // Silently fail - analytics shouldn't break functionality
            });
        }
    }
})();
