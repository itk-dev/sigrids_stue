<?php

/**
 * @file
 * Contains the dynamic background views page display plugin, which is an
 * extension of the std. view page display.
 */

/**
 * We need to include the page display which is extended by dynamic background
 * view display.
 */
include_once drupal_get_path('module', 'views') . '/plugins/views_plugin_display_page.inc';

/**
 * The plugin that handles a full page.
 *
 * @ingroup views_display_plugins
 */
class views_plugin_display_page_db extends views_plugin_display_page {

  function option_definition() {
    $options = parent::option_definition();

    $options['dynamic_background'] = array(
      'title' => 'Dynamic background',
      'description' => t('Select a background for the current page.'),
      'default' => t('No image'),
    );

    return $options;
  }

  /**
   * Provide the summary for page options in the views UI.
   *
   * This output is returned as an array.
   */
  function options_summary(&$categories, &$options) {
    // It is very important to call the parent function here:
    parent::options_summary($categories, $options);

    // Find the currently selected image.
    $view_id = $this->view->name . '_' . $this->display->id;
    $image_id = dynamic_background_views_get_image_id($view_id);
    $options['dynamic_background'] = array(
      'category' => 'page',
      'title' => t('Dynamic background'),
      'value' => !is_null($image_id) ? 'Image ' . ($image_id + 1) : t('No image'),
    );
  }

  /**
   * Provide the default form for setting options.
   */
  function options_form(&$form, &$form_state) {
    // It is very important to call the parent function here:
    parent::options_form($form, $form_state);

    switch ($form_state['section']) {
      case 'dynamic_background':
        $view_id = $form_state['view']->name . '_' . $form_state['view']->current_display;
        $form['#title'] .= t('The selected background');
        $form['dynamic_background'] = dynamic_background_image_selector_form(dynamic_background_views_get_image_id($view_id));
        break;
    }
  }

  function options_validate(&$form, &$form_state) {
    // It is very important to call the parent function here:
    parent::options_validate($form, $form_state);

    // Check that only one image have been selected
    switch ($form_state['section']) {
      case 'dynamic_background':
        $no_of_images = 0;
        foreach ($form_state['values']['dynamic_background'] as $key => $value) {
          if ($value['selected']) {
            $no_of_images++;
            if ($no_of_images > 1) {
              form_error($form['dynamic_background'], t('You should only select one image.'));
            }
          }
        }
        break;
    }
  }

  function options_submit(&$form, &$form_state) {
    // It is very important to call the parent function here:
    parent::options_submit($form, $form_state);
    switch ($form_state['section']) {
      case 'dynamic_background':
        $view_id = $form_state['view']->name . '_' . $form_state['view']->current_display;
        // Check if any image have been selected.
        $image_id = NULL;
        foreach ($form_state['values']['dynamic_background'] as $key => $value) {
          if ($value['selected']) {
            $image_id = $key;
            break;
          }
        }

        // Try to save the image in the database.
        $this->save_background($image_id, $view_id);
        break;
    }
  }

  protected function save_background($image_id, $view_id) {
    if (!is_null($image_id)) {
      if (!is_null(dynamic_background_views_get_image_id($view_id))) {
        // Image found, so update the record.
        db_query('UPDATE {dynamic_background_views} SET  data = %d WHERE view_id = \'%s\'', $image_id, $view_id);
      }
      else {
        // No image found, so insert the image.
        db_query('INSERT INTO {dynamic_background_views} VALUES (\'%s\', %d)', $view_id, $image_id);
      }
    }
    else {
      // No image passe, so delete the one in the database, id it's there.
      db_query('DELETE FROM {dynamic_background_views} WHERE view_id=\'%s\'', $view_id);
    }
  }
}
