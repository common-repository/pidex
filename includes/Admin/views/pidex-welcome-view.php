<?php

/**
 * This file contains the markup for Pidex Welcome view.
 *
 * @package Pidex
 */

?>
<div class="wrap">
    <div class="nunito flex flex-col gap-3 mt-10">
        <div class="text-[#4e4e4f] bg-white container mx-auto flex flex-col gap-9 p-5 rounded-md shadow-[2px_2px_2px_1px_rgba(0,0,0,0.25),inset_2px_2px_2px_1px_rgba(0,0,0,0.25)]">

            <h1 class="text-xl font-bold text-center text-[#4e4e4f]">Welcome to Pidex</h1>

            <div class="flex flex-col gap-3">
                <h1 class="text-right text-white rounded-sm bg-gradient-to-r from-white to-[#ec4d26] pr-3 font-bold">
                    SETTINGS
                </h1>

                <p class="text-lg font-bold">
                    Get <a href="<?php echo esc_url(PIDEX_MERCHANT_API_TOKEN_URL); ?>" target="_blank" class="text-[#ec4d26] font-semibold underline">API Credentials</a> from your Merchant Panel
                </p>

                <div class="flex justify-center">
                    <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/1Dashboard.png'); ?>" alt="Pidex Merchant Dashboard" class="w-3/4 border rounded-md shadow-[4px_4px_10px_0px_rgba(0,0,0,0.25)] hover:scale-110 transform
                                transition duration-700" loading="lazy">
                </div>

                <div class="flex justify-center">
                    <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/2APIToken.png'); ?>" alt="Pidex Merchant API Token" class="w-3/4 border rounded-md shadow-[4px_4px_10px_0px_rgba(0,0,0,0.25)] hover:scale-110 transform
                                transition duration-700" loading="lazy">
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <hr>
                <p class="text-lg font-bold">
                    Enter the API Credentials in your Wordpress Admin Dashboard
                    <br>
                    Go to Pidex <span class="text-[#ec4d26] font-bold">&raquo;</span>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=pidex_settings')); ?>" class="text-[#ec4d26] font-semibold underline" target="_blank">Pidex Settings</a>

                <div class="flex justify-center">
                    <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/3PidexSettings.png'); ?>" alt="Wordpress Plugin - Pidex Settings" class="w-3/4 border rounded-md shadow-[4px_4px_10px_0px_rgba(0,0,0,0.25)] hover:scale-110 transform
                                transition duration-700" loading="lazy">
                </div>
                </p>
                <p class="text-center">Now you are all <span class="text-[#ec4d26]">SET!</span></p>
            </div>
        </div>

        <div class="text-[#4e4e4f] bg-white container mx-auto flex flex-col gap-9 p-5 rounded-md shadow-[2px_2px_2px_1px_rgba(0,0,0,0.25),inset_2px_2px_2px_1px_rgba(0,0,0,0.25)]">
            <div class="flex flex-col gap-3">
                <h1 class="text-right text-white rounded-sm bg-gradient-to-r from-white to-[#ec4d26] pr-3 font-bold">
                    BOOKING
                </h1>

                <p class="text-lg font-bold">
                    For <span class="text-[#ec4d26]">automatic</span> booking, you must use WooCommerce shortcode
                    for checkout (ignore if it is already
                    set)
                </p>

                <div class="relative p-3 font-mono font-normal rounded-md text-md bg-[#4e4e4f] text-white">
                    <input type="text" value="[woocommerce_checkout]" id="pidex-welcome-woocommerce-checkout-shortcode" class="text-white bg-[#4e4e4f] border-none focus:outline-none w-full" readonly>

                    <button class="absolute right-3 top-3 border border-[#ffffff] bg-transparent p-1 rounded-md " onclick="copyWooCommerceCheckoutShortCode()">
                        <svg width="20px" height="20px" enable-background="new 0 0 64 64" fill="#FFFFFF" version="1.1" viewBox="0 0 64 64" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                            <path d="m53.979 9.1429h-3.9683c-0.082699 0-0.1562 0.0284-0.2331 0.047v-4.1671c0-2.7698-2.3046-5.0228-5.1379-5.0228h-34.423c-2.8333 0-5.1379 2.253-5.1379 5.0228v46.843c0 2.7698 2.3046 5.0228 5.1379 5.0228h6.0367v2.2679c0 2.6706 2.2163 4.8432 4.9415 4.8432h32.784c2.7252 0 4.9415-2.1726 4.9415-4.8432v-45.171c-1.8e-6 -2.6706-2.2163-4.8432-4.9415-4.8432zm-46.868 42.723v-46.843c0-1.6488 1.3939-2.991 3.1062-2.991h34.423c1.7123 0 3.1062 1.3422 3.1062 2.991v46.843c0 1.6488-1.3939 2.9911-3.1062 2.9911h-34.423c-1.7123 4e-7 -3.1062-1.3423-3.1062-2.9911zm49.778 7.2907c0 1.5506-1.3055 2.8115-2.9097 2.8115h-32.784c-1.6042 0-2.9098-1.2609-2.9098-2.8115v-2.2679h26.354c2.8333 0 5.1379-2.253 5.1379-5.0228v-40.739c0.0769 0.0186 0.1504 0.047 0.2331 0.047h3.9683c1.6042 0 2.9097 1.2609 2.9097 2.8115v45.171z" />
                            <path d="m38.603 13.206h-22.349c-0.5615 0-1.0159 0.4543-1.0159 1.0158 0 0.5616 0.4544 1.0159 1.0159 1.0159h22.349c0.5615 0 1.0159-0.4543 1.0159-1.0159 0-0.5615-0.4544-1.0158-1.0159-1.0158z" />
                            <path d="m38.603 21.333h-22.349c-0.5615 0-1.0159 0.4543-1.0159 1.0158 0 0.5615 0.4544 1.0159 1.0159 1.0159h22.349c0.5615 0 1.0159-0.4544 1.0159-1.0159 0-0.5615-0.4544-1.0158-1.0159-1.0158z" />
                            <path d="m38.603 29.46h-22.349c-0.5615 0-1.0159 0.4544-1.0159 1.0159s0.4544 1.0159 1.0159 1.0159h22.349c0.5615 0 1.0159-0.4544 1.0159-1.0159s-0.4544-1.0159-1.0159-1.0159z" />
                            <path d="m28.444 37.587h-12.19c-0.5615 0-1.0159 0.4544-1.0159 1.0159s0.4544 1.0159 1.0159 1.0159h12.19c0.5615 0 1.0158-0.4544 1.0158-1.0159s-0.4543-1.0159-1.0158-1.0159z" />
                        </svg>
                    </button>
                </div>

                <p class="text-lg font-bold">
                    and allow automatic booking in
                    <a href="<?php echo esc_url(admin_url('admin.php?page=pidex_settings')); ?>" class="text-[#ec4d26] font-semibold underline" target="_blank">Pidex Settings</a>. It will work
                    something like this.
                </p>

                <video src="<?php echo esc_url(PIDEX_ASSETS_URL . '/video/AutoBooking.mp4'); ?>" type="video/mp4" autoplay playsinline loop preload="auto" muted="true" id="pidex-autobooking-vid" class="border rounded-md shadow-[4px_4px_10px_0px_rgba(0,0,0,0.25)]">

                    <script>
                        const vid = document.getElementById("pidex-autobooking-vid")
                        vid.disablePictureInPicture = true
                    </script>
            </div>

            <div class="flex flex-col gap-3">
                <hr>
                <p class="font-bold text-lg">
                    If you want you can also place orders <span class="text-[#ec4d26]">manually</span>
                </p>

                <div class="flex justify-center">
                    <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/4WooCommerceOrders.png'); ?>" alt="" class="w-3/4 border rounded-md shadow-[4px_4px_10px_0px_rgba(0,0,0,0.25)] hover:scale-110 transform
                                transition duration-700" loading="lazy">
                </div>

                <div class="flex justify-center">
                    <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/5WooCommerceOrderDetails.png'); ?>" alt="" class="w-3/4 border rounded-md shadow-[4px_4px_10px_0px_rgba(0,0,0,0.25)] hover:scale-110 transform
                                transition duration-700" loading="lazy">
                </div>

                <div class="flex justify-center">
                    <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/6WooCommerceOrderStatus.png'); ?>" alt="" class="w-3/4 border rounded-md shadow-[4px_4px_10px_0px_rgba(0,0,0,0.25)] hover:scale-110 transform
                                transition duration-700" loading="lazy">
                </div>
            </div>
        </div>

        <div class="text-[#4e4e4f] container bg-white mx-auto flex flex-col gap-9 p-5 rounded-md shadow-[2px_2px_2px_1px_rgba(0,0,0,0.25),inset_2px_2px_2px_1px_rgba(0,0,0,0.25)]">
            <h1 class="text-right text-white rounded-sm bg-gradient-to-r from-white to-[#ec4d26] pr-3 font-bold">
                TRACKING
            </h1>

            <div class="flex flex-col gap-3">
                <div class="flex flex-col gap-1">
                    <p class="text-lg font-bold">
                        To add Pidex Tracker use this Shortcode
                    </p>

                    <div class="relative p-3 font-mono font-normal rounded-md text-md bg-[#4e4e4f] text-white">
                        <input type="text" value="[pidex_tracker]" id="pidex-welcome-pidex-tracker-shortcode" class="text-white bg-[#4e4e4f] border-none focus:outline-none w-full" readonly>

                        <button class="absolute right-3 top-3 border border-[#ffffff] bg-transparent p-1 rounded-md " onclick="copyPidexTrackerShortCode()">
                            <svg width="20px" height="20px" enable-background="new 0 0 64 64" fill="#FFFFFF" version="1.1" viewBox="0 0 64 64" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                <path d="m53.979 9.1429h-3.9683c-0.082699 0-0.1562 0.0284-0.2331 0.047v-4.1671c0-2.7698-2.3046-5.0228-5.1379-5.0228h-34.423c-2.8333 0-5.1379 2.253-5.1379 5.0228v46.843c0 2.7698 2.3046 5.0228 5.1379 5.0228h6.0367v2.2679c0 2.6706 2.2163 4.8432 4.9415 4.8432h32.784c2.7252 0 4.9415-2.1726 4.9415-4.8432v-45.171c-1.8e-6 -2.6706-2.2163-4.8432-4.9415-4.8432zm-46.868 42.723v-46.843c0-1.6488 1.3939-2.991 3.1062-2.991h34.423c1.7123 0 3.1062 1.3422 3.1062 2.991v46.843c0 1.6488-1.3939 2.9911-3.1062 2.9911h-34.423c-1.7123 4e-7 -3.1062-1.3423-3.1062-2.9911zm49.778 7.2907c0 1.5506-1.3055 2.8115-2.9097 2.8115h-32.784c-1.6042 0-2.9098-1.2609-2.9098-2.8115v-2.2679h26.354c2.8333 0 5.1379-2.253 5.1379-5.0228v-40.739c0.0769 0.0186 0.1504 0.047 0.2331 0.047h3.9683c1.6042 0 2.9097 1.2609 2.9097 2.8115v45.171z" />
                                <path d="m38.603 13.206h-22.349c-0.5615 0-1.0159 0.4543-1.0159 1.0158 0 0.5616 0.4544 1.0159 1.0159 1.0159h22.349c0.5615 0 1.0159-0.4543 1.0159-1.0159 0-0.5615-0.4544-1.0158-1.0159-1.0158z" />
                                <path d="m38.603 21.333h-22.349c-0.5615 0-1.0159 0.4543-1.0159 1.0158 0 0.5615 0.4544 1.0159 1.0159 1.0159h22.349c0.5615 0 1.0159-0.4544 1.0159-1.0159 0-0.5615-0.4544-1.0158-1.0159-1.0158z" />
                                <path d="m38.603 29.46h-22.349c-0.5615 0-1.0159 0.4544-1.0159 1.0159s0.4544 1.0159 1.0159 1.0159h22.349c0.5615 0 1.0159-0.4544 1.0159-1.0159s-0.4544-1.0159-1.0159-1.0159z" />
                                <path d="m28.444 37.587h-12.19c-0.5615 0-1.0159 0.4544-1.0159 1.0159s0.4544 1.0159 1.0159 1.0159h12.19c0.5615 0 1.0158-0.4544 1.0158-1.0159s-0.4543-1.0159-1.0158-1.0159z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <p class="text-lg font-bold">Tracking Result will look something like this</p>

                <div class="flex justify-center">
                    <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/7PidexTackingResult.png'); ?>" alt="" class="w-3/4 border rounded-md shadow-[4px_4px_10px_0px_rgba(0,0,0,0.25)] hover:scale-110 transform
                                transition duration-700" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</div>