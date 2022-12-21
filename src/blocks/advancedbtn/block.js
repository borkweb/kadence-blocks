/**
 * BLOCK: Kadence Advanced Btn
 */
/**
 * External dependencies
 */
import classnames from 'classnames';
import { times } from 'lodash';

/**
 * Internal libraries
 */
import { IconRender, IconSpanTag } from '@kadence/components';
/**
 * Import Icons
 */
import { advancedBtnIcon } from '@kadence/icons';

/**
 * Import Css
 */
 import './style.scss';

import edit from './edit';
import deprecated from './deprecated';
import metadata from './block.json';
/**
 * Internal block libraries
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	useBlockProps
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

/**
 * Register: a Gutenberg Block.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'kadence/advancedbtn', {
	...metadata,
	title: __( 'Advanced Button', 'kadence-blocks' ),
	description: __( 'Create an advanced button or a row of buttons. Style each one, including hover controls!', 'kadence-blocks' ),
	keywords: [
		__( 'button', 'kadence-blocks' ),
		__( 'icon', 'kadence-blocks' ),
		'KB',
	],
	icon: {
		src: advancedBtnIcon,
	},
	edit,
	save: props => {
		const { attributes: { btnCount, btns, hAlign, uniqueID, letterSpacing, forceFullwidth, thAlign, mhAlign, collapseFullwidth } } = props;
		const renderSaveBtns = ( index ) => {
			let relAttr;
			if ( '_blank' === btns[ index ].target ) {
				relAttr = 'noreferrer noopener';
			}
			if ( true === btns[ index ].noFollow ) {
				relAttr = ( relAttr ? relAttr.concat( ' nofollow' ) : 'nofollow' );
			}
			if ( undefined !== btns[ index ].sponsored && true === btns[ index ].sponsored ) {
				relAttr = ( relAttr ? relAttr.concat( ' sponsored' ) : 'sponsored' );
			}
			let btnSize;
			if ( undefined !== btns[ index ].paddingLR || undefined !== btns[ index ].paddingBT ) {
				btnSize = 'custom';
			} else {
				btnSize = 'standard';
			}
			let globalStyles;
			if ( undefined !== btns[ index ].inheritStyles && '' !== btns[ index ].inheritStyles ) {
				globalStyles = 'kb-btn-global-' + btns[ index ].inheritStyles;
			} else {
				globalStyles = '';
			}
			let themeStyles;
			if ( undefined !== btns[ index ].inheritStyles && 'inherit' === btns[ index ].inheritStyles ) {
				themeStyles = 'wp-block-button__link';
			} else {
				themeStyles = '';
			}
			const btnClasses = classnames( {
				'kt-button': true,
				'button': true,
				[ `kt-btn-${ index }-action` ]: true,
				[ `kt-btn-size-${ ( btns[ index ].btnSize ? btns[ index ].btnSize : btnSize ) }` ]: true,
				[ `kt-btn-style-${ ( btns[ index ].btnStyle ? btns[ index ].btnStyle : 'basic' ) }` ]: true,
				[ `kt-btn-svg-show-${ ( ! btns[ index ].iconHover ? 'always' : 'hover' ) }` ]: true,
				[ `kt-btn-has-text-${ ( ! btns[ index ].text ? 'false' : 'true' ) }` ] : true,
				[ `kt-btn-has-svg-${ ( ! btns[ index ].icon ? 'false' : 'true' ) }` ]: true,
				'ktblocksvideopop': 'video' === btns[ index ].target,
				[ btns[ index ].cssClass ]: btns[ index ].cssClass,
				[ globalStyles ]: globalStyles,
				[ themeStyles ]: themeStyles,
				[ `kb-btn-only-icon` ]: ( btns[ index ].icon && btns[ index ].onlyIcon && btns[ index ].onlyIcon[0] ),
				[ `kb-btn-tablet-only-icon` ]:( btns[ index ].icon && btns[ index ].onlyIcon && btns[ index ].onlyIcon[1] ),
				[ `kb-btn-mobile-only-icon` ]: ( btns[ index ].icon && btns[ index ].onlyIcon && btns[ index ].onlyIcon[2] ),
			} );
			return (
				<div className={ `kt-btn-wrap kt-btn-wrap-${ index }` }>
					<a id={ btns[ index ].anchor ? btns[ index ].anchor : undefined } className={ btnClasses } aria-label={ btns[ index ].label ? btns[ index ].label : undefined } download={ ( undefined !== btns[ index ].download && true === btns[ index ].download ? '' : undefined ) } href={ ( ! btns[ index ].link ? '#' : btns[ index ].link ) } target={ ( '_blank' === btns[ index ].target ? btns[ index ].target : undefined ) } rel={ relAttr } style={ {
						borderRadius: ( undefined !== btns[ index ].borderRadius && '' !== btns[ index ].borderRadius ? btns[ index ].borderRadius + 'px' : undefined ),
						borderWidth: ( undefined !== btns[ index ].borderWidth && '' !== btns[ index ].borderWidth ? btns[ index ].borderWidth + 'px' : undefined ),
						letterSpacing: ( undefined !== letterSpacing && '' !== letterSpacing ? letterSpacing + 'px' : undefined ),
					} } >
						{ btns[ index ].icon && 'left' === btns[ index ].iconSide && (
							<IconSpanTag extraClass={ `kt-btn-side-${ btns[ index ].iconSide }` } name={ btns[ index ].icon }/>
						) }
						<RichText.Content
							tagName={ 'span' }
							className="kt-btn-inner-text"
							value={ btns[ index ].text }
						/>
						{ btns[ index ].icon && 'left' !== btns[ index ].iconSide && (
							<IconSpanTag extraClass={ `kt-btn-side-${ btns[ index ].iconSide }` } name={ btns[ index ].icon }/>
						) }
					</a>
				</div>
			);
		};

		const blockProps = useBlockProps.save( {
			className: `kt-btn-align-${ hAlign } kt-btn-tablet-align-${ ( thAlign ? thAlign : 'inherit' ) } kt-btn-mobile-align-${ ( mhAlign ? mhAlign : 'inherit' ) } kt-btns-wrap kt-btns${ uniqueID }${ ( forceFullwidth ? ' kt-force-btn-fullwidth' : '' ) }${ ( collapseFullwidth ? ' kt-mobile-collapse-btn-fullwidth' : '' ) }`,
		} );

		return (
			<div {...blockProps}>
				{ times( btnCount, n => renderSaveBtns( n ) ) }
			</div>
		);
	},
	deprecated,
} );
