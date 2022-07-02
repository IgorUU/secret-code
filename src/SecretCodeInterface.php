<?php

namespace Drupal\secret_code;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a secret code entity type.
 */
interface SecretCodeInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
