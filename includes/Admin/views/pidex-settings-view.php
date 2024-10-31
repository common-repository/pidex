<?php

/**
 * This file contains the markup and functionalities for Pidex API settings view.
 *
 * @package Pidex
 */

?>
<div class="wrap">
    <div class="nunito mt-10">
        <h1 class="text-center font-bold text-xl text-[#4e4e4f] mb-3">
            Pidex API Credentials
        </h1>

        <div id="pidex-submit-settings-container" class="container bg-white mx-auto flex flex-col gap-7 p-5 rounded-md shadow-[2px_2px_2px_1px_rgba(0,0,0,0.25),inset_2px_2px_2px_1px_rgba(0,0,0,0.25)]">

            <div class="flex justify-end">
                <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/pidex.svg'); ?>" alt="pidex" class="w-1/4">
            </div>

            <div class="flex flex-col">
                <label for="merchant_id" class="text-sm font-semibold text-[#ec4d26] mb-1">
                    Merchant ID
                </label>
                <input type="text" name="merchant_id" id="merchant_id" class="border border-[#4e4e4f82] text-[#4e4e4f] h-10 rounded-md px-3" value="<?php echo esc_attr(isset($this->config['merchant_id']) ? $this->config['merchant_id'] : ''); ?>">

                <small class="text-[#4e4e4f]" id="merchant_id_error"></small>
            </div>

            <div class="relative flex flex-col">
                <label for="api_token" class="text-sm font-semibold text-[#ec4d26] mb-1">
                    API Token
                </label>
                <input type="password" name="api_token" id="api_token" class="border border-[#4e4e4f82] text-[#4e4e4f] h-10 rounded-md px-3" value="<?php echo esc_attr(isset($this->config['api_token']) ? $this->config['api_token'] : ''); ?>">

                <div class="absolute right-0 flex items-center pr-3 text-sm leading-5 top-8 hide" id="api_token_eye">
                    <svg class="h-6 text-gray-700" fill="none" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 576 512">
                        <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                        </path>
                    </svg>
                </div>

                <small class="text-[#4e4e4f]" id="api_token_error"></small>
            </div>

            <div class="flex flex-col">
                <label class="relative inline-flex items-center mr-5 cursor-pointer">
                    <input type="checkbox" id="automatic_order-checkbox" class="sr-only peer" value="<?php echo esc_attr(isset($this->config['automatic_order_allowed']) ? ($this->config['automatic_order_allowed'] ? 'true' : 'false') : 'true'); ?>">
                    <div class="w-11 h-6 rounded-full peer bg-[#4e4e4f] peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#ec4d26]">
                    </div>
                    <span class="ml-3 text-xs font-medium text-[#4e4e4f]" id="automatic_order-checkbox-text">
                        Allow automatic booking when a user checks out
                    </span>

                </label>

                <small class="text-[#4e4e4f]" id="automatic_order-checkbox_error"></small>
            </div>

            <div>
                <button id="pidex-submit-settings-button" value="Submit" class="bg-[#ec4d26] rounded-md px-3 py-1 text-white font-semibold border-0 disabled:bg-[#4e4e4f] hover:scale-[1.1] transition-transform" disabled>
                    Save
                </button>
            </div>

            <div class="p-3 mt-3 border border-dashed rounded-md border-[#4e4e4f] hidden" id="overall-validation-messagebox">
                <span class="text-sm">Please fix validation Error(s)</span>
            </div>

            <div class="p-3 mt-3 border border-dashed rounded-md border-[#4e4e4f] hidden" id="message-from-server-messagebox">
                <span class="text-sm" id="message-from-server-messagebox-text"></span>
            </div>
        </div>
    </div>
</div>