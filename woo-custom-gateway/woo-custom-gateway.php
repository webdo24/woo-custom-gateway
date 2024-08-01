<?php
/**
 * Plugin Name: Custom Woo Payment Option
 * Description: Custom payment gateway plugin for WooCommerce with a custom payment option.
 * Version: 1.6.1
 * Author: webdo24
 * Text Domain: woo-payment-webdo24
 **/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('plugins_loaded', 'init_custom_payment_gateway');

function init_custom_payment_gateway() {
    if (!class_exists('WC_Payment_Gateway')) return;

    class WC_Gateway_Custom_Payment extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'custom_payment_1';
            $this->icon = ''; // URL of the icon that will be displayed on the checkout page
            $this->has_fields = false;
            $this->method_title = __('Custom Payment - 1', 'custom-payment-1');
            $this->method_description = __('Custom payment gateway description.', 'custom-payment-1');

            // Load the settings
            $this->init_form_fields();
            $this->init_settings();

            // Define user settings variables
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');

            // Actions
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'custom-payment-1'),
                    'type' => 'checkbox',
                    'label' => __('Enable Custom Payment - 1', 'custom-payment-1'),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', 'custom-payment-1'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'custom-payment-1'),
                    'default' => __('Custom Payment - 1', 'custom-payment-1'),
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => __('Description', 'custom-payment-1'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'custom-payment-1'),
                    'default' => __('Pay using Custom Payment Method.', 'custom-payment-1')
                ),
                // Add additional fields for custom payment settings if needed
            );
        }

        public function thankyou_page() {
            echo '<p>' . __('Thank you for choosing Custom Payment - 1. Instructions will be sent to your email.', 'custom-payment-1') . '</p>';
        }

        // Optional: Implement process_payment method if needed
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);

            // Process the payment here and update order status
            // For example, mark order as processing
            $order->update_status('on-hold', __('Awaiting Custom Payment Confirmation.', 'custom-payment-1'));

            // Return a thank-you page redirect URL
            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($order)
            );
        }
    }

    function add_custom_payment_gateway($methods) {
        $methods[] = 'WC_Gateway_Custom_Payment';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_custom_payment_gateway');
}
?>
