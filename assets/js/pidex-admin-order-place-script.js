//! phpcs:ignoreFile
//! pidex-admin-order-place-script.js - for Pidex WordPress Plugin.
//! version 	: 1.0.0
//! author 		: Pidex Infosys
//! author uri	: https://pidex.biz/pidexinfosys
//! license 	: GPLv3
//! license uri : https://www.gnu.org/licenses/gpl-3.0.html

function copyTrackingLinkClipboard() {
    /* Get the text field */
    let copyText = document.getElementById("pidex-tracking-link-input");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    navigator.clipboard.writeText(copyText.value);
}

jQuery(document).ready(function ($) {
    $('#recipient-city-select').select2({
        data: []
    });

    $('#recipient-zone-select').select2({
        data: []
    });

    $('#recipient-delivery-type-select').select2({
        data: []
    });

    $('b[role="presentation"]').hide();

    /**
     * Event to initialize City Select2
     */
    window.addEventListener('pidexCitiesLoaded', (event) => {
        $('#recipient-city-select').empty();
        $('#recipient-city-select').select2({
            data: event.detail.cities.map((element) => {
                return {
                    id: element.city_id,
                    text: element.city_name
                };
            })
        });

        /**
         * Dispatch pidexCitiesSet Event
         */
        let eventToDispatch = new CustomEvent("pidexCitiesSet", {
            'detail': {}
        });

        window.dispatchEvent(eventToDispatch);
    });

    /**
     * Event to initialize Zone Select2
     */
    window.addEventListener('pidexZonesLoaded', (event) => {
        $('#recipient-zone-select').empty();
        $('#recipient-zone-select').select2({
            data: event.detail.zones.map((element) => {
                return {
                    id: element.zone_id,
                    text: element.zone_name
                };
            })
        });

        /**
         * Enable button for booking
         */
        $('#pidex-place-order-button').prop('disabled', false);
    });

    /**
     * Event to initialize Delivery Type Select2
     */
    window.addEventListener('pidexDeliveryTypesLoaded', (event) => {
        $('#recipient-delivery-type-select').empty();
        $('#recipient-delivery-type-select').select2({
            data: event.detail.deliveryTypes.map((element) => {
                return {
                    id: element.delivery_type_id,
                    text: element.delivery_type
                };
            })
        });
    });

    /**
     * Event to set Recipient City Initially
     */
    window.addEventListener('pidexCitiesSet', (event) => {
        /**
         * Fetch Merchant City
         */
        merchantCityData = {
            pidex_fetch_merchant_city: 'Fetch Merchant City',
            action: 'pidex_fetch_merchant_city',
            _nonce: PIDEX_ADMIN.nonce
        };

        $.get(PIDEX_ADMIN.ajaxurl, merchantCityData, function (response) {
            if (!response.success) {
                /** Show it to Front-end */
            } else {

                const merchantCity = response.data.message.merchantCity;

                /**
                 * Change the value to merchant city
                 */
                $('#recipient-city-select').val(merchantCity).trigger('change');

                /**
                 * dispatch select2:select event for loading zones
                 */
                $('#recipient-city-select').trigger({
                    type: 'select2:select',
                    params: {}
                });
            }
        });
    });

    /**
     * Dispatch event if City is selected
     */
    $('#recipient-city-select').on('select2:select', function (event) {
        const selectedCity = $('#recipient-city-select').select2("val");

        /**
         * Add Zones for the selected City
         */
        zoneData = {
            selectedCity: selectedCity,
            pidex_fetch_zones: 'Fetch Zones',
            action: 'pidex_fetch_zones',
            _nonce: PIDEX_ADMIN.nonce
        };

        $.get(PIDEX_ADMIN.ajaxurl, zoneData, function (response) {

            if (!response.success) {
                /** Show it to Front-end */
            } else {

                /**
                 * Dispatch Event
                 */
                let eventToDispatch = new CustomEvent("pidexZonesLoaded", {
                    'detail': {
                        zones: response.data.message.zones,
                    }
                });
                window.dispatchEvent(eventToDispatch);
            }
        });
    });

    /**
     * Add Cities
     */
    cityData = {
        pidex_fetch_cities: 'Fetch Cities',
        action: 'pidex_fetch_cities',
        _nonce: PIDEX_ADMIN.nonce
    };

    $.get(PIDEX_ADMIN.ajaxurl, cityData, function (response) {
        if (!response.success) {
            /** Show it to Front-end */
        } else {

            /**
             * Dispatch Event
             */
            let eventToDispatch = new CustomEvent("pidexCitiesLoaded", {
                'detail': {
                    cities: response.data.message.cities,
                }
            });
            window.dispatchEvent(eventToDispatch);
        }
    });

    /**
     * Add Delivery Types
     */
    deliveryTypeData = {
        pidex_fetch_delivery_types: 'Fetch Delivery Types',
        action: 'pidex_fetch_delivery_types',
        _nonce: PIDEX_ADMIN.nonce
    };

    $.get(PIDEX_ADMIN.ajaxurl, deliveryTypeData, function (response) {
        if (!response.success) {
            /** Show it to Front-end */
        } else {

            /**
             * Dispatch Event
             */
            let eventToDispatch = new CustomEvent("pidexDeliveryTypesLoaded", {
                'detail': {
                    deliveryTypes: response.data.message.deliveryTypes,
                }
            });
            window.dispatchEvent(eventToDispatch);
        }
    });
});

