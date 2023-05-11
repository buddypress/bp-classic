<?php
/**
 * BP Classic Members Widget Functions.
 *
 * @package bp-classic\inc\members
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Members Legacy Widget.
 *
 * @since 1.0.0
 */
function bp_classic_members_register_members_widget() {
	register_widget( 'BP_Classic_Members_Widget' );
}

/**
 * Registers the "Who's online?" Legacy Widget.
 *
 * @since 1.0.0
 */
function bp_classic_members_register_whos_online_widget() {
	register_widget( 'BP_Classic_Whos_Online_Widget' );
}

/**
 * Registers the "Recently Active" Legacy Widget.
 *
 * @since 1.0.0
 */
function bp_classic_members_register_recently_active_widget() {
	register_widget( 'BP_Classic_Recently_Active_Widget' );
}

/**
 * Register bp-members widgets.
 *
 * @since 1.0.0
 */
function bp_classic_members_register_widgets() {
	add_action( 'widgets_init', 'bp_classic_members_register_members_widget' );
	add_action( 'widgets_init', 'bp_classic_members_register_whos_online_widget' );
	add_action( 'widgets_init', 'bp_classic_members_register_recently_active_widget' );
}
add_action( 'bp_register_widgets', 'bp_classic_members_register_widgets' );

/**
 * AJAX request handler for Members widgets.
 *
 * @since 1.0.0
 *
 * @see BP_Core_Members_Widget
 */
function bp_classic_members_ajax_widget() {

	check_ajax_referer( 'bp_core_widget_members' );

	// Setup some variables to check.
	$filter      = ! empty( $_POST['filter']      ) ? $_POST['filter']                : 'recently-active-members';
	$max_members = ! empty( $_POST['max-members'] ) ? absint( $_POST['max-members'] ) : 5;

	// Determine the type of members query to perform.
	switch ( $filter ) {

		// Newest activated.
		case 'newest-members' :
			$type = 'newest';
			break;

		// Popular by friends.
		case 'popular-members' :
			if ( bp_is_active( 'friends' ) ) {
				$type = 'popular';
			} else {
				$type = 'active';
			}
			break;

		// Default.
		case 'recently-active-members' :
		default :
			$type = 'active';
			break;
	}

	// Setup args for querying members.
	$members_args = array(
		'user_id'         => 0,
		'type'            => $type,
		'per_page'        => $max_members,
		'max'             => $max_members,
		'populate_extras' => true,
		'search_terms'    => false,
	);

	// Query for members.
	if ( bp_has_members( $members_args ) ) : ?>
		<?php echo '0[[SPLIT]]'; // Return valid result. TODO: remove this. ?>
		<?php while ( bp_members() ) : bp_the_member(); ?>
			<li class="vcard">
				<div class="item-avatar">
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
				</div>

				<div class="item">
					<div class="item-title fn"><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></div>
					<?php if ( 'active' === $type ) : ?>
						<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div>
					<?php elseif ( 'newest' === $type ) : ?>
						<div class="item-meta"><span class="activity"><?php bp_member_registered(); ?></span></div>
					<?php elseif ( bp_is_active( 'friends' ) ) : ?>
						<div class="item-meta"><span class="activity"><?php bp_member_total_friend_count(); ?></span></div>
					<?php endif; ?>
				</div>
			</li>

		<?php endwhile; ?>

	<?php else: ?>
		<?php echo "-1[[SPLIT]]<li>"; ?>
		<?php esc_html_e( 'There were no members found, please try another filter.', 'buddypress' ) ?>
		<?php echo "</li>"; ?>
	<?php endif;
}
add_action( 'wp_ajax_widget_members', 'bp_classic_members_ajax_widget' );
add_action( 'wp_ajax_nopriv_widget_members', 'bp_classic_members_ajax_widget' );