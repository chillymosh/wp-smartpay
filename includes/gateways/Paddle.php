<?php

namespace ThemesGrove\SmartPay\Gateways;

use ThemesGrove\SmartPay\Models\SmartPay_Payment;
use ThemeXpert\Paddle\Paddle as PaddleSDK;
use ThemeXpert\Paddle\Util\Price as PaddleSDKPrice;
use ThemeXpert\Paddle\Product\PayLink as PaddleSDKPayLink;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
final class Paddle extends SmartPay_Payment_Gateway
{
    /**
     * The single instance of this class
     */
    private static $instance = null;

    /**
     * Paddle API credentials.
     *
     * @var object
     * @since 1.0.0
     */
    private $credentials = null;

    private static $supported_currency = ['USD', 'EUR', 'GBP', 'ARS', 'AUD', 'BRL', 'CAD', 'CHF', 'CNY', 'CZK', 'DKK', 'HKD', 'HUF', 'INR', 'JPY', 'KRW', 'MXN', 'NZD', 'PLN', 'RUB', 'SEK', 'SGD', 'TWD', 'ZAR'];

    /**
     * Construct Paddle class.
     *
     * @since 0.1
     * @access private
     */
    private function __construct()
    {
        // You must add this line top of the constractor
        add_action('smartpay_paddle_process_payment', [$this, 'process_payment']);

        if (!smartpay_is_gateway_active('paddle')) {
            return;
        }

        if (!in_array(strtoupper(smartpay_get_currency()), self::$supported_currency)) {
            add_action('admin_notices', [$this, 'unsupported_currency_notice']);
            return;
        }

        // Initialize actions.
        $this->init_actions();
    }

    /**
     * Main Paddle Instance.
     *
     * Ensures that only one instance of Paddle exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since 0.1
     * @return object|Paddle
     * @access public
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Paddle)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Initialize wp actions.
     *
     * @access private
     * @since 1.1.0
     * @return void
     */
    private function init_actions()
    {
        add_action('init', [$this, 'process_webhooks']);
        add_filter('smartpay_settings_sections_gateways', [$this, 'gateway_section']);
        add_filter('smartpay_settings_gateways', [$this, 'gateway_settings']);
        add_filter('smartpay_after_payment_receipt', [$this, 'payment_receipt']);
    }

    /**
     * Process webhook requests.
     *
     * @since 1.1.0
     * @return void
     * @access public
     */
    public function process_webhooks()
    {
        if (isset($_GET['smartpay-listener']) && $_GET['smartpay-listener'] == 'paddle') {
            echo 'paddle webhook';
            die();
        }
    }

    public function process_payment($payment_data)
    {
        global $smartpay_options;

        if (!$this->_set_credentials()) {
            // TODO: Implement smartpay_set_error

            die('Credentials error.');
            wp_redirect(get_permalink($smartpay_options['payment_failure_page']), 302);
        }

        $payment_id = smartpay_insert_payment($payment_data);

        if (!$payment_id) {
            die('Can\'t insert payment.');
            wp_redirect(get_permalink($smartpay_options['payment_failure_page']), 302);
        }

        $payment_price = number_format($payment_data['amount'], 2);

        $pay_link_data = array(
            'title'             => 'Payment #' . $payment_id,
            'image_url'         => get_the_post_thumbnail($payment_data['form_id'], [100, 100]),
            'customer_email'    => $payment_data['user_email'],
            'passthrough'       => $payment_id,
            'prices'            => [(string) new PaddleSDKPrice($payment_data['currency'], $payment_price)],
            'quantity' => 1,
            'quantity_variable' => 0,
            'discountable'      => 0,
            'return_url'        => get_permalink(smartpay_get_success_page_uri()),
            'webhook_url'       => get_bloginfo('url') . '/index.php?' . build_query(array(
                'smartpay-listener' => 'paddle',
                'identifier'        => 'fulfillment-webhook',
                'payment-id'        => $payment_id
            )),
        );

        // API request to create pay link
        $api_response_data = json_decode(PaddleSDKPayLink::create($pay_link_data));

        // If Paylink created successfully
        if ($api_response_data && $api_response_data->success == true) {
            update_post_meta($payment_id, 'paddle_pay_link', $api_response_data->response->url);

            $checkout_location = $smartpay_options['paddle_checkout_location'] ?? 'popup';

            if ($checkout_location == 'paddle_checkout') {
                return wp_redirect($api_response_data->response->url, 302);
            } else {
                return wp_redirect(smartpay_get_success_page_uri(), 302);
            }
        } else {
            die('API response error.');
        }

        return wp_redirect(get_permalink($smartpay_options['payment_failure_page']), 302);
    }

