<?php

namespace GravityWP\GravityWP_Addon_Name;

defined( 'ABSPATH' ) || die();

use GF_Field;
use GFAPI;
use GFCommon;
use GFForms;
use GFAddOn;
use GFFormsModel;
use GravityWP\GravityWP_Addon_Name\Field\Settings;
use GravityWP\GravityWP_Addon_Name\Utils;
use RGFormsModel;

// Include the Gravity Forms Add-On Framework.
GFForms::include_addon_framework();

/**
 * GravityWP Addon Name.
 *
 * @since      1.0
 * @package    GravityWP_Addon_Name
 * @subpackage Classes/Addon_Name
 * @author     GravityWP
 * @copyright  Copyright (c) 2021, GravityWP
 */
class Addon_Name extends GFAddOn {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.0
	 * @var    Addon_Name $_instance If available, contains an instance of this class
	 */
	private static $_instance = null;

	/**
	 * Defines the version of the GravityWP Addon Name Add-On.
	 *
	 * @since  1.0
	 * @var    string $_version Contains the version.
	 */
	protected $_version = ADDON_NAME_VERSION;

	/**
	 * Defines the minimum Gravity Forms version required.
	 *
	 * @since  1.0
	 * @var    string $_min_gravityforms_version The minimum version required.
	 */
	protected $_min_gravityforms_version = GWP_ADDON_NAME_MIN_GF_VERSION;

	/**
	 * Defines the plugin slug.
	 *
	 * @since  1.0
	 * @var    string $_slug The slug used for this plugin.
	 */
	protected $_slug = 'gravitywpaddonname';

	/**
	 * Defines the main plugin file.
	 *
	 * @since  1.0
	 * @var    string $_path The path to the main plugin file, relative to the plugins folder.
	 */
	protected $_path = 'gravitywp-addon-name/addonname.php';

	/**
	 * Defines the full path to this class file.
	 *
	 * @since  1.0
	 * @var    string $_full_path The full path.
	 */
	protected $_full_path = GWP_ADDON_NAME_FILE;

	/**
	 * Defines the URL where this add-on can be found.
	 *
	 * @since  1.0
	 * @var    string The URL of the Add-On.
	 */
	protected $_url = 'https://gravitywp.com';

	/**
	 * Defines the title of this add-on.
	 *
	 * @since  1.0
	 * @var    string $_title The title of the add-on.
	 */
	protected $_title = 'GravityWP Addon Name Add-On';

	/**
	 * Defines the short title of the add-on.
	 *
	 * @since  1.0
	 * @var    string $_short_title The short title.
	 */
	protected $_short_title = 'Addonname';

	/**
	 * Defines if Add-On should use Gravity Forms servers for update data.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    bool
	 */
	protected $_enable_rg_autoupgrade = false;

	/**
	 * Defines the capabilities needed for the GravityWP Addon Name Add-On
	 *
	 * @since  1.0
	 * @access protected
	 * @var    array $_capabilities The capabilities needed for the Add-On
	 */
	protected $_capabilities = array( 'gravityforms_gravitywpaddonname', 'gravityforms_gravitywpaddonname_uninstall' );

	/**
	 * Defines the capability needed to access the Add-On settings page.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_settings_page The capability needed to access the Add-On settings page.
	 */
	protected $_capabilities_settings_page = 'gravityforms_gravitywpaddonname';

	/**
	 * Defines the capability needed to access the Add-On form settings page.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_form_settings The capability needed to access the Add-On form settings page.
	 */
	protected $_capabilities_form_settings = 'gravityforms_gravitywpaddonname';

	/**
	 * Defines the capability needed to uninstall the Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_uninstall The capability needed to uninstall the Add-On.
	 */
	protected $_capabilities_uninstall = 'gravityforms_gravitywpaddonname_uninstall';

	/**
	 * Holds the Number Field Settings instance.
	 *
	 * @since 1.0
	 * @var   Settings The Number Field Settings instance
	 */
	protected $_field_settings;

	/**
	 * Store the initialized gwp license handler
	 *
	 * @since  1.0
	 * @access private
	 * @var    Object $_gwp_license_handler License Handler instance.
	 */
	private $_gwp_license_handler = null;

	/**
	 * Defines the URL where this add-on can be found.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string The URL of the Add-On.
	 */
	public $gwp_site_slug = 'addon-name';

