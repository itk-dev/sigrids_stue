<?php

/**
 * @file
 * Implementation of the administration image upload and selecton form.
 */

/**
 * The image administation form.
 */
function dynamic_background_admin_images() {
  $form = array();

  // Load settings
  $settings = variable_get('dynamic_background_setting', array());
  if (empty($settings)) {
    drupal_set_message(t('You have not yet configured "Dynamic background". Click <a href="@link">here</a> to configure it.',
              array('@link' => url('admin/build/backgrounds/settings'))));
    return;
  }

  // File upload form encoded.
  $form['#attributes'] = array('enctype' => 'multipart/form-data');

  // Load default values
  $default = variable_get('dynamic_background_images', array());
  
  for ($i = 0; $settings['num_of_pictures'] > $i; $i++) {
    $form['dynamic_background_picture_' . $i] = array(
      '#type' => 'background_upload_form',
      '#title' => t('Background image %num', array('%num' => $i+1)),
      '#default_value' => isset($default[$i]) ? $default[$i] : array(),
    );
  }
  
  // Give it system setting form style.
  $form = system_settings_form($form);
  unset($form['buttons']['reset']);

  // Set validators and submit handler(s).
  $form['#validate'][] = 'dynamic_background_admin_images_validate';
  $form['#submit'] = array('dynamic_background_admin_images_submit');
  return $form;
}

/**
 * The administration images form validator form. It handles uploading the
 * image and deletion if the checkbox is flaged.
 */
function dynamic_background_admin_images_validate($form, &$form_state) {

  // Load settings.
  $settings = variable_get('dynamic_background_setting', array());

  // Validate the file extension (move to config, with upload path).
  $validators = array(
    'file_validate_extensions' => array($settings['extensions']),
  );

  // Check that only one image is selected as active (use).

  // Loop through all uploaded files.
  foreach ($_FILES['files']['name'] as $file_field => $val) {
    if ($val == '') {
      continue;
    }
    if ($file = file_save_upload($file_field, $validators)) {
      $info = image_get_info($file->filepath);
      $destination = file_directory_path(). '/' . $settings['path'] . '/' . $file->filename;

      // Check if file exists.
      if (file_exists($destination)) {
        form_set_error('default', t('Failded to upload image, as one with that name exists.'));
        return;
      }

      // Copy file.
      if (file_copy($file, $destination, FILE_EXISTS_REPLACE)) {
        $form_state['values'][$file_field]['default'] = $file->filepath;
      }
      else {
        form_set_error('default', t("Failed to upload the image; the %directory directory doesn't exist or is not writable.", array('%directory' => file_directory_path(). '/' . $settings['path'])));
      }
    }
    else {
     form_set_error('default', t("Failed to upload the image; the image type should be jpeg, jpg or png."));
    }
  }

  // Check for image deletion.
  foreach ($form_state['values'] as $field => $data) {
    if (strstr($field, 'dynamic_background_picture_') && $data['picture_delete']) {
      // Found one flaged for deletion.
      if (!file_delete($data['default'])) {
        form_set_error($field, t('Could not delete %file from the system.', array('%file' => $data['default'])));
      }
      else {
        // Remove the delete images informations from the form.
        unset($form_state['values'][$field]['default']);
        $form_state['values'][$field]['picture_delete'] = 0;
      }
    }
  }
}

/**
 * Administration images form submittion handler.
 */
function dynamic_background_admin_images_submit($form, &$form_state) {
  // Exclude unnecessary elements.
  unset($form_state['values']['submit'], $form_state['values']['reset'], $form_state['values']['form_id'], $form_state['values']['op'], $form_state['values']['form_token'], $form_state['values']['form_build_id']);

  // Active background enabled?
  $active = FALSE;

  // Build array with image informationer.
  $images_config = array();
  foreach ($form_state['values'] as $value) {
    $images_config[] = $value;

    // Save current selected image (so we load as little as possible in the front end).
    if ($value['picture_use']) {
      variable_set('dynamic_background_active', $value['default']);
      $active = TRUE;
    }
  }

  // Where a background selected, if not remove the previouse selected one.
  if (!$active) {
    variable_del('dynamic_background_active');
  }

  // Save the images information to the database.
  variable_set('dynamic_background_images', $images_config);
  drupal_set_message(t('The configuration options have been saved.'));
}