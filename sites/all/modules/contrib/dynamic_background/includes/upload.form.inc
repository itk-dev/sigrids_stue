<?php

/**
 * @file
 * The functions here is used to create an new form element
 * (background_upload_form), which can be used to upload images. It also gives
 * the possibility to flag a image for deletion or active background image.
 */

/**
 * Implementation of hook_elements().
 *
 */
function dynamic_background_elements() {
  $types = array();
  $types['background_upload_form'] = array(
    '#input' => TRUE,
    '#process' => array('dynamic_background_upload_form_process'),
    '#element_validate' => array('dynamic_background_upload_form_validate'),
  );
  return $types;
}

/**
 * Implementation of hook_theme(). This defines the default theming function
 * for the background_upload_form element.
 *
 */
function dynamic_background_theme() {
  return array(
    'background_upload_form' => array(
      'arguments' => array('element' => NULL),
    ),
  );
}

/**
 * Theming function for background_upload_form.
 *
 */
function theme_background_upload_form($element) {
  // Style the elements as one form element
  return theme('form_element', $element, '<div class="background-upload-form">'. $element['#children'] .'</div>');
}

/**
 * Implementation of hook_form_process(). Handles the background_upload_form
 * element processing and building.
 *
 */
function dynamic_background_upload_form_process($element, $form_state) {
  $element['#tree'] = TRUE;

  // Merge the default values.
  if (!isset($element['#value'])) {
    $element['#value'] = $element['#default_value'];
  }
  else {
    if (is_array($element['#default_value'])) {
      $element['#value'] = array_merge($element['#value'], $element['#default_value']);
    }
  }

  // If image is already uploaded.
  if (isset($element['#value']['default'])) {
    // Create image preview thumbnail, using imagecache.
    $picture = theme('imagecache', 'dynamic_background_thumb', $element['#value']['default'], t('Image preview'), t('Image preview'), NULL);
    $element['current_picture'] = array(
      '#value' => $picture
    );

    // Checkbox to indicate if this image should be used as background image.
    $element['picture_use'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use picture as background'),
      '#default_value' => $element['#value']['picture_use'],
      '#prefix' => '<div class="picture-use">',
      '#suffix' => '</div>',
    );

    // Checkbox to control deletion of the image.
    $element['picture_delete'] = array(
      '#type' => 'checkbox',
      '#title' => t('Delete picture'),
      '#default_value' => $element['#value']['picture_delete'],
    );
  }
  else {
    // No image uploaded, so display image upload field.
    $element['picture'] = array(
      '#type' => 'file',
      '#description' => t('Select image file to upload'),
    );
  }

  // Load styling and js.
  drupal_add_css(drupal_get_path('module', 'dynamic_background') . '/css/admin_form.css', 'module');
  drupal_add_js(drupal_get_path('module', 'dynamic_background') . '/js/dynamic_background.js', 'module');

  return $element;
}

/**
 * Validation of the background_upload_form element. It have to return the
 * $form.
 *
 */
function dynamic_background_upload_form_validate($element, &$form_state) {
  return $form;
}

/**
 * Implementation of hook_imagecache_default_presets().
 */
function dynamic_background_imagecache_default_presets() {
  $presets = array(
    'dynamic_background_thumb' => array(
      'presetname' => 'dynamic_background_thumb',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale_and_crop',
          'data' => array(
            'width' => '100',
            'height' => '100',
          ),
        ),
      ),
    ),
  );
  return $presets;
}