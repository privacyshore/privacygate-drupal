<?php

namespace Drupal\commerce_privacygate_payments\PluginForm\OffsiteRedirect;

require_once __DIR__ . '/../../PrivacyGate/autoload.php';
require_once __DIR__ . '/../../PrivacyGate/const.php';

use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class PrivacyGateForm extends PaymentOffsiteForm
{

    /**
     * {@inheritdoc}
     */
    public function buildConfigurationForm(array $form, FormStateInterface $form_state)
    {
        $form = parent::buildConfigurationForm($form, $form_state);

        /** @var \Drupal\commerce_payment\Entity\PaymentInterface $payment */
        $payment = $this->entity;
        /** @var \Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayInterface $paymentGatewayPlugin */
        $paymentGatewayPlugin = $payment->getPaymentGateway()->getPlugin();
        $paymentConfiguration = $paymentGatewayPlugin->getConfiguration();
        /** @var \Drupal\commerce_order\Entity\Order $order */
        $order = $payment->getOrder();
        $entity_manager = \Drupal::entityTypeManager();
        $store = $entity_manager->getStorage('commerce_store')->load($order->getStoreId());
        $totalPrice = $order->getTotalPrice();

        $products = [];
        foreach ($order->getItems() as $item) {
            $products[] = t('@product x @quantity', [
                '@product' => $item->getTitle(),
                '@quantity' => (int) $item->getQuantity()
            ]);
        }

        $chargeData = array(
            'local_price' => array(
                'amount' => $totalPrice->getNumber(),
                'currency' => $totalPrice->getCurrencyCode()
            ),
            'pricing_type' => 'fixed_price',
            'name' => t('@store order #@order_number', ['@order_number' => $order->id(), '@store' => $store->getName()]),
            'description' => mb_substr(implode(',', $products), 0, 200),
            'metadata' => [
                METADATA_SOURCE_PARAM => METADATA_SOURCE_VALUE,
                METADATA_ORDER_ID_PARAM => $payment->getOrderId(),
                METADATA_CLIENT_ID_PARAM => $order->getCustomerId(),
                'email' => $order->getEmail()
            ],
            'redirect_url' => $form['#return_url'],
            'cancel_url' => $form['#cancel_url']
        );

        \PrivacyGate\ApiClient::init($paymentConfiguration['api_key']);
        $chargeObj = \PrivacyGate\Resources\Charge::create($chargeData);

        $order->setData('charge_id', $chargeObj->id);
        $order->save();

        return $this->buildRedirectForm($form, $form_state, $chargeObj->hosted_url, $chargeData, PaymentOffsiteForm::REDIRECT_GET);
    }
}
