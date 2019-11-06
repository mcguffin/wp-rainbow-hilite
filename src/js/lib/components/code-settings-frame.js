import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import FASelectModal from 'code-settings-modal';

const { wp } = window;


class CodeSettingsFrame extends Component {
	constructor( {
		value = false,
		title = __( 'Code Settings', 'wp-rainbow-hilite' ),
	} ) {

		super( ...arguments );

		this.onOkay = this.onOkay.bind( this );

		this.modal = new FASelectModal( { value } );

		this.modal.render();

		this.modal.open = this.modal.open.bind( this.modal )
		this.modal.close = this.modal.close.bind( this.modal )

		this.initializeListeners();

	}

	render() {
		return this.props.render( { open: this.modal.open } );
	}

	initializeListeners() {
		this.modal.on( 'okay', this.onOkay )
		this.modal.on( 'close', this.props.onClose )
	}

	componentWillUnmount() {
		this.modal.remove();
	}

	onOkay() {
		const { onOkay } = this.props;
		onOkay( this.modal.getSelection() );
	}

}


export default CodeSettingsFrame;
