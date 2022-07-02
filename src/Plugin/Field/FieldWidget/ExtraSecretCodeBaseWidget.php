<?php

namespace Drupal\secret_code\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'extra_secret_code_base' field widget.
 *
 * @FieldWidget(
 *   id = "extra_secret_code_base",
 *   label = @Translation("ExtraSecretCodeWidget"),
 *   field_types = {"extra_secret_code"},
 * )
 */
class ExtraSecretCodeBaseWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'choice' => 'select',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['choice'] = [
      '#type' => 'radios',
      '#title' => $this->t('How do you want it?'),
      '#options' => [
        'number' => $this->t('Number'),
        'select' => $this->t('Select')
      ],
      '#default_value' => $this->getSetting('choice'),
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary[] = $this->t('Choice: @choice', ['@choice' => $this->getSetting('choice')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $options_two_digits = [];
    $options_three_digits = [];

    for ($i = 1; $i <= 99; $i++) {
      $options_two_digits[$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
    }

    for ($i = 1; $i <= 999; $i++) {
      $options_three_digits[$i] = str_pad($i, 3, '0', STR_PAD_LEFT);
    }

    if ($this->getSetting('choice') !== 'select') {
      $element['#type'] = 'item';
      $element['value_first'] = [
        '#type' => 'number',
        '#title' => 'First value',
        '#default_value' => $items[$delta]->value ?? NULL,
        '#min' => 0,
        '#max' => 99,
      ];
      $element['value_second'] = [
        '#type' => 'number',
        '#title' => 'Second value',
        '#default_value' => $items[$delta]->value ?? NULL,
        '#min' => 0,
        '#max' => 999,
      ];
      $element['value_third'] = [
        '#type' => 'number',
        '#title' => 'Third value',
        '#default_value' => $items[$delta]->value ?? NULL,
        '#min' => 0,
        '#max' => 99,
      ];
      return $element;
    }

    $element['#type'] = 'item';
    $element['value_first'] = [
      '#type' => 'select',
      '#options' => [NULL => 'None'] + $options_two_digits,
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      '#required' => TRUE,
    ];
    $element['value_second'] = [
        '#type' => 'select',
        '#options' => [NULL => 'None'] + $options_three_digits,
        '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
        '#required' => TRUE,
      ];
    $element['value_third'] = [
        '#type' => 'select',
        '#options' => [NULL => 'None'] + $options_two_digits,
        '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
        '#required' => TRUE,
      ];
    return $element;
  }

}
