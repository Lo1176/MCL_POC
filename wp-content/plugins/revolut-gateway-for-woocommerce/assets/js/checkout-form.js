/**
 * Validate State field
 */
jQuery(document).ready(function($) {
    $(document).on('click', '#place_order', function() {
        const billingStateField = $('#billing_state_field');
        const billingState = $('#billing_state');
        const checkRequired = billingStateField.prop('class').search('validate-required');

        if (checkRequired > 0) {
            if (billingStateField.css('display') != 'none' && billingState.val() == ''
                || billingState.val() == null) {
                alert('Please fill in the required fields');
                billingState.focus();
                return false;
            }
        }
    });
});