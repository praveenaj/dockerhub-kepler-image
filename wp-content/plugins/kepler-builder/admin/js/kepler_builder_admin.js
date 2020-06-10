
(function( $ ) {
    "use strict";

    $(document).ready(function(){


        $('#kepler_theme__product-key__submit').click(function() {
            $(this).addClass('is_loading')
            $('#kepler_theme__product-key-error').html('')

            var code = $('#kepler_theme__product-key__input').val();
            var existingCode = $('#kepler_theme_existing_key').val();
            var self = this 
            $.ajax({
                type: "post",
                url: window.kepler_theme_ADMIN_AJAX_URL,
                dataType: "json",
                data: {
                    action: 'kepler_register_product',
                    code: code,
                    existingCode,existingCode
                },
                success: function (data) {
                    $(self).removeClass('is_loading')

                    if (!data.success) {
                        $('#kepler_theme__product-key-error').html(data.data.error)

                    } else {

                        $('#kepler_theme-product-id').text(code)
                        setPremiumLicence();
                        tb_remove();
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {}
            });

        })

        $('#kepler_theme__diagnostic-option').change(function() {
            var isEnabled = $(this).is(":checked")
            
            $.ajax({
                type: "post",
                url: window.kepler_theme_ADMIN_AJAX_URL,
                dataType: "json",
                data: {
                    action: 'kepler_enable_diagnostic',
                    enable: isEnabled
                },
                success: function (data) {

                    if (!data.success) {
                        alert("Unable to enable diagnostic")

                    } else {

                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {}
            });

        })

        $('#kepler_theme__btn-install').click(function(){

            $(this).addClass('is_loading').text('Waiting for Kepler Setup...')

            setInterval(function(){
                $.ajax({
                    type: "post",
                    url: window.kepler_theme_ADMIN_AJAX_URL,
                    dataType: "json",
                    data: {
                        action: 'kepler_get_wizard_status',
                    },
                    success: function (data) {
    
                        if (!data.success) {
                            
                        } else if(data.data.message === '1'){
                            location.reload();
                        }
    
                    },
                    error: function (jqXHR, textStatus, errorThrown) {}
                });
            }, 5000);
        });

        $('#kepler_theme__change_typkit_btn').click(function() {
            $(this).addClass('is_loading')
            $('#kepler_theme__typekit-key-error').html('')

            var key = $('#kepler_theme__typekit-key__input').val()
            var self = this 
            $.ajax({
                type: "post",
                url: window.kepler_theme_ADMIN_AJAX_URL,
                dataType: "json",
                data: {
                    action: 'kepler_set_typekit_kit',
					kit_id: key,
                },
                success: function (data) {
                    $(self).removeClass('is_loading')

                    if (!data.success) {
                        $('#kepler_theme__typekit-key-error').html('Invalid typekit key')

                    } else {
                        
                        tb_remove();
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {}
            });

        })

        $('#kepler_theme__google-key__submit').click(function() {
            $(this).addClass('is_loading')
            $('#kepler_theme__google-key-error').html('')

            var key = $('#kepler_theme__google-key__input').val()
            var self = this 
            $.ajax({
                type: "post",
                url: window.kepler_theme_ADMIN_AJAX_URL,
                dataType: "json",
                data: {
                    action: 'kepler_set_google_map_api',
                    google_key:key
                },
                success: function (data) {
                    $(self).removeClass('is_loading')

                    if (!data.success) {
                        $('#kepler_theme__google-key-error').html('Invalid google map key')

                    } else {
                        
                        tb_remove();
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {}
            });

        })

        function setPremiumLicence () {
            $.ajax({
                type: "post",
                url: window.kepler_theme_ADMIN_AJAX_URL,
                dataType: "json",
                data: {
                    action: 'kepler_set_key_type',
                    type: "Paid"
                },
                success: function (data) {
                    
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });

            $.ajax({
                type: "post",
                url: window.kepler_theme_ADMIN_AJAX_URL,
                dataType: "json",
                data: {
                    action: 'kepler_set_trial_status',
                    status: false
                },
                success: function (data) {
                
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        }

    });

})( jQuery );
