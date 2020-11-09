<?php

namespace SmartPay\Modules\Admin;

use SmartPay\Http\Controllers\Admin\FormController;
use SmartPay\Http\Controllers\Admin\CustomerController;

class Admin
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;

        $this->app->addAction('admin_enqueue_scripts', [$this, 'adminScripts']);
        $this->app->addAction('admin_menu', [$this, 'adminMenu']);
    }

    public function adminMenu()
    {
        add_menu_page(
            __('Dashboard', 'smartpay'),
            __('SmartPay', 'smartpay'),
            'manage_options',
            'smartpay',
            function () {
                echo view('admin');
            },
            smartpay_svg_icon(),
            25
        );

        add_submenu_page(
            'smartpay',
            __('SmartPay - Products', 'smartpay'),
            __('Products', 'smartpay'),
            'manage_options',
            'smartpay#/products',
            function () {
                echo view('admin');
            }
        );

        add_submenu_page(
            'smartpay',
            __('SmartPay - Forms', 'smartpay'),
            __('Forms', 'smartpay'),
            'manage_options',
            'smartpay#/forms',
            function () {
                echo view('admin');
            }
        );

        add_submenu_page(
            'smartpay',
            __('SmartPay - Customers', 'smartpay'),
            __('Customers', 'smartpay'),
            'manage_options',
            'smartpay#/customers',
            function () {
                echo view('admin');
            }
        );

        add_submenu_page(
            'smartpay',
            __('SmartPay - Coupons', 'smartpay'),
            __('Coupons', 'smartpay'),
            'manage_options',
            'smartpay#/coupons',
            function () {
                echo view('admin');
            }
        );

        add_submenu_page(
            'smartpay',
            __('SmartPay - Payments', 'smartpay'),
            __('Payments', 'smartpay'),
            'manage_options',
            'smartpay#/payments',
            function () {
                echo view('admin');
            }
        );
    }

    public function adminScripts()
    {
        wp_register_style('smartpay-admin', SMARTPAY_PLUGIN_ASSETS . '/css/admin.css', '', SMARTPAY_VERSION);
        wp_enqueue_style('smartpay-admin');

        wp_register_script('smartpay-admin', SMARTPAY_PLUGIN_ASSETS . '/js/admin.js', ['jquery', 'wp-element', 'wp-data'], SMARTPAY_VERSION);
        wp_enqueue_script('smartpay-admin');
        wp_localize_script(
            'smartpay-admin',
            'smartpay',
            array(
                'restUrl'  => get_rest_url('', 'smartpay'),
                'adminUrl'  => admin_url('admin.php'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'apiNonce' => wp_create_nonce('wp_rest')
            )
        );

        wp_enqueue_editor();
        wp_enqueue_media();
    }
}