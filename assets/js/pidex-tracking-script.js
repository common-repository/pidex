//! phpcs:ignoreFile
//! pidex-tracking-script.js - for Pidex WordPress Plugin.
//! version 	: 1.0.0
//! author 		: Pidex Infosys
//! author uri	: https://pidex.biz/pidexinfosys
//! license 	: GPLv3
//! license uri : https://www.gnu.org/licenses/gpl-3.0.html

; (function ($) {

    const pidexSubmitTrackButton = $('#pidex-submit-track-button');
    const pidexTrackingNumberInput = $('#pidex-tracking-number-input');

    function submitTrackingNumber() {
        /**
         * Disable Track Button And Show Progress
         */
        pidexSubmitTrackButton.prop('disabled', true);
        pidexSubmitTrackButton.text('Tracking...');

        /**
         * Extract data from front-end
         */
        let data = {
            trackingNumber: pidexTrackingNumberInput.val(),
            submit_pidex_tracking: pidexSubmitTrackButton.val(),
            action: 'pidex_submit_tracking',
            _nonce: PIDEX.nonce
        };

        $.get(PIDEX.ajaxurl, data, function (response) {
            if (!response.success) {
                /** Show it to Front-end */
                $("#pidex-something-went-wrong-in-server").removeClass('hidden');
            } else {
                $("#pidex-something-went-wrong-in-server").addClass('hidden');

                const data = response.data.message;

                if (data.parcel === null) {
                    /** Show it to Front-end */
                    $("#pidex-invalid-tracking-id").removeClass('hidden');
                    $("#pidex-parcel-tracking-result-info").addClass('hidden');
                    $("#pidex-parcel-tracking-result-history").addClass('hidden');
                } else {
                    $("#pidex-invalid-tracking-id").addClass('hidden');
                    $("#pidex-parcel-tracking-result-info").removeClass('hidden');
                    $("#pidex-parcel-tracking-result-history").removeClass('hidden');

                    $("#pidex-tracking-result-tracking-number").text(data.parcel.trackingNumber);
                    $("#pidex-tracking-result-seller-information").text(data.parcel.storeName);
                    $("#pidex-tracking-result-recipient-name").text(data.parcel.recipientName);
                    $("#pidex-tracking-result-recipient-address").text(data.parcel.recipientAddress);
                    $("#pidex-tracking-result-amount-to-collect").text(`৳ ${data.parcel.amountToCollect}`);

                    for (let index = 0; index < data.parcelHistory.length; ++index) {
                        const historyDiv = document.createElement("div");
                        historyDiv.classList.add('flex', 'flex-col', 'pl-3', 'border-l-[3px]', 'border-solid');

                        if (index === 0) {
                            historyDiv.classList.add('border-[#ec4d26]');
                        } else {
                            historyDiv.classList.add('border-gray-300');
                        }

                        const messageSpan = document.createElement("span");
                        messageSpan.classList.add('text-base', 'font-semibold');
                        messageSpan.innerHTML = data.parcelHistory[index].message;

                        const timeSpan = document.createElement("span");
                        timeSpan.classList.add('text-sm', 'font-light');
                        timeSpan.innerText = data.parcelHistory[index].datetime;

                        historyDiv.appendChild(messageSpan);
                        historyDiv.appendChild(timeSpan);

                        document.getElementById('pidex-parcel-tracking-result-history-tracking-details-container').appendChild(historyDiv);
                    }

                    const pidexProgressIcons = document.getElementById('pidex-progress-bar').children;

                    for (let index = 0; index < data.parcel.progressValue; ++index) {
                        const icon = pidexProgressIcons[index];
                        icon.classList.add('active');
                    }

                    document.getElementById('pidex-progress-bar-shim-animated').style.width = `${(data.parcel.progressValue / 5) * 100}%`;
                }
            }

            $("#pidex-tracking-result-container").removeClass('hidden');

            pidexSubmitTrackButton.prop('disabled', false);
            pidexSubmitTrackButton.text('TRACK');
        });
    }

    function clearResult() {
        $("#pidex-tracking-result-tracking-number").text('');
        $("#pidex-tracking-result-seller-information").text('');
        $("#pidex-tracking-result-recipient-name").text('');
        $("#pidex-tracking-result-recipient-address").text('');
        $("#pidex-tracking-result-amount-to-collect").text('');

        document.getElementById('pidex-parcel-tracking-result-history-tracking-details-container').textContent = '';

        const pidexProgressIcons = document.getElementById('pidex-progress-bar').children;

        for (let index = 0; index < 5; ++index) {
            const icon = pidexProgressIcons[index];
            icon.classList.remove('active');
        }

        document.getElementById('pidex-progress-bar-shim-animated').style.width = '0%';
    }

    $(document).ready(function () {
        pidexSubmitTrackButton.prop('disabled', false);

        if (pidexTrackingNumberInput.val() === "") {
            pidexSubmitTrackButton.prop('disabled', true);
        }

        pidexTrackingNumberInput.on('input', function () {
            if (pidexTrackingNumberInput.val() === "") {
                pidexSubmitTrackButton.prop('disabled', true);
            } else {
                pidexSubmitTrackButton.prop('disabled', false);
            }
        });

        pidexSubmitTrackButton.on('click', function (e) {
            clearResult();
            submitTrackingNumber();
        });

        pidexTrackingNumberInput.keypress(function (e) {
            if (e.which == 13) {
                clearResult();
                submitTrackingNumber();
            }
        });
    });
})(jQuery, window);
