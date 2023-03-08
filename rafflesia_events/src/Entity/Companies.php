<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\Entity\Companies.
 */

namespace Drupal\rafflesia_events\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\rafflesia_events\CompaniesInterface;

/**
* Defines the profile type entity class.
*
*@ContentEntityType(
*   id = "company",
*   label = @Translation("Company type"),
*   handlers = {
*     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
*     "list_builder" = "Drupal\rafflesia_events\Entity\Controller\CompanyListBuilder",
*     "views_data" = "Drupal\views\EntityViewsData",
*     "form" = {
*       "add" = "Drupal\rafflesia_events\Form\CompaniesForm",
*       "edit" = "Drupal\rafflesia_events\Form\CompaniesForm",
*       "delete" = "Drupal\rafflesia_events\Form\CompaniesDeleteForm",
*     },
*     "access" = "Drupal\rafflesia_events\CompanyAccessControlHandler",
*   },
*   base_table = "rafflesia_companies",
*   fieldable = TRUE,
*   admin_permission = "administer companies",
*   entity_keys = {
*     "id" = "id",
*     "uuid" = "uuid"
*   },
*   links = {
*     "canonical" = "/companies/{company}",
*     "edit-form" = "/companies/{company}/edit",
*     "delete-form" = "/companies/{company}/delete",
*     "collection" = "/companies/list"
*   },
*   field_ui_base_route = "entity.company_settings",
* )
*/

class Companies extends ContentEntityBase implements CompaniesInterface {

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
      ->setDescription(t('The ID of the Company entity.'))
      ->setReadOnly(TRUE);
    
    // set the uuid
    
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('uuid'))
      ->setDescription(t('The UUID of Company'))
      ->setReadOnly(TRUE);
    
    // langcode
    
    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of Company entity.'));
    
    // changed
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));
    
    // created
    
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));
    
    // Define for the module activity name 
    
    /* Company Name */
    
    $fields['company_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Company Name'))
      ->setDescription(t('The Company Name.'))
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
        'weight' => 20,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    /* Company Ticker */
    $fields['company_ticker'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Company Ticker'))
      ->setDescription(t('The Company Ticker Symbol.'))
      ->setRequired(TRUE) 
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 20,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 10,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    /* Link to Website */
    $fields['company_website'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('IR Website Link'))
      ->setDescription(t('The Company IR Website'))
      ->setRequired(TRUE) 
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 200,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 30,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'link',
        'weight' => 30,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    /* User */
    
    $fields['uid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('User Id'))
      ->setDescription(t('The ID of the user that added the Company'))
      ->setReadOnly(TRUE);
    
    /* Name of the user */
    
     $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('name'))
      ->setDescription(t('Name of the User Added The Company'))
      ->setReadOnly(TRUE)
       ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
      ));
     
    return $fields;
   
  }
  
}
