<?php

namespace Drupal\secret_code\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\secret_code\Plugin\Field\FieldWidget\SecretCodeComboFieldItemList;
use Drupal\secret_code\SecretCodeInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the secret code entity class.
 *
 * @ContentEntityType(
 *   id = "secret_code",
 *   label = @Translation("Secret Code"),
 *   label_collection = @Translation("Secret Codes"),
 *   label_singular = @Translation("secret code"),
 *   label_plural = @Translation("secret codes"),
 *   label_count = @PluralTranslation(
 *     singular = "@count secret codes",
 *     plural = "@count secret codes",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\secret_code\SecretCodeListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\secret_code\SecretCodeAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\secret_code\Form\SecretCodeForm",
 *       "edit" = "Drupal\secret_code\Form\SecretCodeForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "secret_code",
 *   admin_permission = "administer secret code",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/secret-code",
 *     "add-form" = "/secret-code/add",
 *     "canonical" = "/secret-code/{secret_code}",
 *     "edit-form" = "/secret-code/{secret_code}/edit",
 *     "delete-form" = "/secret-code/{secret_code}/delete",
 *   },
 *   field_ui_base_route = "entity.secret_code.settings",
 * )
 */
class SecretCode extends ContentEntityBase implements SecretCodeInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(static::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the secret code was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the secret code was last edited.'));

    $fields['secret_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Secret Code'))
      ->setDescription(t('Please enter your secret code'))
      ->setSettings([
        'max_length' => '80',
        'is_ascii' => FALSE,
        'case_sensitive' => FALSE,
      ])
      ->addConstraint('PrefixSecretCodeConstraint', [
        'prefixes' => ['KA-', 'KN-']
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['extra_secret_code'] = BaseFieldDefinition::create('extra_secret_code')
      ->setLabel(t('Extra Secret Code'))
      ->setDescription(t('This is a field for extra secret code'))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['secret_code_combo'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Secret code combo (computed field)'))
      ->setDescription(t('This field was created in practicing purposes.'))
      ->setComputed(TRUE)
      ->setClass(SecretCodeComboFieldItemList::class)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
