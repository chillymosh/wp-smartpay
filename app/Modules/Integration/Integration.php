<?php

namespace SmartPay\Modules\Integration;

class Integration
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;

        $this->app->addAction('plugins_loaded', [$this, 'bootIntegrations'], 99);

        $this->app->addAction('admin_enqueue_scripts', [$this, 'adminScripts']);

        add_action('wp_ajax_toggle_integration_activation', [$this, 'toggleIntegrationActivation']);
    }

    public static function getIntegrations()
    {
        return [
            'paddle'    =>  [
                'name'       => 'Paddle',
                'excerpt'    => 'Paddle provides financial services for SaaS and Digital services.',
                'cover'      => SMARTPAY_PLUGIN_ASSETS . '/img/integrations/paddle.png',
                'manager'    => null,
                'type'       => 'pro',
                'categories' => ['Payment Gateway'],
            ],
            'stripe'    => [
                'name'       => 'Stripe',
                'excerpt'    => 'Stripe is an American financial services providing company.',
                'cover'      => SMARTPAY_PLUGIN_ASSETS . '/img/integrations/stripe.png',
                'manager'    => null,
                'type'       => 'pro',
                'categories' => ['Payment Gateway'],
            ],
            'bkash' => [
                'name'       => 'bKash',
                'excerpt'    => 'bKash is a mobile financial service in Bangladesh.',
                'cover'      => SMARTPAY_PLUGIN_ASSETS . '/img/integrations/bkash.png',
                'manager'    => null,
                'type'       => 'pro',
                'categories' => ['Payment Gateway'],
            ]
        ];
    }

    public static function getIntegrationManager(string $manager)
    {
        return smartpay()->make($manager);
    }

    public function bootIntegrations()
    {
        foreach (smartpay_active_integrations() as $namespace => $integration) {
            if (!class_exists($integration['manager'])) {
                continue;
            }

            smartpay_integration_get_manager($integration['manager'])->boot();

            do_action('smartpay_integration_' . strtolower($namespace) . '_loaded');
        }

        do_action('smartpay_integrations_loaded');
    }

    public function adminScripts($hook)
    {
        if ('smartpay_page_smartpay-integrations' === $hook) {
            wp_register_script('smartpay-admin-integration', SMARTPAY_PLUGIN_ASSETS . '/js/integration.js', ['jquery'], SMARTPAY_VERSION, true);
            wp_enqueue_script('smartpay-admin-integration');

            // TODO: Make it global
            wp_localize_script(
                'smartpay-admin-integration',
                'smartpay',
                array(
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                )
            );
        }
    }

    public function toggleIntegrationActivation()
    {
        if (!isset($_POST['payload']['nonce']) || !wp_verify_nonce($_POST['payload']['nonce'], 'smartpay_integrations_toggle_activation')) {
            echo 'Invlid request';
            die();
        }

        $action    = sanitize_text_field($_POST['payload']['action'] ?? '');
        $namespace = sanitize_text_field($_POST['payload']['namespace'] ?? '');

        if (!in_array($namespace, array_keys(smartpay_integrations()))) {
            echo 'Requested for invalid integration';
            die();
        }

        if ('activate' === $action) {
            $this->activateIntegration($namespace);
        } else {
            $this->deactivateIntegration($namespace);
        }

        die(); // Must terminate the api/ajax request
    }

    private function activateIntegration(string $integration)
    {
        global $smartpay_options;

        if (!is_array($smartpay_options['integrations'])) {
            $smartpay_options['integrations'] = [];
        }

        if (!in_array($integration, array_keys($smartpay_options['integrations']))) {
            $smartpay_options['integrations'][$integration] = [
                'active'   => true,
                'settings' => []
            ];
        } else {
            $smartpay_options['integrations'][$integration]['active'] = true;
        }

        smartpay_update_settings($smartpay_options);
        echo 'Activated';
    }

    private function deactivateIntegration(string $integration)
    {
        global $smartpay_options;

        if (!in_array($integration, array_keys($smartpay_options['integrations']))) {
            $smartpay_options['integrations'][$integration] = [
                'active'   => false,
                'settings' => []
            ];
        } else {
            $smartpay_options['integrations'][$integration]['active'] = false;
        }

        smartpay_update_settings($smartpay_options);
        echo 'Disabled';
    }
}