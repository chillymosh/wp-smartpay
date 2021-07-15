<?php

namespace SmartPay\Modules\Coupon;

use SmartPay\Http\Controllers\Rest\Admin\CouponController;
use SmartPay\Models\Coupon as ModelsCoupon;
use SmartPay\Models\Form;
use WP_REST_Server;

class Coupon
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;

        $this->app->addAction('admin_enqueue_scripts', [$this, 'adminScripts']);

        $this->app->addAction('rest_api_init', [$this, 'registerRestRoutes']);
        $this->app->addFilter('smartpay_settings_general', [$this, 'couponSettings']);
        $this->app->addAction('before_smartpay_payment_form', [$this, 'showAlert'], 10, 1);
        $this->app->addAction('before_smartpay_payment_form', [$this, 'smartpayCouponPaymentForm'], 20, 1);
        $this->app->addAction('before_smartpay_payment_form_button', [$this, 'showAppliedCouponData'], 20, 1);
        $this->app->addAction('wp_ajax_smartpay_coupon', [$this, 'appliedCouponInForm']);
        $this->app->addAction('wp_ajax_nopriv_smartpay_coupon', [$this, 'appliedCouponInForm']);
    }

    public function adminScripts()
    {
        //
    }

    public function registerRestRoutes()
    {
        $couponController = $this->app->make(CouponController::class);

        register_rest_route('smartpay/v1', 'coupons', [
            [
                'methods'   => WP_REST_Server::READABLE,
                'callback'  => [$couponController, 'index'],
                'permission_callback' => [$couponController, 'middleware'],
            ],
            [
                'methods'   => WP_REST_Server::CREATABLE,
                'callback'  => [$couponController, 'store'],
                'permission_callback' => [$couponController, 'middleware'],
            ],
        ]);

        register_rest_route('smartpay/v1', 'coupons/(?P<id>[\d]+)', [
            [
                'methods'   => WP_REST_Server::READABLE,
                'callback'  => [$couponController, 'show'],
                'permission_callback' => [$couponController, 'middleware'],
            ],
            [
                'methods'   => 'PUT, PATCH',
                'callback'  => [$couponController, 'update'],
                'permission_callback' => [$couponController, 'middleware'],
            ],
            [
                'methods'   => WP_REST_Server::DELETABLE,
                'callback'  => [$couponController, 'destroy'],
                'permission_callback' => [$couponController, 'middleware'],
            ],
        ]);
    }

    public function couponSettings($settings)
    {
        $settings['main']['coupon_heading_settings'] = [
            'id'   => 'coupon_heading_settings',
            'name' => '<h4 class="text-uppercase text-info my-1">' . __('Coupons Settings', 'smartpay') . '</h4>',
            'desc' => '',
            'type' => 'header',
        ];
        $settings['main']['coupon_settings_for_form'] = [
            'id'   => 'coupon_settings_for_form',
            'name' => __('Enable coupons at form', 'smartpay'),
            'label' => __('Enable the use of coupon codes', 'smartpay'),
            'type' => 'checkbox',
        ];
        $settings['main']['coupon_settings_for_product'] = [
            'id'   => 'coupon_settings_for_product',
            'name' => __('Enable coupons at product', 'smartpay'),
            'label' => __('Enable the use of coupon codes', 'smartpay'),
            'type' => 'checkbox',
        ];
        return $settings;
    }

    public function smartpayCouponPaymentForm($form)
    {
        $enable_coupon_settings = smartpay_get_option('coupon_settings_for_form');

        if (!$enable_coupon_settings) {
            return;
        }
?>
<div class="smartpay-coupon-form-toggle">
    <div class="coupon-info mb-4 p-4 bg-light">
        <?php _e('Have a coupon?', 'smartpay'); ?>
        <a href="#" class="smartpayshowcoupon"><?php _e('Click here to enter your code', 'smartpay'); ?></a>
    </div>
</div>
<form class="smartpaycouponform p-4 bg-light d-none">
    <p><?php _e('If you have a coupon code, please apply it below.', 'smartpay'); ?></p>
    <div class="d-flex">
        <input type="text" name="coupon_code" class="m-0" placeholder="<?php _e('Coupon code', 'smartpay'); ?>" id=" coupon_code" style="flex: 1;" />
        <button class="rounded" type="submit" name="submitcoupon"><?php _e('Apply coupon', 'smartpay'); ?></button>
    </div>
</form>
<div></div>
<?php
    }

    public function appliedCouponInForm()
    {
        $couponCode = $_POST['couponCode'] ?? null;
        $formId = $_POST['formId'] ?? null;
        $coupon = ModelsCoupon::where('title', $couponCode)->first();
        if (!$coupon) {
            wp_send_json_error(['message' => 'Coupon Not Found']);
        }

        // expiry date check
        if ($this->validateDate($coupon->expiry_date)) {
            $currentDate = date_create(date('Y-m-d'));
            $expiryDate = date_create($coupon->expiry_date);
            $diff = date_diff($currentDate,  $expiryDate);
            if ($diff->format("%R%a") < 0) {
                wp_send_json_error(['message' => 'Coupon expires']);
            }
        }

        $couponDiscountAmount = $coupon->discount_amount;
        $couponDiscountType = $coupon->discount_type;

        $form = Form::where('id', $formId)->first();
        $formAmounts = $form->amounts;
        $couponData = [];

        foreach ($formAmounts as $singleAmount) {
            if ($couponDiscountType == 'fixed') {
                $discountAmount = $singleAmount['amount'] - $couponDiscountAmount;
                $discountAmount = $discountAmount > 0 ? $discountAmount : 0;
                $couponAmount = $couponDiscountAmount;
            } elseif ($couponDiscountType == 'percent') {
                $discountAmount = $singleAmount['amount'] - ($singleAmount['amount'] * $couponDiscountAmount) / 100;
                $discountAmount = $discountAmount > 0 ? $discountAmount : 0;
                $couponAmount = ($singleAmount['amount'] * $couponDiscountAmount) / 100;
            }
            $couponData['_form_amount_' . $singleAmount['key']] = [
                'mainAmount'        => $singleAmount['amount'],
                'discountAmount'    =>  $discountAmount,
                'couponAmount'      =>  $couponAmount,
            ];
        }

        $currency = smartpay_get_option('currency', 'USD');
        $symbol = smartpay_get_currency_symbol($currency);

        wp_send_json_success(['message' => 'Coupon Applied Successfully', 'currency' => $symbol, 'couponData' => $couponData, 'couponCode' => $couponCode]);
        wp_die();
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function showAlert($form)
    {
    ?>
<div class="smartpay-message-info">
</div>
<?php }

    public function showAppliedCouponData($form)
    {
    ?>
<div class="discount-amounts-container mb-3 d-none">
    <div class="py-2">
        <p class="d-flex justify-content-between m-0">
            <span><?php _e('Subtotal', 'smartpay'); ?></span>
            <span class="subtotal-amount-value"></span>
        </p>
    </div>


    <div class="py-2 border-top border-bottom border-dark">
        <p class="d-flex justify-content-between m-0">
            <span class="coupon-amount-name"></span>
            <span class="coupon-amount-value"></span>
        </p>
    </div>


    <div class="py-2">
        <p class="d-flex justify-content-between m-0">
            <span><?php _e('Total due', 'smartpay'); ?></span>
            <span class="total-amount-value"></span>
        </p>
    </div>

</div>
<?php
    }
}