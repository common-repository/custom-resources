<?php
/**
 * Plugin Name: Custom Resources
 * Plugin URI: http://wordpress.org/plugins/custom-resources/
 * Description: Embed additional resources (styles and scripts) for both your theme and admin pages easily. Works by direct inline embedding or by using URL's.
 * Version: 1.0.7
 * Author: Fineswap
 * Author URI: http://fineswap.com/open-source/?utm_source=wordpress&utm_medium=plugin&utm_term=comment&utm_campaign=custom-resources
 * License: GPLv2
 *
 * Copyright (c) 2014 Fineswap. All rights reserved.
 * This plugin comes with NO WARRANTY WHATSOEVER. Use at your own risk.
 */

// Using namespaces is highly recommended.
namespace Org\Fineswap\OpenSource\CustomResources;

// Constants reused throughout the plugin.
define(__NAMESPACE__.'\NAME', __('Custom Resources'));
define(__NAMESPACE__.'\TITLE', __('Custom Resources settings'));
define(__NAMESPACE__.'\PATH', dirname(__FILE__) . '/');
define(__NAMESPACE__.'\SLUG', 'custom_resources');
define(__NAMESPACE__.'\CFG', SLUG . '_config');
define(__NAMESPACE__.'\CFG_STYLES_EX', 'cr_styles_external');
define(__NAMESPACE__.'\CFG_STYLES_IN', 'cr_styles_inline');
define(__NAMESPACE__.'\CFG_STYLES_ABSOLUTE', 'cr_styles_absolute');
define(__NAMESPACE__.'\CFG_STYLES_LOAD_FRT', 'cr_styles_load_frontend');
define(__NAMESPACE__.'\CFG_STYLES_LOAD_BCK', 'cr_styles_load_backend');
define(__NAMESPACE__.'\CFG_SCRIPT_EX', 'cr_script_external');
define(__NAMESPACE__.'\CFG_SCRIPT_IN', 'cr_script_inline');
define(__NAMESPACE__.'\CFG_SCRIPT_ABSOLUTE', 'cr_script_absolute');
define(__NAMESPACE__.'\CFG_SCRIPT_LOAD_FRT', 'cr_script_load_frontend');
define(__NAMESPACE__.'\CFG_SCRIPT_LOAD_BCK', 'cr_script_load_backend');

// Enclose all logic in one class.
class Controller {
  /**
   * Singleton instance.
   * @var Controller
   */
  private static $instance;

  /**
   * Get an singleton instance of this class.
   * @return Controller
   */
  static function &kickoff() {
    if(!self::$instance) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * Constructor.
   */
  private function __construct() {
    // Add a Settings link in Plugins page.
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'settings_link'));

    // Properly initialize and cleanup on activate/deactivate.
    register_activation_hook(__FILE__, array($this, 'on_activate'));
    register_deactivation_hook(__FILE__, array($this, 'on_deactivate'));

