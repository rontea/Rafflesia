<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\Entity\Controller\CompanyListBuilder.
 */

namespace Drupal\rafflesia_events\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;

class CompanyListBuilder extends EntityListBuilder {
  
   public function render() {
    $build['description'] = [
      '#markup' => $this->t('Sample.', array(
        '@adminlink' => \Drupal::urlGenerator()
          ->generateFromRoute('entity.company_settings'),
      )),
    ];

    $build += parent::render();
    return $build;
  }
  
  public function buildHeader() {
    
    $header['id'] = $this->t('ID');
    $header['company_name'] = $this->t('Company Name');
    $header['company_ticker'] = $this->t('Company Ticker Symbol');
    return $header + parent::buildHeader();
    
  }
  
  public function buildRow(EntityInterface $entity) {
    $row['title'] = $entity->id();
    $row['company_name'] = $entity->company_name->value;
    $row['company_ticker'] = $entity->company_ticker->value;
    
    return $row + parent::buildRow($entity);
  }
  
  
  
}
