<?php  
    defined('BASEPATH') OR exit('No direct script access allowed');  
      
    class Signing extends MY_Controller {  
          
        public function index() {  
			$request = array(
				"store" => array(
					"code" => "MYSF",
					"terminalId" => "T001"
				),
				"order" => array(
					"customerId" => "08881804211",
					"title" => "Internet Plan 50Rb to 08881804211",
					"totalPrice" => array(
						"currency" => "IDR",
						"value" => 50000
					),
					"goods" => [
						array(
							"code" => "VOL2000",
							"price" => array(
								"currency" => "IDR",
								"value" => 50000
							),
							"qty" => 1
						)
					]
				),
				"payment" => array(
					"method" => "OVO",
					"reference" => "1234567891",
					"account" => array(
						"id" => "08872000008"
					),
					"resource" => [
						array(
							"currency" => "IDR",
							"value" => 50000
						)
					]
				)
			);

			$this->load->library('phpseclib/Crypt/RSA');
			$rsa = new RSA();
			//$rsa->setPassword('password'); 
			$rsa->loadKey(file_get_contents('file://c:/mnemonic/projects/go/payment-aggregator/stores/mysf/mysf.key'));

			$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);

			echo $rsa->sign(json_encode($request));
        }	
    }  
?>