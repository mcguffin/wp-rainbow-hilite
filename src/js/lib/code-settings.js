import $ from 'jquery';

const { wp } = window;
const { View } = wp.media;

let _prev_term = '';

const CodeSettingsView = View.extend({
	template: wp.template('wprainbow-code-settings'),
	className: 'wprainbow-code-settings',
	events: {
		'keydown [type="search"]': function(e) {
			if (27 === e.which) { // ESC
				e.stopPropagation();
				e.preventDefault();
				this.reset();
			}
		}
	},
	reset:function() {
		return this;
	},
	/**
	 *	@return icon class name
	 */
	value:function( val ) {
		if ( 'undefined' === typeof val) {
			return this._value;
//			return this.$('[type="radio"]:checked').val();
		}
		this._value = val;
		if ( this.$el ) {
			this.$('[type="radio"][value="'+val+'"]').prop( 'checked', true );
		}
	},
	getSettings:function() {
		return {
			language: 'php',
			line_number: false
		};
	}
});

module.exports = CodeSettingsView;
