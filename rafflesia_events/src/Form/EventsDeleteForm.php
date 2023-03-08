<?php

/**
 * @file
 * Contains \Drupal\rafflesia_events\Form\EventsDeleteForm.
 */

namespace Drupal\rafflesia_events\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class EventsDeleteForm extends ContentEntityConfirmFormBase {
  
  /**
   * {@inheritdoc}
   */
  
  public function getCancelUrl() {
    return new Url('entity.events.collection'); // collection 
  }
  
  /**
   * {@inheritdoc}
   */
  
  public function getQuestion() {
    return $this->t('Are you sure you want to delete Event: %name?', array('%name' => $this->entity->label()));
  }
  
  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }
  /**
   * {@inheritdoc}
   *
   * Delete the entity and log the event. log() replaces the watchdog.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->delete();

    \Drupal::logger('content_entity')->notice('@type: deleted %title.',
      array(
        '@type' => $this->entity->bundle(),
        '%title' => $this->entity->label(),
      ));
    $form_state->setRedirect('entity.events.collection');
  }

}
