<?php

// Auto-rebuild the theme registry during theme development.
if (theme_get_setting('sigrids_stue_rebuild_registry')) {
  drupal_rebuild_theme_registry();
}

/**
 * Implementation of preprocess_comment
 * Change submitted data and add comment counter
 */
function sigrids_stue_preprocess_comment(&$variables){
  static $depths_counter;

  // Add comment counter
  $variables['comment_counter'] = ++$depths_counter[$variables['comment']->nid][$variables['comment']->pid];
  
  // Change submitted data
  $variables[submitted] = '<span class="author">' . $variables['comment']->name . '</span>, ' . format_date($variables['comment']->timestamp, 'custom', 'd. F Y - H:i');
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

}