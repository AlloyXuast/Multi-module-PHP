<?php 

namespace Payments\Crypto;

use GuzzleHttp\Client;

class THETAModule{
    
    public function __construct()
    {
        $this->explorer_url = "https://explorer.thetatoken.org:8443/api/";
        $this->client = new client();
    }

    //
    // Look in the explorer if a newer transaction than $timestap in ms (int) with $amount (Float) exists for $address (varchar)
    //
    public function existsTransaction($address, $amount, $timestamp, $tokenname)
    {
        try{
            $transactions = $this->getAddressTransactions($address);

            function toFixed($number, $decimals) {
               return number_format($number, $decimals, '.', "");
            }
            
            foreach($transactions as $transaction)
            {
                $transaction_info = $this->getTransaction($transaction);

                //allowing only unconfirmed transactions & confirmed transactions newer than $timestamp
                if($transaction_info['body']['timestamp'] != 0 && $transaction_info['body']['timestamp'] < $timestamp)
                {
                    //transaction doesn't exist
                    return [
                        'exists' => false,
                        'txid' => ""
                    ];
                }
                    
                    foreach($transaction_info['tokenTransfers'] as $tokenTransfers)
                    {
			    
			                if ($tokenname == "THETA") {

                        	if (toFixed(($tokenTransfers['body']['data']['outputs'][0]['coins']['thetawei'] / "1000000000000000000"), 0) == $amount) {

	                        	$formattedamount = toFixed(($tokenTransfers['body']['data']['outputs'][0]['coins']['thetawei'] / "1000000000000000000"), 0);
	
                        	} else {

	                        	$formattedamount = toFixed(($tokenTransfers['body']['data']['outputs'][0]['coins']['thetawei'] / "1000000000000000000"), 6);
	
                        	}
				
			                } else if ($tokenname == "TFUEL") {
			
				                  if (toFixed(($tokenTransfers['body']['data']['outputs'][0]['coins']['tfuelwei'] / "1000000000000000000"), 0) == $amount) {

	                        	$formattedamount = toFixed(($tokenTransfers['body']['data']['outputs'][0]['coins']['tfuelwei'] / "1000000000000000000"), 0);
	
                        	} else {

	                        	$formattedamount = toFixed(($tokenTransfers['body']['data']['outputs'][0]['coins']['tfuelwei'] / "1000000000000000000"), 2);
	
                        	}
			
			                  }
                        
                        if($formattedamount == $amount && $tokenTransfers[['body']['data']['outputs'][0]['address'] == $address)
                        {
                            return [
                                'exists' => true,
                                'txid' => $transaction
                            ];
                        }
                    }
                    
                }
            }

        }
        catch(\Throwable $e)
        {
            throw $e;
        }
        
    }

    //
    // Check how many confirmations does $txid have (varchar)
    //
    public function checkConfirmations($txid)
    {
        try{
            $transaction = $this->getTransaction($txid);
            return $transaction['confirmations'];
        }
        catch (\Throwable $e){
            throw $e;
        }
    }

    //
    //Get transactions from address
    //
    public function getAddressTransactions($address)
    {
        try{
            $addressEndpoint = $this->explorer_url . "accounttx/$address";            
            $transactions = $this->client->request('GET', $addressEndpoint);

            //convert response into array
            $transactions_array = (json_decode($transactions->getBody()->getContents(), true))['body'];
            return $transactions_array;
        }
        catch (\Throwable $e){
            throw $e;
        }
    }

    //
    //Get trasnsaction
    //
    public function getTransaction($txid)
    {
        try{
            $transactionEndpoint = $this->explorer_url . "transaction/$txid"; 
            $transaction = $this->client->request('GET', $transactionEndpoint);

            //convert response into array
            $transaction = json_decode($transaction->getBody()->getContents(), true);

            return $transaction;
        }
        catch (\Throwable $e){
            throw $e;
        }
    }

}
