<?php

/**
 * @file
 * Contains \Drupal\rafflesia_activities\Entity\Controller\ActivityListBuilder.
 */

namespace Drupal\rafflesia_activities\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;
use Drupal\rafflesia\RafflesiaUserInformation;

/**
 * Description of ActivityListBuilder
 *
 * @author rontea
 */


/**
 * Provides a list controller for content_entity_example_contact entity.
 *
 * @ingroup rafflesia_activities
 */

class ActivityListBuilder extends EntityListBuilder {
  
  public function buildHeader() {
    
    $header['id'] = $this->t('Activity ID');
    $header['activity_name'] = $this->t('Activity Name');
    $header['name'] = $this->t('Creator');
    return $header + parent::buildHeader();
    
  }
  
  public function buildRow(EntityInterface $entity) {
    $row['title'] = $entity->id();
    $row['activity_name'] = $entity->activity_name->value;
    $row['name'] = $entity->name->value;
    
    return $row + parent::buildRow($entity);
  }
  
  
  
}