    // Load relevant actions based on user's status.
    if(is_admin()) {
      // Hook into backend.
      add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
      add_action('admin_menu', array($this, 'admin_menu'));
      add_action('admin_init', array($this, 'admin_init'));
      add_action('admin_head', array($this, 'admin_head'), 1000);
    } else {
      // Hook into frontend.
      add_action('wp_head', array($this, 'wp_head'), 1000);
    }
  }

  /**
   * Inject a Settings link right in the Plugins page.
   *
   * @param Array $links original links array
   * @return Array updated links array
   */
  function settings_link($links) {
    array_unshift($links, '<a href="options-general.php?page=' . SLUG . '">' . __('Settings') . '</a>');
    return $links;
  }

  /**
   * Load additional backend styles.
   */
  function admin_enqueue_scripts() {
    wp_enqueue_style(SLUG . '_style', plugins_url('style.css', __FILE__));
  }

  /**
   * Add a link under Settings menu.
   */
  function admin_menu() {
    add_options_page(
      __('Settings'),
      NAME,
      'edit_theme_options',
      SLUG,
      array($this, 'admin_menu_output')
    );
  }

  /**
   * Hook to display the plugin's form.
   */
  function admin_menu_output() {
    include(PATH . 'output.php');
  }

  /**
   * Initialize the plugin's sane state.
   */
  function admin_init() {
    // Styles.
    register_setting(CFG, CFG_STYLES_EX);
    register_setting(CFG, CFG_STYLES_IN);
    register_setting(CFG, CFG_STYLES_ABSOLUTE);
    register_setting(CFG, CFG_STYLES_LOAD_FRT);
    register_setting(CFG, CFG_STYLES_LOAD_BCK);

    // Scripts.
    register_setting(CFG, CFG_SCRIPT_EX);
    register_setting(CFG, CFG_SCRIPT_IN);
    register_setting(CFG, CFG_SCRIPT_ABSOLUTE);
    register_setting(CFG, CFG_SCRIPT_LOAD_FRT);
    register_setting(CFG, CFG_SCRIPT_LOAD_BCK);
  }

  /**
   * Load custom resources for backend.
   */
  function admin_head() {
    $this->load_styles(TRUE);
    $this->load_scripts(TRUE);
  }

  /**
   * Load custom resources for frontend.
   */
  function wp_head() {
    $this->load_styles(FALSE);
    $this->load_scripts(FALSE);
  }

  /**
   * Populate plugin-specific options in the backend.
   */
  function on_activate() {
    // Styles.
    add_option(CFG_STYLES_EX, '');
    add_option(CFG_STYLES_IN, '');
    add_option(CFG_STYLES_ABSOLUTE, '');
    add_option(CFG_STYLES_LOAD_FRT, '1');
    add_option(CFG_STYLES_LOAD_BCK, '1');

    // Scripts.
    add_option(CFG_SCRIPT_EX, '');
    add_option(CFG_SCRIPT_IN, '');
    add_option(CFG_SCRIPT_ABSOLUTE, '');
    add_option(CFG_SCRIPT_LOAD_FRT, '1');
    add_option(CFG_SCRIPT_LOAD_BCK, '1');
  }

  /**
   * Remove plugin-specific options from the backend.
   */
  function on_deactivate() {
    // Styles.
    delete_option(CFG_STYLES_EX);
    delete_option(CFG_STYLES_IN);
    delete_option(CFG_STYLES_ABSOLUTE);
    delete_option(CFG_STYLES_LOAD_FRT);
    delete_option(CFG_STYLES_LOAD_BCK);

    // Scripts.
    delete_option(CFG_SCRIPT_EX);
    delete_option(CFG_SCRIPT_IN);
    delete_option(CFG_SCRIPT_ABSOLUTE);
    delete_option(CFG_SCRIPT_LOAD_FRT);
    delete_option(CFG_SCRIPT_LOAD_BCK);
  }

  /**
   * Inject necessary HTML to properly load custom styles.
   *
   * @param Boolean $isAdmin whether this is the frontend or backend.
   */
  private function load_styles($isAdmin) {
    // Load checkbox selection.
    $frontendAllowed = get_option(CFG_STYLES_LOAD_FRT);
    $backendAllowed = get_option(CFG_STYLES_LOAD_BCK);

    // Only load styles if state is sane.
    if(($isAdmin && $backendAllowed) || (!$isAdmin && $frontendAllowed)) {
      $styles = array();

      // First, load any external resources.
      $resources = trim(get_option(CFG_STYLES_EX));

      // If external resources defined, add them to output.
      if(!empty($resources)) {
        // Split string into an array.
        $resources = explode("\n", $resources);

        // If to turn relative links to absolute ones.
        $makeAbsolute = !strcmp('1', get_option(CFG_STYLES_ABSOLUTE));

        // Cycle through the URLs.
        foreach($resources as $resource) {
          // Trim the URL, on Windows there might be a \r.
          $resource = trim($resource);

          if($makeAbsolute) {
            // Remove leading slashes.
            $resource = ltrim($resource, '/');

            // Construct final URL.
            $resource = home_url($resource);
          }

          // Enqueue it.
          $styles[] = '<link rel=\'stylesheet\' href=\'' . htmlspecialchars($resource) . '\' type=\'text/css\' media=\'all\' />';
        }
      }

      // Second, load any external resources.
      $resources = trim(get_option(CFG_STYLES_IN));

      // If external resources defined, add them to output.
      if(!empty($resources)) {
        // Embed into the page.
        $styles[] = '<style type=\'text/css\'>';
        $styles[] = '<!--/*--><![CDATA[/*><!--*/';
        $styles[] = $resources;
        $styles[] = '/*]]>*/-->';
        $styles[] = '</style>';
      }

      $styles = implode(PHP_EOL, $styles);

      if(!empty($styles)) {
        echo $styles . PHP_EOL;
      }
    }
  }

  /**
   * Inject necessary HTML to properly load custom scripts.
   *
   * @param Boolean $isAdmin whether this is the frontend or backend.
   */
  private function load_scripts($isAdmin) {
    // Load checkbox selection.
    $frontendAllowed = get_option(CFG_SCRIPT_LOAD_FRT);
    $backendAllowed = get_option(CFG_SCRIPT_LOAD_BCK);

    // Only load styles if state is sane.
    if(($isAdmin && $backendAllowed) || (!$isAdmin && $frontendAllowed)) {
      $scripts = array();

      // First, load any external resources.
      $resources = trim(get_option(CFG_SCRIPT_EX));

      // If external resources defined, add them to output.
      if(!empty($resources)) {
        // Split string into an array.
        $resources = explode("\n", $resources);

        // If to turn relative links to absolute ones.
        $makeAbsolute = !strcmp('1', get_option(CFG_SCRIPT_ABSOLUTE));

        // Cycle through the URLs.
        foreach($resources as $resource) {
          // Trim the URL, on Windows there might be a \r.
          $resource = trim($resource);

          if($makeAbsolute) {
            // Remove leading slashes.
            $resource = ltrim($resource, '/');

            // Construct final URL.
            $resource = home_url($resource);
          }

          // Enqueue it.
          $scripts[] = '<script type=\'text/javascript\' src=\'' . htmlspecialchars($resource) . '\'></script>';
        }
      }

      // Second, load any external resources.
      $resources = trim(get_option(CFG_SCRIPT_IN));

      // If external resources defined, add them to output.
      if(!empty($resources)) {
        // Embed into the page.
        $scripts[] = '<script type=\'text/javascript\'>';
        $scripts[] = '<!--//--><![CDATA[//><!--';
        $scripts[] = $resources;
        $scripts[] = '//--><!]]>';
        $scripts[] = '</script>';
      }

      $scripts = implode(PHP_EOL, $scripts);

      if(!empty($scripts)) {
        echo $scripts . PHP_EOL;
      }
    }
  }
}

// Multi-site is currently not supported.
if(!is_multisite()) {
  Controller::kickoff();
}
