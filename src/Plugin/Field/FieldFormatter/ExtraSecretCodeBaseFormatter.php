<?php

namespace Drupal\secret_code\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ExtraSecretCodeFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "extra_secret_code_base",
 *   label = @Translation("ExtraSecretCodeFormatter"),
 *   field_types = {
 *     "extra_secret_code"
 *   }
 * )
 */
class ExtraSecretCodeBaseFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'foo' => 'bar',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $elements['foo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Foo'),
      '#default_value' => $this->getSetting('foo'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary[] = $this->t('Foo: @foo', ['@foo' => $this->getSetting('foo')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#type' => 'container',
        'template' => [
          '#type' => 'inline_template',
          '#template' => '<h4 style="display: inline">{{ item.value_first }}</h4> --- <h1 style="display: inline">{{ item.value_second }}</h1> --- <h4 style="display: inline">{{ item.value_third }}</h4>',
          '#context' => [
            'item' => $item
          ],
        ],
        '#attributes' => ['class' => 'extra-secret-code']
      ];
    }

    return $element;
  }

}
