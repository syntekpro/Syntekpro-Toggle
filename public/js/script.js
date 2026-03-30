/**
 * Syntekpro-Toggle - Dark Mode Script
 * Handles dark mode toggle with localStorage persistence and analytics tracking
 */

(function() {
    'use strict';

    // Get settings from admin panel
    var settings = window.syntekproToggleSettings || {
        defaultMode: 'auto',
        enableToggle: true,
        storageMode: 'local',
        storageDays: 365,
        storageKey: 'syntekpro-dark-mode-v1',
        autoModeSource: 'os',
        autoTimeStart: '19:00',
        autoTimeEnd: '07:00',
        autoApplyOnLoad: true,
        autoListenOs: true,
        enableAnimations: true,
        toggleAnimationSpeed: 0.3,
        respectReducedMotion: true,
        forceHighContrast: false,
        focusRingStyle: 'default',
        analyticsDebounceMs: 500,
        analyticsBatch: false,
        analyticsBatchInterval: 5000,
        analyticsBatchMax: 10,
        analyticsPageviewOnceSession: true,
        debugMode: false
    };

    var storageKey = settings.storageKey || 'syntekpro-dark-mode-v1';
    var lastEventTimes = {};
    var eventQueue = [];
    var batchTimer = null;
    var toggleObserver = null;
    var osPreferenceListenerBound = false;

    // Track page view on load (if analytics enabled)
    trackAnalyticsEvent('page_view');

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDarkModeToggle);
    } else {
        initDarkModeToggle();
    }

    function initDarkModeToggle() {
        syncToggleButtons();
        observeToggleButtons();
        bindOsPreferenceListener();
    }

    function getToggleButtons() {
        return Array.prototype.slice.call(document.querySelectorAll('.syntekpro-dark-mode-toggle'));
    }

    function syncToggleButtons() {
        const toggleButtons = getToggleButtons();

        // Update button appearance based on current mode
        updateToggleButtons(toggleButtons);

        if (!toggleButtons.length) {
            return;
        }

        // Toggle dark mode on button click
        toggleButtons.forEach(function(toggleBtn) {
            if (toggleBtn.dataset.syntekproToggleBound === '1') {
                return;
            }

            toggleBtn.dataset.syntekproToggleBound = '1';
            toggleBtn.addEventListener('click', function() {
                const isDarkMode = document.documentElement.classList.toggle('dark-mode');

                // Save preference
                setStoredMode(isDarkMode);

                // Track toggle click
                trackAnalyticsEvent('toggle_click');

                // Track mode change
                trackAnalyticsEvent('mode_change', { mode: isDarkMode ? 'dark' : 'light' });

                // Update button appearance
                syncToggleButtons();
            });
        });
    }

    function bindOsPreferenceListener() {
        // Listen for OS preference changes (only if auto mode is enabled)
        if (
            osPreferenceListenerBound ||
            settings.defaultMode !== 'auto' ||
            !settings.autoListenOs ||
            settings.autoModeSource !== 'os' ||
            !window.matchMedia
        ) {
            return;
        }

        osPreferenceListenerBound = true;

        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        darkModeMediaQuery.addEventListener('change', function(e) {
            // Only apply OS preference if user hasn't manually set a preference
            const savedMode = getStoredMode();
            if (savedMode === null) {
                if (e.matches) {
                    document.documentElement.classList.add('dark-mode');
                } else {
                    document.documentElement.classList.remove('dark-mode');
                }
                syncToggleButtons();
            }
        });
    }

    function observeToggleButtons() {
        if (toggleObserver || !window.MutationObserver) {
            return;
        }

        const observerTarget = document.body || document.documentElement;

        if (!observerTarget) {
            return;
        }

        toggleObserver = new MutationObserver(function(mutations) {
            const foundToggle = mutations.some(function(mutation) {
                return Array.prototype.some.call(mutation.addedNodes, function(node) {
                    return node.nodeType === 1 && (
                        node.classList.contains('syntekpro-dark-mode-toggle') ||
                        node.querySelector('.syntekpro-dark-mode-toggle')
                    );
                });
            });

            if (foundToggle) {
                syncToggleButtons();
            }
        });

        toggleObserver.observe(observerTarget, {
            childList: true,
            subtree: true
        });
    }

    function updateToggleButtons(toggleButtons) {
        if (!toggleButtons || !toggleButtons.length) return;

        const isDarkMode = document.documentElement.classList.contains('dark-mode');

        toggleButtons.forEach(function(toggleBtn) {
            const sunIcon = toggleBtn.querySelector('.syntekpro-icon-sun');
            const moonIcon = toggleBtn.querySelector('.syntekpro-icon-moon');
            toggleBtn.classList.toggle('is-dark', isDarkMode);

            if (isDarkMode) {
                toggleBtn.setAttribute('aria-label', 'Switch to Light Mode');
                if (sunIcon) sunIcon.style.display = 'flex';
                if (moonIcon) moonIcon.style.display = 'none';
            } else {
                toggleBtn.setAttribute('aria-label', 'Switch to Dark Mode');
                if (sunIcon) sunIcon.style.display = 'none';
                if (moonIcon) moonIcon.style.display = 'flex';
            }
        });

        if (isDarkMode) {
            applyMediaFilters();
        } else {
            removeMediaFilters();
        }
    }

    function getCookie(name) {
        var value = '; ' + document.cookie;
        var parts = value.split('; ' + name + '=');
        if (parts.length === 2) {
            return parts.pop().split(';').shift();
        }
        return null;
    }

    function setCookie(name, value, days) {
        var maxAge = days * 24 * 60 * 60;
        document.cookie = name + '=' + value + '; Max-Age=' + maxAge + '; path=/; SameSite=Lax';
    }

    function getStoredMode() {
        var value = null;
        if (settings.storageMode === 'local' || settings.storageMode === 'both') {
            try {
                value = localStorage.getItem(storageKey);
            } catch (e) {
                value = null;
            }
        }
        if ((value === null || value === '') && (settings.storageMode === 'cookie' || settings.storageMode === 'both')) {
            value = getCookie(storageKey);
        }
        return value;
    }

    function setStoredMode(isDarkMode) {
        if (settings.storageMode === 'local' || settings.storageMode === 'both') {
            try {
                localStorage.setItem(storageKey, isDarkMode);
            } catch (e) {
                // Ignore storage errors
            }
        }
        if (settings.storageMode === 'cookie' || settings.storageMode === 'both') {
            setCookie(storageKey, isDarkMode, settings.storageDays || 365);
        }
    }

    /**
     * Apply filters to images, videos, and slides when dark mode is enabled
     */
    function applyMediaFilters() {
        if (!window.syntekproToggleSettings || !window.syntekproToggleSettings.mediaSettings) {
            return;
        }

        const mediaSettings = window.syntekproToggleSettings.mediaSettings;
        const root = document.documentElement;

        // Apply image filters (or set to default if disabled)
        if (mediaSettings.enableImageFilter) {
            const imageBrightness = (mediaSettings.imageBrightness || 100) / 100;
            const imageContrast = (mediaSettings.imageContrast || 100) / 100;
            root.style.setProperty('--syntekpro-image-brightness', imageBrightness);
            root.style.setProperty('--syntekpro-image-contrast', imageContrast);
        } else {
            // Set to default values when disabled
            root.style.setProperty('--syntekpro-image-brightness', '1');
            root.style.setProperty('--syntekpro-image-contrast', '1');
        }

        // Apply video filters (or set to default if disabled)
        if (mediaSettings.enableVideoFilter) {
            const videoBrightness = (mediaSettings.videoBrightness || 100) / 100;
            const videoContrast = (mediaSettings.videoContrast || 100) / 100;
            root.style.setProperty('--syntekpro-video-brightness', videoBrightness);
            root.style.setProperty('--syntekpro-video-contrast', videoContrast);
        } else {
            // Set to default values when disabled
            root.style.setProperty('--syntekpro-video-brightness', '1');
            root.style.setProperty('--syntekpro-video-contrast', '1');
        }

        // Apply slide filters (or set to default if disabled)
        if (mediaSettings.enableSlideFilter) {
            const slideBrightness = (mediaSettings.slideBrightness || 100) / 100;
            const slideInvert = mediaSettings.slideInvert ? 1 : 0;
            root.style.setProperty('--syntekpro-slide-brightness', slideBrightness);
            root.style.setProperty('--syntekpro-slide-invert', slideInvert);
        } else {
            // Set to default values when disabled
            root.style.setProperty('--syntekpro-slide-brightness', '1');
            root.style.setProperty('--syntekpro-slide-invert', '0');
        }
    }

    /**
     * Remove media filters when dark mode is disabled
     */
    function removeMediaFilters() {
        const root = document.documentElement;
        root.style.removeProperty('--syntekpro-image-brightness');
        root.style.removeProperty('--syntekpro-image-contrast');
        root.style.removeProperty('--syntekpro-video-brightness');
        root.style.removeProperty('--syntekpro-video-contrast');
        root.style.removeProperty('--syntekpro-slide-brightness');
        root.style.removeProperty('--syntekpro-slide-invert');
    }

    /**
     * Track analytics event via AJAX
     */
    function trackAnalyticsEvent(eventType, eventData) {
        // Only track if AJAX URL is available (WordPress environment)
        if (typeof syntekproToggleAjax === 'undefined') {
            return;
        }

        if (settings.analyticsPageviewOnceSession && eventType === 'page_view') {
            var sessionKey = storageKey + ':page_view';
            try {
                if (sessionStorage.getItem(sessionKey)) {
                    return;
                }
                sessionStorage.setItem(sessionKey, '1');
            } catch (e) {
                // Ignore storage errors
            }
        }

        if (settings.analyticsDebounceMs && settings.analyticsDebounceMs > 0) {
            var now = Date.now();
            if (lastEventTimes[eventType] && now - lastEventTimes[eventType] < settings.analyticsDebounceMs) {
                return;
            }
            lastEventTimes[eventType] = now;
        }

        if (settings.analyticsBatch) {
            queueAnalyticsEvent(eventType, eventData);
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

        if (settings.debugMode && window.console) {
            console.log('[Syntekpro Toggle] Tracked event:', eventType, eventData || {});
        }
    }

    function queueAnalyticsEvent(eventType, eventData) {
        eventQueue.push({ type: eventType, data: eventData || {} });

        if (eventQueue.length >= settings.analyticsBatchMax) {
            flushAnalyticsQueue();
            return;
        }

        if (!batchTimer) {
            batchTimer = setTimeout(function() {
                flushAnalyticsQueue();
            }, settings.analyticsBatchInterval);
        }
    }

    function flushAnalyticsQueue() {
        if (!eventQueue.length || typeof syntekproToggleAjax === 'undefined') {
            batchTimer = null;
            return;
        }

        var payload = eventQueue.slice(0);
        eventQueue = [];
        batchTimer = null;

        var data = new FormData();
        data.append('action', 'syntekpro_track_analytics');
        data.append('nonce', syntekproToggleAjax.nonce);
        data.append('events', JSON.stringify(payload));

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

        if (settings.debugMode && window.console) {
            console.log('[Syntekpro Toggle] Sent analytics batch:', payload);
        }
    }
})();
