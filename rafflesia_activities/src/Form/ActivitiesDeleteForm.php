<?php

/**
 * @file
 * Contains \Drupal\rafflesia_activities\Form\ActivitiesDeleteForm.
 */


namespace Drupal\rafflesia_activities\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Description of ActivitiesDeleteForm
 *
 * @author rontea
 */

/**
 * Provides a form for deleting a content_entity_example entity.
 *
 * @ingroup rafflesia_activities
 */


class ActivitiesDeleteForm extends ContentEntityConfirmFormBase {
  /**
   * {@inheritdoc}
   */
  
  public function getCancelUrl() {
    return new Url('entity.activities.collection');
  }
  
  /**
   * {@inheritdoc}
   */
  
  public function getQuestion() {
    return $this->t('Are you sure you want to delete Activity: %name?', array('%name' => $this->entity->label()));
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

    \Drupal::logger('content_entity_example')->notice('@type: deleted %title.',
      array(
        '@type' => $this->entity->bundle(),
        '%title' => $this->entity->label(),
      ));
    $form_state->setRedirect('entity.activities.collection');
  }

}
