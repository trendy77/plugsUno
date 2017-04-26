<?php
/**
 * Plugin Name: Optimize your feed for feedly
 * Plugin URI:  https://implenton.com/optimize-your-feed-for-feedly/
 * Description: RSS discovery extensions that allow publisher to deliver a richer discovery experience in feedly.
 * Version:     1.0
 * Author:      implenton
 * Author URI:  https://implenton.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: oyfff
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or die( 'Uhum!' );

register_activation_hook( __FILE__, 'oyfff_activate' );

add_action( 'plugins_loaded', 'oyfff_load_textdomain' );

add_action( 'rss2_ns', 'oyfff_add_webfeeds_namespace' );
add_action( 'rss2_head', 'oyfff_add_cover_icon' );

add_filter( 'the_content_feed', 'oyfff_add_featured_image' );
add_filter( 'the_excerpt_rss', 'oyfff_add_featured_image' );

add_action( 'rss2_head', 'oyfff_add_logo_accentcolor' );
add_action( 'rss2_head', 'oyfff_add_related' );
add_action( 'rss2_head', 'oyfff_add_google_analytics' );

add_action( 'admin_menu', 'oyfff_add_admin_menu' );
add_action( 'admin_init', 'oyfff_settings_init' );

add_action( 'admin_enqueue_scripts', 'oyfff_wp_admin_scripts' );

function oyfff_activate() {

    register_uninstall_hook( __FILE__, 'oyfff_uninstall' );

}

function oyfff_uninstall() {

    delete_option( 'oyfff_settings' );

}

function oyfff_load_textdomain() {

    load_plugin_textdomain( 'oyfff', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

}

/**
 * https://blog.feedly.com/10-ways-to-optimize-your-feed-for-feedly/
 *
 * 01. Feed metadata used for discovery
 *
 * "These extensions allow content creators control the cover image, icon, title
 * and description that are used to present feeds when users search for new sources."
 */

function oyfff_add_webfeeds_namespace() {

    echo 'xmlns:webfeeds="http://webfeeds.org/rss/1.0"';

}

function oyfff_add_cover_icon() {

    $options = get_option( 'oyfff_settings' );

    if ( $options['oyfff_cover'] ) {

        echo sprintf(
            '<webfeeds:cover image="%s" />',
            esc_url( $options['oyfff_cover'] )
        );

    }

    if ( $options['oyfff_icon'] ) {

        echo sprintf(
            '<webfeeds:icon>%s</webfeeds:icon>',
            esc_url( $options['oyfff_icon'] )
        );

    }

}

/**
 * 04. Featured image
 *
 * "If the content of the story in the feed has an img element with a webfeedsFeaturedVisual classname,
 * that image will be selected as the featured image.""
 */

function oyfff_add_featured_image( $content ) {

    global $post;

    $options = get_option( 'oyfff_settings' );

    if ( $options['oyfff_featured_image'] ) {

        if ( has_post_thumbnail( $post->ID ) ) {

            $post_thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
            $post_thumbnail = "<figure><img src='$post_thumbnail_url' class='webfeedsFeaturedVisual'></figure>";

            $content = $post_thumbnail . $content;

        }

    }

    return $content;

}

/**
 * 06. Enhanced branding
 *
 * "When you specify an SVG-formatted logo and an accent color
 * we will place your logo on each of your stories and change the colors
 * of hyperlinks throughout your content to your chosen accent color."
 */

function oyfff_add_logo_accentcolor() {

    $options = get_option( 'oyfff_settings' );

    if ( $options['oyfff_logo'] ) {

        echo sprintf(
            '<webfeeds:logo>%s</webfeeds:logo>',
            esc_url( $options['oyfff_logo'] )
        );

    }

    if ( $options['oyfff_accentcolor'] ) {

        echo sprintf(
            '<webfeeds:accentColor>%s</webfeeds:accentColor>',
            esc_html( ltrim( $options['oyfff_accentcolor'], '#' ) )
        );

    }

}

/**
 * 07. Related stories
 *
 * "Often times when delving deep into your content, readers will want more than just one story on the given topic."
 */

function oyfff_add_related() { ?>

    <webfeeds:related layout="card" target="browser"/>

<?php }

/**
 * 08. Google Analytics integration
 *
 * "The pageview events reported to GA are based on the canonical URL of the stories,
 * allowing you to aggregate the views you are getting on feedly and on your website
 * and get a more global understanding of how users engage with your content."
 */

function oyfff_add_google_analytics() {

    $options = get_option( 'oyfff_settings' );

    if ( $options['oyfff_google_analytics'] ) {

        echo sprintf(
            '<webfeeds:analytics id="%s" engine="GoogleAnalytics"/>',
            esc_attr( $options['oyfff_google_analytics'] )
        );

    }

}

function oyfff_add_admin_menu() {

    add_options_page( 'Optimize your for feed feedly', 'OYFFF', 'manage_options', 'oyfff', 'oyfff_settings' );

}