    public function payment_receipt($payment_data)
    {
        if ('paddle' != $payment_data->gateway) {
            return;
        }

        // if($payment_data[])
        // $payment_id = smartpay_get_purchase_session();

        echo $this->_pay_now_content($payment_data);
    }

    private function _pay_now_content($payment_data)
    {

        $vendor_id = smartpay_get_option('paddle_vendor_id');

        if (empty($vendor_id)) {
            die('Credentials error.');
        }

        $paddle_pay_link = get_post_meta($payment_data->id, 'paddle_pay_link', true);

        if (!$paddle_pay_link) {
            die('Paddle pay link not found.');
            return;
        }

        if ('publish' != $payment_data->status) {
            $content = '';
            $content .= '<p>' . __(
                'Thank you for your order, please click the button below to pay with Paddle.',
                'wp-smartpay-edd'
            ) . '</p>';
            $content .= '<div style="margin: 0 auto;text-align: center;">';
            $content .= sprintf('<a href="#!" class="paddle_button button alt" data-override="%s">Pay Now!</a>', $paddle_pay_link);
            $content .= '</div>';

            $content .= '<script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script><script type="text/javascript">';
            $content .= 'jQuery.getScript("https://cdn.paddle.com/paddle/paddle.js", function(){';
            $content .= 'Paddle.Setup({';
            $content .= sprintf('vendor: %s', $vendor_id);
            $content .= ' });';

            // Open popup on page load
            $content .= 'Paddle.Checkout.open({';
            $content .= sprintf('override: "%s"', $paddle_pay_link);
            $content .= '});';

            $content .= '});';
            $content .= '</script>';

            return $content;
        }
    }

    /**
     * Add Gateway subsection
     *
     * @since 1.0.0
     * @param array $sections Gateway subsections
     * @return array
     * @access public
     */
    public function gateway_section(array $sections = array()): array
    {
        $sections['paddle'] = __('Paddle', 'wp-smartpay');

        return $sections;
    }


