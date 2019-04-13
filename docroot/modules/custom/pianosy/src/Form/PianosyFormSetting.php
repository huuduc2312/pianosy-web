<?php

namespace Drupal\pianosy\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class PianosyFormSetting extends ConfigFormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'pianosy_setting_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('pianosy.settings');

    $form['executable'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Pianosy Executable'),
      '#maxlength' => 128,
      '#default_value' => $config->get('pianosy_shell'),
      '#description' => $this->t('Path to pianosy shell file'),
      '#required' => true
    );

    $form['domain'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Host'),
      '#default_value' => $config->get('pianosy_domain'),
      '#description' => $this->t('IP or Domain'),
      '#required' => true
    );

    $form['debug'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Debug mode'),
      '#default_value' => false,
      '#description' => $this->t('Debug form and log message'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $executable = $form_state->getValue('executable');
    if (empty($executable)) {
      $form_state->setErrorByName('executable', $this->t('Please add path to pianosy shell file.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $config = $this->config('pianosy.settings');
    $config->set('pianosy_shell', $form_state->getValue('executable'));
    $config->set('pianosy_domain', $form_state->getValue('domain'));
    $config->set('pianosy_debug', $form_state->getValue('debug'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'pianosy.settings',
    ];
  }
}
