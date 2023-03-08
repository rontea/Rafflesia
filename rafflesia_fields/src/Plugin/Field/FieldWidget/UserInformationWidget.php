<?php
/* Need to Implement Auto Code*/

/**
* @file
* Contains \Drupal\rafflesia_fields\Plugin\Field\FieldWidget\UserInformationWidget
*/

namespace Drupal\rafflesia_fields\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
* Plugin implementation of the 'userinformation_default' widget.
*
* @FieldWidget(
*   id = "userinformation_default",
*   label = @Translation("Visible User Information"),
*   field_types = {
 *    "userinformation",
 *  }
* )
*/

class UserInformationWidget extends WidgetBase {
      
  /**
  * {@inheritdoc}
  */
  public function formElement(FieldItemListInterface $items,$delta, array $element, array &$form, FormStateInterface $form_state) {
    
   $id = \Drupal::currentUser()->id();
    // Auto Generate Field
   $user =  User::load($id);
    // Get the Value of Presentation 
   $field = $items->getValue();
   // set the Permission
   $permission = 'view rafflesia_fields';
   
   $element['color_hexa'] = [
      '#type' => 'color',
      '#title' => $this->t('Color'),
      '#default_value' => $field[0]['color_hexa'],
      '#description' => 'Set Your Color that will Reflect on the Report',
    ];
   
   if($user->hasPermission($permission)){
      $element['first_name'] = array(
          '#type' => 'textfield',
          '#title' => t('First name'),
          '#default_value' => $field[0]['first_name'],
          '#size' => 25,
          '#required' => $element['#required'],
      );

      $element['last_name'] = array(
          '#type' => 'textfield',
          '#title' => t('Last name'),
          '#default_value' => $field[0]['last_name'],
          '#size' => 25,
          '#required' => $element['#required'],
      );

      $element['type_name'] = array(
          '#type' => 'select',
          '#title' => t('Position'),
          '#default_value' => $field[0]['type_name'],
          '#description' => 'Select the position of WSM',
          '#required' => $element['#required'],
          '#options' => array(t('Manager'), t('WSM')),
      );
   }else{
     
      $element['first_name'] = array(
          '#type' => 'textfield',
          '#title' => t('First name'),
          '#default_value' => $field[0]['first_name'],
          '#size' => 25,
          '#required' => $element['#required'],
          '#disabled' => TRUE,
      );

      $element['last_name'] = array(
          '#type' => 'textfield',
          '#title' => t('Last name'),
          '#default_value' => $field[0]['last_name'],
          '#size' => 25,
          '#required' => $element['#required'],
          '#disabled' => TRUE,
      );

      $element['type_name'] = array(
          '#type' => 'select',
          '#title' => t('Position'),
          '#default_value' => $field[0]['type_name'],
          '#description' => 'Select the position of WSM',
          '#required' => $element['#required'],
          '#options' => array(t('Manager'), t('WSM')),
          '#disabled' => TRUE,
      );
   }
   
   return $element;
      
  }  
}