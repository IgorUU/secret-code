<?php

namespace Drupal\secret_code;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the secret code entity type.
 */
class SecretCodeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view secret code');

      case 'update':
        return AccessResult::allowedIfHasPermissions(
          $account,
          ['edit secret code', 'administer secret code'],
          'OR',
        );

      case 'delete':
        return AccessResult::allowedIfHasPermissions(
          $account,
          ['delete secret code', 'administer secret code'],
          'OR',
        );

      case 'approve':
        return AccessResult::allowedIfHasPermission($account, 'approve secret code');

      default:
        // No opinion.
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions(
      $account,
      ['create secret code', 'administer secret code'],
      'OR',
    );
  }

}