/**
 * src: handsontable/handsontable/blob/405c1a4884f7b236a34f6590c0bf6dd9dba8f993/handsontable/src/helpers/number.js#L19
 * @param value value to check
 * @param additionalDelimiters any additional delimiters, if any
 * @returns returns true if the value is numeric, false otherwise
 */
function isNumeric(value, additionalDelimiters = []) {
    const type = typeof value;

    if (type === 'number') {
        return !isNaN(value) && isFinite(value);

    } else if (type === 'string') {
        if (value.length === 0) {
            return false;

        } else if (value.length === 1) {
            return /\d/.test(value);
        }

        const delimiter = Array.from(new Set(['.', ...additionalDelimiters]))
            .map(d => `\\${d}`)
            .join('|');

        return new RegExp(`^[+-]?\\s*(((${delimiter})?\\d+((${delimiter})\\d+)?(e[+-]?\\d+)?)|(0x[a-f\\d]+))$`, 'i')
            .test(value.trim());

    } else if (type === 'object') {
        return !!value && typeof value.valueOf() === 'number' && !(value instanceof Date);
    }

    return false;
}

function isNullOrUndefinedOrEmptyString(value) {
    return value === undefined || value === null || value === "";
}

function isBangladeshPhoneNumber(value) {
    return /^(01)[0-9]{9}$/.test(value);
}

function handleParcelInputValidation(parcelData) {
    let _isValid = true;

    /**
     *  recipientName: required + string,
     *  recipientPhone: required + BD 11 digit phone format,
     *  recipientAddress: required + string,
     *  amountToCollect: required + numeric,
     *  recipientCity: required + numeric,
     *  recipientZone: required + numeric,
     *  deliveryType: required + numeric,
     */
    for (const key in parcelData) {

        switch (key) {
            /**
             * Ignore these checks
             */
            case "wooCommerceProductId":
            case "merchantOrderId":
            case "quantity":
            case "specialInstruction":
            case "itemDescriptionAndPrice":
                continue;
        }

        let inputId = "";
        let validationMessageId = "";
        let validationMessageText = "";

        switch (key) {
            case "recipientName":
                inputId = "recipient_name"
                validationMessageId = inputId + "_error";

                validationMessageText = isNullOrUndefinedOrEmptyString(parcelData[key]) ? 'Please enter Recipient Name' : '';
                break;

            case "recipientPhone":
                inputId = "recipient_phone";
                validationMessageId = inputId + "_error";

                validationMessageText = isNullOrUndefinedOrEmptyString(parcelData[key]) ? 'Please enter Recipient Phone. ' : '';
                validationMessageText += isBangladeshPhoneNumber(parcelData[key]) ? '' : 'Phone number should contain 11 digits and start with 01. ';
                break;

            case "recipientAddress":
                inputId = "recipient_address";
                validationMessageId = inputId + "_error";

                validationMessageText = isNullOrUndefinedOrEmptyString(parcelData[key]) ? 'Please enter Recipient Address' : '';
                break;

            case "amountToCollect":
                inputId = "amount_to_collect";
                validationMessageId = inputId + "_error";

                validationMessageText = isNullOrUndefinedOrEmptyString(parcelData[key]) ? 'Please enter Collection Amount. ' : '';
                validationMessageText += isNumeric(parcelData[key]) ? '' : 'Collection Amount should be numeric. ';
                break;

            case "recipientCity":
                inputId = "recipient-city-select";
                validationMessageId = inputId + "_error";

                validationMessageText = isNullOrUndefinedOrEmptyString(parcelData[key]) ? 'Please enter Recipient City. ' : '';
                validationMessageText += isNumeric(parcelData[key]) ? '' : 'Please enter a valid Recipient City. ';
                break;

            case "recipientZone":
                inputId = "recipient-zone-select";
                validationMessageId = inputId + "_error";

                validationMessageText = isNullOrUndefinedOrEmptyString(parcelData[key]) ? 'Please enter Recipient Zone. ' : '';
                validationMessageText += isNumeric(parcelData[key]) ? '' : 'Please enter a valid Recipient Zone. ';
                break;

            case "deliveryType":
                inputId = "recipient-delivery-type-select";
                validationMessageId = inputId + "_error";

                validationMessageText = isNullOrUndefinedOrEmptyString(parcelData[key]) ? 'Please enter Delivery Type. ' : '';
                validationMessageText += isNumeric(parcelData[key]) ? '' : 'Please enter a valid Delivery Type. ';
                break;
        }

        if (validationMessageText !== "") {
            _isValid = false;

            document.getElementById(validationMessageId).innerText = validationMessageText;

            if (key === "recipientCity" || key === "recipientZone") {
                document.getElementById(inputId).classList.add('invalid');
            } else {
                document.getElementById(inputId).classList.add('border-[#ec4d26]');
                document.getElementById(inputId).classList.add('bg-[#ec4e2639]');
            }
        } else {
            if (key === "recipientCity" || key === "recipientZone") {
                document.getElementById(inputId).classList.remove('invalid');
            } else {
                document.getElementById(inputId).classList.remove('border-[#ec4d26]');
                document.getElementById(inputId).classList.remove('bg-[#ec4e2639]');
            }

            document.getElementById(validationMessageId).innerText = '';
        }
    }

    return _isValid;
}

