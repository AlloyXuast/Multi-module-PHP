<?php 

namespace Payments\Crypto;

use GuzzleHttp\Client;

class THETAModule{
    
    public function __construct()
    {
        $this->explorer_url = "https://api.chisdealhd.co.uk/v1/cryptoproxyexplorer/theta/";
        $this->client = new client();
    }

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
                if($transaction_info['blocktime'] != 0 && $transaction_info['blocktime'] < $timestamp)
                {
                    //transaction doesn't exist
                    return [
                        'exists' => false,
                        'txid' => ""
                    ];
                }

                foreach($transaction_info['vout'] as $vout)
                {
		
	            
		    if ($tokenname == "THETA") {

                        	if (toFixed(($vout[0]['coins']['thetawei'] / "1000000000000000000"), 0) == $amount) {

	                        	$formattedamount = toFixed(($vout[0]['coins']['thetawei'] / "1000000000000000000"), 0);
	
                        	} else {

	                        	$formattedamount = toFixed(($vout[0]['coins']['thetawei'] / "1000000000000000000"), 2);
	
                        	}
				
		   } else if ($tokenname == "TFUEL") {
			
				if (toFixed(($vout[0]['coins']['tfuelwei'] / "1000000000000000000"), 0) == $amount) {

	                        	$formattedamount = toFixed(($vout[0]['coins']['tfuelwei'] / "1000000000000000000"), 0);
	
                        	} else {

	                        	$formattedamount = toFixed(($vout[0]['coins']['tfuelwei'] / "1000000000000000000"), 2);
	
                        	}
			
		    }
	           		
                    if($formattedamount == $amount && $$vout['body']['data']['outputs'][0]['address'] == $address)
                    {
                        return [
                            'exists' => true,
                            'txid' => $transaction
                        ];
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
            $addressEndpoint = $this->explorer_url . "address/$address";            
            $transactions = $this->client->request('GET', $addressEndpoint);

            //convert response into array
            $transactions_array = (json_decode($transactions->getBody()->getContents(), true))['transactions'];
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
            $transactionEndpoint = $this->explorer_url . "tx/$txid"; 
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
