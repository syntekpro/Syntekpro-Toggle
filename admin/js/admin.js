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
        (function initCollapsibleSections() {
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
    });
    
})(jQuery);
