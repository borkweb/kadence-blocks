/**
 * BLOCK: Kadence Form Block
 *
 * Registering a basic block with Gutenberg.
 */

import classnames from 'classnames';

/**
 * Import Icons
 */
import { formBlockIcon } from '@kadence/icons';
import { times } from 'lodash';
/**
 * Import edit
 */
import edit from './edit';
import deprecated from './deprecated';

/**
 * Import Css
 */
 import './style.scss';
import { Fragment } from '@wordpress/element';
import { RichText, useBlockProps } from '@wordpress/block-editor';
/**
 * Internal block libraries
 */
import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';
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
registerBlockType( 'kadence/form', {
	...metadata,
	title: __( 'Form', 'kadence-blocks' ),
	description: __( 'Create a contact or marketing form for your website.', 'kadence-blocks' ),
	keywords: [
		__( 'contact', 'kadence-blocks' ),
		__( 'marketing', 'kadence-blocks' ),
		'KB',
	],
	icon: {
		src: formBlockIcon,
	},
	edit,
	save: props => {
		const { attributes: { uniqueID, fields, submit, style, postID, hAlign, recaptcha, recaptchaVersion, honeyPot, messages, submitLabel } } = props;
		const fieldOutput = ( index ) => {
			if ( 'hidden' === fields[ index ].type ) {
				return (
					<input type="hidden" name={ `kb_field_${ index }` } value={ fields[ index ].default } />
				);
			}
			const fieldClassName = classnames( {
				'kadence-blocks-form-field': true,
				[ `kb-form-field-${ index }` ]: index,
				[ `kb-field-desk-width-${ fields[ index ].width[ 0 ] }` ]: fields[ index ].width && fields[ index ].width[ 0 ],
				[ `kb-field-tablet-width-${ fields[ index ].width[ 1 ] }` ]: fields[ index ].width && fields[ index ].width[ 1 ],
				[ `kb-field-mobile-width-${ fields[ index ].width[ 2 ] }` ]: fields[ index ].width && fields[ index ].width[ 2 ],
				[ `kb-input-size-${ style[ 0 ].size }` ]: style[ 0 ].size,
				'kb-accept-form-field': 'accept' === fields[ index ].type,
			} );
			let acceptLabel;
			let acceptLabelBefore;
			let acceptLabelAfter;
			if ( fields[ index ].label && fields[ index ].label.includes( '{privacy_policy}' ) ) {
				acceptLabelBefore = fields[ index ].label.split( '{' )[ 0 ];
				acceptLabelAfter = fields[ index ].label.split( '}' )[ 1 ];
				acceptLabel = (
					<Fragment>
						{ acceptLabelBefore }<a href={ ( undefined !== kadence_blocks_params.privacy_link && '' !== kadence_blocks_params.privacy_link ? kadence_blocks_params.privacy_link : '#' ) } target="blank" rel="noopener noreferrer">{ ( undefined !== kadence_blocks_params.privacy_title && '' !== kadence_blocks_params.privacy_title ? kadence_blocks_params.privacy_title : 'Privacy policy' ) }</a>{ acceptLabelAfter }
					</Fragment>
				);
			} else {
				acceptLabel = fields[ index ].label;
			}
			return (
				<div className={ fieldClassName } >
					{ 'accept' === fields[ index ].type && (
						<Fragment>
							{ fields[ index ].showLink && (
								<a href={ ( undefined !== fields[ index ].default && '' !== fields[ index ].default ? fields[ index ].default : '#' ) } target="_blank" rel="noopener noreferrer" className={ 'kb-accept-link' }>{ ( undefined !== fields[ index ].placeholder && '' !== fields[ index ].placeholder ? fields[ index ].placeholder : 'View Privacy Policy' ) }</a>
							) }
							{ undefined !== fields[ index ].ariaLabel && fields[ index ].ariaLabel && (
								<span id={ `kb_field_desc_${ uniqueID }_${ index }` } className="screen-reader-text kb-field-desc-label">{ fields[ index ].ariaLabel }</span>
							) }
							<input type="checkbox" name={ `kb_field_${ index }` } id={ `kb_field_${ uniqueID }_${ index }` } className={ `kb-field kb-checkbox-style kb-${ fields[ index ].type }` } value={ 'accept' } checked={ fields[ index ].inline ? true : false } data-type={ fields[ index ].type } data-required={ ( fields[ index ].required ? 'yes' : undefined ) } data-required-message={ fields[ index ].requiredMessage ? fields[ index ].requiredMessage : undefined } data-validation-message={ fields[ index ].errorMessage ? fields[ index ].errorMessage : undefined } aria-describedby={ undefined !== fields[ index ].ariaLabel && fields[ index ].ariaLabel ? `kb_field_desc_${ uniqueID }_${ index }` : undefined } />
							<label htmlFor={ `kb_field_${ uniqueID }_${ index }` }>{ ( fields[ index ].label ? acceptLabel : '' ) }{ ( fields[ index ].required && style[ 0 ].showRequired ? <span className="required">*</span> : '' ) }</label>
						</Fragment>
					) }
					{ 'accept' !== fields[ index ].type && (
						<Fragment>
							{ fields[ index ].showLabel && (
								<label htmlFor={ `kb_field_${ uniqueID }_${ index }` }>{ ( fields[ index ].label ? fields[ index ].label : '' ) }{ ( fields[ index ].required && style[ 0 ].showRequired ? <span className="required">*</span> : '' ) }</label>
							) }
							{ undefined !== fields[ index ].ariaLabel && fields[ index ].ariaLabel && (
								<span id={ `kb_field_desc_${ uniqueID }_${ index }` } className="screen-reader-text">{ fields[ index ].ariaLabel }</span>
							) }
							{ 'textarea' === fields[ index ].type && (
								<textarea name={ `kb_field_${ index }` } id={ `kb_field_${ uniqueID }_${ index }` } data-label={ fields[ index ].label } type={ fields[ index ].type } placeholder={ fields[ index ].placeholder } value={ fields[ index ].default } data-type={ fields[ index ].type } className={ `kb-field kb-text-style-field kb-${ fields[ index ].type }-field kb-field-${ index }` } rows={ fields[ index ].rows } data-required={ ( fields[ index ].required ? 'yes' : undefined ) } data-required-message={ fields[ index ].requiredMessage ? fields[ index ].requiredMessage : undefined } data-validation-message={ fields[ index ].errorMessage ? fields[ index ].errorMessage : undefined } aria-describedby={ undefined !== fields[ index ].ariaLabel && fields[ index ].ariaLabel ? `kb_field_desc_${ uniqueID }_${ index }` : undefined } />
							) }
							{ 'select' === fields[ index ].type && (
								<select name={ ( fields[ index ].multiSelect ? `kb_field_${ index }[]` : `kb_field_${ index }` ) } id={ `kb_field_${ uniqueID }_${ index }` } multiple={ ( fields[ index ].multiSelect ? true : false ) } data-label={ fields[ index ].label } type={ fields[ index ].type } value={ fields[ index ].default } data-type={ fields[ index ].type } className={ `kb-field kb-select-style-field kb-${ fields[ index ].type }-field kb-field-${ index }` } data-required={ ( fields[ index ].required ? 'yes' : undefined ) } data-required-message={ fields[ index ].requiredMessage ? fields[ index ].requiredMessage : undefined } data-validation-message={ fields[ index ].errorMessage ? fields[ index ].errorMessage : undefined } aria-describedby={ undefined !== fields[ index ].ariaLabel && fields[ index ].ariaLabel ? `kb_field_desc_${ uniqueID }_${ index }` : undefined } >
									{ undefined !== fields[ index ].placeholder && '' !== fields[ index ].placeholder && (
										<option
											value=""
											disabled={ true }
											selected={ '' === fields[ index ].default ? true : false }
										>
											{ fields[ index ].placeholder }
										</option>
									) }
									{ times( fields[ index ].options.length, n => (
										<option
											key={ n }
											selected={ ( undefined !== fields[ index ].options[ n ].value && fields[ index ].options[ n ].value === fields[ index ].default ? true : false ) }
											value={ ( undefined !== fields[ index ].options[ n ].value ? fields[ index ].options[ n ].value : '' ) }
										>{ ( undefined !== fields[ index ].options[ n ].label ? fields[ index ].options[ n ].label : '' ) }</option>
									) ) }
								</select>
							) }
							{ 'checkbox' === fields[ index ].type && (
								<div data-type={ fields[ index ].type } data-label={ fields[ index ].label } id={ `kb_field_${ uniqueID }_${ index }` } className={ `kb-field kb-checkbox-style-field kb-${ fields[ index ].type }-field kb-field-${ index } kb-radio-style-${ fields[ index ].inline ? 'inline' : 'normal' }` } data-required={ ( fields[ index ].required ? 'yes' : undefined ) } data-required-message={ fields[ index ].requiredMessage ? fields[ index ].requiredMessage : undefined } data-validation-message={ fields[ index ].errorMessage ? fields[ index ].errorMessage : undefined }>
									{ times( fields[ index ].options.length, n => (
										<div key={ n } data-type={ fields[ index ].type } className={ `kb-checkbox-item kb-checkbox-item-${ n }` }>
											<input type="checkbox" name={ `kb_field_${ index }[]` } id={ `kb_field_${ index }_${ n }` } className={ 'kb-sub-field kb-checkbox-style' } value={ ( undefined !== fields[ index ].options[ n ].value ? fields[ index ].options[ n ].value : '' ) } checked={ ( undefined !== fields[ index ].options[ n ].value && fields[ index ].options[ n ].value === fields[ index ].default ? true : false ) } />
											<label htmlFor={ `kb_field_${ index }_${ n }` }>{ ( undefined !== fields[ index ].options[ n ].label ? fields[ index ].options[ n ].label : '' ) }</label>
										</div>
									) ) }
								</div>
							) }
							{ 'radio' === fields[ index ].type && (
								<div data-type={ fields[ index ].type } data-label={ fields[ index ].label } id={ `kb_field_${ uniqueID }_${ index }` } className={ `kb-field kb-radio-style-field kb-${ fields[ index ].type }-field kb-field-${ index } kb-radio-style-${ fields[ index ].inline ? 'inline' : 'normal' }` } data-required={ ( fields[ index ].required ? 'yes' : undefined ) } data-required-message={ fields[ index ].requiredMessage ? fields[ index ].requiredMessage : undefined } data-validation-message={ fields[ index ].errorMessage ? fields[ index ].errorMessage : undefined }>
									{ times( fields[ index ].options.length, n => (
										<div key={ n } data-type={ fields[ index ].type } className={ `kb-radio-item kb-radio-item-${ n }` }>
											<input type="radio" name={ `kb_field_${ index }[]` } id={ `kb_field_${ index }_${ n }` } className={ 'kb-sub-field kb-radio-style' } value={ ( undefined !== fields[ index ].options[ n ].value ? fields[ index ].options[ n ].value : '' ) } checked={ ( undefined !== fields[ index ].options[ n ].value && fields[ index ].options[ n ].value === fields[ index ].default ? true : false ) } />
											<label htmlFor={ `kb_field_${ index }_${ n }` }>{ ( undefined !== fields[ index ].options[ n ].label ? fields[ index ].options[ n ].label : '' ) }</label>
										</div>
									) ) }
								</div>
							) }
							{ 'textarea' !== fields[ index ].type && 'select' !== fields[ index ].type && 'checkbox' !== fields[ index ].type && 'radio' !== fields[ index ].type && (
								<input name={ `kb_field_${ index }` } id={ `kb_field_${ uniqueID }_${ index }` } data-label={ fields[ index ].label } type={ fields[ index ].type } placeholder={ fields[ index ].placeholder } value={ fields[ index ].default } data-type={ fields[ index ].type } className={ `kb-field kb-text-style-field kb-${ fields[ index ].type }-field kb-field-${ index }` } autoComplete={ ( '' !== fields[ index ].auto ? fields[ index ].auto : undefined ) } data-required={ ( fields[ index ].required ? 'yes' : undefined ) } data-required-message={ fields[ index ].requiredMessage ? fields[ index ].requiredMessage : undefined } data-validation-message={ fields[ index ].errorMessage ? fields[ index ].errorMessage : undefined } aria-describedby={ undefined !== fields[ index ].ariaLabel && fields[ index ].ariaLabel ? `kb_field_desc_${ uniqueID }_${ index }` : undefined } />
							) }
						</Fragment>
					) }
					{ undefined !== fields[ index ].description && '' !== fields[ index ].description && (
						<span className={ 'kb-field-help' }>{ ( fields[ index ].description ? fields[ index ].description : '' ) }</span>
					) }
				</div>
			);
		};
		const renderFieldOutput = (
			<Fragment>
				{ times( fields.length, n => fieldOutput( n ) ) }
			</Fragment>
		);
		const submitClassName = classnames( {
			'kadence-blocks-form-field': true,
			'kb-submit-field': true,
			[ `kb-field-desk-width-${ submit[ 0 ].width[ 0 ] }` ]: submit[ 0 ].width && submit[ 0 ].width[ 0 ],
			[ `kb-field-tablet-width-${ submit[ 0 ].width[ 1 ] }` ]: submit[ 0 ].width && submit[ 0 ].width[ 1 ],
			[ `kb-field-mobile-width-${ submit[ 0 ].width[ 2 ] }` ]: submit[ 0 ].width && submit[ 0 ].width[ 2 ],
		} );

		const blockProps = useBlockProps.save( {
			className: `kadence-form-${ uniqueID } kb-form-wrap${ ( hAlign ? ' kb-form-align-' + hAlign : '' ) }`
		} );

		return (
			<div {...blockProps}>
				<form className="kb-form" action="" method="post" data-error-message={ messages && messages[0] && messages[0].preError ? messages[0].preError : undefined }>
					{ renderFieldOutput }
					<input type="hidden" name="_kb_form_id" value={ uniqueID } />
					<input type="hidden" name="_kb_form_post_id" value={ postID } />
					<input type="hidden" name="action" value="kb_process_ajax_submit" />
					{ recaptcha && (
						<Fragment>
							{ recaptchaVersion === 'v2' && (
								<div className="kadence-blocks-form-field google-recaptcha-checkout-wrap">
									<p id="kb-container-g-recaptcha" className="google-recaptcha-container">
										<span id={ `kb_recaptcha_${ uniqueID }` } className={ `kadence-blocks-g-recaptcha-v2 g-recaptcha kb_recaptcha_${ uniqueID }` } style={ {
											display: 'inline-block',
										} }>
										</span>
									</p>
								</div>
							) }
							{ recaptchaVersion !== 'v2' && (
								<input type="hidden" name="recaptcha_response" className={ `kb_recaptcha_response kb_recaptcha_${ uniqueID }` } />
							) }
						</Fragment>
					) }
					{ honeyPot && (
						<input className="kadence-blocks-field verify" type="text" name="_kb_verify_email" autoComplete="off" aria-hidden="true" placeholder="Email" tabIndex="-1" />
					) }
					<div className={ submitClassName }>
						{ submitLabel && (
							<span id={ `kb_submit_label_${ uniqueID }` } className="screen-reader-text kb-submit-desc-label">{ submitLabel }</span>
						) }
						<RichText.Content
							tagName="button"
							aria-describedby={ submitLabel ? `kb_submit_label_${ uniqueID }` : undefined }
							value={ ( '' !== submit[ 0 ].label ? submit[ 0 ].label : 'Submit' ) }
							className={ `kb-forms-submit button kb-button-size-${ ( submit[ 0 ].size ? submit[ 0 ].size : 'standard' ) } kb-button-width-${ ( submit[ 0 ].widthType ? submit[ 0 ].widthType : 'auto' ) }` }
						/>
					</div>
				</form>
			</div>
		);
	},
	deprecated
} );
