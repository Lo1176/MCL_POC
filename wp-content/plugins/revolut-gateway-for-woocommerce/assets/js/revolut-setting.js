jQuery(document).ready(function ($) {
    const setupWebhookSandbox = $('#woocommerce_revolut_setup_webhook_sandbox');
    const setupWebhookLive = $('#woocommerce_revolut_setup_webhook_live');
    setupWebhookSandbox.hide();
    $('#woocommerce_revolut_cc_restore_button').hide();
    setupWebhookLive.hide();
    const revolutMode = $('#woocommerce_revolut_mode');
    const apiSandboxKey = $('#woocommerce_revolut_api_key_sandbox').parents().closest('tr');
    const apiLiveKey = $('#woocommerce_revolut_api_key').parents().closest('tr');
    const setup_webhook_sandbox = setupWebhookSandbox.parents('tr');
    const setup_webhook_live = setupWebhookLive.parents('tr');
    const payment_action = $('#woocommerce_revolut_payment_action');
    const capture_checkbox = $('#woocommerce_revolut_accept_capture').parents().closest('tr');

    if (revolutMode.val() == "sandbox") {
        change("sandbox");
    } else {
        change("live");
    }
    if (payment_action.val() == 'authorize') {
        capture_checkbox.show();
    } else {
        capture_checkbox.hide();
    }

    revolutMode.on('change', function () {
        var mode = $(this).val();

        if (mode == "sandbox") {
            change("sandbox");
        } else {
            change("live");
        }
    });

    // change payment action
    payment_action.on('change', function () {
        if (payment_action.val() == 'authorize') {
            capture_checkbox.show();
        } else {
            capture_checkbox.hide();
        }
    });

    // change API mode
    function change(mode) {
        if (mode == "sandbox") {
            apiSandboxKey.show();
            setup_webhook_sandbox.show()
            apiLiveKey.hide();
            setup_webhook_live.hide();
        } else {
            apiSandboxKey.hide();
            apiLiveKey.show();
            setup_webhook_sandbox.hide();
            setup_webhook_live.show();
        }
    }

    // set up webhook
    $('.setup-webhook').click(function (event) {
        event.preventDefault();
        const mode = $('#woocommerce_revolut_mode').val();
        let api;
        if (mode == "sandbox") {
            api = $('#woocommerce_revolut_api_key_sandbox').val();
        } else {
            api = $('#woocommerce_revolut_api_key').val();
        }
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'wc_revolut_set_webhook',
                mode: mode,
                apiKey: api
            },

            success: function (response) {
                if (mode == "sandbox") {
                    if (response == true) {
                        $("#span-for-active-button-sandbox").text("Success");
                        $('.setup-webhook').prop('disabled', true);
                    } else {
                        $("#span-for-active-button-sandbox").text("Setup Failed");
                    }
                } else {
                    if (response == true) {
                        $("#span-for-active-button-live").text("Success");
                        $('.setup-webhook').prop('disabled', true);
                    } else {
                        $("#span-for-active-button-live").text("Setup Failed");
                    }
                }
            }
        });
    });

    $(document).on('change', '.revolut_styling_option_enable', function () {
        if (!$('.revolut_styling_option_enable').is(':checked')) {
            $('.revolut_styling_option').parents('tr').hide();
            restoreStylinOptions();
        } else {
            $('.revolut_styling_option').parents('tr').show();
        }
    });

    $(document).on('click', '.revolut_style_restore', function () {
        restoreStylinOptions();
    });


    if (!$('.revolut_styling_option_enable').is(':checked')) {
        $('.revolut_styling_option').parents('tr').hide();
    }

    function restoreStylinOptions() {
        $('#woocommerce_revolut_cc_widget_background_color').val(default_options.default_bg_color).trigger('change');
        $('#woocommerce_revolut_cc_widget_text_color').val(default_options.default_text_color).trigger('change');
        $('#woocommerce_revolut_cc_revolut_logo_color').prop("selectedIndex", 0).val();
    }
});