	/**
	 * Returns an instance of this class, and stores it in the $_instance property.
	 *
	 * @since  1.0
	 *
	 * @return Addon_Name $_instance An instance of the Addon_Name class
	 */
	public static function get_instance() {

		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	/**
	 * Register initialization hooks.
	 *
	 * @since  1.0
	 */
	public function init() {

		parent::init();

		if ( ! $this->is_gravityforms_supported() ) {
			return;
		}
		// add filters here:
		// add_filter( 'gform_field_content', array( $this, 'filter_apply_text_field' ), 10, 5 );
	}

	/**
	 * Register admin initialization hooks.
	 *
	 * @since  1.0
	 */
	public function init_admin() {

			// Init license handler.
		if ( ! $this->_gwp_license_handler ) {
			$this->_gwp_license_handler = new GravityWP\LicenseHandler\LicenseHandler( __CLASS__, 'APPSERO_UNIQUE_PLUGIN_ID', plugin_dir_path( __DIR__ ) . 'addonname.php' );
		}

		parent::init_admin();

		if ( ! $this->is_gravityforms_supported() ) {
			return;
		}

		$this->_field_settings = Settings::get_instance();
		$this->_field_settings->admin_hooks();

	}

	/**
	 * Register frontend initialization hooks.
	 *
	 * @since  1.0
	 */
	public function init_frontend() {

		parent::init_frontend();

		if ( ! $this->is_gravityforms_supported() ) {
			return;
		}

		$this->_field_settings = Settings::get_instance();
		$this->_field_settings->frontend_hooks();

	}

	/**
	 * Register scripts.
	 *
	 * @since  1.0
	 *
	 * @return array
	 */
	public function scripts() {

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

		$scripts = array(
			array(
				'handle'  => $this->get_slug() . '_form_editor',
				'deps'    => array( 'jquery' ),
				'src'     => $this->get_base_url() . "/js/form_editor{$min}.js",
				'version' => $this->_version,
				'enqueue' => array(
					array( 'admin_page' => array( 'form_editor' ) ),
				),
			),
			array(
				'handle'  => $this->get_slug() . '_scripts',
				'deps'    => array( 'jquery' ),
				'src'     => $this->get_base_url() . "/js/scripts{$min}.js",
				'version' => $this->_version,
				'enqueue' => array(
					array( $this, 'frontend_script_callback' ),
				),
			),
		);

		return array_merge( parent::scripts(), $scripts );

	}

	/**
	 * Register styles.
	 *
	 * @since  1.0
	 *
	 * @return array
	 */
	public function styles() {

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

		$styles = array(
			array(
				'handle'  => $this->get_slug(),
				'src'     => $this->get_base_url() . "/css/style{$min}.css",
				'version' => $this->_version,
				'enqueue' => array(
					array( $this, 'frontend_styles_callback' ),
				),
			),
		);

		return array_merge( parent::styles(), $styles );

	}

	/**
	 * A temp fix to force the $_path value.
	 *
	 * Somehow our $_path value was loaded as "includes/addonname.php" without respecting our property value when accessing $this->_path.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function get_path() {
		return 'gravitywp-addon-name/addonname.php';
	}

	/**
	 * Returns the physical path of the plugins root folder.
	 *
	 * @since 1.0
	 *
	 * @param string $full_path The full path.
	 *
	 * @return string
	 */
	public function get_base_path( $full_path = '' ) {

		if ( empty( $full_path ) ) {
			// Change this from __FILE__ to __DIR__ because the main class is in the includes folder.
			$full_path = __DIR__;
		}

		$folder = basename( dirname( $full_path ) );

		return WP_PLUGIN_DIR . '/' . $folder;
		parent::get_base_path()
	}

	/**
	 * Returns the url of the root folder of the current Add-On.
	 *
	 * @since 1.0
	 *
	 * @param string $full_path Optional. The full path to the plugin file.
	 *
	 * @return string
	 */
	public function get_base_url( $full_path = '' ) {

		if ( empty( $full_path ) ) {
			// Change this from $_full_path to __DIR__ because the main class is in the includes folder.
			$full_path = __DIR__;
		}

		return plugins_url( null, $full_path );

	}

	// # PLUGIN SETTINGS -----------------------------------------------------------------------------------------------
	/**
	 * Define plugin settings fields.
	 *
	 * @since  1.0.2
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {
		// Retrieve license fields.
		$license_fields = $this->_gwp_license_handler->plugin_settings_license_fields();
		$fields         = array( $license_fields );
		return $fields;
	}

	// # FILTER FIELD VALUES -----------------------------------------------------------------------------------------------


	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * The frontend styles callback.
	 *
	 * @since 1.0
	 *
	 * @param array $form The form object.
	 *
	 * @return bool
	 */
	public function frontend_styles_callback( $form ) {
		/*
		$fields = GFAPI::get_fields_by_type( $form, array( 'number' ) );

		foreach ( $fields as $field ) {

			if ( $this->is_addon_name_settings_enabled( $field ) ) {
				return true;
			}
		}
		*/

		return false;

	}

	/**
	 * Check if the addonname settings enabled.
	 *
	 * @since 1.0
	 *
	 * @param array $form The Form object.
	 *
	 * @return bool
	 */
	private function is_addon_name_settings_enabled( $form ) {
		return false;
	}

	/**
	 * Check if the fronted scripts should be enqueued.
	 *
	 * @since  1.0
	 *
	 * @param array $form The form currently being processed.
	 *
	 * @return bool If the script should be enqueued.
	 */
	public function frontend_script_callback( $form ) {

		return $form && $this->is_addon_name_settings_enabled( $form );

	}

	/**
	 * Get the custom field settings value.
	 *
	 * @since 1.0
	 *
	 * @param GF_Field $field The field.
	 * @param string   $group The settings group.
	 * @param string   $key   The settings key.
	 *
	 * @return float|int|string
	 */
	private function get_field_setting( $field, $group, $key ) {

		$value = rgars( $field->gwp, "$group/$key" );

		switch ( $key ) {

			case 'enabled':
				return boolval( $value );

			default:
				// Always escape the output.
				return esc_html( $value );

		}

	}

}
