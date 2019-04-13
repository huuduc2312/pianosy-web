<?php

/**
 * @file
 * Contains \Drupal\pianosy\Plugin\Block\PianosyBlock.
 */
namespace Drupal\pianosy\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
/**
 * Provides a 'pianosy' block.
 *
 * @Block(
 *   id = "pianosy_block",
 *   admin_label = @Translation("Pianosy Block"),
 *   category = @Translation("Pianosy")
 * )
 */
class PianosyBlock extends BlockBase
{
  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $form = \Drupal::formBuilder()->getForm('Drupal\pianosy\Form\PianosyForm');
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  // protected function blockAccess(AccountInterface $account)
  // {
  //   return AccessResult::allowedIfHasPermission($account, 'create app');
  // }
}