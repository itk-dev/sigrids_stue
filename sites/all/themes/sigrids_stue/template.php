<?php

/**
 * Implementation of hook_menu_item().
 *
 * Adds odd/even to all menu lists.
 */
function sigrids_stue_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
  static $zebra = FALSE;
  $zebra = !$zebra;
  $class = ($menu ? 'expanded' : ($has_children ? 'collapsed' : 'leaf'));
  if (!empty($extra_class)) {
    $class .= ' '. $extra_class;
  }
  if ($in_active_trail) {
    $class .= ' active-trail';
  }
  if ($zebra) {
    $class .= ' even';
  }
  else {
    $class .= ' odd';
  }
  return '<li class="'. $class .'">'. $link . $menu ."</li>\n";
}

// Auto-rebuild the theme registry during theme development.
if (theme_get_setting('sigrids_stue_rebuild_registry')) {
  drupal_rebuild_theme_registry();
}

function sigrids_stue_preprocess_page(&$vars) {

  global $theme_info;

  // Get the path to the theme to make the code more efficient and simple.
  $path = drupal_get_path('theme', $theme_info->name);

  // conditional styles
  // xpressions documentation  -> http://msdn.microsoft.com/en-us/library/ms537512.aspx

  // syntax for .info
  // top stylesheets[all][] = style/reset.css
  // ie stylesheets[ condition ][all][] = ie6.css
  // ------------------------------------------------------------------------

  // Check for IE conditional stylesheets.
  if (isset($theme_info->info['ie stylesheets']) AND theme_get_setting('sigrids_stue_stylesheet_conditional')) {

    $ie_css = array();

    // Format the array to be compatible with drupal_get_css().
    foreach ($theme_info->info['ie stylesheets'] as $condition => $media) {
      foreach ($media as $type => $styles) {
        foreach ($styles as $style) {
          $ie_css[$condition][$type]['theme'][$path . '/' . $style] = TRUE;
        }
      }
    }
    // Append the stylesheets to $styles, grouping by IE version and applying
    // the proper wrapper.
    foreach ($ie_css as $condition => $styles) {
      $vars['styles'] .= '<!--[' . $condition . ']>' . "\n" . drupal_get_css($styles) . '<![endif]-->' . "\n";
    }
  }

  /**
  * Implementation of hook_preprocess_page().
  *
  * Create footer menu.
  */
  $tree = menu_tree_all_data('primary-links');
  $vars['footer_menu'] = '<div class="footer-menu grid-8">' . menu_tree_output($tree) . '</div>';

}

/**
 * Add current page to breadcrumb
 */
function sigrids_stue_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    $title = drupal_get_title();
    if (!empty($title)) {
      // Get separator
      global $theme_info;
      if (theme_get_setting('sigrids_stue_breadcrumb_separator')) {
        $sep = '<span class="breadcrumb-sep">'. theme_get_setting('sigrids_stue_breadcrumb_separator').'</span>';
      }
      $breadcrumb[]='<span class="breadcrumb-current">'. $title .'</span>';
    }
    return '<div class="breadcrumb">'. implode($sep, $breadcrumb) .'</div>';
  }
}