<?php
/**
* @file
* Contains \Drupal\rafflesia_fields\Plugin\Field\FieldFormatter\UserInformationFormatter
*/

namespace Drupal\rafflesia_fields\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

    /**
    * Plugin implementation of the 'user_information_one_line' formatter.
    *
    * @FieldFormatter(
    *   id = "user_information_f",
    *   label = @Translation("User Information One line"),
    *   description = @Translation("Field Formatter for User Information"), 
    *   field_types = {
    *     "userinformation",
    *   }
    * )
    */ 


class UserInformationFormatter extends FormatterBase {
   /**
    * {@inheritdoc}
    */
    public function viewElements(FieldItemListInterface $items,$langcode) {
      $element = array();
      
      foreach ($items as $delta => $item) {
        $element[$delta] = array(
          '#markup' => $this->t('@first @last', array(
            '@color' => $items->color_hexa,  
            '@first' => $items->first_name,
            '@last' => $items->last_name,
            '@type' => $items->type_name,
            
            )
         ),
        );
      }
      return $element;
    }
}