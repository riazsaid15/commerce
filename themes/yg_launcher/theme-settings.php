<?php

/**
 * @file
 * Provides an additional config form for theme settings.
 */

use Drupal\Core\Form\FormStateInterface;

function yg_launcher_form_system_theme_settings_alter(array &$form, FormStateInterface $form_state) {
  $form['yg_launcher_settings']= array(
    '#type' => 'details',
    '#title' => t('YG Launcher Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#group' => 'bootstrap',
    '#weight' => 10,
  );

  $form['yg_launcher_settings']['social_links'] = array(
    '#type' => 'details',
    '#title' => t('Social Links'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['yg_launcher_settings']['social_links']['facebook_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Facebook Url'),
    '#default_value' => theme_get_setting('facebook_url'),
  );
   $form['yg_launcher_settings']['social_links']['twitter_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Twitter Url'),
    '#default_value' => theme_get_setting('twitter_url'),
  );
  $form['yg_launcher_settings']['social_links']['instagram_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Instagram Url'),
    '#default_value' => theme_get_setting('instagram_url'),
  );
  $form['yg_launcher_settings']['social_links']['google_plus_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Google plus Url'),
    '#default_value' => theme_get_setting('google_plus_url'),
  );
 $form['yg_launcher_settings']['social_links']['pinterest_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Pinterest Url'),
    '#default_value' => theme_get_setting('pinterest_url'),
  );
  $form['yg_launcher_settings']['bg-setting'] = array(
    '#type' => 'details',
    '#title' => t('Background & Date Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['yg_launcher_settings']['bg-setting']['bg-image'] = array(
    '#type' => 'managed_file',
    '#title'    => t('Background Image'),
    '#upload_location' => file_default_scheme() . '://theme/backgrounds/',
    '#default_value' => theme_get_setting('bg-image'),
    '#upload_validators' => array(
      'file_validate_extensions' => array('png gif jpg jpeg'),
      'file_validate_size' => array(25600000),
    ),
  );
  $form['yg_launcher_settings']['bg-setting']['date'] = array(
    '#type' => 'date',
    '#title' => t('Launch Date'),
    '#description' => t('Please enter the date'),
    '#default_value' => theme_get_setting('date'),
    );
  $form['yg_launcher_settings']['bg-setting']['custom_message'] = array(
    '#type' => 'textfield',
    '#title' => t('Date Expired custom message'),
    '#description' => t('Please enter the yg_launcher title'),
    '#default_value' => theme_get_setting('custom_message'),
    );
//footer custom text
$form['yg_launcher_settings']['yg_launcher_info'] = array(
    '#type' => 'details',
    '#title' => t('yg_launcher Details'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
   $form['yg_launcher_settings']['yg_launcher_info']['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#description' => t('Please enter the yg_launcher title'),
    '#default_value' => theme_get_setting('title'),
    );
   $form['yg_launcher_settings']['yg_launcher_info']['description'] = array(
    '#type' => 'textarea',
    '#title' => t('Description'),
    '#description' => t('Please enter the yg_launcher description in yg_launcher theme...'),
    '#default_value' => theme_get_setting('description'),
    );

#footer
 $form['yg_launcher_settings']['footer'] = array(
    '#type' => 'details',
    '#title' => t('Footer Section'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $footer_description = theme_get_setting('footer_desc');
  $form['yg_launcher_settings']['footer']['footer_desc'] = array(
  '#type' => 'text_format',
  '#title' => t('Footer Description'),
  '#description' => t('Please enter the footer description...'),
  '#default_value' => $footer_description['value'],
  '#format'        => $footer_description['format'],
  );
}


