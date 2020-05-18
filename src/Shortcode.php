<?php

namespace SmartPay;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
final class Shortcode
{
    /**
     * The single instance of this class.
     */
    private static $instance = null;

    /**
     * Construct Shortcode class.
     *
     * @since 0.1
     */
    private function __construct()
    {
        add_shortcode('smartpay_form', [$this, 'form_shortcode']);

        add_shortcode('smartpay_product', [$this, 'product_shortcode']);

        add_shortcode('smartpay_payment_receipt', [$this, 'payment_receipt_shortcode']);

        add_shortcode('smartpay_payment_history', [$this, 'payment_history_shortcode']);
    }

    /**
     * Main Shortcode Instance.
     *
     * Ensures that only one instance of Shortcode exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since 0.1
     *
     * @return object|Shortcode
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Shortcode)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function form_shortcode($atts)
    {
        // global $smartpay_options;

        extract(shortcode_atts([
            'id' => null,
        ], $atts));

        if (!isset($id)) {
            return;
        }

        $form = get_post($id);

        if ($form && 'publish' === $form->post_status) {

            $has_keys = true;

            // Show a notice to admins if they have not setup paddle.
            if (!$has_keys && current_user_can('manage_options')) {
                return wp_kses_post(sprintf(
                    /* translators: %1$s Opening anchor tag, do not translate. %2$s Closing anchor tag, do not translate. */
                    __('Please complete your %1$sPaddle Setup%2$s to view the payment form.', 'smartpay'),
                    sprintf(
                        '<a href="%s">',
                        add_query_arg(
                            array(
                                'page' => 'smartpay-setting',
                                'tab'  => 'gateways',
                            ),
                            admin_url('admin.php')
                        )
                    ),
                    '</a>'
                ));
                // Show nothing to guests if Stripe is not setup.
            } else if (!$has_keys && !current_user_can('manage_options')) {
                return '';
            }

            try {
                ob_start();

                $this->render_form_html($form);

                return ob_get_clean();
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function render_form_html($form)
    {

        $payment_page = smartpay_get_option('payment_page', 0);
        if ($payment_page) {
            $data = [
                'form_id'                           => $form->ID,
                'form_action'                       => get_permalink(absint($payment_page)),
                'amount'                            => get_post_meta($form->ID, '_form_amount', true),
                'payment_type'                      => get_post_meta($form->ID, '_form_payment_type', true),
                'amount'                            => get_post_meta($form->ID, '_form_amount', true),
                'payment_button_text'               => get_post_meta($form->ID, '_form_payment_button_text', true),
                'payment_button_processing_text'    => get_post_meta($form->ID, '_form_payment_button_processing_text', true),
                'payment_button_style'              => get_post_meta($form->ID, '_form_payment_button_style', true),
                'paddle_checkout_image'             => get_post_meta($form->ID, '_form_paddle_checkout_image', true),
                'paddle_checkout_location'          => get_post_meta($form->ID, '_form_paddle_checkout_location', true),
            ];

            echo smartpay_view_render('payment/shortcode/pay_now', $data);
        } else {
            echo 'Please setup your payment page.';
        }
    }

    public function product_shortcode($atts)
    {
        extract(shortcode_atts([
            'id' => null,
        ], $atts));

        if (!isset($id)) {
            return;
        }

        $product = smartpay_get_product($id);

        if (!$product->can_purchase()) {
            echo 'You can\'t buy this product';
            return;
        }

        try {
            ob_start();

            echo smartpay_view_render('shortcodes/product', ['product' => $product]);

            return ob_get_clean();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function payment_receipt_shortcode($atts)
    {
        $payment_id = smartpay_get_session_payment_id();

        if (!isset($payment_id)) {
            return;
        }

        $payment = smartpay_get_payment($payment_id);

        try {
            ob_start();

            echo smartpay_view_render('shortcodes/payment_receipt', ['payment' => $payment]);

            return ob_get_clean();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function payment_history_shortcode($atts)
    {
        ob_start();

        echo smartpay_view_render('shortcodes/payment_history');

        return ob_get_clean();
    }
}