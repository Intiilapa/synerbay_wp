<?php use Premmerce\SDK\V2\FileManager\FileManager;

defined( "ABSPATH" ) || die;

/**
 * @var int $minimum_amount
 * @var string $role
 * @var string $type
 * @var array $price_rules_fixed
 * @var array $price_rules_percentage
 * @var FileManager $fileManager
 *
 */

global $wp_roles;

$roleName = isset( $wp_roles->role_names[ $role ] ) ? translate_user_role( $wp_roles->role_names[ $role ] ) : $role;
?>

<div class="tpt-role-based-role tpt-role-based-role--<?php echo $role; ?>"
     data-role-slug="<?php echo $role ?>" data-role-name="<?php echo $roleName ?>">
    <div class="tpt-role-based-role__header">
        <div class="tpt-role-based-role__name">
            <b><?php echo $roleName; ?></b>
        </div>
        <div class="tpt-role-based-role__actions">
            <span class="tpt-role-based-role__action-toggle-view tpt-role-based-role__action-toggle-view--open"></span>
            <a href="#" class="tpt-role-based-role-action--delete"><?php _e( 'Remove', 'woocommerce' ); ?></a>
        </div>
    </div>
    <div class="tpt-role-based-role__content">
		<?php

		$fileManager->includeTemplate( 'addons/role-based-tiers/simple/add-price-rules.php', array(
			'minimum_amount'         => $minimum_amount,
			'price_rules_fixed'      => $price_rules_fixed,
			'price_rules_percentage' => $price_rules_percentage,
			'type'                   => $type,
			'isFree'                 => false,
			'role'                   => $role
		) );

		?>
    </div>
</div>