<?php

/**
 * @subpackage Markdown Content
 * @package Boilerplate
 * @version 1.0.0
 *
 * Allows for Markdown formatted content.
 *
 * Exposes filters:
 * markdown_content_raw: The Markdown content as it's loaded from the file, before parsing.
 * markdown_content: The HTML content, after being parsed.
 */

# Set minimum Boilerplate version
plugin_requires_version( 'markdown-content', '1.5.3' );

# Load Dependencies
if ( !class_exists('Parsedown') || !class_exists('ParsedownExtra') ) {
    require_once __DIR__ . '/inc/Parsedown.php';
    require_once __DIR__ . '/inc/ParsedownExtra.php';
}

/**
 * Get Content
 *
 * Load a file and parse Markdown content
 *
 * @param $file (string) (optional) Optional file to load, if no file is specified
 * it will try to load a file based on the current path.
 */
function get_content( $file = false, $echo = true ) {

    # Get content path
    if ( ! $path = get('site.content_dir') ) {
        $path = '_content';
    }

    # Prefix root dir to content path
    $path = ROOT_DIR . '/' . $path;

    # Get filename
    if ( ! $file ) {
        $file = get('page.path') . '.md';
    }

    # Load Parsedown
    $md = new ParsedownExtra();

    # Load content
    if ( ! $content = file_get_contents($path . '/' . $file) ) {
        return false;
    }

    # Filter Content Before Parsing Markdown
    $content = apply_filters('markdown_content_raw', $content);

    # Parse Content
    $content = $md->text($content);

    # Filter Content After Parsing Markdown
    $content = apply_filters('markdown_content', $content);

    # Return content
    if ( $echo === true ) {
        echo $content;
        return;
    }

    return $content;

}
