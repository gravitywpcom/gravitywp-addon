/* eslint-disable linebreak-style */
import '../css/style.scss';

window.GWPAddonname = null;

const GWPAddonnames = [];

( function() {
	window.GWPAddonname = function( fields ) {
		const self = GWPAddonname;

		self.bindEvents = function() {
			//filters here
			/*gform.addFilter( 'gform_calculation_result', function( result, formulaField, formId ) {
				return self.filterCalculationValue( result, formId + '_' + formulaField.field_id );
			} );*/

		};
		self.getGWPAddonnameSettings = function() {
			return GWPAddonnames;
		};

		self.init = function( ) {
			// Copy all field settings into a global variable to support multiple forms.
			for ( const prop in fields ) {
				if ( fields.hasOwnProperty( prop ) ) {
					// Push each value from `obj` into `extended`
					GWPAddonnames[ prop ] = fields[ prop ];
				}
			}

			// Add event listeners.
			for ( const fieldId in fields ) {
				const input = document.getElementById( 'input_' + fieldId );
				const props = GWPAddonnames[ fieldId ];
				//add event listeners here
				//input.addEventListener( 'change', self.updateValue );
			}

			// Bind Events.
			self.bindEvents();
		};

		self.init( fields );
	};
}() );