function handleApiErrors() {

}


; (function ($) {
    /**
     * Pidex Place Order - *** START ***
     */
    let pidexPlacerOrderButton = $("#pidex-place-order-button");
    let pidexPlaceOrderContainer = $("#pidex-place-order-container");

    pidexPlacerOrderButton.on("click", function (e) {
        e.preventDefault();

        /**
         * Disable Pidex Button and show progress
         */
        e.target.innerText = "Sending...";
        e.target.disabled = true;

        /**
         * Extract data from front-end
         */
        let parcelData = {
            wooCommerceProductId: $("#woocommerce_product_id", pidexPlaceOrderContainer).val(),
            merchantOrderId: $("#merchant_order_id", pidexPlaceOrderContainer).val(),
            recipientName: $("#recipient_name", pidexPlaceOrderContainer).val(),
            recipientPhone: $("#recipient_phone", pidexPlaceOrderContainer).val().replace("+88", ""),
            recipientAddress: $("#recipient_address", pidexPlaceOrderContainer).val(),
            amountToCollect: $("#amount_to_collect", pidexPlaceOrderContainer).val(),
            recipientCity: $("#recipient-city-select", pidexPlaceOrderContainer).val(),
            recipientZone: $("#recipient-zone-select", pidexPlaceOrderContainer).val(),
            deliveryType: $("#recipient-delivery-type-select", pidexPlaceOrderContainer).val(),
            quantity: $("#quantity", pidexPlaceOrderContainer).val(),
            specialInstruction: $("#special_instruction", pidexPlaceOrderContainer).val(),
            itemDescriptionAndPrice: $("#item_description_and_price", pidexPlaceOrderContainer).val(),
        };


        const _isValid = handleParcelInputValidation(parcelData);
        parcelData.wooCommerceOrderDump = $("#woocommerce_order_dump", pidexPlaceOrderContainer).val();

        if (_isValid === false) {
            $('#overall-validation-messagebox').removeClass('hidden');
            e.target.disabled = false;
            e.target.innerText = "Pidex";
        } else {
            $('#overall-validation-messagebox').addClass('hidden');

            parcelData.submit_pidex_order_place = $("#pidex-place-order-button", pidexPlaceOrderContainer).val();
            parcelData.action = 'pidex_place_order_metabox_form';
            parcelData._nonce = PIDEX_ADMIN.nonce;

            $.post(PIDEX_ADMIN.ajaxurl, parcelData, function (response) {
                if (!response.success) {
                    /** Show it to Front-end */

                    for (const key in response.data) {
                        $(`#${key}_error`).text(response.data[key]);

                        if (key === "recipient-city-select" || key === "recipient-zone-select") {
                            $(`#${key}`).addClass('invalid');
                        } else {
                            $(`#${key}`).addClass('border-[#ec4d26]');
                            $(`#${key}`).addClass('bg-[#ec4e2639]');
                        }
                    }

                    $('#pidex-validation-messagebox').removeClass('hidden');
                    let text = "";
                    for (const key in response.data.body.data.errors) {
                        text += response.data.body.data.errors[key] + '<br>';
                    }
                    $('#pidex-validation-messagebox-text').html(text);

                    e.target.disabled = false;
                    e.target.innerText = "Pidex";
                } else {

                    $("#pidex-after-place-order-container").removeClass('hidden');
                    $("#pidex-place-order-container").addClass('hidden');

                    inputs = {
                        recipient_name: '',
                        recipient_phone: '',
                        recipient_address: '',
                        amount_to_collect: '',
                        "recipient-city-select": '',
                        "recipient-zone-select": '',
                        "recipient-delivery-type-select": '',
                    };

                    for (const key in inputs) {
                        $(`#${key}_error`).text(response.data[key]);

                        if (key === "recipient-city-select" || key === "recipient-zone-select") {
                            $(`#${key}`).removeClass('invalid');
                        } else {
                            $(`#${key}`).removeClass('border-[#ec4d26]');
                            $(`#${key}`).removeClass('bg-[#ec4e2639]');
                        }
                    }

                    $('#pidex-validation-messagebox').addClass('hidden');
                    $('#pidex-validation-messagebox-text').html("");

                    e.target.disabled = false;
                    e.target.innerText = "Pidex";

                    /**
                     * Toastify-JS
                     */
                    Toastify({
                        text: "Order Placed Successfully",
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
            });
        }
    });
    /**
     * Pidex Place Order - *** END ***
     */
})(jQuery, window);