//! phpcs:ignoreFile
//! pidex-welcome-script.js - for Pidex WordPress Plugin.
//! version 	: 1.0.0
//! author 		: Pidex Infosys
//! author uri	: https://pidex.biz/pidexinfosys
//! license 	: GPLv3
//! license uri : https://www.gnu.org/licenses/gpl-3.0.html

function copyWooCommerceCheckoutShortCode() {
    /* Get the text field */
    let copyText = document.getElementById("pidex-welcome-woocommerce-checkout-shortcode");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    navigator.clipboard.writeText(copyText.value);
}

function copyPidexTrackerShortCode() {
    /* Get the text field */
    let copyText = document.getElementById("pidex-welcome-pidex-tracker-shortcode");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    navigator.clipboard.writeText(copyText.value);
}