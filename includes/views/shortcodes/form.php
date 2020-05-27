<?php
$form_action = smartpay_get_payment_page_uri();
$gateways = smartpay_get_enabled_payment_gateways(true);

$chosen_gateway = isset($_REQUEST['gateway']) && smartpay_is_gateway_active($_REQUEST['gateway']) ? $_REQUEST['gateway'] : smartpay_get_default_gateway();
$has_payment_error = false;
?>


<div class="smartpay">
    <div class="card">
        <form id="payment_form" action="<?php echo $form_action; ?>" method="POST">
            <?php wp_nonce_field('smartpay_process_payment', 'smartpay_process_payment'); ?>

            <input type="hidden" name="smartpay_action" value="smartpay_process_payment">
            <input type="hidden" name="smartpay_purchase_type" value="form_payment">
            <input type="hidden" name="form_id" value="<?php echo $form_id ?>">


            <div class="bg-light border-bottom">
                <img src="<?php echo $form_image; ?>" class="card-img-top">
            </div>
            <div class="card-body p-5">

                <h4>Payment : <?php echo smartpay_amount_format($amount); ?></h4>

                <p>Payment type : <?php echo 'recurring' == $payment_type  ? 'Subscription' : 'One Time' ?></p>
                <br>

                <label for="first_name">Payment by</label>
                <ul class="list-unstyled">
                    <?php if (count($gateways)) : ?>

                    <?php foreach ($gateways as $gateway_id => $gateway) : ?>
                    <li>
                        <?php echo '<label for="smartpay-gateway-' . esc_attr($gateway_id) . '">
								<input type="radio" name="smartpay_gateway" id="smartpay-gateway-' . esc_attr($gateway_id) . '" value="' . esc_attr($gateway_id) . '"' . checked($gateway_id, $chosen_gateway, false) . '>';
                                echo esc_html($gateway['checkout_label']);
                                echo '</label>';
                                ?>
                    </li>
                    <?php endforeach; ?>

                    <?php else : ?>
                    <?php
                        $has_payment_error = true;
                        echo 'You must enable a payment gateway to proceed a payment.';
                        ?>
                    <?php endif; ?>
                </ul>

                <br>
                <input type="text" name="smartpay_first_name" value="Al-Amin">
                <input type="text" name="smartpay_last_name" value="Firdows">
                <input type="text" name="smartpay_email" value="alaminfirdows@gmail.com">
                <br>
                <button id="pay_now" type="button" class="btn btn-primary btn-block btn-lg"
                    <?php if ($has_payment_error) echo 'disabled'; ?>>
                    <?php echo $payment_button_text ?: 'Pay Now' ?>
                </button>
            </div>
        </form>
    </div> <!-- card -->

    <!-- Modal -->
    <div class="modal fade" id="smartpay_payment_gateway_popup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Process payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- .smartpay -->