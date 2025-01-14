<?php
/**
 * Class to Build the Tabs Block.
 *
 * @package Kadence Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to Build the Tabs Block.
 *
 * @category class
 */
class Kadence_Blocks_Tabs_Block extends Kadence_Blocks_Abstract_Block {

	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Block name within this namespace.
	 *
	 * @var string
	 */
	protected $block_name = 'tabs';

	/**
	 * Block determines in scripts need to be loaded for block.
	 *
	 * @var string
	 */
	protected $has_script = true;

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Builds CSS for block.
	 *
	 * @param array $attributes the blocks attributes.
	 * @param Kadence_Blocks_CSS $css the css class for blocks.
	 * @param string $unique_id the blocks attr ID.
	 */
	public function build_css( $attributes, $css, $unique_id ) {

		$css->set_style_id( 'kb-' . $this->block_name . $unique_id );

		$layout = isset( $attributes['layout'] ) ? $attributes['layout'] : 'tabs';

		// Main font
		if ( isset( $attributes['googleFont'] ) && $attributes['googleFont'] && ( ! isset( $attributes['loadGoogleFont'] ) || true == $attributes['loadGoogleFont'] ) && isset( $attributes['typography'] ) ) {

			$font_variant = isset( $attributes['fontVariant'] ) ? $attributes['fontVariant'] : null;
			$font_subset  = isset( $attributes['fontSubset'] ) ? $attributes['fontSubset'] : null;

			$css->maybe_add_google_font( $attributes['typography'], $font_variant, $font_subset );
		}

		// Subtitle font
		if ( isset( $attributes['subtitleFont'] ) && is_array( $attributes['subtitleFont'] ) && isset( $attributes['subtitleFont'][0] ) && is_array( $attributes['subtitleFont'][0] ) && isset( $attributes['subtitleFont'][0]['google'] ) && $attributes['subtitleFont'][0]['google'] && ( ! isset( $attributes['subtitleFont'][0]['loadGoogle'] ) || true === $attributes['subtitleFont'][0]['loadGoogle'] ) && isset( $attributes['subtitleFont'][0]['family'] ) ) {
			$subtitle_font = $attributes['subtitleFont'][0];

			$font_variant = isset( $subtitle_font['variant'] ) ? $subtitle_font['variant'] : null;
			$font_subset  = isset( $subtitle_font['subset'] ) ? $subtitle_font['subset'] : null;

			$css->maybe_add_google_font( $subtitle_font['family'], $font_variant, $font_subset );
		}


		/*
		 * Container
		 */
		$css->set_selector( '.kt-tabs-id' . $unique_id . ' > .kt-tabs-content-wrap > .wp-block-kadence-tab' );

		$border_args = array( 'tablet_key' => false, 'mobile_key' => false );
		$css->render_measure_output( $attributes, 'contentBorder', 'border-width', $border_args );
		if ( ! empty( $attributes['contentBorderColor'] ) ) {
			$css->add_property( 'border-color', $css->sanitize_color( $attributes['contentBorderColor'] ) );
		}

		if ( isset( $attributes['contentBorderRadius'] ) && ! empty( $attributes['contentBorderRadius'] ) && is_array( $attributes['contentBorderRadius'] ) ) {
			$css->render_measure_output( $attributes, 'contentBorderRadius', 'border-radius', $border_args );
		}

		$css->render_measure_output( $attributes, 'innerPadding', 'padding' );

		if ( ! empty( $attributes['minHeight'] ) ) {
			$css->add_property( 'min-height', $attributes['minHeight'] );
		}

		if ( ! empty( $attributes['contentBgColor'] ) ) {
			$css->add_property( 'background', $css->sanitize_color( $attributes['contentBgColor'] ) );
		}


		/*
		 * Tablet wrapper padding L
		 */
		$widthType = isset( $attributes['widthType'] ) ? $attributes['widthType'] : 'normal';
		if ( ! empty( $attributes['titleMargin'] ) && is_array( $attributes['titleMargin'] ) ) {
			$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li' );
			$margin_args = array( 'tablet_key' => false, 'mobile_key' => false );
			$css->render_measure_output( $attributes, 'titleMargin', 'margin', $margin_args );
		}

		if ( 'vtabs' !== $layout && 'percent' === $widthType ) {
			if ( isset( $attributes['gutter'] ) && ! empty( $attributes['gutter'] ) && is_array( $attributes['gutter'] ) ) {
				$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title' );
				$css->add_property( 'margin-right', $attributes['gutter'][0] . 'px' );

				if ( is_rtl() ) {
					$css->set_selector( '.rtl .wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title' );
					$css->add_property( 'margin-right', '0px' );
					$css->add_property( 'margin-left', $attributes['gutter'][0] . 'px' );
				}
			}
			if ( isset( $attributes['tabWidth'] ) && ! empty( $attributes['tabWidth'] ) && is_array( $attributes['tabWidth'] ) && ! empty( $attributes['tabWidth'][1] ) && '' !== $attributes['tabWidth'][1] ) {
				$css->set_media_state( 'tablet' );
				$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list.kb-tabs-list-columns > li' );
				$css->add_property( 'flex', '0 1 ' . round( 100 / $attributes['tabWidth'][1], 2 ) . '%' );
				$css->set_media_state( 'desktop' );
			}
			if ( ! empty( $attributes['gutter'] ) && is_array( $attributes['gutter'] ) && isset( $attributes['gutter'][1] ) && is_numeric( $attributes['gutter'][1] ) ) {
				$css->set_media_state( 'tablet' );
				$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title' );
				$css->add_property( 'margin-right', $attributes['gutter'][1] . 'px' );


				if ( is_rtl() ) {
					$css->set_selector( '.rtl .wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title' );
					$css->add_property( 'margin-right', '0px' );
					$css->add_property( 'margin-left', $attributes['gutter'][1] . 'px' );
				}

				$css->set_media_state( 'desktop' );
			}
			if ( ! empty( $attributes['tabWidth'] ) && is_array( $attributes['tabWidth'] ) && ! empty( $attributes['tabWidth'][2] ) && '' !== $attributes['tabWidth'][2] ) {
				$css->set_media_state( 'mobile' );
				$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list.kb-tabs-list-columns > li' );
				$css->add_property( 'flex', '0 1 ' . round( 100 / $attributes['tabWidth'][2], 2 ) . '%' );
				$css->set_media_state( 'desktop' );
			}
			if ( ! empty( $attributes['gutter'] ) && is_array( $attributes['gutter'] ) && isset( $attributes['gutter'][2] ) && is_numeric( $attributes['gutter'][2] ) ) {
				$css->set_media_state( 'mobile' );
				$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title' );
				$css->add_property( 'margin-right', $attributes['gutter'][2] . 'px' );

				if ( is_rtl() ) {
					$css->set_selector( '.rtl .wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title' );
					$css->add_property( 'margin-right', '0px' );
					$css->add_property( 'margin-left', $attributes['gutter'][2] . 'px' );
				}
				$css->set_media_state( 'desktop' );
			}
		}

		$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title, .wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-content-wrap > .kt-tabs-accordion-title .kt-tab-title' );
		if ( isset( $attributes['size'] ) && ! empty( $attributes['size'] ) ) {
			$css->add_property( 'font-size', $attributes['size'] . ( ! isset( $attributes['sizeType'] ) ? 'px' : $attributes['sizeType'] ) );
		}
		if ( isset( $attributes['lineHeight'] ) && ! empty( $attributes['lineHeight'] ) ) {
			$css->add_property( 'line-height', $attributes['lineHeight'] . ( ! isset( $attributes['lineType'] ) ? 'px' : $attributes['lineType'] ) );
		}
		if ( ! empty( $attributes['letterSpacing'] ) ) {
			$css->add_property( 'letter-spacing', $attributes['letterSpacing'] . 'px' );
		}
		if ( isset( $attributes['typography'] ) && ! empty( $attributes['typography'] ) ) {
			$css->add_property( 'font-family', $attributes['typography'] );
		}
		if ( isset( $attributes['fontWeight'] ) && ! empty( $attributes['fontWeight'] ) ) {
			$css->add_property( 'font-weight', $attributes['fontWeight'] );
		}
		if ( isset( $attributes['fontStyle'] ) && ! empty( $attributes['fontStyle'] ) ) {
			$css->add_property( 'font-style', $attributes['fontStyle'] );
		}
		if ( isset( $attributes['textTransform'] ) && ! empty( $attributes['textTransform'] ) ) {
			$css->add_property( 'text-transform', $attributes['textTransform'] );
		}
		if ( isset( $attributes['titleBorderWidth'] ) && ! empty( $attributes['titleBorderWidth'] ) && is_array( $attributes['titleBorderWidth'] ) ) {
			$css->add_property( 'border-width', $attributes['titleBorderWidth'][0] . 'px ' . $attributes['titleBorderWidth'][1] . 'px ' . $attributes['titleBorderWidth'][2] . 'px ' . $attributes['titleBorderWidth'][3] . 'px' );
		}
		if ( isset( $attributes['titleBorderRadius'] ) && ! empty( $attributes['titleBorderRadius'] ) && is_array( $attributes['titleBorderRadius'] ) ) {
			$css->add_property( 'border-radius', $attributes['titleBorderRadius'][0] . 'px ' . $attributes['titleBorderRadius'][1] . 'px ' . $attributes['titleBorderRadius'][2] . 'px ' . $attributes['titleBorderRadius'][3] . 'px' );
		}
		if ( isset( $attributes['titlePadding'] ) && ! empty( $attributes['titlePadding'] ) && is_array( $attributes['titlePadding'] ) ) {
			$css->add_property( 'padding', $attributes['titlePadding'][0] . 'px ' . $attributes['titlePadding'][1] . 'px ' . $attributes['titlePadding'][2] . 'px ' . $attributes['titlePadding'][3] . 'px' );
		}
		if ( isset( $attributes['titleBorder'] ) && ! empty( $attributes['titleBorder'] ) ) {
			$css->add_property( 'border-color', $css->sanitize_color( $attributes['titleBorder'] ) );
		}
		if ( isset( $attributes['titleColor'] ) && ! empty( $attributes['titleColor'] ) ) {
			$css->add_property( 'color', $css->sanitize_color( $attributes['titleColor'] ) );
		}
		if ( isset( $attributes['titleBg'] ) && ! empty( $attributes['titleBg'] ) ) {
			$css->add_property( 'background', $css->sanitize_color( $attributes['titleBg'] ) );
		}

		/*
		 * Hover
		 */
		$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title:hover, .wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-content-wrap > .kt-tabs-accordion-title .kt-tab-title:hover' );
		if ( ! empty( $attributes['titleBorderHover'] ) ) {
			$css->add_property( 'border-color', $css->sanitize_color( $attributes['titleBorderHover'] ) );
		}
		if ( ! empty( $attributes['titleColorHover'] ) ) {
			$css->add_property( 'color', $css->sanitize_color( $attributes['titleColorHover'] ) );
		}
		if ( ! empty( $attributes['titleBgHover'] ) ) {
			$css->add_property( 'background', $css->sanitize_color( $attributes['titleBgHover'] ) );
		}


		/*
		 * Active
		 */
		$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li.kt-tab-title-active .kt-tab-title, .wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-content-wrap > .kt-tabs-accordion-title.kt-tab-title-active .kt-tab-title' );
		if ( ! empty( $attributes['titleBorderActive'] ) ) {
			$css->add_property( 'border-color', $css->sanitize_color( $attributes['titleBorderActive'] ) );
		}
		if ( ! empty( $attributes['titleColorActive'] ) ) {
			$css->add_property( 'color', $css->sanitize_color( $attributes['titleColorActive'] ) );
		}
		if ( ! empty( $attributes['titleBgActive'] ) ) {
			$css->add_property( 'background', $css->sanitize_color( $attributes['titleBgActive'] ) );
		} else {
			$css->add_property( 'background', '#ffffff' );
		}


		if ( isset( $attributes['tabSize'] ) || isset( $attributes['tabLineHeight'] ) ) {
			$css->set_media_state( 'tablet' );
			$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title, .wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-content-wrap > .kt-tabs-accordion-title .kt-tab-title' );
			if ( isset( $attributes['tabSize'] ) ) {
				$css->add_property( 'font-size', $attributes['tabSize'] . ( ! isset( $attributes['sizeType'] ) ? 'px' : $attributes['sizeType'] ) );
			}
			if ( isset( $attributes['tabLineHeight'] ) ) {
				$css->add_property( 'line-height', $attributes['tabLineHeight'] . ( ! isset( $attributes['lineType'] ) ? 'px' : $attributes['lineType'] ) );
			}
			$css->set_media_state( 'desktop' );
		}
		if ( isset( $attributes['mobileSize'] ) || isset( $attributes['mobileLineHeight'] ) ) {
			$css->set_media_state( 'mobile' );
			$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-tab-title, .wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-content-wrap > .kt-tabs-accordion-title .kt-tab-title' );
			if ( isset( $attributes['mobileSize'] ) ) {
				$css->add_property( 'font-size', $attributes['mobileSize'] . ( ! isset( $attributes['sizeType'] ) ? 'px' : $attributes['sizeType'] ) );
			}
			if ( isset( $attributes['mobileLineHeight'] ) ) {
				$css->add_property( 'line-height', $attributes['mobileLineHeight'] . ( ! isset( $attributes['lineType'] ) ? 'px' : $attributes['lineType'] ) );
			}
			$css->set_media_state( 'desktop' );

		}


		if ( isset( $attributes['enableSubtitle'] ) && true == $attributes['enableSubtitle'] && isset( $attributes['subtitleFont'] ) && is_array( $attributes['subtitleFont'] ) && is_array( $attributes['subtitleFont'][0] ) ) {
			$css->set_selector( '.kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-title-sub-text, .kt-tabs-id' . $unique_id . ' > .kt-tabs-content-wrap > .kt-tabs-accordion-title .kt-title-sub-text' );
			$subtitle_font = $attributes['subtitleFont'][0];
			if ( isset( $subtitle_font['size'] ) && is_array( $subtitle_font['size'] ) && ! empty( $subtitle_font['size'][0] ) ) {
				$css->add_property( 'font-size', $subtitle_font['size'][0] . ( ! isset( $subtitle_font['sizeType'] ) ? 'px' : $subtitle_font['sizeType'] ) );
			}
			if ( isset( $subtitle_font['lineHeight'] ) && is_array( $subtitle_font['lineHeight'] ) && ! empty( $subtitle_font['lineHeight'][0] ) ) {
				$css->add_property( 'line-height', $subtitle_font['lineHeight'][0] . ( ! isset( $subtitle_font['lineType'] ) ? 'px' : $subtitle_font['lineType'] ) );
			}
			if ( ! empty( $subtitle_font['letterSpacing'] ) ) {
				$css->add_property( 'letter-spacing', $subtitle_font['letterSpacing'] . 'px' );
			}
			if ( ! empty( $subtitle_font['textTransform'] ) ) {
				$css->add_property( 'text-transform', $subtitle_font['textTransform'] );
			}
			if ( ! empty( $subtitle_font['family'] ) ) {
				$css->add_property( 'font-family', $subtitle_font['family'] );
			}
			if ( ! empty( $subtitle_font['style'] ) ) {
				$css->add_property( 'font-style', $subtitle_font['style'] );
			}
			if ( ! empty( $subtitle_font['weight'] ) ) {
				$css->add_property( 'font-weight', $subtitle_font['weight'] );
			}
			if ( isset( $subtitle_font['padding'] ) && is_array( $subtitle_font['padding'] ) ) {
				$css->add_property( 'padding', $subtitle_font['padding'][0] . 'px ' . $subtitle_font['padding'][1] . 'px ' . $subtitle_font['padding'][2] . 'px ' . $subtitle_font['padding'][3] . 'px' );
			}
			if ( isset( $subtitle_font['margin'] ) && is_array( $subtitle_font['margin'] ) ) {
				$css->add_property( 'margin', $subtitle_font['margin'][0] . 'px ' . $subtitle_font['margin'][1] . 'px ' . $subtitle_font['margin'][2] . 'px ' . $subtitle_font['margin'][3] . 'px' );
			}
		}

		if ( isset( $attributes['subtitleFont'] ) && is_array( $attributes['subtitleFont'] ) && isset( $attributes['subtitleFont'][0] ) && is_array( $attributes['subtitleFont'][0] ) && ( ( isset( $attributes['subtitleFont'][0]['size'] ) && is_array( $attributes['subtitleFont'][0]['size'] ) && isset( $attributes['subtitleFont'][0]['size'][1] ) && ! empty( $attributes['subtitleFont'][0]['size'][1] ) ) || ( isset( $attributes['subtitleFont'][0]['lineHeight'] ) && is_array( $attributes['subtitleFont'][0]['lineHeight'] ) && isset( $attributes['subtitleFont'][0]['lineHeight'][1] ) && ! empty( $attributes['subtitleFont'][0]['lineHeight'][1] ) ) ) ) {
			$css->set_media_state( 'tablet' );
			$css->set_selector( '.kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-title-sub-text, .kt-tabs-id' . $unique_id . ' > .kt-tabs-content-wrap > .kt-tabs-accordion-title .kt-title-sub-text' );
			if ( isset( $attributes['subtitleFont'][0]['size'][1] ) && ! empty( $attributes['subtitleFont'][0]['size'][1] ) ) {
				$css->add_property( 'font-size', $attributes['subtitleFont'][0]['size'][1] . ( ! isset( $attributes['subtitleFont'][0]['sizeType'] ) ? 'px' : $attributes['subtitleFont'][0]['sizeType'] ) );
			}
			if ( isset( $attributes['subtitleFont'][0]['lineHeight'][1] ) && ! empty( $attributes['subtitleFont'][0]['lineHeight'][1] ) ) {
				$css->add_property( 'line-height', $attributes['subtitleFont'][0]['lineHeight'][1] . ( ! isset( $attributes['subtitleFont'][0]['lineType'] ) ? 'px' : $attributes['subtitleFont'][0]['lineType'] ) );
			}
			$css->set_media_state( 'desktop' );
		}


		if ( isset( $attributes['subtitleFont'] ) && is_array( $attributes['subtitleFont'] ) && isset( $attributes['subtitleFont'][0] ) && is_array( $attributes['subtitleFont'][0] ) && ( ( isset( $attributes['subtitleFont'][0]['size'] ) && is_array( $attributes['subtitleFont'][0]['size'] ) && isset( $attributes['subtitleFont'][0]['size'][2] ) && ! empty( $attributes['subtitleFont'][0]['size'][2] ) ) || ( isset( $attributes['subtitleFont'][0]['lineHeight'] ) && is_array( $attributes['subtitleFont'][0]['lineHeight'] ) && isset( $attributes['subtitleFont'][0]['lineHeight'][2] ) && ! empty( $attributes['subtitleFont'][0]['lineHeight'][2] ) ) ) ) {
			$css->set_media_state( 'mobile' );
			$css->set_selector( '.kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li .kt-title-sub-text, .kt-tabs-id' . $unique_id . ' > .kt-tabs-content-wrap > .kt-tabs-accordion-title .kt-title-sub-text' );
			if ( isset( $attributes['subtitleFont'][0]['size'][2] ) && ! empty( $attributes['subtitleFont'][0]['size'][2] ) ) {
				$css->add_property( 'font-size', $attributes['subtitleFont'][0]['size'][2] . ( ! isset( $attributes['subtitleFont'][0]['sizeType'] ) ? 'px' : $attributes['subtitleFont'][0]['sizeType'] ) );
			}
			if ( isset( $attributes['subtitleFont'][0]['lineHeight'][2] ) && ! empty( $attributes['subtitleFont'][0]['lineHeight'][2] ) ) {
				$css->add_property( 'line-height', $attributes['subtitleFont'][0]['lineHeight'][2] . ( ! isset( $attributes['subtitleFont'][0]['lineType'] ) ? 'px' : $attributes['subtitleFont'][0]['lineType'] ) );
			}
			$css->set_media_state( 'desktop' );
		}

		/* SVG Icon */
		if ( isset( $attributes['titles'] ) && array_filter( array_column( $attributes['titles'], 'icon' ) ) ) {
			$css->set_selector( '.wp-block-kadence-tabs .kt-tabs-id' . $unique_id . ' > .kt-tabs-title-list li svg' );
			$css->add_property( 'font-size', $attributes['iSize'] . 'px' );
		}

		return $css->css_output();
	}

	/**
	 * Registers scripts and styles.
	 */
	public function register_scripts() {
		parent::register_scripts();
		// If in the backend, bail out.
		if ( is_admin() ) {
			return;
		}
		if ( apply_filters( 'kadence_blocks_check_if_rest', false ) && kadence_blocks_is_rest() ) {
			return;
		}

		wp_register_script( 'kadence-blocks-' . $this->block_name, KADENCE_BLOCKS_URL . 'includes/assets/js/kt-tabs.min.js', array(), KADENCE_BLOCKS_VERSION, true );

	}

}

Kadence_Blocks_Tabs_Block::get_instance();
