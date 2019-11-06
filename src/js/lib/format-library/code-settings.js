/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Component } from '@wordpress/element';
import { create, toHTMLString, insert, insertObject } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import CodeSettingsFrame from 'components/code-settings-frame';

const format_name = 'wprainbow/code-settings';
const format_title = __( 'Code Settings', 'pp-fontawesome' );

// @see https://github.com/WordPress/gutenberg/issues/18000

export const codeSettings = {
	name:format_name,
	title:format_title,
	keywords: [ __( 'code', 'pp-fontawesome' ), __( 'syntax', 'pp-fontawesome' ) ],
	object: false,
	tagName: 'code:wprainbow', // must use some fake tag here
	className: null,
	attributes: {
		className: 'class'
	},
	onMouseDown: e => e.stopImmediatePropagation(),
	edit: class PPFontawesome extends Component {
		constructor() {
			super( ...arguments );
			this.openModal = this.openModal.bind( this );
			this.closeModal = this.closeModal.bind( this );
			this.state = {
				modal: false,
			};
		}

		openModal() {
			this.setState( { modal: true } );
		}

		closeModal() {
			this.setState( { modal: false } );
		}

		render() {
			const { value, onChange, activeObjectAttributes } = this.props;

			return (
				<div>
					<RichTextToolbarButton
						icon='code'
						title={ format_title }
						onClick={ this.openModal }
					/>
					{ this.state.modal && <CodeSettingsFrame
						onOkay={ ( { className, set, name, label } ) => {
							this.closeModal();
							/*
							let ins = {
								type: format_name,
								attributes: {
									className: className,
								}
							};
							let inserted = insertObject( value, insert );
							/*/
							let ins = create( { html: `<span class="pp-icon ${className}"></span>` } );
							let inserted = insert( value, ins );
							//*/
							onChange( inserted );
						} }
						onClose={ this.closeModal }
						render={ ( { open } ) => {
							open();
							return null;
						} }
					/> }
				</div>
			);
		}
	},
};
