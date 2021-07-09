<?php

namespace Modules\Icommercebraintree\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base Api
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;


class BraintreeApiController extends BaseApiController
{

    
    public function __construct(){
        
    }

    /**
    * Braintree API - Get gateway
    * @param 
    * @return gateway
    */
    public function getGateway(){

        // Payment Method Configuration
        $paymentMethod = braintree_getPaymentMethodConfiguration();

        $config = new \Braintree\Configuration([
            'environment' => $paymentMethod->options->mode,
            'merchantId' => $paymentMethod->options->merchantId,
            'publicKey' => $paymentMethod->options->publicKey,
            'privateKey' => $paymentMethod->options->privateKey
        ]);

        $gateway = new \Braintree\Gateway($config);

        return $gateway;

    }

    /**
    * Braintree API - Generate Client Token
    * @param 
    * @return token
    */
    public function generateClientToken(){

        $gateway = $this->getGateway();
        $clientToken = $gateway->clientToken()->generate();

        return $clientToken;

    }

    /**
    * Braintree API - Create Transaction
    * @param 
    * @return result
    */
    //https://developer.paypal.com/braintree/docs/reference/request/transaction/submit-for-settlement
    public function createTransaction($order,$nonceFromTheClient){

        $gateway = $this->getGateway();

        //Optional
        $customer = [
            'email' => $order->email,
            'firstName' => $order->first_name,
            'lastName' => $order->last_name,
        ];
        
        $result = $gateway->transaction()->sale([
          'orderId' => $order->id,
          'amount' => $order->total,
          'paymentMethodNonce' => $nonceFromTheClient,
          'customer' => $customer,
          'options' => [
            'submitForSettlement' => True
          ]
        ]);

        return $result;

    }

    /**
    * Braintree API - Get Transaction
    * @param $id
    * @return transaction
    */
    public function getTransaction($id){

        $gateway = $this->getGateway();
       
        $transaction = $gateway->transaction()->find($id);

        return $transaction;

    }




 
}