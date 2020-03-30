<?php

namespace Drupal\commerce_funds\Plugin\views\field;

use Drupal\views\ResultRow;
use Drupal\views\Plugin\views\field\Custom;
use Drupal\Core\Url;
use Drupal\commerce_funds\Entity\Transaction;

/**
 * A handler to provide escrow operations for users.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("commerce_funds_escrow_operations")
 */
class EscrowOperations extends Custom {

  /**
   * {@inheritdoc}
   */
  public function usesGroupBy() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    // Override options.
    $options['alter']['contains'] = [
      'alter_text' => FALSE,
    ];
    $options['hide_alter_empty'] = TRUE;

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->field_alias = 'operations';
  }

  /**
   * Return the operations for an escrow payment.
   *
   * @param Drupal\views\ResultRow $values
   *   Views handler values to be modified.
   *
   * @return array
   *   Renderable dropbutton.
   */
  protected function renderEscrowOperations(ResultRow $values) {
    $transaction_hash = Transaction::load($values->transaction_id)->getHash();
    $status = $values->_entity->getStatus();
    $current_display = $this->displayHandler->display['id'];

    $links = [];

    if ($current_display == "incoming_escrow_payments") {
      if ($status == 'Pending') {
        $args = [
          'action' => 'cancel-escrow',
          'transaction_hash' => $transaction_hash,
        ];
        $links['cancel'] = [
          'title' => $this->t('Cancel'),
          'url' => Url::fromRoute('commerce_funds.escrow.cancel', $args),
        ];
      }
      else {
        return $this->t('None');
      }
    }

    if ($current_display == "outgoing_escrow_payments") {
      if ($status == 'Pending') {
        $args = [
          'action' => 'cancel-escrow',
          'transaction_hash' => $transaction_hash,
        ];
        $links['cancel'] = [
          'title' => $this->t('Cancel'),
          'url' => Url::fromRoute('commerce_funds.escrow.cancel', $args),
        ];
        $args['action'] = 'release-escrow';
        $links['release'] = [
          'title' => $this->t('Release'),
          'url' => Url::fromRoute('commerce_funds.escrow.release', $args),
        ];
      }
      else {
        return $this->t('None');
      }
    }

    $dropbutton = [
      '#type' => 'dropbutton',
      '#links' => $links,
      '#attributes' => [
        'class' => [
          'escrow-link',
        ],
      ],
    ];

    return $dropbutton;
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    return $this->renderEscrowOperations($values);
  }

}
