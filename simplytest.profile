<?php

/**
 * @file
 * Enables modules and site configuration for a minimal site installation.
 */

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function simplytest_form_install_configure_form_alter(&$form, $form_state) {
  // Pre-populate the site name with the server name.
  $form['site_information']['site_name']['#default_value'] = 'simplytest.me';
}

/**
 * Implements hook_menu().
 */
function simplytest_menu() {
  $items = array();
  // General administrative config path.
  $items['admin/simplytest'] = array(
    'title' => 'Simplytest',
    'description' => 'Administer simplytest site settings.',
    'access arguments' => array('administer simplytest'),
    'page callback' => 'simplytest_admin_menu_block_page',
    'type' => MENU_NORMAL_ITEM,
  );
  // Main start page path.
  $items['start'] = array(
    'page callback' => 'simplytest_start_page',
    'access arguments' => array('access simplytest page'),
    'type' => MENU_CALLBACK,
  );
  return $items;
}

/**
 * Administrative block overview page.
 */
function simplytest_admin_menu_block_page() {
  module_load_include('inc', 'system', 'system.admin');
  $item = menu_get_item();
  if ($content = system_admin_menu_block($item)) {
    $output = theme('admin_block_content', array('content' => $content));
  }
  else {
    $output = t('You do not have any administrative items.');
  }
  return $output;
}

/**
 * Implements hook_permission().
 */
function simplytest_permission() {
  // General permissions.
  return array(
    'access simplytest page' => array(
      'title' => t('Access simplytest home page'),
    ),
    'administer simplytest' => array(
      'title' => t('Administer simplytest'),
    ),
    'submit simplytest requests' => array(
      'title' => t('Submit simplytest requests'),
    ),
    'bypass antiflood' => array(
      'title' => t('Bypass anti-flood mechanisms'),
    ),
  );
}

/**
 * Home page callback.
 */
function simplytest_start_page() {
  // Set empty breadcrumb for start page.
  drupal_set_breadcrumb(array());
  // Set title to site slogan.
  drupal_set_title(variable_get('site_slogan'));
  return array();
}
