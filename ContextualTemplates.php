<?php

namespace wpscholar\WordPress;

/**
 * Class ContextualTemplates
 *
 * @package wpscholar\WordPress
 */
class ContextualTemplates {

	/**
	 * Locate a contextual template given a slug name.
	 *
	 * @param string $slug
	 * @param bool $load
	 * @param bool $require_once
	 *
	 * @return string
	 */
	public static function locateTemplate( $slug, $load = false, $require_once = false ) {

		$templates = [];

		$contexts = Context::getContext();
		foreach ( $contexts as $context ) {
			$templates[] = "{$slug}-{$context}.php";
		}
		$templates[] = "{$slug}.php";

		$templates = apply_filters( __METHOD__, $templates, $slug, $contexts );

		return locate_template( $templates, $load, $require_once );
	}

	/**
	 * Load a contextual template given a slug name.
	 *
	 * @param string $slug
	 * @param string $name
	 */
	public static function getTemplatePart( $slug, $name = null ) {

		$template = '';

		if ( ! is_null( $name ) ) {
			$template = self::locateTemplate( "{$slug}-{$name}" );
		}

		if ( ! $template ) {
			$template = self::locateTemplate( $slug );
		}

		if ( $template ) {

			$file = basename( $template );

			do_action( "before_template__{$slug}", $template, $name, $file );
			do_action( "before_template_file__{$file}", $template, $slug, $name );

			load_template( $template, false );

			do_action( "after_template_file__{$file}", $template, $slug, $name );
			do_action( "after_template__{$slug}", $template, $name, $file );

		}
	}

}
