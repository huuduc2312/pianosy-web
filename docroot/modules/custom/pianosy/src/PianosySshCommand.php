<?php

namespace Drupal\pianosy;

use Drupal\drd\SshPhp;
use Drupal\drd\SshLibSec;
use Drupal\drd\Entity\Host;
use Drupal\Component\Serialization\Json;

/**
 * Class PianosySshCommand.
 *
 * @package Drupal\pianosy
 */
class PianosySshCommand
{
  const Pianosy_HOST_ID = 1;
  
  const Pianosy_SHELL_CREATE_APP = 1;
    
    /**
   * DRD host entity.
   *
   * @var \Drupal\drd\Entity\Host
   */
  protected $host;

  /**
   * SSH connection.
   *
   * @var SshInterface
   */
  protected $connection;

  /**
   * SSH command.
   *
   * @var string
   */
  protected $command = '';

  /**
   * Set the DRD host entity.
   *
   * @param \Drupal\drd\Entity\Host $host
   *   The host entity.
   *
   * @return $this
   */
  public function setHost(Host $host)
  {
    $this->host = $host;
    $this->initConnection();
    return $this;
  }

  /**
   * Set the SSH command.
   *
   * @param string $command
   *   The command.
   *
   * @return $this
   */
  public function setCommand($command)
  {
    $this->command = $command;
    return $this;
  }

  /**
   * Get the SSH output.
   *
   * @return string
   *   The output.
   */
  public function getOutput()
  {
    return $this->connection->getOutput();
  }

  /**
   * Get the SSH Json output.
   *
   * @return string
   *   The Json decoded output.
   */
  public function getJsonOutput()
  {
    try {
      return Json::decode($this->connection->getOutput());
    } catch (\Exception $ex) {
      return false;
    }
  }

  /**
   * Execute the SSH command.
   *
   * @return bool
   *   TRUE, if command executed successfully.
   */
  public function execute()
  {
    return $this->connection->exec($this->command);
  }

  /**
   * Initialize SSH connection.
   */
  private function initConnection()
  {
    if (empty($this->host->supportsSsh())) {
      throw new \Exception('SSH for this host is disabled.');
    }

    $settings = $this->host->getSshSettings();

    if (!empty($settings['host'])) {
      $domain = $settings['host'];
    } 
    else {
      $app_config = \Drupal::config('pianosy.settings');
      $domain = $app_config->get('pianosy_domain');
    }

    if (!function_exists('ssh2_connect')) {
      $this->connection = new SshPhp(
        $domain,
        $settings['port'],
        $settings['auth']['mode'],
        $settings['auth']['username'],
        $settings['auth']['password'],
        $settings['auth']['file_public_key'],
        $settings['auth']['file_private_key'],
        $settings['auth']['key_secret']
      );
    } else {
      $this->connection = new SshLibSec(
        $domain,
        $settings['port'],
        $settings['auth']['mode'],
        $settings['auth']['username'],
        $settings['auth']['password'],
        $settings['auth']['file_public_key'],
        $settings['auth']['file_private_key'],
        $settings['auth']['key_secret']
      );
    }
    if (!$this->connection->login()) {
      throw new \Exception('SSH authentication failed.');
    }
  }

}
