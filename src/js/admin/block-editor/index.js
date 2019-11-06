import { registerFormatType } from '@wordpress/rich-text';
import { codeSettings } from 'format-library/code-settings';

wp.richText.registerFormatType( 'wprainbow/code-settings', codeSettings );
