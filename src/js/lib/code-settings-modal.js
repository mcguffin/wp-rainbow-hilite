import $ from 'jquery';
import CodeSettings from 'code-settings';

const { wp } = window;
const { Modal } = wp.media.view;




const FASelectModal = Modal.extend({
	className: 'fa-select-modal-container',
	events: {
		'click [data-action="okay"]': function(e) {
			this.propagate('okay');
		},
		'click [data-action="cancel"]': function(e) {
			this.close();
		}
	},
	initialize: function(  ) {
		this.controller = { trigger: e => {} } // fake controller
		this._allowNull = false;
		let ret = Modal.prototype.initialize.apply(this,arguments);
		this.settings_view = new CodeSettings();
		this.settings_view.render();
		this.content( this.settings_view );
		return ret;
	},
	getSettings: function() {
		this.settings_view.getSettings();
	}
});

module.exports = FASelectModal;
