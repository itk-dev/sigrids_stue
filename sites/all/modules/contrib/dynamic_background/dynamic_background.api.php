<?php

/**
 * @file
 * Hooks provided by the dynamic background module.
 *
 */

/**
 * Sub-modules that provieds image selection to the css generation process should
 * implement this hook.
 *
 * @param array $vars
 * @return array
 */
function hook_dynamic_background_css($vars) {
  return array(
    'image' => 'PATH TO IMAGE',
    'css_configuration' => 'DRUPAL VARIABLE WITH CSS CONFIGURATION',
    'weight' => 'WEIGHT OF THE CSS',
  );
}