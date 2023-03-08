<?php
/**
* @file
* Contains \Drupal\mymodule\Controller\SettingController class.
*/
namespace Drupal\rafflesia\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
* Returns responses for My Module module.
*/

class SettingController extends ControllerBase {
  /**
  * Returns markup for our custom page.
  */
  public function customPage() {
    return ['#markup' => t('Welcome to Rafflesia Setting Page'),];
  }
}