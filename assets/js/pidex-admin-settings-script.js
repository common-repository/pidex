//! phpcs:ignoreFile
//! pidex-admin-settings-script.js - for Pidex WordPress Plugin.
//! version 	: 1.0.0
//! author 		: Pidex Infosys
//! author uri	: https://pidex.biz/pidexinfosys
//! license 	: GPLv3
//! license uri : https://www.gnu.org/licenses/gpl-3.0.html

jQuery(document).ready(function ($) {
    $('#pidex-submit-settings-button').prop('disabled', false);
    $('#automatic_order-checkbox').val() == 'true' ? $('#automatic_order-checkbox').prop("checked", true) : $('#automatic_order-checkbox').prop("checked", false);

    $(document).on('change', '#automatic_order-checkbox', function () {
        if ($('#automatic_order-checkbox').is(':checked')) {
            $('#automatic_order-checkbox').val('true');
            $('#automatic_order-checkbox-text').text('Allow automatic booking when user checks out');
        } else {
            $('#automatic_order-checkbox').val('false');
            $('#automatic_order-checkbox-text').text('Do not allow automatic booking when user checks out');
        }
    });

    function hidePassword() {
        $('#api_token_eye').removeClass('show').addClass('hide');
        $('#api_token').attr('type', 'password');
    }

    function showPassword() {
        $('#api_token_eye').removeClass('hide').addClass('show');
        $('#api_token').attr('type', 'text');
    }

    $('#api_token_eye').hasClass('hide') ? hidePassword() : showPassword();
    $(document).on('click', '#api_token_eye', function () {
        $('#api_token_eye').hasClass('show') ? hidePassword() : showPassword();
    });
});

;(function ($) {
    function isNullOrUndefinedOrEmptyString(value) {
        return value === undefined || value === null || value === "";
    }

    function verifyPidexMerchantId(merchantId) {
        let _isValid = true;
        let inputId = "merchant_id";
        let validationMessageId = inputId + "_error";

        let validationMessageText = isNullOrUndefinedOrEmptyString(merchantId) ? 'Please enter Merchant ID. ' : '';

        if (validationMessageText === "") {
            settingsData = {
                merchantId: merchantId,
                verify_pidex_merchant_id: "verifyPidexMerchantID",
                action: 'pidex_verify_merchant_id',
                _nonce: PIDEX_ADMIN.nonce,
            };

            $.ajaxSetup({async: false});
            $.get(PIDEX_ADMIN.ajaxurl, settingsData, function (response) {
                if (!response.success) {
                    /** Show it to Front-end */
                    validationMessageText += response.data.body.error;
                } else {
                }
            });
            $.ajaxSetup({async: true});
        }

        if (validationMessageText !== "") {
            _isValid = false;

            document.getElementById("pidex-submit-settings-button").disabled = false;
            document.getElementById("pidex-submit-settings-button").innerText = "Save";
            document.getElementById(validationMessageId).innerText = validationMessageText;

            document.getElementById(inputId).classList.add('border-[#ec4d26]');
            document.getElementById(inputId).classList.add('bg-[#ec4e2639]');
            $('#overall-validation-messagebox').removeClass('hidden');
        } else {
            document.getElementById(inputId).classList.remove('border-[#ec4d26]');
            document.getElementById(inputId).classList.remove('bg-[#ec4e2639]');

            document.getElementById(validationMessageId).innerText = '';
        }

        return _isValid;
    }

    function handleSettingsInputValidation(settingsData) {
        let _isValid = true;

        /**
         * merchantId: required + string,
         * apiToken: required + string
         */
        for (const key in settingsData) {
            let inputId = "";
            let validationMessageId = "";
            let validationMessageText = "";

            switch (key) {
                case "merchantId":
                    _isValid = _isValid && verifyPidexMerchantId(settingsData[key]);
                    continue;

                case "apiToken":
                    inputId = "api_token";
                    validationMessageId = inputId + "_error";

                    validationMessageText = isNullOrUndefinedOrEmptyString(settingsData[key]) ? 'Please enter API Token. ' : '';
                    break;

                case "automaticOrderAllowed":
                    inputId = "automatic_order-checkbox";
                    validationMessageId = inputId + "_error";

                    validationMessageText = isNullOrUndefinedOrEmptyString(settingsData[key]) ? 'Please select an option. ' : '';

                    break;
            }

            if (validationMessageText !== "") {
                _isValid = false;

                document.getElementById(validationMessageId).innerText = validationMessageText;

                document.getElementById(inputId).classList.add('border-[#ec4d26]');
                document.getElementById(inputId).classList.add('bg-[#ec4e2639]');
            } else {
                document.getElementById(inputId).classList.remove('border-[#ec4d26]');
                document.getElementById(inputId).classList.remove('bg-[#ec4e2639]');

                document.getElementById(validationMessageId).innerText = '';
            }
        }

        return _isValid;
    }

    /**
     * Pidex Submit Settings - *** START ***
     */
    let pidexSubmitSettingsButton = $("#pidex-submit-settings-button");
    let pidexSubmitSettingsContainer = $("#pidex-submit-settings-container");

    pidexSubmitSettingsButton.on("click", function (e) {

        /**
         * Disable Submit Button and show progress
         */
        e.target.innerText = "Saving...";
        e.target.disabled = true;

        /**
         * Extract data from front-end
         */
        let settingsData = {
            merchantId: $("#merchant_id", pidexSubmitSettingsContainer).val(),
            apiToken: $("#api_token", pidexSubmitSettingsContainer).val(),
            automaticOrderAllowed: $("#automatic_order-checkbox", pidexSubmitSettingsContainer).val()
        };

        const _isValid = handleSettingsInputValidation(settingsData);

        if (_isValid === false) {
            $('#overall-validation-messagebox').removeClass('hidden');
            e.target.disabled = false;
            e.target.innerText = "Save";
        } else {
            $('#overall-validation-messagebox').addClass('hidden');

            settingsData.submit_pidex_settings = $("#pidex-submit-settings-button", pidexSubmitSettingsContainer).val();
            settingsData.action = 'pidex_submit_settings';
            settingsData._nonce = PIDEX_ADMIN.nonce;

            $.post(PIDEX_ADMIN.ajaxurl, settingsData, function (response) {
                if (!response.success) {
                    /** Show it to Front-end */
                    $("#message-from-server-messagebox").removeClass('hidden');
                    $("#message-from-server-messagebox-text").text(response.data.body);

                } else {
                    $("#message-from-server-messagebox").addClass('hidden');

                    /**
                     * Toastify-JS
                     */
                    Toastify({
                        text: "Pidex Settings Saved Successfully",
                        duration: 5000,
                        className: "rounded-md",
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "#ec4d26",
                        },
                        onClick: function () {
                        } // Callback after click
                    }).showToast();
                }

                e.target.disabled = false;
                e.target.innerText = "Save";
            });
        }
    });


    /**
     * Pidex Submit Settings - *** END ***
     */
})(jQuery, window);