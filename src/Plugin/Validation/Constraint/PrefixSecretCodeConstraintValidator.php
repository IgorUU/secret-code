<?php

namespace Drupal\secret_code\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the PrefixSecretCodeConstraint constraint.
 */
class PrefixSecretCodeConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($item, Constraint $constraint) {

    $value = $item->value;
    // @DCG Validate the value here.
    foreach ($constraint->prefixes as $prefix) {
      if (str_starts_with($value, $prefix)) {
        return;
      }
    }
    $this->context->addViolation($constraint->errorMessage, [
      '@prefixes' => implode(', ', $constraint->prefixes)
    ]);
  }
}
