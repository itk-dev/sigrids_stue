<?php

// Auto-rebuild the theme registry during theme development.
if (theme_get_setting('sigrids_stue_rebuild_registry')) {
  drupal_rebuild_theme_registry();
}

/**
 * Implementation of preprocess_views_slideshow_singleframe.
 * Change submitted data and add comment counter.
 * Don't show Slideshow when view returns empty result.
 */
function sigrids_stue_preprocess_views_slideshow_singleframe(&$vars) {  
  $empty = FALSE;
  foreach ($vars['view']->result[0] as $attr => $value){
    if ($attr == 'node_data_field_images_field_images_fid' && empty($value)) {
      $empty = TRUE;
    }
  }
  if($empty){
    unset($vars['view']->result);
  }
}

/**
 * Implementation of preprocess_comment.
 * Change submitted data and add comment counter.
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

  // Add Google Apps verification
  drupal_set_html_head('<meta name="google-site-verification" content="YGEy5EFD77DfoXXPsbp5Oal03Sweh6FHkFtiErzahbs" />');
  
  // Add IE 8 meta tag.
  drupal_set_html_head('<meta http-equiv="x-ua-compatible" content="IE=8">');
  // Make sure $head is updated in page.tpl.php see: http://api.drupal.org/api/drupal/includes--common.inc/function/drupal_set_html_head/6#comment-4614.
  $vars['head'] = drupal_get_html_head();

  // Get the path to the theme to make the code more efficient and simple.
  $path = drupal_get_path('theme', $theme_info->name);

  // conditional styles
  // xpressions documentation  -> http://msdn.microsoft.com/en-us/library/ms537512.aspx.

  // syntax for .info.
  // top stylesheets[all][] = style/reset.css.
  // ie stylesheets[ condition ][all][] = ie6.css.
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
    // Append the stylesheets to $styles, grouping by IE version and applying.
    // the proper wrapper.
    foreach ($ie_css as $condition => $styles) {
      $vars['styles'] .= '<!--[' . $condition . ']>' . "\n" . drupal_get_css($styles) . '<![endif]-->' . "\n";
    }
  }

}

/**
 * Implementation of theme_links, to change comment links in views.
 */
function sigrids_stue_links($links, $attributes = array('class' => 'links')) {
  global $language;
  $output = '';

  // Comment hack.
  if ($attributes['class'] == 'links inline') {
    if (isset($links['comment_add']) && !isset($links['comment_comments'])) {
      // Find nid and remove add link.
      $nid = split('/', $links['comment_add']['href']);
      $nid = $nid[2];
      unset($links['comment_add']);

      // Update information
      $links['comment_comments'] = array(
        'title' => t('0 comments'),
        'href' => 'node/' . $nid,
        'attributes' => array(
          'title' => t('Jump to the first comment of this posting.')
        ),
        'fragment' => 'comments',
      );
    }
    unset($links['comment_new_comments']);
  }

  if (count($links) > 0) {
    $output = '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = $key;

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class .= ' first';
      }
      if ($i == $num_links) {
        $class .= ' last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
           && (empty($link['language']) || $link['language']->language == $language->language)) {
        $class .= ' active';
      }
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      else if (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}


/**
 * Views Slideshow: "pause" control.
 *
 * @ingroup themeable.
 */
function sigrids_stue_views_slideshow_singleframe_control_pause($vss_id, $view, $options) {
  // Remove pause button.
}