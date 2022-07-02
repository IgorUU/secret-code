<?php

namespace Drupal\secret_code\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\secret_code\Entity\SecretCode;

class ScApprovementForm extends ConfirmFormBase {

  protected $action;

  /**
   * @var \Drupal\secret_code\Entity\SecretCode
   */
  protected $secretCode;

  function buildForm(array $form, FormStateInterface $form_state, $action = NULL, SecretCode $secret_code = NULL) {
    $this->action = $action;
    $this->secretCode = $secret_code;
    return parent::buildForm($form, $form_state);
  }

  /**
   * @inheritDoc
   */
  public function getQuestion() {
    if ($this->action === 'confirm') {
      return $this->t('Are you sure you want to approve this secret code?');
    }
    return $this->t('Are you sure you want to reject this secret code?');
  }

  /**
   * @inheritDoc
   */
  public function getCancelUrl() {
    return Url::fromRoute($this->getRedirectDestination()->get());
  }

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'confirmation_secret_code';
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->secretCode->set('state', $this->action)->save();
  }

}
