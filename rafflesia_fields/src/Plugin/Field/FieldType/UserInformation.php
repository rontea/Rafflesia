<?php
/**
* @file
* Contains \Drupal\rafflesia_fields\Plugin\Field\FieldType\UserInformation.
*/
namespace Drupal\rafflesia_fields\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
* Plugin implementation of the 'userinformation' field type.
*
* @FieldType(
*   id = "userinformation",
*   label = @Translation("User Information"),
*   description = @Translation("This field stores a first and last name."),
*   category = @Translation("General"),
*   default_widget = "userinformation_default",
*   default_formatter = "user_information_f",
* )
*/

class UserInformation extends FieldItemBase {
    
    /**
    * {@inheritdoc}
    */
    public static function schema(FieldStorageDefinitionInterface  $field_definition) {
        
        return array(
          'columns' => array(
              'first_name' => array(
              'description' => 'First name.',
              'type' => 'varchar',
              'length' => '255',
              'not null' => TRUE,
              'default' => '',
              ),
              'last_name' => array(
              'description' => 'Last name.',
              'type' => 'varchar',
              'length' => '255',
              'not null' => TRUE,
              'default' => '',
              ),
              'type_name' => array(
              'description' => 'Add the type of the company.',
              'type' => 'varchar',
              'length' => '255',
              'not null' => TRUE,
              'default' => '',
              ),
              'color_hexa' => array(
              'description' => 'Color of the user in Graph',
              'type' => 'varchar',
              'length' => '255',
              'not null' => TRUE,
              'default' => '',
              ),
          ),
        'indexes' => array(
          'first_name' => array('first_name'),
          'last_name' => array('last_name'),
        ),
      );
    }
    
    /**
    * {@inheritdoc}
    */
    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
        $properties['first_name'] = DataDefinition::create('string')->setLabel(t('First name'));
        $properties['last_name'] = DataDefinition::create('string')->setLabel(t('Last name'));
        $properties['type_name'] = DataDefinition::create('string')->setLabel(t('Webhosting Service Execution'));
        $properties['color_hexa'] = DataDefinition::create('string')->setLabel(t('Select Color'));
        return $properties;
    }
    
}