function oyfff_settings_init() {

    register_setting( 'oyfff_settings', 'oyfff_settings' );

    $options = get_option( 'oyfff_settings' );

    add_settings_section(
        'oyfff_values',
        null,
        function() {

            echo sprintf(
                '<a target="_blank" href="%s">%s</a>',
                esc_url( 'https://blog.feedly.com/10-ways-to-optimize-your-feed-for-feedly/' ),
                esc_html__( 'Read the related article on feedly\'s blog.', 'oyfff' )
            );

        },
        'oyfff_settings'
    );

    add_settings_field(
        'oyfff_cover',
        __( 'Cover URL', 'oyfff' ),
        function() use ( $options ) { ?>

            <p>
                <input type="url"
                       class="regular-text"
                       placeholder="http://yoursite.com/a-large-cover-image.png"
                       name="oyfff_settings[oyfff_cover]"
                       value="<?php echo esc_attr( $options['oyfff_cover'] ); ?>">
            </p>

            <p>
                <a class="button button-secondary oyfff-media-select"
                   href="#"
                   data-title="<?php esc_attr_e( 'Select your Cover Image', 'oyfff' ); ?>"
                   data-insert="oyfff_settings[oyfff_cover]">
                    <?php esc_html_e( 'Select from Media Library', 'oyfff' ); ?>
                </a> <small><?php esc_html_e( 'or Paste the URL', 'oyfff' ); ?></small>
            </p>

        <?php },
        'oyfff_settings',
        'oyfff_values'
    );

    add_settings_field(
        'oyfff_icon',
        __( 'Icon SVG URL', 'oyfff' ),
        function() use ( $options ) { ?>

            <p>
                <input type="url"
                       class="regular-text"
                       placeholder="http://yoursite.com/icon.svg"
                       name="oyfff_settings[oyfff_icon]"
                       value="<?php echo esc_attr( $options['oyfff_icon'] ); ?>">
            </p>

            <p>
                <a class="button button-secondary oyfff-media-select"
                   href="#"
                   data-title="<?php esc_attr_e( 'Select your Icon SVG', 'oyfff' ); ?>"
                   data-insert="oyfff_settings[oyfff_icon]">
                    <?php esc_html_e( 'Select from Media Library', 'oyfff' ); ?>
                </a> <small><?php esc_html_e( 'or Paste the URL', 'oyfff' ); ?></small>
            </p>

        <?php },
        'oyfff_settings',
        'oyfff_values'
    );

    add_settings_field(
        'oyfff_featured_image',
        __( 'Featured Image', 'oyfff' ),
        function() use ( $options ) { ?>

            <p>
                <label>

                    <input type="hidden"
                           name="oyfff_settings[oyfff_featured_image]"
                           value="0">

                    <input type="checkbox"
                           name="oyfff_settings[oyfff_featured_image]"
                           value="1"
                           <?php checked( $options['oyfff_featured_image'], 1 ); ?>>

                    <?php esc_html_e( 'Insert the featured image before the content', 'oyfff' ); ?>

                </label>
            </p>

        <?php },
        'oyfff_settings',
        'oyfff_values'
    );

    add_settings_field(
        'oyfff_logo',
        __( 'Logo SVG URL', 'oyfff' ),
        function() use ( $options ) { ?>

            <p>
                <input type="url"
                       class="regular-text"
                       placeholder="http://yoursite.com/logo-30px-height.svg"
                       name="oyfff_settings[oyfff_logo]"
                       value="<?php echo esc_attr( $options['oyfff_logo'] ); ?>">
            </p>

            <p>
                <a class="button button-secondary oyfff-media-select"
                   href="#"
                   data-title="<?php esc_attr_e( 'Select your Logo SVG', 'oyfff' ); ?>"
                   data-insert="oyfff_settings[oyfff_logo]">
                    <?php esc_html_e( 'Select from Media Library', 'oyfff' ); ?>
                </a> <small><?php esc_html_e( 'or Paste the URL', 'oyfff' ); ?></small>
            </p>

        <?php },
        'oyfff_settings',
        'oyfff_values'
    );

    add_settings_field(
        'oyfff_accentcolor',
        __( 'Accent Color', 'oyfff' ),
        function() use ( $options ) { ?>

            <p>
                <input type="text"
                       class="regular-text color-picker"
                       placeholder="00FF00"
                       name="oyfff_settings[oyfff_accentcolor]"
                       value="<?php echo esc_attr( $options['oyfff_accentcolor'] ); ?>">
            </p>

        <?php },
        'oyfff_settings',
        'oyfff_values'
    );

    add_settings_field(
        'oyfff_google_analytics',
        __( 'Google Analytics UA', 'oyfff' ),
        function() use ( $options ) { ?>

            <p>
                <input type="text"
                       class="regular-text"
                       placeholder="UA-xxx"
                       name="oyfff_settings[oyfff_google_analytics]"
                       value="<?php echo esc_attr( $options['oyfff_google_analytics'] ); ?>">
            </p>

            <p class="description">
                <?php esc_html_e( 'Tracking ID is a string like UA-000000-01.', 'oyfff' ); ?>
            </p>

        <?php },
        'oyfff_settings',
        'oyfff_values'
    );

}

function oyfff_settings() { ?>

    <div class="wrap">

        <form action="options.php" method="post">

            <h1><?php esc_html_e( 'Optimize your feed for feedly' ); ?></h1>

            <?php

                settings_fields( 'oyfff_settings' );
                do_settings_sections( 'oyfff_settings' );
                submit_button();

            ?>

        </form>

    </div>

<?php }

function oyfff_wp_admin_scripts( $hook_suffix ) {

    if ( 'settings_page_oyfff' !== $hook_suffix  ) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );

    wp_enqueue_script( 'optimize-your-feed-for-feedly',
        plugins_url('optimize-your-feed-for-feedly.js', __FILE__ ),
        array(
            'wp-color-picker',
        ),
        false,
        true
    );

}