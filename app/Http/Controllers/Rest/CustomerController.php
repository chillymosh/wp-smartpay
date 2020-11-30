<?php

namespace SmartPay\Http\Controllers\Rest;

use SmartPay\Http\Controllers\RestController;
use SmartPay\Models\Customer;
use WP_REST_Request;
use WP_REST_Response;

class CustomerController extends RestController
{
    /**
     * Check permissions for the request.
     *
     * @param WP_REST_Request $request.
     */
    public function middleware(WP_REST_Request $request)
    {
        if (!current_user_can('manage_options')) {
            return new \WP_Error('rest_forbidden', esc_html__('You cannot view the resource.'), [
                'status' => is_user_logged_in() ? 403 : 401,
            ]);
        }

        return true;
    }

    /**
     * Get a customer
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function show(WP_REST_Request $request): WP_REST_Response
    {
        $customer = Customer::find($request->get_param('id'));

        if (!$customer) {
            return new WP_REST_Response(['message' => __('Customer not found', 'smartpay')], 404);
        }

        return new WP_REST_Response(['customer' => $customer]);
    }

    /**
     * Update customer
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function update(WP_REST_Request $request): WP_REST_Response
    {
        $customer = Customer::find($request->get_param('id'));

        if (!$customer) {
            return new WP_REST_Response(['message' => __('Customer not found', 'smartpay')], 404);
        }

        $requestData = \json_decode($request->get_body(), true);

        if (empty($requestData['first_name']) || empty($requestData['last_name']) || empty($requestData['email'])) {
            return new WP_REST_Response(['message' => __('You must input first name, last name and email', 'smartpay')], 404);
        }

        if (isset($requestData['password']) && (!isset($requestData['password_confirm']) || ($requestData['password'] !== $requestData['password_confirm']))) {
            return new WP_REST_Response(['message' => __('Password not matched', 'smartpay')], 404);
        };

        global $wpdb;
        $wpdb->query('START TRANSACTION');

        $customer->first_name = $requestData['first_name'];
        $customer->last_name = $requestData['last_name'];
        $customer->email = $requestData['email'];
        $customer->save();

        //TODO: user Database table
        $userdata = wp_update_user([
            'ID' => $request->get_param('id'),
            'display_name' => $requestData['first_name']  . ' ' . $requestData['last_name'],
            'user_email' => $requestData['email'],
        ]);

        if (is_wp_error($userdata)) {
            $wpdb->query('ROLLBACK');
            return new WP_REST_Response(['message' => __('Customer info not updated', 'smartpay')], 404);
        }

        $wpdb->query('COMMIT');
        return new WP_REST_Response(['customer' => $customer, 'message' => __('Customer updated', 'smartpay')]);
    }
}