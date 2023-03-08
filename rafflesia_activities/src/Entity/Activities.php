<?php

/**
 * @file
 * Contains \Drupal\rafflesia_activities\Entity\Activities.
 */

namespace Drupal\rafflesia_activities\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\rafflesia_activities\ActivitiesInterface;

/**
* Defines the profile type entity class.
*
*@ContentEntityType(
*   id = "activity",
*   label = @Translation("Activity type"),
*   handlers = {
*     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
*     "list_builder" = "Drupal\rafflesia_activities\Entity\Controller\ActivityListBuilder",
*     "views_data" = "Drupal\views\EntityViewsData",
*     "form" = {
*       "add" = "Drupal\rafflesia_activities\Form\ActivitiesForm",
*       "edit" = "Drupal\rafflesia_activities\Form\ActivitiesForm",
*       "delete" = "Drupal\rafflesia_activities\Form\ActivitiesDeleteForm",
*     },
*     "access" = "Drupal\rafflesia_activities\ActivitiesAccessControlHandler",
*   },
*   base_table = "rafflesia_activities",
*   fieldable = TRUE,
*   admin_permission = "administer activities",
*   entity_keys = {
*     "id" = "id",
*     "uuid" = "uuid"
*   },
*   links = {
*     "canonical" = "/activities/{activity}",
*     "edit-form" = "/activities/{activity}/edit",
*     "delete-form" = "/activities/{activity}/delete",
*     "collection" = "/activities/list"
*   },
*   field_ui_base_route = "entity.activity_type.edit_form",
* )
*/

class Activities extends ContentEntityBase implements ActivitiesInterface {

  /**
   * {@inheritdoc}
   */
  
  public function getChangedTime() {
    return $this->get('created')->value;
  }
  
  
  /**
   * {@inheritdoc}
   */

  public function setChangedTime($timestamp) {
    $this->set('changed', $timestamp);
  }
  
   /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }
  
  
   /**
   * {@inheritdoc}
   */
  public function getChangedTimeAcrossTranslations()  {
    $changed = $this->getUntranslated()->getChangedTime();
    foreach ($this->getTranslationLanguages(FALSE) as $language)    {
      $translation_changed = $this->getTranslation($language->getId())->getChangedTime();
      $changed = max($translation_changed, $changed);
    }
    return $changed;
  }
  
  
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
   
    // set the id
    
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('id'))
      ->setDescription(t('The ID of the Activity entity.'))
      ->setReadOnly(TRUE);
    
    // set the uuid
    
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('uuid'))
      ->setDescription(t('The UUID of Activities'))
      ->setReadOnly(TRUE);
    
    // langcode
    
    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of Activity entity.'));
    
    // changed
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));
    
    // created
    
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));
    
    // Define for the module activity name 
    
    /* Activity Name */
    
    $fields['activity_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Activity Name'))
      ->setDescription(t('The Activity Name of the Activity Entity.'))
      ->setRequired(TRUE) 
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    /* User */
    
    $fields['uid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('User Id'))
      ->setDescription(t('The ID of the user that added the activity'))
      ->setReadOnly(TRUE);
    
    /* Name of the user */
    
     $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('name'))
      ->setDescription(t('Name of the User Added The Activity'))
      ->setReadOnly(TRUE)
       ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
      ));
     
    return $fields;
   
  }
  
}