    /**
     * Register the gateway settings for Paddle
     *
     * @since 1.1.0
     * @param array $settings
     * @return array
     * @access public
     */
    public function gateway_settings(array $settings): array
    {
        $gateway_settings = array(
            array(
                'id'    => 'paddle_settings',
                'name'  => '<strong>' . __('Paddle Gateway Settings', 'wp-smartpay') . '</strong>',
                'desc'  => __('Configure your Paddle Gateway Settings', 'wp-smartpay'),
                'type'  => 'header'
            ),

            array(
                'id'    => 'paddle_vendor_id',
                'name'  => __('Vendor ID', 'wp-smartpay'),
                'desc'  => __('Enter your Paddle Vendor ID', 'wp-smartpay'),
                'type'  => 'text'
            ),
            array(
                'id'    => 'paddle_vendor_auth_code',
                'name'  => __('Auth Codes', 'wp-smartpay'),
                'desc'  => __('Get Auth Code from Paddle : Developer > Authentication', 'wp-smartpay'),
                'type'  => 'text'
            ),
            array(
                'id'    => 'paddle_public_key',
                'name'  => __('Public Key', 'wp-smartpay'),
                'desc'  => __('Get Your Public Key – this can be found  under Developer Tools > Public Key', 'wp-smartpay'),
                'type'  => 'textarea',
                'size'  => 'regular',
            ),
            // array(
            //     'id' => 'paddle_is_api_authenticated_content',
            //     'type' => 'custom_content',
            //     'content' => '<p id="is_api_authenticated_result" class="notice hidden is-dismissible"></p><br><button type="button" id="smartpay_paddle_check_is_api_authenticated" class="button button-primary">Check Credentials</button>',
            // ),
            // array(
            //     'id'    => 'paddle_checkout_label',
            //     'name'  => __('Gateway Title', 'wp-smartpay'),
            //     'desc'  => __('Set a custom title for the payment page. If you don\'t set, it will use the default value.', 'wp-smartpay'),
            //     'type'  => 'text',
            // ),
            // array(
            //     'id'    => 'paddle_checkout_icon',
            //     'name'  => __('Gateway Icon', 'wp-smartpay'),
            //     'desc'  => __('Gateway Icon URL must be including http:// or https://. If you don\'t set, it will use the default value.', 'wp-smartpay'),
            //     'type'  => 'upload',
            //     'size'  => 'regular',
            // ),
            // array(
            //     'id'    => 'paddle_checkout_image',
            //     'name'  => __('Checkout Image URL', 'wp-smartpay'),
            //     'desc'  => __('Checkout Image URL must be including https://. If you don\'t set, it will use the default value.', 'wp-smartpay'),
            //     'type'  => 'upload',
            //     'size'  => 'regular',
            // ),
            array(
                'id'    => 'paddle_checkout_location',
                'name'  => __('Checkout Location', 'wp-smartpay'),
                'desc'  => __('Select Checkout Location', 'wp-smartpay'),
                'type'  => 'select',
                'options'   => array(
                    'popup' => 'Popup',
                    'paddle_checkout' => 'Paddle Checkout'
                ),
                'size'  => 'regular',
                'defaultValue'  => 'popup',
            ),
            array(
                'id'    => 'paddle_checkout_location_description',
                'desc'  => __('<p><b>Warning:</b> You must set the Instant Notification System (INS) for Paddle Checkout.<br>', 'wp-smartpay'),
                'type'  => 'descriptive_text',
            ),

            $paddle_webhook_description_text = __(
                sprintf(
                    '<p>For Paddle to function completely, you must configure your Instant Notification System. Visit your <a href="%s" target="_blank">account dashboard</a> to configure them. Please add the URL below to all notification types. It doesn\'t work for localhost or local IP.</p><p><b>INS URL:</b> <code>%s</code></p>.',
                    'https://vendors.paddle.com/alerts-webhooks',
                    home_url("index.php?smartpay-listener=paddle")
                ),
                'wp-smartpay'
            ),

            $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? $paddle_webhook_description_text .= __('<p><b>Warning!</b> It seems you are on the localhost.</p>', 'wp-smartpay') : '',

            array(
                'id'    => 'paddle_webhook_description',
                'type'  => 'descriptive_text',
                'name'  => __('Instant Notification System (INS)', 'wp-smartpay'),
                'desc'  => $paddle_webhook_description_text,

            ),
        );

        return array_merge($settings, ['paddle' => $gateway_settings]);
    }

    /**
     * Set and check API credentials
     *
     * @since 1.1.7
     * @return boolean
     * @access private
     */
    private function _set_credentials(): bool
    {
        global $smartpay_options;

        $vendor_id          = $smartpay_options['paddle_vendor_id']         ?? null;
        $vendor_auth_code   = $smartpay_options['paddle_vendor_auth_code']  ?? null;
        $public_key         = $smartpay_options['paddle_public_key']        ?? null;

        if (empty($vendor_id) || empty($vendor_auth_code) || empty($public_key)) {
            // TODO: Add smartpay payment error notice
            die('SmartPay-Paddle: Set credentials; You must enter your vendor id, auth codes and public key for Paddle in gateway settings.');
        }

        PaddleSDK::setApiCredentials($vendor_id, $vendor_auth_code);

        return true;
    }

    public function unsupported_currency_notice()
    {
        echo __('<div class="error"><p>Unsupported currency! Your currency <code>' . strtoupper(smartpay_get_currency()) . '</code> does not supported by Paddle.</p></div>', 'wp-smartpay');
    }
}