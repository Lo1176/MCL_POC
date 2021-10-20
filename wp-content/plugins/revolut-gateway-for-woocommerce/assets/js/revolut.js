const PAYMENT_METHOD = {CreditCard: 'revolut_cc', RevolutPay: 'revolut_pay'};

/**
 * Get payment data
 * @param {Object} address
 * @return {{}}
 */
function getPaymentData(address) {
    let paymentData = {};

    paymentData.name = address.billing_first_name + ' ' + address.billing_last_name;
    paymentData.email = address.billing_email;
    paymentData.phone = address.billing_phone;

    if (address.billing_country !== undefined && typeof address.billing_postcode !== undefined) {
        paymentData.billingAddress = {
            countryCode: address.billing_country,
            region: address.billing_state,
            city: address.billing_city,
            streetLine1: address.billing_address_1,
            streetLine2: address.billing_address_2,
            postcode: address.billing_postcode,
        };
    }


    if (address.ship_to_different_address && address.shipping_country !== undefined && address.shipping_postcode !== undefined) {
        paymentData.shippingAddress = {
            countryCode: address.shipping_country,
            region: address.shipping_state,
            city: address.shipping_city,
            streetLine1: address.shipping_address_1,
            streetLine2: address.shipping_address_2,
            postcode: address.shipping_postcode,
        };
    }

    return paymentData;
}

