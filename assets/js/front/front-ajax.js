jQuery(document).ready(function ($) {
    $('#nl_form').on('submit', function (e) {
        e.preventDefault();
        let email = $('#nl_email').val();

                // AJAX request to filter posts
                        $.ajax({
                            url:nl_ajax.nl_ajaxurl , //ajax url
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'nl_new_letter_subscribe',
                                email:email,
                                _nonce:nl_ajax._nl_nonce
                            },
                            beforeSend: function () {
                                $('.nl-btn').html('<div class="nl-loader"></div>');
                            },
                            success: function (response) {
                                if (response.success) {
                                    $.toast({
                                        text: response.message, // Text that is to be shown in the toast
                                        heading: ' ', // Optional heading to be shown on the toast
                                        icon: 'success', // Type of toast icon
                                        showHideTransition: 'slide', // fade, slide or plain
                                        allowToastClose: false, // Boolean value true or false
                                        hideAfter: 5000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
                                        stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
                                        position: 'top-left', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values


                                        textAlign: 'right',  // Text alignment i.e. left, right or center
                                        loader: true,  // Whether to show loader or not. True by default
                                        loaderBg: '#9EC600',  // Background color of the toast loader
                                        beforeShow: function () {
                                        }, // will be triggered before the toast is shown
                                        afterShown: function () {
                                        }, // will be triggered after the toat has been shown
                                        beforeHide: function () {
                                        }, // will be triggered before the toast gets hidden
                                        afterHidden: function () {
                                        }  // will be triggered after the toast has been hidden
                                    });
                                }
                            },
                            error: function (error) {
                                if (error.error) {
                                    $.toast({
                                        text: error.responseJSON.message, // Text that is to be shown in the toast
                                        heading: error.responseJSON.title, // Optional heading to be shown on the toast
                                        icon: 'error', // Type of toast icon
                                        showHideTransition: 'slide', // fade, slide or plain
                                        allowToastClose: false, // Boolean value true or false
                                        hideAfter: 5000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
                                        stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
                                        position: 'top-left', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values


                                        textAlign: 'right',  // Text alignment i.e. left, right or center
                                        loader: true,  // Whether to show loader or not. True by default
                                        loaderBg: '#9EC600',  // Background color of the toast loader
                                        beforeShow: function () {
                                        }, // will be triggered before the toast is shown
                                        afterShown: function () {
                                        }, // will be triggered after the toat has been shown
                                        beforeHide: function () {
                                        }, // will be triggered before the toast gets hidden
                                        afterHidden: function () {
                                        }  // will be triggered after the toast has been hidden
                                    });
                                }
                            },
                            complete: function () {
                                $('.nl-btn').text('عضویت');
                            },
                        });
    });
});