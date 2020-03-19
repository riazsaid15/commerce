<?php

namespace Drupal\earnpoints\EventSubscriber;

// Use Drupal\earnpoints\Plugin\Commerce\CheckoutPane\EarnPoints;.
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Class OrderCompleteSubscriber.
 *
 * @package Drupal\earnpoints
 */
class OrderCompleteSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  private $order;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   */
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   *
   * Get Subscribed Events.
   *
   * @return array
   */
  public static function getSubscribedEvents() {
    $events['commerce_order.place.post_transition'] = ['orderCompleteHandler'];
    return $events;
  }

  /**
   * This method is called whenever the commerce_order.place.post_transition
   * event is dispatched.
   *
   * @param \Drupal\state_machine\Event\WorkflowTransitionEvent $event
   */
  public function orderCompleteHandler(WorkflowTransitionEvent $event) {

    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $event->getEntity();

    $userPoints = 0;
    $currentAdjustments = $order->getAdjustments();

    $this->orderInfo();
    $this->order->getData('earnuserpoints');

  }

}
