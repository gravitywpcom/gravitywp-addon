<?php

namespace GravityWP\GravityWP_Addon_Name\Field;

use GF_Field;
use GFAPI;
use GFFormDisplay;

defined( 'ABSPATH' ) || die();

/**
 * Number field settings.
 *
 * @since      1.0
 * @package    GravityWP_Addon_Name
 * @subpackage Classes/Field/Settings
 * @author     GravityWP
 * @copyright  Copyright (c) 2022, GravityWP
 */
class Settings {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.0
	 * @var    Settings $_instance If available, contains an instance of this class
	 */
	private static $_instance = null;

	/**
	 * Returns an instance of this class, and stores it in the $_instance property.
	 *
	 * @since  1.0
	 *
	 * @return Settings $_instance An instance of the Settings class
	 */
	public static function get_instance() {

		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	/**
	 * Add admin hooks.
	 *
	 * @since 1.0
	 */
	public function admin_hooks() {

		add_action( 'gform_field_standard_settings', array( $this, 'field_settings' ), 10, 2 );
		add_action( 'gform_editor_js', array( $this, 'editor_script' ) );
		add_filter( 'gform_tooltips', array( $this, 'field_tooltips' ) );

	}

	/**
	 * Add frontend hooks.
	 *
	 * @since 1.0
	 */
	public function frontend_hooks() {

		// Add javascript to frontend to construct the addon name.
		add_filter( 'gform_register_init_scripts', array( $this, 'register_init_scripts' ), 10, 3 );

	}

	/**
	 * Add allowed variables option standard field settings.
	 *
	 * @since 1.0
	 *
	 * @param int $position The position.
	 * @param int $form_id  The form id.
	 */
	public function field_settings( $position, $form_id ) {

		if ( $position === - 1 ) {
			include_once trailingslashit( gwp_addon_name()->get_base_path() ) . 'includes/templates/settings/all.php';
		} 
	}

	/**
	 * Add editor scripts.
	 *
	 * @since 1.0
	 */
	public function editor_script() {
		?>
		<script type='text/javascript'>
			// adding setting to fields of type "number".
			//fieldSettings.number += ', .gwp_addon_name';
		</script>
		<?php
	}

	/**
	 * Add the tooltips.
	 *
	 * @since 1.0
	 *
	 * @param array $tooltips Tooltips.
	 *
	 * @return array
	 */
	public function field_tooltips( $tooltips ) {

		$tooltips['gwp_setting_name'] = sprintf( '<strong>%1$s</strong> %2$s <strong>%3$s</strong>', esc_html__( 'Tooltip', 'gravitywpaddonname' ), esc_html__( 'Tooltip', 'gravitywpaddonname' ), esc_html__( 'Tooltip', 'gravitywpaddonname' ) );
		return $tooltips;

	}

	/**
	 * Add data for frontpage form script.
	 *
	 * @since 1.0
	 *
	 * @param array $form         Form object.
	 * @param array $field_values Current field values. Not used.
	 * @param bool  $is_ajax      If form is being submitted via AJAX.
	 */
	public function register_init_scripts( $form, $field_values, $is_ajax ) {

		if ( ! gwp_addon_name()->frontend_script_callback( $form ) ) {
			return;
		}

		// Load all addonname settings.
		$fields           = GFAPI::get_fields_by_type( $form, array( 'number' ) );
		$args             = array();
		$slider_input_ids = array();
		foreach ( $fields as $field ) {
			if ( ! empty( $field->gwp && ! $field->is_administrative() ) ) {
				$args[ $form['id'] . '_' . $field['id'] ] = $field->gwp;
			}
		}

		$script = 'new GWPAddonname( ' . wp_json_encode( $args, JSON_FORCE_OBJECT ) . ' );';

		// Add the inline script to form scripts.
		GFFormDisplay::add_init_script( $form['id'], gwp_addon_name()->get_slug(), GFFormDisplay::ON_PAGE_RENDER, $script );

		// Modify the initscripts.
		$init_scripts = GFFormDisplay::$init_scripts[ $form['id'] ];

		// Force our script to be added in the beginning of init scripts.
		$key   = gwp_addon_name()->get_slug() . '_' . GFFormDisplay::ON_PAGE_RENDER;
		$value = $init_scripts[ $key ];
		unset( $init_scripts[ $key ] );
		GFFormDisplay::$init_scripts[ $form['id'] ] = array( $key => $value ) + $init_scripts;

	}

}
