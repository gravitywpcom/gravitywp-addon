<?php
/**
 * @license proprietary?
 *
 * Modified by __root__ on 12-January-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
namespace GravityWP\GravityWP_Addon_Name\GravityWP\LicenseHandler;

use GFCommon;

defined( 'ABSPATH' ) || die();

/**
 * Handles GWP Licenses.
 *
 * @version 1.0.18
 */
class LicenseHandler {

	/**
	 * Store the initialized app_sero client
	 *
	 * @since  1.0
	 * @access private
	 * @var    Object $_appsero_client The app_sero client instance.
	 */
	private $_appsero_client = null;

	/**
	 * Store the initialized app_sero license handler
	 *
	 * @since  1.0
	 * @access private
	 * @var    Object $_appsero_client The app_sero client instance.
	 */
	private $_license_handler = null;

	/**
	 * Store the GravityWP GF Addon classname
	 *
	 * @since  1.0
	 * @access private
	 * @var    string $_addon_class the GravityWP GF Addon classname.
	 */
	private $_addon_class = '';

	/**
	 * Store the GravityWP GF Addon slug
	 *
	 * @since  1.0
	 * @access private
	 * @var    string $_addon_slug the GravityWP GF Addon slug.
	 */
	private $_addon_slug = '';

	/**
	 * Store the GravityWP GF Addon license
	 *
	 * @since  1.0
	 * @access private
	 * @var    string $_addon_license the GravityWP GF Addon license.
	 */
	private $_addon_license = '';

	/**
	 * Store the GravityWP GF Addon title
	 *
	 * @since  1.0
	 * @access private
	 * @var    string $_addon_title the GravityWP GF Addon title.
	 */
	private $_addon_title = '';

	/**
	 * Store the GravityWP GF Addon path
	 *
	 * @since  1.0
	 * @access private
	 * @var    string $_addon_file_path The GravityWP GF Addon path.
	 */
	private $_addon_file_path = '';

	/**
	 * Constructor.
	 *
	 * @since  1.0
	 *
	 * @param string $gwp_addon_class GravityWP GF Addon classname.
	 * @param string $license_hash Appsero license hash for this Addon.
	 * @param string $plugin_file_path Path to main plugin file.
	 *
	 * @return void
	 */
	public function __construct( $gwp_addon_class, $license_hash, $plugin_file_path ) {
		$this->_addon_class   = $gwp_addon_class;
		$this->_addon_slug    = $gwp_addon_class::get_instance()->get_slug();
		$this->_addon_license = $gwp_addon_class::get_instance()->get_plugin_setting( $this->_addon_slug . '_license_key' );
		$this->_addon_title   = $gwp_addon_class::get_instance()->plugin_page_title();
		$this->_addon_file_path    = $plugin_file_path;

		$this->_appsero_client = new \GravityWP\GravityWP_Addon_Name\Appsero\Client( $license_hash, $this->_addon_title, $plugin_file_path );

		$this->_license_handler = $this->_appsero_client->license();

		if ( $this->_license_handler->is_valid() ) {
			$this->_appsero_client->updater();

		} else {
			add_action( 'admin_notices', array( $this, 'action_admin_notices' ) );
		}
	}

	/**
	 * Display an admin notice.
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function action_admin_notices() {
		$site_slug           = $this->_addon_class::get_instance()->gwp_site_slug;
		$primary_button_link = admin_url( 'admin.php?page=gf_settings&subview=' . $this->_addon_slug );
		if ( ! empty( $site_slug ) ) {
			$url = "https://gravitywp.com/add-on/{$site_slug}/?utm_source=admin_notice&utm_medium=admin&utm_content=inactive&utm_campaign=Admin%20Notice";
		} else {
			$url = 'https://gravitywp.com/add-ons/?utm_source=admin_notice&utm_medium=admin&utm_content=inactive&utm_campaign=Admin%20Notice';
		}

		$message = esc_html__( 'Your %1$s license has not been actived. This means you are missing out on security fixes, updates and support.%2$sActivate your license%3$s or %4$sget a license here%5$s', 'gravitywp-license-handler' );
		$message = sprintf( $message, $this->_addon_title, '<br /><br /><a href="' . esc_url( $primary_button_link ) . '" class="button button-primary">', '</a>', '<a href="' . esc_url( $url ) . '" class="button button-secondary">', '</a>' );

		$key = $this->_addon_slug . '_license_notice';

		$notice = array(
			'key'          => $key,
			'capabilities' => $this->_addon_slug . '_app_settings',
			'type'         => 'error',
			'text'         => $message,
		);

		$notices = array( $notice );

		GFCommon::add_dismissible_message( $message, $key, 'warning', false, true );
	}

	/**
	 * Define plugin settings fields.
	 *
	 * @since  1.0
	 *
	 * @return array
	 */
	public function plugin_settings_license_fields() {
		$this->_addon_license = $this->_addon_class::get_instance()->get_plugin_setting( $this->_addon_slug . '_license_key' );

		$license_field = array(
			'name'                => $this->_addon_slug . '_license_key',
			'tooltip'             => esc_html__( 'Enter the license key you received after purchasing the plugin.', 'gravitywp-license-handler' ),
			'label'               => esc_html__( 'License Key', 'gravitywp-license-handler' ),
			'type'                => 'text',
			'input_type'          => 'password',
			'class'               => 'medium',
			'default_value'       => '',
			'required'            => true,
			'validation_callback' => array( $this, 'license_validation' ),
			'feedback_callback'   => array( $this, 'license_feedback' ),
			'error_message'       => esc_html__( 'Invalid or expired license', 'gravitywp-license-handler' ),
		);

		return array(
			'title'  => esc_html__( 'To unlock plugin updates and support, please enter your license key below', 'gravitywp-license-handler' ),
			'fields' => array( $license_field ),
		);
	}

	/**
	 * Determine if the license key is valid so the appropriate icon can be displayed next to the field.
	 *
	 * @param string $value The current value of the license_key field.
	 * @param array  $field The field properties.
	 *
	 * @since  1.0
	 *
	 * @return bool|null
	 */
	public function license_feedback( $value, $field ) {
		if ( empty( $value ) ) {
			return null;
		}

		if ( $this->_license_handler->is_valid() ) {
			GFCommon::remove_dismissible_message( $this->_addon_slug . '_license_notice' );
			return true;
		}

		return false;
	}


	/**
	 * Handle license key activation or deactivation.
	 *
	 * @since  1.0
	 *
	 * @param array  $field The field properties.
	 * @param string $field_setting The submitted value of the license_key field.
	 */
	public function license_validation( $field, $field_setting ) {
		$old_license = $this->_addon_class::get_instance()->get_plugin_setting( $this->_addon_slug . '_license_key' );
		if ( $old_license && $field_setting !== $old_license ) {
			// Send the remote request to deactivate the old license.
			$this->_license_handler->license_form_submit(
				array(
					'_nonce'      => wp_create_nonce( $this->_addon_title ), // create a nonce with name.
					'_action'     => 'deactive', // active, deactive.
					'license_key' => '', // no need to provide if you want to deactive.
				)
			);
		}

		if ( ! empty( $field_setting ) ) {
			// Send the remote request to activate the new license.
			$this->_license_handler->license_form_submit(
				array(
					'_nonce'      => wp_create_nonce( $this->_addon_title ), // create a nonce with name.
					'_action'     => 'active', // active, deactive.
					'license_key' => $field_setting,
				)
			);
			// Reset the license handler, to unset the  $_license_handler->is_valid_licnese value.
			$this->_license_handler = $this->_appsero_client->license();
		}
	}
}
