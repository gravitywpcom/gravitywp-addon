( function( $ ) {
	window.GWPAddonname = function() {
		// Cannot use `self = this`; because `this` would be referred to the `window`.
		const self = GWPAddonname;

		self.getDefaults = function() {
			return {
			};
		};

		self.init = function() {
			self.hooks();
		};

		self.hooks = function() {
			gform.addAction( 'gform_post_load_field_settings', function( arr ) {
				const field = arr[ 0 ];

				if ( field.type === 'FIELDTYPE' ) {
					self.initAllFieldProperty();

					self.eventHandler();
				}
			} );
		};

		self.initAllFieldProperty = function() {
			if ( ! field.hasOwnProperty( 'gwp' ) || field.gwp === '' ) {
				field.gwp = self.getDefaults();
			}
		};

		self.init();
	};

	$( document ).ready( GWPAddonname );
}( jQuery ) );
