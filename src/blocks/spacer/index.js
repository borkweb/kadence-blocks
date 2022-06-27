import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import edit from './edit';
import metadata from './block.json';
import save from './save';
import transforms from './transforms';
import deprecated from './deprecated';
const { name } = metadata;
import { spacerIcon } from '@kadence/icons';
import { __ } from '@wordpress/i18n';

export { metadata, name };

export const settings = {

};

registerBlockType('kadence/spacer', {
	getEditWrapperProps( { blockAlignment } ) {
		if ( 'full' === blockAlignment || 'wide' === blockAlignment || 'center' === blockAlignment ) {
			return { 'data-align': blockAlignment };
		}
	},
	...metadata,
	title: __( 'Spacer/Divider', 'kadence-blocks' ),
	keywords: [
		__( 'spacer', 'kadence-blocks' ),
		__( 'divider', 'kadence-blocks' ),
		__( 'separator', 'kadence-blocks' ),
		'KB',
	],
	icon: {
		src: spacerIcon,
	},
	transforms,
	deprecated,
	edit,
	save
});