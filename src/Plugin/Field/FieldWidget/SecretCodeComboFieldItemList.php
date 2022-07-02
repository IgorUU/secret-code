<?php

namespace Drupal\secret_code\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

class SecretCodeComboFieldItemList extends FieldItemList {

  use ComputedItemListTrait;

  protected function computeValue() {
    $entity = $this->getEntity();

    if (!$entity->get('extra_secret_code')->isEmpty() && !$entity->get('secret_code')->isEmpty()) {
      $secret_code = $entity->get('secret_code')->value;
      $prefix = substr($secret_code, 0, 3);
      $suffix = $entity->get('extra_secret_code')->value_first;
      $suffix += $entity->get('extra_secret_code')->value_second;
      $suffix += $entity->get('extra_secret_code')->value_third;
      $secret_code_combo = $prefix . $suffix;
      $this->list[0] = $this->createItem(0, $secret_code_combo);
    }
  }
}
