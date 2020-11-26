<?php

namespace SmartPay\Modules\Frontend;

use SmartPay\Modules\Frontend\Utilities\Downloader;

class Common
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;

        $this->app->make(Downloader::class);

        $this->app->addAction('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts()
    {
        wp_register_style('smartpay-app', SMARTPAY_PLUGIN_ASSETS . '/css/app.css', '', SMARTPAY_VERSION);
        wp_enqueue_style('smartpay-app');

        wp_register_script('smartpay-bootstrap', SMARTPAY_PLUGIN_ASSETS . '/js/bootstrap.js', ['jquery'], SMARTPAY_VERSION);
        wp_enqueue_script('smartpay-bootstrap');
        wp_register_script('smartpay-app', SMARTPAY_PLUGIN_ASSETS . '/js/app.js', ['jquery'], SMARTPAY_VERSION, true);
        wp_enqueue_script('smartpay-app');
        wp_localize_script(
            'smartpay-app',
            'smartpay',
            array(
                'restUrl'  => get_rest_url('', 'smartpay'),
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'apiNonce' => wp_create_nonce('wp_rest')
            )
        );
    }
}
