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
            <input type="hidden" name="smartpay_payment_type" value="form_payment">
            <input type="hidden" name="smartpay_form_id" value="<?php echo $form->ID ?>">

            <!-- Form image -->
            <?php if (isset($form->image)) : ?>
            <div class="bg-light border-bottom">
                <img src="<?php echo $form->image; ?>" class="card-img-top" alt="<?php echo $form->title; ?>">
            </div>
            <?php endif; ?>

            <div class="card-body p-5">
                <h4><?php echo $form->title; ?></h4>

                <?php if ($form->has_multiple_amount()) : ?>
                <ul class="list-group m-0">
                    <?php foreach ($form->amounts as $index => $amount) : ?>
                    <li class="list-group-item m-0 my-2 py-4">
                        <label for="smartpay-amount-<?php echo esc_attr($index); ?>" class="d-block m-0">
                            <input class="d-none" type="radio" name="smartpay_amount" id="smartpay-amount-<?php echo esc_attr($index); ?>" value="<?php echo esc_attr($index); ?>" checked>
                            <h6 class="m-0"><?php echo smartpay_amount_format($amount); ?></h6>
                        </label>
                    </li>
                    <?php endforeach; ?>

                </ul>
                <?php else : ?>
                <strong>Payment : <?php echo smartpay_amount_format($form->amounts[0]); ?></strong>
                <?php endif; ?>

                <!-- // Allow custom payment -->
                <?php if ($form->allow_custom_amount) : ?>
                <div class="form-group">
                    <label for="smartpay-amount-custom" class="d-block m-0">Pay custom amount</label>
                    <!-- // TODO: On fixed amount click set amount here. -->
                    <input type="text" id="smartpay-amount-custom" name="smartpay_amount" value="<?php echo end($form->amounts); ?>" placeholder="5.0">
                </div>
                <?php endif; ?>

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
                <button id="pay_now" type="button" class="btn btn-primary btn-block btn-lg" <?php if ($has_payment_error) echo 'disabled'; ?>>Pay Now</button>
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