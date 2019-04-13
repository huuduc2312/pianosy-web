<?php

/**
 * @file
 * Contains \Drupal\wm_app\Form\WMAppForm.
 */
namespace Drupal\wm_app\Form;

use Drupal\drd\Entity\Host;
use \Drupal\node\Entity\Node;
use \Drupal\user\Entity\User;
use Drupal\Core\Form\FormBase;
use Drupal\wm_app\WMSshCommand;
use Symfony\Component\Yaml\Parser;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\field\Entity\FieldStorageConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;

class WMAppForm extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'wm_app_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $app_config = \Drupal::config('wm_app.settings');
    // $debug_enabled = $app_config->get('wm_app_debug');

    $distributions = wm_app_get_drupal_distribution();
    $dist_keys = array_keys($distributions);
    $latest = array_shift($dist_keys);

    $form['app_info'] = array(
      '#type' => 'fieldset',
      '#collapsible' => false,
    );
    $form['app_info']['app_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Application name'),
      '#description' => $this->t("Permanent name used for your app's default URL and code repository. You cannot change your app name after creation."),
      '#required' => true,
    );
    $form['app_info']['app_version'] = array(
      '#type' => 'select',
      '#title' => $this->t('Distribution'),
      '#options' => $distributions,
      '#default_value' => $latest,
      '#required' => true,
    );
    $form['app_info']['description'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#maxlength' => 128,
      // '#default_value' => $this->t('WM demo create app'),
      '#required' => true,
    );
    $build_hosts = wm_app_get_hosts_by_type('build');
    $build_host_ids = array_keys($build_hosts);
    $form['app_info']['host'] = array(
      '#type' => 'select',
      '#title' => $this->t('Select server for your application'),
      '#options' => $build_hosts,
      '#default_value' => reset($build_host_ids),
      '#required' => true,
    );
    
    $form['app_info']['quick_create'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('By pass drupal installer ?'),
      '#default_value' => false,
    );

    $form['app_info']['git_info'] = array(
      '#type' => 'fieldset',
      '#collapsible' => false,
      '#title' => $this->t('Git setting'),
    );
    
    $form['app_info']['git_info']['create_repo'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Create git repo ?'),
      // '#default_value' => true,
    );
    
    $form['app_info']['git_info']['visibility_level'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Visibility Level'),
      '#options' => array(
        'private' => $this->t('Private'),
        'internal' => $this->t('Internal'),
        'public' => $this->t('Public'),
      ),
      '#default_value' => 'private',
      '#states' => array(
        'visible' => array(
          ':input[name="create_repo"]' => array('checked' => TRUE),
        ),
      )
    );
    $deploy_hosts = wm_app_get_hosts_by_type('deploy');
    $deploy_host_ids = array_keys($deploy_hosts);
    $form['app_info']['host_deploy'] = array(
      '#type' => 'select',
      '#title' => $this->t('Select server to deploy your application'),
      '#options' => $deploy_hosts,
      '#default_value' => reset($deploy_host_ids),
      // '#required' => true,
      '#states' => array(
        'visible' => array(
          ':input[name="create_repo"]' => array('checked' => true),
        ),
      )
    );
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Create'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    if ($values['app_name']) {
      if (!Parser::preg_match("(^[a-z0-9]+$)", $values['app_name'])){
        $form_state->setErrorByName('app_name', $this->t('Application name is lower character and number only'));
      }
      if (_check_duplicate_title($values['app_name'], 'application')) {
        $form_state->setErrorByName('app_name', $this->t('Application name is exists'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    if (isset($values['create_repo']) && $values['create_repo']) {
      $description = empty($values['description']) ? 'WM demo create app' : $values['description'];
      $git_project = wm_app_create_gitlab_repo($values['app_name'], $description, $values['visibility_level']);
    }
    $this->wm_app_create($values, $git_project);
    $path = \Drupal::url('<front>');
    $response = new RedirectResponse($path);
    // wait git deploy app to dev
    sleep(15);
    $response->send();
    $this->messenger()->addMessage($this->t('Congratulations, You created @name!', array('@name' => $form_state->getValue('app_name'))));
  }

  /**
   * Undocumented function
   *
   * @param [type] $values
   * @return void
   */
  public function wm_app_create($values, $git_project = null)
  {
    $cu = \Drupal::currentUser();
    $app_config = \Drupal::config('wm_app.settings');
    
    $tiers = wm_app_load_active_tiers();

    $host = Host::load($values['host']);
    if ($values['host'] == $values['host_deploy']){
      $host_deploy = $host;
    }
    else {
      $host_deploy = Host::load($values['host_deploy']);
    }

    $sids = array();
    foreach ($tiers as $tier) {
      if ($tier->getName() == WMSshCommand::TIER_BUILD) {
        $sids[] = $this->wm_app_setup_app($host, $tier, $values, $git_project, $host_deploy);
      }
      else if($values['create_repo']) {
        $tier_name = strtolower($tier->getName());
        $this->wm_app_create_vhost_environment($host_deploy, $values['app_name'], $tier_name, $app_config);
        $sids[] = $this->wm_app_create_environment($host_deploy, $tier, $values, $app_config);
      }
    }

    $app = [
      'type' => 'application',
      'uid' => $cu->id(),
      'title' => $values['app_name'],
      'field_wm_environments' => $sids,
      'field_wm_host' => $host->id(),      
      'body' => ['value' => $values['description'], 'format' => 'full_html'],
    ];

    if (!empty($git_project)) {
      $rid = $this->wm_app_create_repository($git_project);
      $app['field_git_info'] = [["target_id" => $rid]];
    }

    $node = Node::create($app);

    $node->save();
    //author is member
    $this->wm_app_create_member($node);
    //ref application to environments
    $this->wm_app_environments_ref_app($sids, $node->id());
    
    return $node->id();
  }

  public function wm_app_create_repository($git_project) {
    $cu = \Drupal::currentUser();
    $repo = array(
      'type' => 'repository',
      'uid' => $cu->id(),
      'title' => $git_project->__get('name'),
      'field_git_project_id' => $git_project->__get('id'),
      'field_git_ssh_url' => $git_project->__get('ssh_url_to_repo'),
      'field_git_web_url' => $git_project->__get('web_url'),
      // 'field_runners_token' => $git_project->__get('runners_token'),
      'body' => ['value' => $git_project->__get('description'), 'format' => 'full_html'],
    );
    $node = Node::create($repo);
    $node->save();
    return $node->id();
  }

  public function wm_app_create_member($app)
  {
    $cu = \Drupal::currentUser();
    $title = $app->getTitle() . ' has ' . $cu->getDisplayName() . ' member';
    $member = array(
      'type' => 'member',
      'uid' => $cu->id(),
      'title' => $title,
      'field_user' => [["target_id" => $cu->id()]],
      'field_application' => [["target_id" => $app->id()]],
    );
    $node = Node::create($member);
    $node->save();
    return $node->id();
  }

  /**
   * Undocumented function
   *
   * @param [type] $values
   * @param [type] $tier
   * @param [type] $host
   * @return void
   */
  public function wm_app_create_environment($host, $tier, $values, $app_config)
  {
    $cu = \Drupal::currentUser();

    $state_title = $values['app_name'] . '_' . strtolower($tier->getName());
    
    if ($tier->getName() == WMSshCommand::TIER_BUILD) {
      $domain = wm_app_create_user_domain($app_config, $host, $cu->getDisplayName(), $values);
      $db_name = $cu->getDisplayName() . '_' . $values['app_name'];
    }
    else {
      $settings = $host->getSshSettings();
      $user_des = $settings['auth']['username'];
      $domain = wm_app_create_tier_domain($app_config, $host, $tier, $values);
      $db_name = $user_des . '_' . $values['app_name'] . strtolower($tier->getName());
    }
    //when is database created?
    $db_id = $this->wm_app_add_database($db_name);

    $node = Node::create(array(
      'type' => 'environment',
      'uid' => $cu->id(),
      'title' => $state_title,
      'field_wm_host' => $host->id(),
      'field_wm_domain' => array(
        "uri" => isset($domain) ? $domain : null,
        "title" => isset($state_title) ? $state_title : null,
        "options" => ["target" => "_blank"]
      ),
      'field_php_version' => array('value' => '7.1'),
      'field_wm_tier' => array(['target_id' => $tier->id()]),
      'field_databases' => array(['target_id' => $db_id]),
      'field_active_database' => array(['target_id' => $db_id]),
      'body' => ['value' => '', 'format' => 'full_html']
    ));

    $node->save();
    return $node->id();
  }

  /**
   * Undocumented function
   *
   * @param [type] $host
   * @param [type] $tier
   * @param [type] $values
   * @param [type] $git_project
   * @return void
   */
  public function wm_app_setup_app($host, $tier, $values, $git_project = null, $host_deploy) {
    $params = array();
    $cu = \Drupal::currentUser();
    $app_config = \Drupal::config('wm_app.settings');
    $executable = $app_config->get('wm_app_shell');
    $params['-u'] = $cu->getDisplayName();
    $params['-v'] = $values['app_version'];
    $params['-a'] = $values['app_name'];
    $params['-t'] = strtolower($tier->getName());
    $params['-e'] = WMSshCommand::WM_SHELL_CREATE_APP;
    $params['-q'] = isset($values['quick_create']) ? $values['quick_create'] : 0;
    if (!empty($git_project)) {
      $current_user = User::load($cu->id());
      $settings = $host_deploy->getSshSettings();
      $params['-m'] = $cu->getEmail();
      $params['-n'] = _get_user_full_name($current_user);
      $params['-g'] = $git_project->__get('ssh_url_to_repo');
      $params['-d'] = $settings['auth']['username'];
    }

    $msg_title = 'wm_app setup ' . $values['app_name'];
    $output = wm_app_execute_ssh_command($host, $executable, $params, $msg_title);
    
    $sid = $this->wm_app_create_environment($host, $tier, $values, $app_config);
    return $sid;
  }

  /**
   * Undocumented function
   *
   * @param [type] $host_deploy
   * @param [type] $tier
   * @param [type] $app_name
   * @return void
   */
  public function wm_app_create_vhost_environment($host_deploy, $app_name, $tier_name, $app_config) {
    $params = array();
    $executable = $app_config->get('wm_app_deploy_shell');
    $settings = $host_deploy->getSshSettings();
    $params['-u'] = $settings['auth']['username'];
    $params['-e'] = WMSshCommand::WM_SHELL_CREATE_VHOST;
    $params['-a'] = $app_name;
    $params['-t'] = $tier_name;

    $msg_title = "wm_app create vhost $tier_name";
    $output = wm_app_execute_ssh_command($host_deploy, $executable, $params, $msg_title);
  }

  /**
   * Undocumented function
   *
   * @param [type] $backup_name
   * @param [type] $type
   * @return void
   */
  public function wm_app_add_database($db_name)
  {
    $cu = \Drupal::currentUser();

    $node = Node::create(array(
      'type' => 'database',
      'uid' => $cu->id(),
      'title' => $db_name,
    ));

    $node->save();
    return $node->id();
  }

  public function wm_app_environments_ref_app($env_ids, $app_id) {
    foreach($env_ids as $env_id) {
      $env = Node::load($env_id);
      $env->field_application->target_id = $app_id;
      $env->save();
    }
  }
}
