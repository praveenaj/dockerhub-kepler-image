<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kepler_theme
 */

 // Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {  // Cannot access pages directly.
	exit( 'Direct script access denied.' );
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php if ( function_exists( 'wp_body_open' ) ) {
      wp_body_open();
  } else {
      do_action( 'wp_body_open' );
  } ?>
  <div id="page" class="site">
    <header id="header">
      <?php
      // Check if a header is set for the current page (using Kepler builder), 
      // if so, load it else fallback to default header. 
      $header_content = kepler_theme()->header->kepler_theme_header_content();
      if($header_content != "") 
        echo do_shortcode($header_content);
      else
        get_template_part( 'template-parts/layout/default_header' );
      ?> 
    </header>

	  <div id="content" class="page-content">

