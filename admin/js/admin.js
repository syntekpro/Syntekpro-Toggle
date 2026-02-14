/**
 * Admin JavaScript for Syntekpro Toggle
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Initialize WordPress Color Picker
        if ($.fn.wpColorPicker) {
            $('.syntekpro-color-picker').wpColorPicker({
                change: function(event, ui) {
                    // Update preview when color changes
                    updatePreview();
                },
                clear: function() {
                    // Reset to default when cleared
                    updatePreview();
                }
            });
        }
        
        // Handle Frontend Color Presets
        $(document).on('change', 'input[name="syntekpro_toggle_options[color_preset]"]', function() {
            applyFrontendPreset();
        });
        
        // Handle Admin Color Presets
        $(document).on('change', 'input[name="syntekpro_toggle_options[admin_color_preset]"]', function() {
            applyAdminPreset();
        });
        
        // Live preview of settings (optional enhancement)
        function updatePreview() {
            // Get current values
            var bgColor = $('input[name="syntekpro_toggle_options[bg_color]"]').val();
            var textColor = $('input[name="syntekpro_toggle_options[text_color]"]').val();
            var linkColor = $('input[name="syntekpro_toggle_options[link_color]"]').val();
            
            // Update color preview indicators
            updateColorIndicator('bg_color', bgColor);
            updateColorIndicator('text_color', textColor);
            updateColorIndicator('link_color', linkColor);
        }
        
        function updateColorIndicator(field, color) {
            var $indicator = $('#' + field + '-indicator');
            if ($indicator.length) {
                $indicator.css('background-color', color);
            }
        }
        
        // Frontend Color Presets Data
        var frontendPresets = {
            'default': { bg: '#1a1a1a', text: '#ffffff', link: '#6ea8fe', secondary: '#2d2d2d' },
            'midnight': { bg: '#0f1419', text: '#e6edf3', link: '#58a6ff', secondary: '#1c2128' },
            'carbon': { bg: '#0d0d0d', text: '#f0f0f0', link: '#4a9eff', secondary: '#1a1a1a' },
            'slate': { bg: '#1e1e1e', text: '#d4d4d4', link: '#569cd6', secondary: '#2d2d2d' },
            'ocean': { bg: '#001f3f', text: '#e8f4f8', link: '#7fdbff', secondary: '#002a52' },
            'forest': { bg: '#0d1b0d', text: '#e8f5e9', link: '#81c784', secondary: '#1b2f1b' },
            'purple': { bg: '#1a0d2e', text: '#f3e5f5', link: '#ce93d8', secondary: '#2e1a3e' },
            'dracula': { bg: '#282a36', text: '#f8f8f2', link: '#8be9fd', secondary: '#44475a' },
            'nord': { bg: '#2e3440', text: '#eceff4', link: '#88c0d0', secondary: '#3b4252' },
            'monokai': { bg: '#272822', text: '#f8f8f2', link: '#66d9ef', secondary: '#3e3d32' },
            'solarized': { bg: '#002b36', text: '#839496', link: '#268bd2', secondary: '#073642' },
            'gruvbox': { bg: '#282828', text: '#ebdbb2', link: '#83a598', secondary: '#3c3836' },
            'material': { bg: '#263238', text: '#eeffff', link: '#82aaff', secondary: '#37474f' },
            'one': { bg: '#282c34', text: '#abb2bf', link: '#61afef', secondary: '#21252b' },
            'tokyo': { bg: '#1a1b26', text: '#c0caf5', link: '#7aa2f7', secondary: '#24283b' },
            'ayu': { bg: '#0f1419', text: '#e6e1cf', link: '#59c2ff', secondary: '#191e2a' },
            'cobalt': { bg: '#193549', text: '#ffffff', link: '#80ffbb', secondary: '#234e6d' },
            'espresso': { bg: '#2a211c', text: '#bdae9d', link: '#6c99bb', secondary: '#392e28' },
            'synthwave': { bg: '#262335', text: '#f92aad', link: '#72f1b8', secondary: '#382e3c' },
            'rose': { bg: '#191724', text: '#e0def4', link: '#c4a7e7', secondary: '#1f1d2e' }
        };
        
        // Admin Color Presets Data
        var adminPresets = {
            'default': { bg: '#0f1115', text: '#e7e9ee', accent: '#2563eb', surface: '#191e2a' },
            'nord': { bg: '#2e3440', text: '#eceff4', accent: '#88c0d0', surface: '#3b4252' },
            'dracula': { bg: '#282a36', text: '#f8f8f2', accent: '#bd93f9', surface: '#44475a' },
            'carbon': { bg: '#161616', text: '#f4f4f4', accent: '#0f62fe', surface: '#262626' },
            'tokyo': { bg: '#1a1b26', text: '#c0caf5', accent: '#7aa2f7', surface: '#24283b' },
            'monokai': { bg: '#272822', text: '#f8f8f2', accent: '#66d9ef', surface: '#3e3d32' },
            'gruvbox': { bg: '#282828', text: '#ebdbb2', accent: '#83a598', surface: '#3c3836' },
            'material': { bg: '#263238', text: '#eeffff', accent: '#82aaff', surface: '#37474f' },
            'one': { bg: '#282c34', text: '#abb2bf', accent: '#61afef', surface: '#21252b' },
            'ayu': { bg: '#1f2430', text: '#cbccc6', accent: '#73d0ff', surface: '#232834' },
            'solarized': { bg: '#002b36', text: '#839496', accent: '#268bd2', surface: '#073642' },
            'ocean': { bg: '#001f3f', text: '#e8f4f8', accent: '#7fdbff', surface: '#002a52' },
            'forest': { bg: '#0d1b0d', text: '#e8f5e9', accent: '#81c784', surface: '#1b2f1b' },
            'purple': { bg: '#1a0d2e', text: '#f3e5f5', accent: '#ce93d8', surface: '#2e1a3e' },
            'slate': { bg: '#1e1e1e', text: '#d4d4d4', accent: '#569cd6', surface: '#2d2d2d' }
        };
        
        // Apply Frontend Preset
        function applyFrontendPreset() {
            var preset = $('input[name="syntekpro_toggle_options[color_preset]"]:checked').val();
            if (!preset || !frontendPresets[preset]) return;
            
            var colors = frontendPresets[preset];
            
            // Update color picker values
            $('input[name="syntekpro_toggle_options[bg_color]"]').val(colors.bg).iris('color', colors.bg).trigger('change');
            $('input[name="syntekpro_toggle_options[text_color]"]').val(colors.text).iris('color', colors.text).trigger('change');
            $('input[name="syntekpro_toggle_options[link_color]"]').val(colors.link).iris('color', colors.link).trigger('change');
            $('input[name="syntekpro_toggle_options[secondary_bg_color]"]').val(colors.secondary).iris('color', colors.secondary).trigger('change');
            
            updatePreview();
        }
        
        // Apply Admin Preset
        function applyAdminPreset() {
            var preset = $('input[name="syntekpro_toggle_options[admin_color_preset]"]:checked').val();
            if (!preset || !adminPresets[preset]) return;
            
            var colors = adminPresets[preset];
            
            // Update color picker values
            $('input[name="syntekpro_toggle_options[admin_bg_color]"]').val(colors.bg).iris('color', colors.bg).trigger('change');
            $('input[name="syntekpro_toggle_options[admin_text_color]"]').val(colors.text).iris('color', colors.text).trigger('change');
            $('input[name="syntekpro_toggle_options[admin_accent_color]"]').val(colors.accent).iris('color', colors.accent).trigger('change');
            $('input[name="syntekpro_toggle_options[admin_surface_color]"]').val(colors.surface).iris('color', colors.surface).trigger('change');
            
            // Also set border and link colors based on the preset
            var borderColor = colors.surface;
            var linkColor = colors.accent;
            var linkHoverColor = colors.accent;
            
            $('input[name="syntekpro_toggle_options[admin_border_color]"]').val(borderColor).iris('color', borderColor).trigger('change');
            $('input[name="syntekpro_toggle_options[admin_link_color]"]').val(linkColor).iris('color', linkColor).trigger('change');
            $('input[name="syntekpro_toggle_options[admin_link_hover_color]"]').val(linkHoverColor).iris('color', linkHoverColor).trigger('change');
            
            updatePreview();
        }
        
        // Auto-apply presets on page load if one is selected
        if ($('input[name="syntekpro_toggle_options[color_scheme_mode]"]:checked').val() === 'preset') {
            applyFrontendPreset();
        }
        
        if ($('input[name="syntekpro_toggle_options[admin_color_scheme_mode]"]:checked').val() === 'preset') {
            applyAdminPreset();
        }
        
        // Add visual indicators for color choices
        $('.syntekpro-color-picker').each(function() {
            var $input = $(this);
            var color = $input.val();
            var fieldName = $input.attr('name').match(/\[([^\]]+)\]/)[1];
            
            // Add color indicator
            if (!$('#' + fieldName + '-indicator').length) {
                $input.closest('td').append(
                    '<span id="' + fieldName + '-indicator" class="color-indicator" style="display:inline-block;width:30px;height:30px;border:1px solid #ddd;border-radius:3px;margin-left:10px;vertical-align:middle;background-color:' + color + '"></span>'
                );
            }
        });
        
        // Button size preview
        $('input[name="syntekpro_toggle_options[button_size]"]').on('input', function() {
            var size = $(this).val();
            var $preview = $('#button-size-preview');
            
            if (!$preview.length) {
                $(this).after('<div id="button-size-preview" style="display:inline-block;margin-left:15px;vertical-align:middle;"></div>');
                $preview = $('#button-size-preview');
            }
            
            $preview.html('<span style="display:inline-block;width:' + size + 'px;height:' + size + 'px;border:2px solid #2271b1;border-radius:50%;background:#f0f0f1;"></span>');
        }).trigger('input');
        
        // Transition speed preview
        $('input[name="syntekpro_toggle_options[transition_speed]"]').on('input', function() {
            var speed = $(this).val();
            var $info = $('#transition-speed-info');
            
            if (!$info.length) {
                $(this).parent().append('<span id="transition-speed-info" style="margin-left:10px;color:#646970;"></span>');
                $info = $('#transition-speed-info');
            }
            
            if (speed == 0) {
                $info.text('(Instant)');
            } else if (speed < 0.2) {
                $info.text('(Very Fast)');
            } else if (speed < 0.4) {
                $info.text('(Fast)');
            } else if (speed < 0.6) {
                $info.text('(Normal)');
            } else if (speed < 1) {
                $info.text('(Slow)');
            } else {
                $info.text('(Very Slow)');
            }
        }).trigger('input');
        
        // Default mode help text
        $('#default_mode').on('change', function() {
            var mode = $(this).val();
            var $help = $(this).siblings('.description');
            
            var helpTexts = {
                'auto': 'Respects user\'s OS/system dark mode preference and updates automatically.',
                'light': 'Always starts in light mode. Users can manually toggle to dark.',
                'dark': 'Always starts in dark mode. Users can manually toggle to light.',
                'manual': 'Starts in light mode, users must click toggle to enable dark mode.'
            };
            
            if (helpTexts[mode]) {
                $help.text(helpTexts[mode]);
            }
        });
        
        // Validate custom CSS
        $('textarea[name="syntekpro_toggle_options[custom_css]"]').on('blur', function() {
            var css = $(this).val().trim();
            var $warning = $('#custom-css-warning');
            
            // Remove existing warning
            $warning.remove();
            
            // Check for common issues
            if (css.includes('{') || css.includes('}')) {
                $(this).after('<p id="custom-css-warning" style="color:#d63638;font-weight:600;">⚠️ Do not include curly braces { }. Only add CSS properties.</p>');
            }
        });
        
        // Form validation
        $('form').on('submit', function(e) {
            var buttonSize = parseInt($('input[name="syntekpro_toggle_options[button_size]"]').val());
            
            if (buttonSize < 30 || buttonSize > 100) {
                e.preventDefault();
                alert('Button size must be between 30 and 100 pixels.');
                return false;
            }
            
            var transitionSpeed = parseFloat($('input[name="syntekpro_toggle_options[transition_speed]"]').val());
            
            if (transitionSpeed < 0 || transitionSpeed > 2) {
                e.preventDefault();
                alert('Transition speed must be between 0 and 2 seconds.');
                return false;
            }
        });
        
        // Add reset to defaults button
        if (!$('#syntekpro-reset-defaults').length) {
            $('.submit').append(
                '<button type="button" id="syntekpro-reset-defaults" class="button" style="margin-left:10px;">Reset to Defaults</button>'
            );
            
            $('#syntekpro-reset-defaults').on('click', function() {
                if (confirm('Are you sure you want to reset all settings to their default values?')) {
                    // Set default values
                    $('#default_mode').val('auto');
                    $('input[name="syntekpro_toggle_options[enable_toggle]"]').prop('checked', true);
                    $('#button_position').val('bottom-right');
                    $('input[name="syntekpro_toggle_options[button_size]"]').val('50').trigger('input');
                    $('input[name="syntekpro_toggle_options[bg_color]"]').iris('color', '#1a1a1a');
                    $('input[name="syntekpro_toggle_options[text_color]"]').iris('color', '#ffffff');
                    $('input[name="syntekpro_toggle_options[link_color]"]').iris('color', '#6ea8fe');
                    $('input[name="syntekpro_toggle_options[secondary_bg_color]"]').iris('color', '#2d2d2d');
                    $('textarea[name="syntekpro_toggle_options[custom_css]"]').val('');
                    $('input[name="syntekpro_toggle_options[transition_speed]"]').val('0.3').trigger('input');
                    
                    updatePreview();
                    
                    alert('Settings reset to defaults. Click "Save Settings" to apply changes.');
                }
            });
        }
        
        // Show/hide toggle button setting based on default mode
        function toggleButtonVisibility() {
            var defaultMode = $('#default_mode').val();
            var $toggleRow = $('input[name="syntekpro_toggle_options[enable_toggle]"]').closest('tr');
            
            if (defaultMode === 'light' || defaultMode === 'dark') {
                $toggleRow.show();
                $toggleRow.find('.description').text('Users can still toggle if button is enabled.');
            } else {
                $toggleRow.show();
            }
        }
        
        $('#default_mode').on('change', toggleButtonVisibility);
        toggleButtonVisibility();
        
        // Add smooth scroll to sections
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this).attr('href');
            if ($(target).length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $(target).offset().top - 50
                }, 500);
            }
        });

        // Collapsible settings sections (h2/h3) in admin forms and .wrap areas
        // DISABLED: Using admin.php collapsible system instead to avoid duplication
        // (function initCollapsibleSections() {
            var $container = $('.syntekpro-toggle-admin');
            if (!$container.length) return;

            var $headings = $container.find('.wrap h2:not(.nav-tab-wrapper), .wrap h3, form h2, form h3');
            if (!$headings.length) return;

            $headings.each(function(index) {
                    var $heading = $(this);
                    if ($heading.hasClass('syntekpro-section-heading')) return;

                    // Collect section content until the next heading at same level
                    var $content = $heading.nextUntil('h2, h3');
                    if (!$content.length) return;

                    var $wrap = $('<div class="syntekpro-section-content"></div>');
                    $content.wrapAll($wrap);

                    var initiallyOpen = index === 0; // open first by default
                    var $toggle = $('<button type="button" class="button-link syntekpro-section-toggle" aria-expanded="' + initiallyOpen + '"><span class="dashicons ' + (initiallyOpen ? 'dashicons-arrow-down' : 'dashicons-arrow-right') + '"></span></button>');
                    $heading.addClass('syntekpro-section-heading').append($toggle);

                    if (!initiallyOpen) {
                        $wrap.hide();
                    }

                    var toggleContent = function(forceState) {
                        var isOpen = $wrap.is(':visible');
                        var nextState = typeof forceState === 'boolean' ? forceState : !isOpen;
                        if (nextState) {
                            $wrap.slideDown(150);
                        } else {
                            $wrap.slideUp(150);
                        }
                        $toggle.attr('aria-expanded', nextState);
                        $toggle.find('.dashicons')
                            .toggleClass('dashicons-arrow-down', nextState)
                            .toggleClass('dashicons-arrow-right', !nextState);
                    };

                    $toggle.on('click', function(e) {
                        e.preventDefault();
                        toggleContent();
                    });

                    $heading.on('click', function(e) {
                        if ($(e.target).closest('.syntekpro-section-toggle').length) return;
                        toggleContent();
                    });
                });
        })();
        // } DISABLED - Using admin.php collapsible system instead */
    });
    
})(jQuery);
