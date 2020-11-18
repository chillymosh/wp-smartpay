<?php

use SmartPay\Modules\Integration\Integration;

function smartpay_integrations()
{
    return apply_filters('smartpay_integrations', Integration::getIntegrations());
}

// function smartpay_active_integrations()
// {
//     $integrations = smartpay_integrations();
//     $activated_integrations = smartpay_get_activated_integrations();

//     return array_intersect_key($integrations, array_flip($activated_integrations));
// }

function smartpay_get_activated_integrations()
{
    $integrations = smartpay_integrations();
    $activated_integrations = (array) smartpay_get_option('activated_integrations', []);

    return array_intersect(array_keys($integrations), $activated_integrations);
}

function smartpay_integration_is_installed($integration)
{
    return isset($integration['manager']) && smartpay_integration_get_manager($integration['manager']);
}

// function smartpay_integration_get_manager(string $manager): Integration
// {
//     return new $manager;
// }

// function smartpay_integration_get_config(Integration $integration): array
// {
//     return $integration->config();
// }

function smartpay_integration_get_not_installed_message(string $type): void
{
    switch ($type) {
        case 'pro':
        default:
            $message = '<a href="#" class="btn btn-sm flex-grow-1 text-decoration-none btn-primary">Upgrade to pro</a>';

            break;
    }

    echo apply_filters('smartpay_integration_get_not_installed_message', $message, $type);
}