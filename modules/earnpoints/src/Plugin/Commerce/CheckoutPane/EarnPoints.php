<?php

namespace Drupal\earnpoints\Plugin\Commerce\CheckoutPane;

use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneBase;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneInterface;
use Drupal\commerce_store\Entity\Store;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_order\Adjustment;
use Drupal\commerce_price\Price;
use Drupal\user\Entity\User;

/**
 * Provides the earn points.
 *
 * @CommerceCheckoutPane(
 *   id = "earnpoints_productpoint",
 *   label = @Translation("Earn User Points"),
 *   default_step = "order_information",
 * )
 */
class EarnPoints extends CheckoutPaneBase implements CheckoutPaneInterface {

  private $user_points;

  /**
   * {@inheritdoc}
   * Build Pane Form.
   *
   * @param array $pane_form
   *   , FormStateInterface $form_state, $complete_form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param array $complete_form
   *
   * @return array
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function buildPaneForm(array $pane_form, FormStateInterface $form_state, array &$complete_form) {

    // Get user Points.
    $user_points = $this->getUserPoints();

    // Load the order & porduct points and its quantity.
    $earnPoints = $this->orderInfo();

    drupal_set_message(t('If you proceed to checkout, you will earn %earnpoints Points!', ['%earnpoints' => $earnPoints]));

    $options = [
      '0' => t('Don\'t use points'),
      '1' => t('Use all usable points'),
      '2' => t('Use Specific points'),
    ];
    if (!empty($user_points) && $user_points != 0) {
      $pane_form['userpoints_types'] = [
        '#type' => 'radios',
        '#title' => t('User Points'),
        '#options' => $options,
        '#description' => t('You have the points at the moment @points', ['@points' => $user_points]),
        '#default_value' => '0',
      ];
    }
    $pane_form['user_points_use'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Points'),
      '#default_value' => '',
      '#required' => FALSE,
    ];

    $pane_form['user_points_use']['#states'] = [
      'visible' => [
        ':input[name="earnpoints_productpoint[userpoints_types]"]' => ['value' => '2'],
      ],
    ];

    $pane_form['newpoints'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Optional order comment'),
      '#default_value' => $earnPoints,
      '#size' => 60,
    ];
    return $pane_form;
  }

  /**
   * {@inheritdoc}
   * Validate Pane Form.
   *
   * @param $pane_form
   *   , FormStateInterface $form_state, $complete_form
   */
  public function validatePaneForm(array &$pane_form, FormStateInterface $form_state, array &$complete_form) {
    $values = $form_state->getValue($pane_form['#parents']);

    if (isset($values['userpoints_types']) && !empty($values['userpoints_types']) && $values['userpoints_types'] == 2) {
      // Check if value is numeric.
      if (!is_numeric($values['user_points_use'])) {
        $form_state->setError($pane_form, $this->t('Please add numeric value for Points.'));
      }

      if ($values['user_points_use'] > $this->user_points) {
        $form_state->setError($pane_form, $this->t('Sorry'));

      }
    }

  }

  /**
   * {@inheritdoc}
   * Submit Pane Form.
   *
   * @param $pane_form
   *   , FormStateInterface $form_state, $complete_form
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitPaneForm(array &$pane_form, FormStateInterface $form_state, array &$complete_form) {
    $values = $form_state->getValue($pane_form['#parents']);
    $paymentInformation = $form_state->getValue('payment_information');

    if (isset($values['userpoints_types']) && !empty($values['userpoints_types'])) {
      // Calculate usable points based on user selected value.
      $totalUsablePoints = '';
      switch ($values['userpoints_types']) {
        case '2':
          $totalUsablePoints = $values['user_points_use'];
          break;

        case '1':
          // Get all valid user points.
          $totalUsablePoints = $this->getUserPoints();
          break;

        default:
          $totalUsablePoints = '0';
          break;
      }

      if (!empty($totalUsablePoints)) {
        $orderItemTotal = 0;

        if ($this->order->hasItems()) {
          foreach ($this->order->getItems() as $orderItem) {
            $orderItemTotal += $orderItem->getTotalPrice()->getNumber();
          }
        }

        if ($orderItemTotal < $totalUsablePoints) {
          $totalUsablePoints = $orderItemTotal;
        }

        foreach ($this->order->getItems() as $orderItem) {
          $purchasedEntity = $orderItem->getPurchasedEntity();
          $productId = $purchasedEntity->get('product_id')->getString();
          $product = Product::load($productId);
          // To get the store details for currency code.
          $store = Store::load(reset($product->getStoreIds()));
        }

        // Create adjustment object for current order.
        $adjustments = new Adjustment([
          'type' => 'custom',
          'label' => 'User Points Deduction',
          'amount' => new Price('-' . $totalUsablePoints, $store->get('default_currency')
            ->getString()),
        ]);

        $this->order->addAdjustment($adjustments);
        $this->order->save();
      }
    }
    $this->order->setData('earnuserpoints', $values['newpoints']);
  }

  /**
   * Get user available points.
   *
   * @return array
   */
  public function getUserPoints() {
    // Load the current user & get the user points.
    $user = User::load(\Drupal::currentUser()->id());
    $user_points = $user->get('field_user_points')->getValue()[0]['value'];

    return $user_points;
  }

  /**
   * Get user available points.
   *
   * @return float|int ()
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function orderInfo() {
    $entity_manager = \Drupal::entityManager();
    $cart_manager = \Drupal::service('commerce_cart.cart_manager');
    $cart_provider = \Drupal::service('commerce_cart.cart_provider');
    $store = $entity_manager->getStorage('commerce_store')->load(1);

    $cart = $cart_provider->getCart('default', $store);
    $earnPoints = 0;
    foreach ($cart->getItems() as $order_item) {
      $product_variation = $order_item->getPurchasedEntity();
      $product_quantity = round($order_item->getQuantity());

      foreach ($product_variation->get('field_points')->getValue() as $points) {
        $earnPoints += $points['value'] * $product_quantity;
      }
    }

    return $earnPoints;
  }

}
