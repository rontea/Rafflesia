<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\Entity\Events.
 */

namespace Drupal\rafflesia_events\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\rafflesia_events\EventsInterface;

/**
* Defines the profile type entity class.
*
*@ContentEntityType(
*   id = "events",
*   label = @Translation("Event Entity List"),
*   handlers = {
*     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
*     "list_builder" = "Drupal\rafflesia_events\Entity\Controller\EventsListBuilder",
*     "views_data" = "Drupal\views\EntityViewsData",
*     "form" = {
*       "add" = "Drupal\rafflesia_events\Form\EventsForm",
*       "edit" = "Drupal\rafflesia_events\Form\EventsForm",
*       "delete" = "Drupal\rafflesia_events\Form\EventsDeleteForm",
*     },
*     "access" = "Drupal\rafflesia_events\EventsAccessControlHandler",
*   },
*   base_table = "rafflesia_events",
*   fieldable = TRUE,
*   admin_permission = "administer events",
*   entity_keys = {
*     "id" = "id",
*     "uuid" = "uuid"
*   },
*   links = {
*     "canonical" = "/events/{events}",
*     "edit-form" = "/events/{events}/edit",
*     "delete-form" = "/events/{events}/delete",
*     "collection" = "/events/list"
*   },
*   field_ui_base_route = "entity.events_settings",
* )
*/

class Events extends ContentEntityBase implements EventsInterface {

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
      ->setDescription(t('The ID of the Events entity.'))
      ->setReadOnly(TRUE);
    
    // set the uuid
    
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('uuid'))
      ->setDescription(t('The UUID of Events'))
      ->setReadOnly(TRUE);
    
    // langcode
    
    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of Events entity.'));
    
    // changed
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));
    
    // created
    
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));
    
   /* Define the Entity */ 
    
    /* Event Name */
    
    $fields['event_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Event Name'))
      ->setDescription(t('The Event Name.'))
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
    
    /* Event Date */
    $fields['event_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Event Date'))
      ->setDescription(t('The Event Date'))
      ->setRequired(TRUE) 
      ->setSettings(array(
        'default_value' => '',
        'text_processing' => 0,
      ));
    
    /* Event Date */
    $fields['event_time'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Event Time'))
      ->setDescription(t('The Event Time'))
      ->setRequired(TRUE) 
      ->setSettings(array(
        'default_value' => '',
        'text_processing' => 0,
      ));
        
    
    /* User */
    
    $fields['uid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('User Id'))
      ->setDescription(t('The ID of the user that added the Company'))
      ->setReadOnly(TRUE);
    
    /* Name of the user */
    
     $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('name'))
      ->setDescription(t('Name of the User Added The Events'))
      ->setReadOnly(TRUE)
       ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
      ));
     
    return $fields;
   
  }
  
}
