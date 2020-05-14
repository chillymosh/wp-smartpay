<?php
// var_dump($product)

$form_action = smartpay_get_payment_page_uri();
$gateways = smartpay_get_enabled_payment_gateways(true);

$chosen_gateway = isset($_REQUEST['gateway']) && smartpay_is_gateway_active($_REQUEST['gateway']) ? $_REQUEST['gateway'] : smartpay_get_default_gateway();
$has_payment_error = false;
?>

<div class="smartpay">
	<div class="card">
		<div class="bg-light border-bottom">
			<img src="//static-2.gumroad.com/res/gumroad/6164410083782/asset_previews/1141ebca31596778ba2dd76532e2caa7/retina/responsive.png" class="card-img-top" alt="<?php echo $product->name; ?>">
		</div>
		<div class="card-body p-5">
			<div class="row">
				<div class="col-8">
					<p class=""><?php echo smartpay_amount_format($product->base_price); ?></p>
					<h2 class="card-title mt-0 mb-2"><?php echo $product->name; ?></h2>
					<div class="card-text"><?php echo $product->description; ?></div>
				</div>
				<div class="col">
					<form action="<?php echo $form_action; ?>" method="POST">

						<?php wp_nonce_field('smartpay_process_payment', 'smartpay_process_payment'); ?>

						<input type="hidden" name="smartpay_action" value="smartpay_process_payment">
						<input type="hidden" name="smartpay_purchase_type" value="product_purchase">
						<input type="hidden" name="smartpay_product_id" value="<?php echo $product->get_ID() ?>">
						<!-- // TODO: Implement variations -->
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

						<input type="text" name="smartpay_first_name" value="Al-Amin">
						<input type="text" name="smartpay_last_name" value="Firdows">
						<input type="text" name="smartpay_email" value="alaminfirdows@gmail.com">
						<br>

						<button type="submit" class="btn btn-primary btn-block btn-lg" <?php if ($has_payment_error) echo 'disabled'; ?>>
							<?php echo $payment_button_text ?? 'Pay Now' ?></button>
						</form>
				</div>
			</div> <!-- row -->
		</div> <!-- card-body -->
	</div> <!-- card -->
</div> <!-- .smartpay -->
