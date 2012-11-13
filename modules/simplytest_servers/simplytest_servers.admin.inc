<?php

/**
 * @file
 * Worker server management.
 *
 * Configuration page.
 */


/**
 * Server configuration form.
 */
function simplytest_servers_servers_form($form = array()) {

  $servers = simplytest_servers_get();

  $form['#servers'] = $servers;

  foreach($servers as $server) {
    $form[$server['id']] = simplytest_servers_config_form_elements($server);
  }

  $form['new'] = simplytest_servers_config_form_elements();

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save configuration'),
  );

  return $form;
}

/**
 * Server config elements.
 */
function simplytest_servers_config_form_elements($config = NULL) {
  // Build server config fieldset.
  $elements = array(
    '#type' => 'fieldset',
    '#title' => isset($config['name']) ? $config['name'] : t('New server'),
    '#collapsible' => TRUE,
    '#collapsed' => !empty($config),
  );
  // Decide whether to use default config.
  if (empty($config)) {
    $config = array(
      'id' => 'new',
      'active' => FALSE,
      'name' => t('New server'),
      'host' => '',
      'port' => 22,
      'user' => '',
      'password' => '',
      'slots' => 0,
      'spawn' => '',
    );
  }
  else {
    $elements['#title'] .= ' (' . ($config['active'] ? t('Active') : t('Inactive')) . ')';
  }
  $elements[$config['id'] . '__active'] = array(
    '#type' => 'checkbox',
    '#title' => t('Active'),
    '#default_value' => $config['active'],
  );
  $elements[$config['id'] . '__name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#default_value' => $config['name'],
  );
  $elements[$config['id'] . '__host'] = array(
    '#type' => 'textfield',
    '#title' => t('Server hostname'),
    '#default_value' => $config['host'],
  );
  $elements[$config['id'] . '__port'] = array(
    '#type' => 'textfield',
    '#title' => t('SSH port'),
    '#default_value' => $config['port'],
  );
  $elements[$config['id'] . '__user'] = array(
    '#type' => 'textfield',
    '#title' => t('SSH User'),
    '#default_value' => $config['user'],
  );
  $elements[$config['id'] . '__password'] = array(
    '#type' => 'textfield',
    '#title' => t('SSH Password'),
    '#default_value' => $config['password'],
  );
  $elements[$config['id'] . '__slots'] = array(
    '#type' => 'textfield',
    '#title' => t('Slots (current possible submissions)'),
    '#default_value' => $config['slots'],
  );
  $elements[$config['id'] . '__spawn'] = array(
    '#type' => 'textfield',
    '#title' => t('Spawn by script execution'),
    '#default_value' => $config['spawn'],
  );
  return $elements;
}

/**
 * Server configuration form, submit handler.
 */
function simplytest_servers_servers_form_submit($form, &$form_state) {
  if (isset($form_state['values']['new__active']) && $form_state['values']['new__active']) {
    db_insert('simplytest_servers')
      ->fields(array(
        'data' => serialize(array(
          'name' => $form_state['values']['new__name'],
          'host' => $form_state['values']['new__host'],
          'port' => $form_state['values']['new__port'],
          'user' => $form_state['values']['new__user'],
          'password' => $form_state['values']['new__password'],
          'spawn' => $form_state['values']['new__spawn'],
        )),
        'slots' => $form_state['values']['new__slots'],
        'active' => TRUE,
      ))
      ->execute();
  }

  foreach ($form['#servers'] as $server) {
    db_update('simplytest_servers')
      ->condition('id', $server['id'])
      ->fields(array(
        'data' => serialize(array(
          'name' => $form_state['values'][$server['id'] . '__name'],
          'host' => $form_state['values'][$server['id'] . '__host'],
          'port' => $form_state['values'][$server['id'] . '__port'],
          'user' => $form_state['values'][$server['id'] . '__user'],
          'password' => $form_state['values'][$server['id'] . '__password'],
          'spawn' => $form_state['values'][$server['id'] . '__spawn'],
        )),
        'slots' => $form_state['values'][$server['id'] . '__slots'],
        'active' => $form_state['values'][$server['id'] . '__active'],
      ))
      ->execute();
  }

  drupal_set_message(t('Updated configuration successfully!'));

}