<?php

/**
 * @file
 * Contains \Drupal\pianosy\Form\PianosyForm.
 */
namespace Drupal\pianosy\Form;

use Drupal\drd\Entity\Host;
use \Drupal\node\Entity\Node;
use \Drupal\user\Entity\User;
use Drupal\Core\Form\FormBase;
use Drupal\pianosy\PianosySshCommand;
use Symfony\Component\Yaml\Parser;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\field\Entity\FieldStorageConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PianosyForm extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'pianosy_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $pianosy_config = \Drupal::config('pianosy.settings');

    $form['sheet_img'] = array(
      '#type' => 'managed_file',
      '#title' => $this->t('Sheet'),
      '#upload_location' => 'public://pianosy',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg'],
      ],
      '#description' => $this->t("Sheet image"),
      // '#required' => true,
    );
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Execute'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    // $values = $form_state->getValues();
    // if ($values['app_name']) {
    // }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    $fid = $values['sheet_img']; 
    $file_obj = file_load(reset($fid));

    print_r(reset($file_obj)); exit;
    $this->pianosy_execute($values);
    
    $path = \Drupal::url('<front>');
    $response = new RedirectResponse($path);
    sleep(15);
    $response->send();
    $this->messenger()->addMessage($this->t('Executed!'));
  }

  public function pianosy_execute($values) {
    $settings = \Drupal::config('pianosy.settings');
    $executable = $settings->get('pianosy_shell');
    $host_type = 'build';
    $conditions = [
      [
        'key' => 'field_host_type',
        'value' => $host_type
      ]
    ];
    $drd_hosts = pianosy_load_drd_hosts($conditions);
    $host = reset($drd_hosts);
    $params = array();

    $msg_title = 'pianosy execute';
    $output = pianosy_execute_ssh_command($host, $executable, $params, $msg_title);
  }
}
