<?php

namespace Drupal\secret_code\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'extra_secret_code' field type.
 *
 * @FieldType(
 *   id = "extra_secret_code",
 *   label = @Translation("Extra Secret Code"),
 *   category = @Translation("General"),
 *   default_widget = "extra_secret_code_base",
 *   default_formatter = "extra_secret_code_base"
 * )
 *
 * @DCG
 * If you are implementing a single value field type you may want to inherit
 * this class form some of the field type classes provided by Drupal core.
 * Check out /core/lib/Drupal/Core/Field/Plugin/Field/FieldType directory for a
 * list of available field type implementations.
 */
class ExtraSecretCodeItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value_first = $this->get('value_first')->getValue();
    $value_second = $this->get('value_second')->getValue();
    $value_third = $this->get('value_third')->getValue();
    return $value_first === NULL || $value_first === '' || $value_second === NULL || $value_second === '' || $value_third === NULL || $value_third === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    // @DCG
    // See /core/lib/Drupal/Core/TypedData/Plugin/DataType directory for
    // available data types.
    $properties['value_first'] = DataDefinition::create('string')
      ->setLabel(t('Value first'))
      ->setRequired(TRUE);
    $properties['value_second'] = DataDefinition::create('string')
      ->setLabel(t('Value second'))
      ->setRequired(TRUE);
    $properties['value_third'] = DataDefinition::create('string')
      ->setLabel(t('Value third'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
//  public function getConstraints() {
//    $constraints = parent::getConstraints();
//
//    $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();
//
//    // @DCG Suppose our value must not be longer than 10 characters.
//    $options['value']['Length']['max'] = 10;
//
//    // @DCG
//    // See /core/lib/Drupal/Core/Validation/Plugin/Validation/Constraint
//    // directory for available constraints.
//    $constraints[] = $constraint_manager->create('ComplexData', $options);
//    return $constraints;
//  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'value_first' => [
        'type' => 'varchar',
        'not null' => FALSE,
        'description' => 'First value',
        'length' => 2,
      ],
      'value_second' => [
        'type' => 'varchar',
        'not null' => FALSE,
        'description' => 'Second value',
        'length' => 3,
      ],
      'value_third' => [
        'type' => 'varchar',
        'not null' => FALSE,
        'description' => 'Third value',
        'length' => 2,
      ],
    ];

    $schema = [
      'columns' => $columns,
      // @DCG Add indexes here if necessary.
    ];

    return $schema;
  }

}
