<?php

namespace Modules\Icommercebraintree\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base Api
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;


class BraintreeApiController extends BaseApiController
{

    private $gateway;
    private $braintreeService;

    public function __construct(){
       $this->gateway = $this->getGateway(); 
       $this->braintreeService = app("Modules\Icommercebraintree\Services\BraintreeService");
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

        $clientToken = $this->gateway->clientToken()->generate();

        return $clientToken;

    }

    /**
    * Braintree API - Create Transaction
    * @param 
    * @return result
    */
    //https://developer.paypal.com/braintree/docs/reference/request/transaction/submit-for-settlement
    public function createTransaction($order,$nonceFromTheClient){

        //Optional
        $customer = [
            'email' => $order->email,
            'firstName' => $order->first_name,
            'lastName' => $order->last_name,
        ];
        
        $result = $this->gateway->transaction()->sale([
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

        $transaction = $this->gateway->transaction()->find($id);

        return $transaction;

    }

    /**
    * Braintree API - Create Payment Method
    * @param 
    * @return
    */
    public function createPaymentMethod($customerId,$nonceFromTheClient){
        
        $result = $this->gateway->paymentMethod()->create([
            'customerId' => $customerId,
            'paymentMethodNonce' => $nonceFromTheClient
        ]);

        return $result;

    }

    /**
    * Braintree API - Create Customer
    * @param 
    * @return
    */
    public function createCustomer($order,$nonceFromTheClient){

        $result = $this->gateway->customer()->create([
            'id' => $order->customer_id,
            'firstName' => $order->first_name,
            'lastName' => $order->last_name,
            'email' => $order->email,
            'paymentMethodNonce' => $nonceFromTheClient
        ]);

        return $result;

    }

    /**
    * Braintree API - Find Customer
    * @param 
    * @return
    */
    public function findCustomer($id){

        try {
        
            $customer = $this->gateway->customer()->find($id);

        }catch(\Exception $e){

            \Log::info('Module Icommercebraintree: Find Customer - Not Found');
            
            $customer = null;
        }

        return $customer;
    }

    /**
    * Braintree API - Create Suscription
    * @param 
    * @return
    */
    public function createSuscription($order,$planId,$nonceFromTheClient){
        
        
        $customer = $this->findCustomer($order->customer_id); 

        // Customer Exist
        if(isset($customer->id)){

            // Add Payment Method
            $createdPaymentMethod = $this->createPaymentMethod($customer->id,$nonceFromTheClient);

            if($createdPaymentMethod->success){

                // Get Payment Method Token
                $newPaymentMethodToken = $createdPaymentMethod->paymentMethod->token;

                // Add Suscription
                $result = $this->gateway->subscription()->create([
                    'paymentMethodToken' => $newPaymentMethodToken,
                    'planId' => $planId
                ]);

            }else{

                \Log::error('Icommercebraintree: Braintree API - Create suscription - createdPaymentMethod');

            }     
           
        }else{

            // New Customer
            $createdCustomer = $this->createCustomer($order,$nonceFromTheClient);

            if($createdCustomer->success){

                // Get Payment Method Token
                $paymentToken = $createdCustomer->customer->paymentMethods[0]->token;

                // Add Suscription
                $result = $this->gateway->subscription()->create([
                    'paymentMethodToken' => $paymentToken,
                    'planId' => $planId
                ]);

            }else{

                $errors = $this->braintreeService->getErrors($createdCustomer->errors->deepAll());

                \Log::error('Icommercebraintree: Braintree API - Create suscription - createdCustomer');

                throw new \Exception($errors['string'], 204); 
            }

        }


        return $result;

    }



 
}