jQuery(function ($) {
    let $body = $(document.body);
    let $form = $('form.woocommerce-checkout');
    let $order_review = $('#order_review');
    let $payment_save = $('form#add_payment_method');

    let isSubmitting = false;
    let isSubmitted = false;
    let cardStatus = null;

    /**
     * Start processing
     */
    function startProcessing() {
        $.blockUI({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
        isSubmitting = true;
    }

    /**
     * Stop processing
     */
    function stopProcessing() {
        $.unblockUI();
        $('.blockUI.blockOverlay').hide();
        isSubmitting = false;
    }

    /**
     * Handle status change
     * @param {string} status
     */
    function handleStatusChange(status) {
        cardStatus = status;
    }

    /**
     * Handle validation
     * @param {array} errors
     */
    function handleValidation(errors) {
        let messages = errors.filter((item) => item != null).map((item) => item.toString())
        displayError(messages);
    }

    /**
     * Handle error message
     * @param {string} message
     */
    function handleError(messages) {
        messages = messages.toString().split(',');
        messages = messages.filter(Boolean);
        const currentPaymentMethod = getPaymentMethod();

        if (!messages.length || messages.length < 0 || !currentPaymentMethod) {
            return;
        }

        let isChangePaymentMethodAddPage = $payment_save.length > 0;

        if (isChangePaymentMethodAddPage) {
            displayError(messages);
        } else {
            handleSuccess(messages);
        }
    }

    /**
     * Display error message
     * @param messages
     */
    function displayError(messages) {
        const currentPaymentMethod = getPaymentMethod();
        $('.revolut-error').remove();

        if (!messages.length || messages.length < 0 || !currentPaymentMethod) {
            return;
        }

        let error_view = '<div class="revolut-error woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' +
            '<ul class="woocommerce-error">' +
            messages.map(function (message) {
                return '<li>' + message + '</li>';
            }).join('') +
            '</ul>' +
            '</div>';

        currentPaymentMethod.methodId === PAYMENT_METHOD.RevolutPay
            ? $('#woocommerce-revolut-pay-element').after(error_view)
            : $('#wc-revolut_cc-cc-form').after(error_view);

        stopProcessing();
    }

    /**
     * Handle cancel
     */
    function handleCancel() {
        stopProcessing();
    }

    /**
     * Check if we should save the selected Payment Method
     */
    function shouldSavePaymentMethod() {
        const currentPaymentMethod = $('input[name="payment_method"]:checked').val();
        let savePaymentDetails = 0;

        let target = document.getElementById('woocommerce-revolut-card-element');

        if (currentPaymentMethod === PAYMENT_METHOD.CreditCard) {
            if ($('#wc-revolut_cc-new-payment-method').length) {
                savePaymentDetails = $('#wc-revolut_cc-new-payment-method:checked').length;
            } else {
                savePaymentDetails = target.dataset.paymentMethodSaveIsMandatory;
            }
        }

        return savePaymentDetails;
    }

    /**
     * Check if Revolut Payment options is selected
     */
    function isRevolutPaymentMethodSelected() {
        const currentPaymentMethod = $('input[name="payment_method"]:checked').val();
        return currentPaymentMethod === PAYMENT_METHOD.CreditCard || currentPaymentMethod === PAYMENT_METHOD.RevolutPay;
    }

    /**
     * Check if we should use the saved Payment Method
     */
    function payWithPaymentToken() {
        return $('#wc-revolut_cc-payment-token-new:checked').length < 1 && $('[id^="wc-revolut_cc-payment-token"]:checked').length > 0;
    }

    /**
     * Handle if success
     */
    function handleSuccess(errorMessage = '') {
        isSubmitting = false;
        isSubmitted = true;
        stopProcessing();
        const currentPaymentMethod = getPaymentMethod();
        const savePaymentMethod = shouldSavePaymentMethod();

        const el_input_public_id = '<input type="hidden" class="revolut_public_id" name="revolut_public_id" value="' + currentPaymentMethod.publicId + '">';
        const el_input_save_payment_method = '<input type="hidden" class="revolut_cc-save-payment-method" name="revolut_cc-save-payment-method" value="' + savePaymentMethod + '">';
        const el_input_payment_error = '<input type="hidden" class="revolut_payment_error" name="revolut_payment_error" value="' + errorMessage + '">';

        if ($form.length) {
            $form.append(el_input_public_id);
            $form.append(el_input_save_payment_method);
            $form.append(el_input_payment_error);
            $form.removeClass('.processing');
            $form.trigger('submit');
        } else if ($order_review.length) {
            $order_review.append(el_input_public_id);
            $order_review.append(el_input_save_payment_method);
            $order_review.append(el_input_payment_error);
            $order_review.submit();
        } else if ($payment_save.length) {
            $payment_save.append(el_input_public_id);
            $payment_save.append(el_input_save_payment_method);
            $payment_save.append(el_input_payment_error);
            $payment_save.submit();
        }
    }

    let instance = null;

    /**
     * Update revolut order
     */
    function handleUpdate() {

        const currentPaymentMethod = getPaymentMethod();
        if (instance !== null) {
            instance.destroy();
        }

        togglePlaceOrderButton();

        if (currentPaymentMethod.methodId !== PAYMENT_METHOD.RevolutPay) {
            let cardWidgetPaymentOptions = {
                target: currentPaymentMethod.target,
                hidePostcodeField: !$body.hasClass('woocommerce-add-payment-method'),
                locale: currentPaymentMethod.locale,
                onCancel: handleCancel,
                onStatusChange: handleStatusChange,
                onValidation: handleValidation,
                onError: handleError,
                onSuccess: handleSuccess,
                styles: {
                    default: {
                        color: currentPaymentMethod.textcolor,
                        "::placeholder": {
                            color: currentPaymentMethod.textcolor,
                        }
                    },
                },
            };

            if (currentPaymentMethod.savePaymentDetails > 0) {
                cardWidgetPaymentOptions['savePaymentMethodFor'] = currentPaymentMethod.savePaymentMethodFor;
            }
            instance = RevolutCheckout(currentPaymentMethod.publicId).createCardField(cardWidgetPaymentOptions);
        } else {
            instance = RevolutCheckout(currentPaymentMethod.publicId).revolutPay({
                target: currentPaymentMethod.target,
                locale: currentPaymentMethod.locale,
                validate: handleRevolutPaySubmit,
                onCancel: handleCancel,
                onError: handleError,
                onSuccess: handleSuccess,
            });
        }
    }

    /**
     * Show validation errors
     * @param errorMessage
     */
    function submitError(errorMessage) {
        $('.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message').remove();
        $form.prepend('<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + errorMessage + '</div>'); // eslint-disable-line max-len
        $form.removeClass('processing').unblock();
        $form.find('.input-text, select, input:checkbox').trigger('validate').blur();
        var scrollElement = $('.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout');
        if (!scrollElement.length) {
            scrollElement = $('.form.checkout');
        }
        $.scroll_to_notices(scrollElement);
        $(document.body).trigger('checkout_error');
    }

    /**
     * Submit credit card payment
     * @return {boolean}
     */
    function handleCreditCardSubmit() {
        if (isSubmitting) {
            return false;
        }
        if (isSubmitted) {
            isSubmitted = false;
            return true;
        }

        startProcessing();

        if (payWithPaymentToken()) {
            handleSuccess();
            return false;
        }

        const json = getCheckoutFormData();
        if (json.payment_method === PAYMENT_METHOD.CreditCard) {
            validateCheckoutForm().then(function (valid) {
                if (valid) {
                    instance.submit(getPaymentData(json));
                } else {
                    stopProcessing();
                }
            }).catch(function (err) {
                stopProcessing();
                submitError(err);
            });
        }
        return false;
    }

    /**
     * Validate checkout form entries
     * @returns {Promise(boolean)}
     */
    function validateCheckoutForm() {
        return new Promise(function (resolve, reject) {

            if ($body.hasClass('woocommerce-order-pay')) {
                resolve(true);
            }

            $.ajax({
                type: 'POST',
                url: wc_checkout_params.checkout_url,
                data: $form.serialize() + '&revolut_create_wc_order=1',
                dataType: 'json',
                success: function (result) {
                    if (result.result === 'revolut_wc_order_created' || result.result === 'success') {
                        resolve(true);
                    } else if (result.result === 'fail' ||  result.result === 'failure') {
                        stopProcessing();
                        resolve(false);
                        if (typeof result.messages != "undefined") {
                            submitError(result.messages);
                        } else {
                            submitError('<div class="woocommerce-error">' + wc_checkout_params.i18n_checkout_error + '</div>');
                        }
                    } else {
                        stopProcessing();
                        resolve(false);
                        submitError('<div class="woocommerce-error">Invalid response</div>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    stopProcessing();
                    resolve(false);
                    submitError('<div class="woocommerce-error">' + errorThrown + '</div>');
                }
            });
        });
    }

    if ($body.hasClass('woocommerce-order-pay') || $body.hasClass('woocommerce-add-payment-method')) {
        handleUpdate();
    }

    /**
     * Submit card on Manual Order Payment
     */
    function submitOrderPay() {
        const json = getCheckoutFormData();
        if (json.payment_method === PAYMENT_METHOD.CreditCard) {
            if (isSubmitting) {
                return false;
            }
            if (isSubmitted) {
                isSubmitted = false;
                return true;
            }

            startProcessing();

            if (payWithPaymentToken()) {
                return handleSuccess();
            }

            validateCheckoutForm().then(function (valid) {
                if (valid) {
                    getBillingInfo().then(function (billing_info) {
                        if ($body.hasClass('woocommerce-order-pay')) {
                            instance.submit(billing_info);
                        }
                    });
                } else {
                    stopProcessing();
                }
            })
            return false;
        }
    }


    /**
     * Submit card on Payment method save
     */
    function submitPaymentMethodSave() {
        if (payWithPaymentToken()) {
            return handleSuccess();
        }

        let isChangePaymentMethodPage = $('#wc-revolut-change-payment-method').length > 0;

        const json = getCheckoutFormData();
        if (json.payment_method === PAYMENT_METHOD.CreditCard) {
            getCustomerBaseInfo().then(function (billing_info) {
                if ($body.hasClass('woocommerce-add-payment-method') || isChangePaymentMethodPage) {
                    instance.submit(billing_info);
                }
            });
        }
    }

    /**
     * Get checkout form entries as json
     * @returns {{}}
     */
    function getCheckoutFormData() {
        let current_form = $form;

        if ($payment_save.length) {
            current_form = $payment_save;
        }

        let checkout_form_data = {};

        if (wc_revolut.page === 'order_pay') {
            checkout_form_data = $('#order_review').serializeArray().reduce(function (acc, item) {
                if (item.name.search('billing') >= 0) {
                    if (item.name.search('email') >= 0) {
                        acc[item.name] = item.value;
                    }
                } else {
                    acc[item.name] = item.value;
                }
                return acc;
            }, {});
        } else {
            checkout_form_data = current_form.serializeArray().reduce(function (acc, item) {
                acc[item.name] = item.value;
                return acc;
            }, {});
        }

        if (checkout_form_data['shipping_country'] == '') {
            if ($('#ship-to-different-address-checkbox').length > 0 && !$('#ship-to-different-address-checkbox').is(':checked')) {
                checkout_form_data['shipping_country'] = checkout_form_data['billing_country'];
            }
        }

        return checkout_form_data;
    }


    /**
     * Submit credit card payment
     * @return {Promise(boolean)}
     */
    function handleRevolutPaySubmit() {
        return new Promise(function (resolve, reject) {
            startProcessing();
            validateCheckoutForm().then(function (valid) {
                stopProcessing();
                if (!valid) {
                    resolve(false);
                }
                resolve(true);
            }).catch(function (err) {
                submitError(err);
                reject(err);
            });
        });
    }

    /**
     * Get billing info for manual order payments
     * @returns {Promise({})}
     */
    function getBillingInfo() {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: "POST",
                url: wc_revolut.ajaxurl,
                data: {
                    action: 'get_order_pay_billing_info',
                    order_id: wc_revolut.order_id,
                    order_key: wc_revolut.order_key,
                },

                success: function (response) {
                    resolve(response);
                },
                catch: function (err) {
                    reject(err);
                }
            });
        });
    }

    /**
     * Get customer billing info for payment method save
     * @returns {Promise({})}
     */
    function getCustomerBaseInfo() {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: "POST",
                url: wc_revolut.ajaxurl,
                data: {
                    action: 'get_customer_base_info',
                },

                success: function (response) {
                    resolve(response);
                },
                catch: function (err) {
                    reject(err);
                }
            });
        });
    }

    /**
     * Show/hide order button based on selected payment method
     */
    function togglePlaceOrderButton() {
        const currentPaymentMethod = getPaymentMethod();
        currentPaymentMethod.methodId !== PAYMENT_METHOD.RevolutPay
            ? $('#place_order').removeClass('hidden_by_revolut').show()
            : $('#place_order').addClass('hidden_by_revolut').hide();
    }

    /**
     * Get selected payment method
     * @returns {{}}
     */
    function getPaymentMethod() {
        const currentPaymentMethod = $('input[name="payment_method"]:checked').val();
        let target = currentPaymentMethod !== PAYMENT_METHOD.RevolutPay
            ? document.getElementById('woocommerce-revolut-card-element')
            : document.getElementById('woocommerce-revolut-pay-element');
        if (target == null) {
            return false;
        }
        let publicId = target.dataset.publicId;
        let locale = target.dataset.locale;
        let textcolor = target.dataset.textcolor;


        let savePaymentDetails = 0;
        let savePaymentMethodFor = '';
        if (currentPaymentMethod === PAYMENT_METHOD.CreditCard) {
            if ($('#wc-revolut_cc-new-payment-method').length) {
                savePaymentDetails = $('#wc-revolut_cc-new-payment-method:checked').length;
                savePaymentMethodFor = $('#wc-revolut_cc-new-payment-method').val();
            } else {
                savePaymentDetails = target.dataset.paymentMethodSaveIsMandatory;
                savePaymentMethodFor = target.dataset.savePaymentFor;
            }
        }

        return {
            methodId: currentPaymentMethod,
            target: target,
            publicId: publicId,
            locale: locale,
            textcolor: textcolor,
            savePaymentDetails: savePaymentDetails,
            savePaymentMethodFor: savePaymentMethodFor,
        }
    }

    $body.on('updated_checkout payment_method_selected', handleUpdate);
    $form.on('checkout_place_order_revolut_cc', handleCreditCardSubmit);

    $order_review.on('submit', function (e) {
        if (isRevolutPaymentMethodSelected()) {
            if ($('.revolut_public_id').length === 0) {
                e.preventDefault();
                let isChangePaymentMethodPage = $('#wc-revolut-change-payment-method').length > 0;
                if (isChangePaymentMethodPage) {
                    submitPaymentMethodSave();
                } else {
                    submitOrderPay();
                }
            } else {
                startProcessing();
            }
        }
    });

    $payment_save.on('submit', function (e) {
        if (isRevolutPaymentMethodSelected()) {
            if ($('.revolut_public_id').length === 0) {
                e.preventDefault();
                submitPaymentMethodSave()
            }
        }
    });

    $(document).on('change', '#wc-revolut_cc-new-payment-method', function () {
        handleUpdate();
    });

    if (wc_revolut.page === 'order_pay') {
        $(document.body).trigger('wc-credit-card-form-init');
    }
});

