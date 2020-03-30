<?php

namespace Drupal\commerce_funds\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\commerce_payment\Entity\PaymentGateway;
use Drupal\commerce_store\StoreStorageInterface;
use Drupal\commerce_funds\FundsDefaultCurrency;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form to configure the fees for each transaction type.
 */
class ConfigureFees extends ConfigFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The store storage interface.
   *
   * @var \Drupal\commerce_store\StoreStorageInterface
   */
  protected $storeStorage;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Class constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, StoreStorageInterface $store_storage, MessengerInterface $messenger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->storeStorage = $store_storage;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity_type.manager')->getStorage('commerce_store'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'commerce_funds_configure_fees';
  }

  /**
   * {@inheritdoc}
   *
   * Https://www.drupal.org/docs/8/api/form-api/configformbase-with-simple-configuration-api.
   */
  protected function getEditableConfigNames() {
    return [
      'commerce_funds.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('commerce_funds.settings');

    $enabled_methods = PaymentGateway::loadMultiple() ? PaymentGateway::loadMultiple() : NULL;
    $store = $this->storeStorage->loadDefault();
    // If no store set a message.
    if (!$store) {
      $message = $this->t('You haven\'t configured a store yet. Please <a href=":url">configure one</a> before.', [
        ':url' => Url::fromRoute('entity.commerce_store.collection')->toString(),
      ]);
      $this->messenger->addError($message);
      return $form;
    }
    $funds_default_currency = FundsDefaultCurrency::create(\Drupal::getContainer(), $store);
    $currency = $funds_default_currency->printConfigureFeesCurrency();

    $fees = $config->get('fees') ?: [];

    $form['deposit'] = [
      '#type' => 'details',
      '#title' => $this->t('Deposit Fees'),
      '#open' => TRUE,
    ];

    if ($enabled_methods) {
      $form['deposit']['#markup'] = $this->t('Fees are added on top of the amount requested by the user during checkout process.');

      $enabled_methods_num = count($enabled_methods);
      foreach ($enabled_methods as $method) {
        $method_id = $method->id();
        if ($method_id != 'free_order' && $method->getPluginId() != 'funds_balance') {
          $form['deposit']['deposit_' . $method_id . '_rate'] = [
            '#type' => 'number',
            '#min' => 0,
            '#title' => $this->t('@method Fee (%)', ['@method' => $method->label()]),
            '#description' => $this->t('Fee taken for Deposits made using @method', ['@method' => $method->label()]),
            '#default_value' => array_key_exists('deposit_' . $method_id . '_rate', $fees) ? $fees['deposit_' . $method_id . '_rate'] : 0,
            '#step' => 0.01,
            '#size' => 2,
            '#maxlength' => 3,
            '#required' => TRUE,
          ];

          $form['deposit']['deposit_' . $method_id . '_fixed'] = [
            '#type' => 'number',
            '#min' => 0,
            '#title' => $this->t('Fixed @method Fee (@currency)', [
              '@method' => $method->label(),
              '@currency' => $currency,
            ]
            ),
            '#description' => $this->t('Fixed fee taken (or minimum applied if a rate is defined) for Deposits made using @method', ['@method' => $method->label()]),
            '#default_value' => array_key_exists('deposit_' . $method_id . '_fixed', $fees) ? $fees['deposit_' . $method_id . '_fixed'] : 0,
            '#step' => 0.01,
            '#size' => 2,
            '#maxlength' => 3,
            '#required' => TRUE,
          ];
        }
        if ($method_id == "free_order" && $enabled_methods_num <= 1) {
          $form['deposit']['no_methods'] = [
            '#markup' => $this->t('No paying deposit method have been enabled.'),
          ];
        }
      }
    }
    else {
      $form['deposit']['no_methods'] = [
        '#markup' => $this->t('No payment method have been enabled for deposit.'),
      ];
    }

    $form['internal'] = [
      '#type' => 'details',
      '#title' => $this->t('Internal exchange Fees'),
      '#open' => TRUE,
    ];

    $form['internal']['#markup'] = $this->t('Fees are added on top of the amount entered by the user.');

    $form['internal']['transfer_rate'] = [
      '#type' => 'number',
      '#min' => 0,
      '#title' => $this->t('Transfer Fee (%)'),
      '#description' => $this->t('Commission rate taken on Transfers'),
      '#default_value' => $fees ? $fees['transfer_rate'] : 0,
      '#step' => 0.01,
      '#size' => 2,
      '#maxlength' => 3,
      '#required' => TRUE,
    ];

    $form['internal']['transfer_fixed'] = [
      '#type' => 'number',
      '#min' => 0,
      '#title' => $this->t('Fixed Transfer Fee (@currency)', ['@currency' => $currency]),
      '#description' => $this->t('Fixed fee taken (or minimum applied if a rate is defined) on Transfers.'),
      '#default_value' => $fees ? $fees['transfer_fixed'] : 0,
      '#step' => 0.01,
      '#size' => 2,
      '#maxlength' => 3,
      '#required' => TRUE,
    ];

    $form['internal']['escrow_rate'] = [
      '#type' => 'number',
      '#min' => 0,
      '#title' => $this->t('Escrow Fee (%)'),
      '#description' => $this->t('Commission rate taken on Escrows'),
      '#default_value' => $fees ? $fees['escrow_rate'] : 0,
      '#step' => 0.01,
      '#size' => 2,
      '#maxlength' => 3,
      '#required' => TRUE,
    ];

    $form['internal']['escrow_fixed'] = [
      '#type' => 'number',
      '#min' => 0,
      '#title' => $this->t('Fixed Escrow Fee (@currency)', ['@currency' => $currency]),
      '#description' => $this->t('Fixed fee taken (or minimum applied if a rate is defined) on Escrows.'),
      '#default_value' => $fees ? $fees['escrow_fixed'] : 0,
      '#step' => 0.01,
      '#size' => 2,
      '#maxlength' => 3,
      '#required' => TRUE,
    ];

    $form['payment'] = [
      '#type' => 'details',
      '#title' => $this->t('Payment Fees'),
      '#open' => TRUE,
    ];

    $form['payment']['#markup'] = $this->t('Fees are deducted from the amount paid by the user for the product. The product owner is credited of the amount deducted from the fee. The site balance is credited from the fee.');

    $form['payment']['payment_rate'] = [
      '#type' => 'number',
      '#min' => 0,
      '#title' => $this->t('Payment Fees (%)'),
      '#description' => $this->t('Fees applied on payment using funds balance.'),
      '#default_value' => $fees ? $fees['payment_rate'] : 0,
      '#step' => 0.01,
      '#size' => 2,
      '#maxlength' => 3,
      '#required' => TRUE,
    ];

    $form['payment']['payment_fixed'] = [
      '#type' => 'number',
      '#min' => 0,
      '#title' => $this->t('Fixed Payment fees (@currency)', ['@currency' => $currency]),
      '#description' => $this->t('Fixed fees applied on payments using funds balance (or minimum applied if a rate is defined).'),
      '#default_value' => $fees ? $fees['payment_fixed'] : 0,
      '#step' => 0.01,
      '#size' => 2,
      '#maxlength' => 3,
      '#required' => TRUE,
    ];

    $form['withdraw'] = [
      '#type' => 'details',
      '#title' => $this->t('Withdrawal Fees'),
      '#open' => TRUE,
    ];

    $withdrawal_methods = $this->config('commerce_funds.settings')->get('withdrawal_methods')['methods'];

    if ($withdrawal_methods && !empty(array_filter($withdrawal_methods))) {
      $form['withdraw']['#markup'] = $this->t('Fees are applied on top of the amount requested by the user.');

      foreach ($withdrawal_methods as $key => $method) {
        if ($method) {
          $method_id = $key;
          $form['withdraw']['withdraw_' . $method_id . '_rate'] = [
            '#type' => 'number',
            '#min' => 0,
            '#title' => $this->t('@method Fee (%)', ['@method' => $method]),
            '#description' => $this->t('Fee taken for Withdrawals made using @method', ['@method' => $method]),
            '#default_value' => array_key_exists('withdraw_' . $method_id . '_rate', $fees) ? $fees['withdraw_' . $method_id . '_rate'] : 0,
            '#step' => 0.01,
            '#size' => 2,
            '#maxlength' => 3,
            '#required' => TRUE,
          ];

          $form['withdraw']['withdraw_' . $method_id . '_fixed'] = [
            '#type' => 'number',
            '#min' => 0,
            '#title' => $this->t('Fixed @method Fee (@currency)', [
              '@method' => $method,
              '@currency' => $currency,
            ]
            ),
            '#description' => $this->t('Fixed fee taken (or minimum applied if a rate is defined) for Withdrawals made using @method', ['@method' => $method]),
            '#default_value' => array_key_exists('withdraw_' . $method_id . '_fixed', $fees) ? $fees['withdraw_' . $method_id . '_fixed'] : 0,
            '#step' => 0.01,
            '#size' => 2,
            '#maxlength' => 3,
            '#required' => TRUE,
          ];
        }
      }
    }
    else {
      $form['withdraw']['no_methods'] = [
        '#markup' => $this->t('No withdrawal method have been enabled.'),
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->cleanValues()->getValues();
    $this->config('commerce_funds.settings')
      ->set('fees', $values)
      ->save();

    parent::submitForm($form, $form_state);
  }

}
