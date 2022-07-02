<?php

namespace Drupal\secret_code\Routing;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider;
use Symfony\Component\Routing\Route;

class ScHtmlRouteProvider extends DefaultHtmlRouteProvider {

  function getRoutes(EntityTypeInterface $entity_type) {
    $collection = parent::getRoutes($entity_type);
    $entity_type_id = $entity_type->id();
    $collection->add("entity.{$entity_type_id}.confirm", $this->getApprovementFormRoute($entity_type));
    return $collection;
  }

  function getApprovementFormRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->hasLinkTemplate('confirm')) {
      $entity_type_id = $entity_type->id();
      $route = new Route($entity_type->getLinkTemplate('confirm'));
    }
    $route
      ->setDefaults([
        '_form' => "\Drupal\secret_code\Form\ScApprovementForm"
      ])
      ->setRequirement('_entity_access',"{$entity_type_id}.approve")
      ->setOption('parameters', [
        $entity_type_id => ['type' => 'entity:' . $entity_type_id],
      ])
      ->setRequirement('action', 'confirm|reject'); // This part refers to validating route parameters. https://www.drupal.org/docs/8/api/routing-system/parameters-in-routes/validate-route-parameters
    return $route;
  }

}
