<?php
/**
 * This file contains the markup for Pidex Tracking view.
 *
 * @package Pidex
 */

?>
<div class="wrap">
    <div class="container flex flex-col gap-5 mx-auto mt-10 nunito">

        <div class="flex justify-center">
            <h1 class="text-3xl font-bold text-[#4e4e4f] nunito">Pidex Tracker</h1>
        </div>

        <div class="p-4 bg-white shadow-[0px_0px_20px_-8px_rgba(27,30,26,0.45)] rounded-md">
            <div class="relative flex">
                <div class="absolute top-0.5">
                    <svg width="40px" height="40px" version="1.1" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <title>ic_fluent_search_square_24_regular</title>
                        <desc>Created with Sketch.</desc>
                        <g fill="none" fill-rule="evenodd">
                            <g fill="#4e4e4f" fill-rule="nonzero">
                                <path
                                        d="m17.75 3c1.7949 0 3.25 1.4551 3.25 3.25v11.5c0 1.7949-1.4551 3.25-3.25 3.25h-11.5c-1.7949 0-3.25-1.4551-3.25-3.25v-11.5c0-1.7949 1.4551-3.25 3.25-3.25h11.5zm0 1.5h-11.5c-0.9665 0-1.75 0.7835-1.75 1.75v11.5c0 0.9665 0.7835 1.75 1.75 1.75h11.5c0.9665 0 1.75-0.7835 1.75-1.75v-11.5c0-0.9665-0.7835-1.75-1.75-1.75zm-6.75 2.75c2.0711 0 3.75 1.6789 3.75 3.75 0 0.7642-0.22859 1.475-0.62113 2.0678l2.4015 2.4019c0.29289 0.29289 0.29289 0.76777 0 1.0607-0.26627 0.26627-0.68293 0.29047-0.97654 0.072618l-0.084118-0.072618-2.4019-2.4015c-0.59277 0.39254-1.3036 0.62113-2.0678 0.62113-2.0711 0-3.75-1.6789-3.75-3.75 0-2.0711 1.6789-3.75 3.75-3.75zm0 1.5c-1.2426 0-2.25 1.0074-2.25 2.25 0 1.2426 1.0074 2.25 2.25 2.25 1.2426 0 2.25-1.0074 2.25-2.25 0-1.2426-1.0074-2.25-2.25-2.25z">
                                </path>
                            </g>
                        </g>
                    </svg>
                </div>
                <input type="text" id="pidex-tracking-number-input"
                       class="rounded-tl-md rounded-bl-md rounded-tr-none rounded-br-none w-full pl-11 pr-2 py-2 border focus:outline-none focus:border-[#ec4d26]"
                       placeholder="Pidex Tracking Number">

                <button type="button" id="pidex-submit-track-button" value="Submit"
                        class="rounded-tr-md rounded-br-md rounded-tl-none rounded-bl-none px-4 py-2 font-bold text-white bg-[#ec4d26] hover:bg-[#4e4e4f] disabled:bg-[#4e4e4f] transition-colors tracking-widest"
                        disabled>
                    TRACK
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-7 border-2 border-solid border-[#e5e7eb] rounded-md p-4 bg-white text-[#4e4e4f] hidden"
             id="pidex-tracking-result-container">

            <div class="hidden border border-[#ec4d26] bg-[#ec4e2639] rounded-md p-2"
                 id="pidex-something-went-wrong-in-server">
                <h1 class="text-lg font-semibold text-center">Something Went Wrong!</h1>
            </div>

            <h1 class="hidden text-lg font-semibold text-center" id="pidex-invalid-tracking-id">Invalid Tracking ID</h1>

            <div class="flex justify-between hidden" id="pidex-parcel-tracking-result-info">
                <div class="flex flex-col justify-between gap-5">
                    <span class="font-bold">Consignment ID</span>

                    <div class="flex flex-col">
                        <span class="font-bold">Seller Information</span>
                        <span class="text-[#ec4d26] font-light" id="pidex-tracking-result-seller-information"></span>
                    </div>

                    <span class="font-bold">Product Price</span>
                </div>

                <div class="flex flex-col gap-5 text-right">
                    <span class="font-light" id="pidex-tracking-result-tracking-number"></span>

                    <div class="flex flex-col">
                        <span class="font-bold">Recepient Information</span>
                        <span class="text-[#ec4d26] font-light" id="pidex-tracking-result-recipient-name"></span>
                        <span class="text-[#ec4d26] font-light" id="pidex-tracking-result-recipient-address"></span>
                    </div>

                    <span id="pidex-tracking-result-amount-to-collect"></span>
                </div>
            </div>

            <div class="hidden flex flex-col gap-9 shadow-[0px_0px_20px_-8px_rgba(27,30,26,0.45)] rounded-md p-5"
                 id="pidex-parcel-tracking-result-history">
                <span class="text-lg">Tracking Details</span>

                <ul class="overflow-hidden text-gray-400" id="pidex-progress-bar">
                    <li id="order-confirmed">
                        <strong>Order Confirmed</strong>
                    </li>
                    <li id="picked-up">
                        <strong>Picked Up</strong>
                    </li>
                    <li id="in-transit">
                        <strong>In Transit</strong>
                    </li>
                    <li id="out-for-delivery">
                        <strong>Out For Delivery</strong>
                    </li>
                    <li id="delivered">
                        <strong>Delivered</strong>
                    </li>
                </ul>

                <div class="flex flex-col">
                    <div class="w-full bg-gray-200 rounded-md">
                        <div style="" id="pidex-progress-bar-shim-animated" class="top-0 h-4 rounded-md shim-red"></div>
                    </div>
                </div>

                <div class="flex flex-col gap-3" id="pidex-parcel-tracking-result-history-tracking-details-container">
                </div>
            </div>
        </div>
    </div>
</div>