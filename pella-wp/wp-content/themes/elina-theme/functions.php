<?php
/**
 * Elina Theme functions and definitions
 */

if ( ! function_exists( 'elina_theme_setup' ) ) :
    function elina_theme_setup() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );
    }
endif;
add_action( 'after_setup_theme', 'elina_theme_setup' );

/**
 * Enqueue scripts and styles.
 */
function elina_theme_scripts() {
    wp_enqueue_style( 'elina-theme-style', get_stylesheet_uri(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'elina_theme_scripts' );

/**
 * Register Custom Post Type for Projects
 * NOTE: This is now handled by the Pella Nova Projects plugin.
 * The post type will only be registered when the plugin is active.
 */
// Removed - Plugin now handles Projects post type registration

/**
 * Add Meta Boxes for Project Fields
 * NOTE: This is now handled by the Pella Nova Projects plugin.
 * Meta boxes will only appear when the plugin is active.
 */
// Removed - Plugin now handles meta boxes