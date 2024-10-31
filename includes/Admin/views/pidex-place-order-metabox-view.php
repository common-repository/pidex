<?php

/**
 * This file contains the view for PidexPlaceOrderMetabox form.
 *
 * @package Pidex\Admin
 */
?>

<?php if ($apiCredentialStatus === 'empty' || $apiCredentialStatus === 'invalid') { ?>
    <div class="container mx-auto flex flex-col gap-4 p-5 rounded-md shadow-[2px_2px_2px_1px_rgba(0,0,0,0.25),inset_2px_2px_2px_1px_rgba(0,0,0,0.25)]">
        <div>
            <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/pidex.svg'); ?>" alt="pidex" class="w-full">
        </div>

        <?php if ($apiCredentialStatus === 'empty') { ?>
            <p>
                Please check your API credentials. One or both fields are <span class="text-[#ec4d26]">missing</span>
            </p>
        <?php } ?>

        <?php if ($apiCredentialStatus === 'invalid') { ?>
            <p>
                Please check your API credentials. API token is <span class="text-[#ec4d26]">invalid</span>
            </p>
        <?php } ?>
    </div>
<?php } else { ?>

    <div id="pidex-after-place-order-container" class="<?php echo sanitize_html_class($orderShipped ? '' : 'hidden'); ?> container nunito mx-auto flex flex-col gap-4 p-5 rounded-md shadow-[2px_2px_2px_1px_rgba(0,0,0,0.25),inset_2px_2px_2px_1px_rgba(0,0,0,0.25)]">
        <div>
            <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/pidex.svg'); ?>" alt="pidex" class="w-full">
        </div>

        <div class="text-[#4e4e4f]">
            Order Placed on
            <span class="text-[#ec4d26] font-bold">
                <?php
                if ($orderShipped) {
                    $date = new DateTimeImmutable($orderShipped->created_at);
                    echo esc_html($date->format('F j, Y'));
                }
                ?>
            </span>
        </div>
        <div class="text-[#4e4e4f]">
            Order Placed by
            <span class="text-[#ec4d26] font-bold">
                <?php echo esc_html($orderShipped ? $orderShipped->user : ''); ?>
            </span>
        </div>
        <div class="text-[#4e4e4f]">
            Tracking Number
            <?php if ($orderShipped) { ?>
                <?php if ($orderShipped->tracking_number === "") { ?>
                    <span class="text-[#4e4e4f]">
                        Will be available on next reload
                    </span>
                <?php } else { ?>
                    <span class="text-[#ec4d26] font-bold">
                        <?php echo esc_html($orderShipped->tracking_number); ?>
                    </span>
                <?php } ?>
            <?php } else { ?>
                <span class="text-[#4e4e4f]">
                    Will be available on next reload
                </span>
            <?php } ?>
        </div>

        <div class="flex">
            <input type="text" name="" id="pidex-tracking-link-input" class="w-full border border-[#4e4e4f82] text-[#4e4e4f] h-10 rounded-tl-md rounded-bl-md rounded-tr-none rounded-br-none px-3 focus:outline-none" value="<?php echo $orderShipped ? ($orderShipped->tracking_number === "" ? 'Tracking link will be available on next reload' : PIDEX_BASE_TRACKING_URL . '/' . $orderShipped->tracking_number) : 'Tracking link will be available on next reload'; ?>" readonly>

            <button type="button" class="bg-[#ec4d26] p-2 rounded-tr-md rounded-br-md rounded-tl-none rounded-bl-none" onclick="copyTrackingLinkClipboard()" <?php echo esc_attr($orderShipped ? ($orderShipped->tracking_number === "" ? 'disabled' : '') : 'disabled'); ?>>
                <svg width="20px" height="20px" enable-background="new 0 0 64 64" fill="#FFFFFF" version="1.1" viewBox="0 0 64 64" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                    <path d="m53.979 9.1429h-3.9683c-0.082699 0-0.1562 0.0284-0.2331 0.047v-4.1671c0-2.7698-2.3046-5.0228-5.1379-5.0228h-34.423c-2.8333 0-5.1379 2.253-5.1379 5.0228v46.843c0 2.7698 2.3046 5.0228 5.1379 5.0228h6.0367v2.2679c0 2.6706 2.2163 4.8432 4.9415 4.8432h32.784c2.7252 0 4.9415-2.1726 4.9415-4.8432v-45.171c-1.8e-6 -2.6706-2.2163-4.8432-4.9415-4.8432zm-46.868 42.723v-46.843c0-1.6488 1.3939-2.991 3.1062-2.991h34.423c1.7123 0 3.1062 1.3422 3.1062 2.991v46.843c0 1.6488-1.3939 2.9911-3.1062 2.9911h-34.423c-1.7123 4e-7 -3.1062-1.3423-3.1062-2.9911zm49.778 7.2907c0 1.5506-1.3055 2.8115-2.9097 2.8115h-32.784c-1.6042 0-2.9098-1.2609-2.9098-2.8115v-2.2679h26.354c2.8333 0 5.1379-2.253 5.1379-5.0228v-40.739c0.0769 0.0186 0.1504 0.047 0.2331 0.047h3.9683c1.6042 0 2.9097 1.2609 2.9097 2.8115v45.171z" />
                    <path d="m38.603 13.206h-22.349c-0.5615 0-1.0159 0.4543-1.0159 1.0158 0 0.5616 0.4544 1.0159 1.0159 1.0159h22.349c0.5615 0 1.0159-0.4543 1.0159-1.0159 0-0.5615-0.4544-1.0158-1.0159-1.0158z" />
                    <path d="m38.603 21.333h-22.349c-0.5615 0-1.0159 0.4543-1.0159 1.0158 0 0.5615 0.4544 1.0159 1.0159 1.0159h22.349c0.5615 0 1.0159-0.4544 1.0159-1.0159 0-0.5615-0.4544-1.0158-1.0159-1.0158z" />
                    <path d="m38.603 29.46h-22.349c-0.5615 0-1.0159 0.4544-1.0159 1.0159s0.4544 1.0159 1.0159 1.0159h22.349c0.5615 0 1.0159-0.4544 1.0159-1.0159s-0.4544-1.0159-1.0159-1.0159z" />
                    <path d="m28.444 37.587h-12.19c-0.5615 0-1.0159 0.4544-1.0159 1.0159s0.4544 1.0159 1.0159 1.0159h12.19c0.5615 0 1.0158-0.4544 1.0158-1.0159s-0.4543-1.0159-1.0158-1.0159z" />
                </svg>
            </button>
        </div>

        <?php if ($orderShipped) { ?>
            <?php if ($orderShipped->tracking_number !== "") { ?>
                <div class="flex">
                    <a href="<?php echo esc_url(PIDEX_BASE_ORDER_DETAILS_URL . '/' . $orderShipped->tracking_number); ?>" target="_blank" class="text-center w-full p-2 bg-white border-[#4e4e4f] border rounded-md text-[#4e4e4f] font-bold hover:bg-[#4e4e4f] hover:text-white">
                        Details
                    </a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <div id="pidex-place-order-container" class="<?php echo sanitize_html_class($orderShipped ? 'hidden' : ''); ?> nunito mx-auto flex flex-col gap-1 p-5 rounded-md shadow-[2px_2px_2px_1px_rgba(0,0,0,0.25),inset_2px_2px_2px_1px_rgba(0,0,0,0.25)]">

        <div class="flex justify-end">
            <img src="<?php echo esc_url(PIDEX_ASSETS_URL . '/image/pidex.svg'); ?>" alt="pidex" class="w-full">
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Merchant Order ID
            </label>

            <input type="text" name="merchant_order_id" id="merchant_order_id" class="border border-[#4e4e4f82] text-[#4e4e4f] h-10 rounded-md px-3" placeholder="<?php esc_attr__('Merchant Order ID', 'pidex'); ?>" value="<?php echo esc_attr($this->isParcelInformationSet() ? $this->parcelInformation['merchantOrderId'] : ''); ?>">

            <input type="hidden" name="woocommerce_product_id" id="woocommerce_product_id" value="<?php echo esc_attr($this->isParcelInformationSet() ? $this->parcelInformation['merchantOrderId'] : ''); ?>">

            <small class="text-[#4e4e4f]" id="merchant_order_id_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Recipient Name
                <span class="text-[#4e4e4f]">*</span>
            </label>
            <input type="text" name="recipient_name" id="recipient_name" class="border border-[#4e4e4f82] text-[#4e4e4f] h-10 rounded-md px-3" placeholder="<?php esc_attr__('Recipient Name', 'pidex'); ?>" value="<?php echo esc_attr($this->isParcelInformationSet() ? $this->parcelInformation['recipientName'] : ''); ?>">

            <small class="text-[#4e4e4f]" id="recipient_name_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Recipient Phone
                <span class="text-[#4e4e4f]">*</span>
            </label>
            <input type="text" name="recipient_phone" id="recipient_phone" class="border border-[#4e4e4f82] text-[#4e4e4f] h-10 rounded-md px-3" placeholder="<?php esc_attr__('Recipient Phone', 'pidex'); ?>" value="<?php echo esc_attr($this->isParcelInformationSet() ? $this->parcelInformation['recipientPhone'] : ''); ?>">

            <small class="text-[#4e4e4f]" id="recipient_phone_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Recipient Address
                <span class="text-[#4e4e4f]">*</span>
            </label>
            <textarea type="text" name="recipient_address" id="recipient_address" class="border border-[#4e4e4f82] text-[#4e4e4f] h-20 rounded-md px-3 py-2" placeholder="<?php esc_attr__('Recipient Address', 'pidex'); ?>"><?php echo esc_attr($this->isParcelInformationSet() ? $this->parcelInformation['recipientAddress'] : ''); ?></textarea>

            <small class="text-[#4e4e4f]" id="recipient_address_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Amount To Collect
                <span class="text-[#4e4e4f]">*</span>
            </label>
            <input type="text" name="amount_to_collect" id="amount_to_collect" class="border border-[#4e4e4f82] text-[#4e4e4f] h-10 rounded-md px-3" placeholder="<?php esc_attr__('Amount To Collect', 'pidex'); ?>" value="<?php echo esc_attr($this->isParcelInformationSet() ? $this->parcelInformation['amountToCollect'] : ''); ?>">

            <small class="text-[#4e4e4f]" id="amount_to_collect_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Recipient City
                <span class="text-[#4e4e4f]">*</span>
            </label>

            <select name="recipient-city" id="recipient-city-select" class="pidex-select">
            </select>

            <small class="text-[#4e4e4f] mt-2" id="recipient-city-select_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Recipient Zone
                <span class="text-[#4e4e4f]">*</span>
            </label>

            <select name="recipient-zone" id="recipient-zone-select" class="pidex-select">
            </select>

            <small class="text-[#4e4e4f] mt-2" id="recipient-zone-select_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Delivery Type
                <span class="text-[#4e4e4f]">*</span>
            </label>

            <select name="delivery-type" id="recipient-delivery-type-select" class="pidex-select">
            </select>

            <small class="text-[#4e4e4f] mt-2" id="recipient-delivery-type-select_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Quantity
            </label>
            <input type="text" name="quantiy" id="quantity" class="border border-[#4e4e4f82] text-[#4e4e4f] h-10 rounded-md px-3" placeholder="<?php esc_attr__('Merchant Order ID', 'pidex'); ?>" value="<?php echo esc_attr($this->isParcelInformationSet() ? $this->parcelInformation['quantity'] : ''); ?>">

            <small class="text-[#4e4e4f]" id="quantity_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Special Instruction
            </label>
            <textarea type="text" name="special_instruction" id="special_instruction" class="border border-[#4e4e4f82] text-[#4e4e4f] h-20 rounded-md px-3 py-2" placeholder="<?php esc_attr__('Special Instruction', 'pidex'); ?>"></textarea>

            <small class="text-[#4e4e4f]" id="special_instruction_error"></small>
        </div>

        <div class="flex flex-col">
            <label for="" class="text-xs font-semibold text-[#ec4d26] mb-1">
                Item Description & Item Price
            </label>
            <textarea type="text" name="item_description_and_price" id="item_description_and_price" class="border border-[#4e4e4f82] text-[#4e4e4f] h-20 rounded-md px-3 py-2" placeholder="<?php esc_attr__('Item Description & Item Price', 'pidex'); ?>"><?php echo esc_attr($this->isParcelInformationSet() ? $this->parcelInformation['itemDescriptionAndPrice'] : ''); ?></textarea>

            <small class="text-[#4e4e4f]" id="item_description_and_price_error"></small>
        </div>

        <div class="mt-3">
            <input type="hidden" name="woocommerce_order_dump" id="woocommerce_order_dump" value="<?php echo esc_attr($this->isParcelInformationSet() ? $this->parcelInformation['wooCommerceOrderDump'] : ''); ?>">

            <button id="pidex-place-order-button" value="Submit" type="button" class="bg-[#ec4d26] rounded-md px-3 py-1 text-white font-semibold border-0 disabled:bg-[#4e4e4f] hover:scale-[1.1] transition-transform" disabled>
                Pidex
            </button>
        </div>

        <div class="p-3 mt-3 border border-dashed rounded-md border-[#4e4e4f] hidden" id="overall-validation-messagebox">
            <span class="text-sm">Please fix validation Error(s)</span>
        </div>

        <div class="p-3 mt-3 border border-dashed rounded-md border-[#4e4e4f] hidden" id="pidex-validation-messagebox">
            <span class="text-sm" id="pidex-validation-messagebox-text"></span>
        </div>
    </div>

<?php } ?>