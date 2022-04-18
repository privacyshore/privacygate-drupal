<?php

namespace Drupal\commerce_privacygate_payments\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\commerce_privacygate_payments\IpnInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PrivacyGatePaymentController extends ControllerBase
{
  private $ipn;

  public function __construct(IpnInterface $ipn)
  {
    $this->ipn = $ipn;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('commerce_privacygate_payments.ipn')
    );
  }

  public function process(Request $request)
  {
    // Get IPN request data and basic processing for the IPN request.
    $this->ipn->process($request);
    $response = new Response();
    $response->setContent('');
    return $response;
  }
}
