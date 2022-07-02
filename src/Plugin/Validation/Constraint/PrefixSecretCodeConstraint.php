<?php

namespace Drupal\secret_code\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Provides a PrefixSecretCodeConstraint constraint.
 *
 * @Constraint(
 *   id = "PrefixSecretCodeConstraint",
 *   label = @Translation("PrefixSecretCodeConstraint", context = "Validation"),
 * )
 *
 * @DCG
 * To apply this constraint on third party field types. Implement
 * hook_field_info_alter().
 */
class PrefixSecretCodeConstraint extends Constraint {

  public $errorMessage = 'The secret code need to start with @prefixes';
  public $prefixes = [];

}
