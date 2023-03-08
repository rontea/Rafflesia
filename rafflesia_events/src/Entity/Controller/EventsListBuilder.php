<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\Entity\Controller\EventsListBuilder.
 */

namespace Drupal\rafflesia_events\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;


class EventsListBuilder extends EntityListBuilder {
  
  public function render() {
    $build['description'] = [
      '#markup' => $this->t('The event.', array(
        '@adminlink' => \Drupal::urlGenerator()
          ->generateFromRoute('entity.events_settings'),
      )),
    ];

    $build += parent::render();
    return $build;
  }
  
  public function buildHeader() {
    
    $header['id'] = $this->t('ID');
    $header['event_name'] = $this->t('Event Name');
    $header['event_date'] = $this->t('Event Date');
    $header['name'] = $this->t('Event Author');
    return $header + parent::buildHeader();
    
  }
  
  public function buildRow(EntityInterface $entity) {
     
    $row['id'] = $entity->id();
    $row['event_name'] = $entity->event_name->value;
    $row['event_date'] = $entity->event_date->value;
    $row['name'] = $entity->name->value;
     
    return $row + parent::buildRow($entity);
  }
  
  
  
}
