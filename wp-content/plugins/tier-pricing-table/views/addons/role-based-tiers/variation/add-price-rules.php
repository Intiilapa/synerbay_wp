<?php if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * @var string $role
 * @var bool $isFree
 * @var int $minimum_amount
 * @var int $loop
 *
 */

?>

<p class="form-field form-row" style="width: 48%">
    <label for="tiered-price-start-from">
		<?php esc_attr_e( 'Tiered pricing minimum quantity', 'tier-pricing-table' ); ?>
    </label>
	<?php
	$tip = sprintf( __( 'Set if you are selling the product <b>for %s</b> from qty more than 1', 'tier-pricing-table' ), $role );
	echo wc_help_tip( $tip );
	?>
    <br>
    <input type="number"
           placeholder="1"
           value="<?php echo esc_attr( $minimum_amount ); ?>"
           min="1"
           id="tiered-price-start-from"
           name="tiered_pricing_minimum_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo esc_attr($role); ?>]"
    >
</p>

<p class="form-field">
    <label for="tiered-price-type-select"
           style="display: block"><?php _e( "Tiered pricing type", 'tier-pricing-table' ); ?></label>
    <select name="tiered_price_rules_type_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo $role; ?>]" id="tiered-price-type-select"
            style="width: 50%"
            data-role-tiered-price-type-select>
        <option value="fixed" <?php selected( 'fixed', $type ); ?> ><?php _e( 'Fixed',
				'tier-pricing-table' ); ?></option>
		<?php if ( $isFree ): ?>
            <option disabled><?php _e( 'Percentage (Only in premium version)', 'tier-pricing-table' ); ?></option>
		<?php else: ?>
            <option value="percentage" <?php selected( 'percentage', $type ); ?> ><?php _e( 'Percentage',
					'tier-pricing-table' ); ?></option>
		<?php endif; ?>

    </select>
</p>

<p class="form-field <?php echo $type === 'percentage' ? 'hidden' : ''; ?>" data-role-tiered-price-type-fixed
   data-role-tiered-price-type>
    <label style="display: block"><?php _e( "Tiered price", 'tier-pricing-table' ); ?></label>
    <span data-price-rules-wrapper>
        <?php if ( ! empty( $price_rules_fixed ) ): ?>
	        <?php foreach ( $price_rules_fixed as $amount => $price ): ?>
                <span data-price-rules-container>
                    <span data-price-rules-input-wrapper>
                        <input type="number" value="<?php echo $amount; ?>" min="2"
                               placeholder="<?php _e( 'Quantity', 'tier-pricing-table' ); ?>"
                               class="price-quantity-rule price-quantity-rule--simple"
                               name="tiered_price_fixed_quantity_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo $role; ?>][]">
                        <input type="text" value="<?php echo wc_format_localized_price( $price ); ?>"
                               placeholder="<?php _e( 'Price', 'tier-pricing-table' ); ?>"
                               class="wc_input_price price-quantity-rule--simple"
                               name="tiered_price_fixed_price_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo $role; ?>][]">
                    </span>
                    <span class="notice-dismiss remove-price-rule" data-remove-price-rule></span>
                    <br>
                    <br>
                </span>

	        <?php endforeach; ?>
        <?php endif; ?>

        <span data-price-rules-container>
            <span data-price-rules-input-wrapper>
                <input type="number" min="2" placeholder="<?php _e( 'Quantity', 'tier-pricing-table' ); ?>"
                       class="price-quantity-rule price-quantity-rule--simple"
                       name="tiered_price_fixed_quantity_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo $role; ?>][]">
                <input type="text" placeholder="<?php _e( 'Price', 'tier-pricing-table' ); ?>"
                       class="wc_input_price  price-quantity-rule--simple"
                       name="tiered_price_fixed_price_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo $role; ?>][]">
            </span>
            <span class="notice-dismiss remove-price-rule" data-remove-price-rule></span>
            <br>
            <br>
        </span>
    <button data-add-new-price-rule class="button"><?php _e( 'New tier', 'tier-pricing-table' ); ?></button>
    </span>
</p>

<p class="form-field <?php echo $type === 'fixed' ? 'hidden' : ''; ?>" data-role-tiered-price-type-percentage
   data-role-tiered-price-type>
    <label style="display: block"><?php _e( "Tiered price", 'tier-pricing-table' ); ?></label>
    <span data-price-rules-wrapper>
        <?php if ( ! empty( $price_rules_percentage ) ): ?>
	        <?php foreach ( $price_rules_percentage as $amount => $discount ): ?>
                <span data-price-rules-container>
                    <span data-price-rules-input-wrapper>
                        <input type="number" value="<?php echo $amount; ?>" min="2"
                               placeholder="<?php _e( 'Quantity', 'tier-pricing-table' ); ?>"
                               class="price-quantity-rule price-quantity-rule--simple"
                               name="tiered_price_percent_quantity_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo $role; ?>][]">
                        <input type="number" value="<?php echo $discount; ?>" max="99"
                               placeholder="<?php _e( 'Percent discount', 'tier-pricing-table' ); ?>"
                               class="price-quantity-rule--simple"
                               name="tiered_price_percent_discount_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo $role; ?>][]">
                    </span>
                    <span class="notice-dismiss remove-price-rule" data-remove-price-rule></span>
                    <br>
                    <br>
                </span>

	        <?php endforeach; ?>
        <?php endif; ?>

        <span data-price-rules-container>
            <span data-price-rules-input-wrapper>
                <input type="number" min="2" placeholder="<?php _e( 'Quantity', 'tier-pricing-table' ); ?>"
                       class="price-quantity-rule price-quantity-rule--simple"
                       name="tiered_price_percent_quantity_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo $role; ?>][]">
                <input type="number" max="99" placeholder="<?php _e( 'Percent discount', 'tier-pricing-table' ); ?>"
                       class="price-quantity-rule--simple"
                       name="tiered_price_percent_discount_roles_variable[<?php echo esc_attr( $loop ); ?>][<?php echo $role; ?>][]">
            </span>
            <span class="notice-dismiss remove-price-rule" data-remove-price-rule></span>
            <br>
            <br>
        </span>
    <button data-add-new-price-rule class="button"><?php _e( 'New tier', 'tier-pricing-table' ); ?></button>

    </span>
</p>