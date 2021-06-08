<?php 

namespace Payments;

use GuzzleHttp\Client;

class ETHModule{
    
    public function __construct()
    {
        $this->explorer_url = "https://eth.flitswallet.app/api/";
        $this->client = new client();
    }

    //
    // Look in the explorer if a newer transaction than $timestap in ms (int) with $amount (Float) exists for $address (varchar)
    //
    public function existsTransaction($address, $amount, $timestamp, $erc20, $tokenname)
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
                if($transaction_info['blockTime'] != 0 && $transaction_info['blockTime'] < $timestamp)
                {
                    //transaction doesn't exist
                    return [
                        'exists' => false,
                        'txid' => ""
                    ];
                }

                if ($erc20 == true) {
                    
                    foreach($transaction_info['tokenTransfers'] as $tokenTransfers)
                    {

                        if (toFixed(($tokenTransfers['value'] / "1000000000000000000"), 0) == $amount) {

	                        $formattedamount = toFixed(($tokenTransfers['value'] / "1000000000000000000"), 0);
	
                        } else {

	                        $formattedamount = toFixed(($tokenTransfers['value'] / "1000000000000000000"), 5);
	
                        }
                        
                        if($formattedamount == $amount && $tokenTransfers['symbol'] == $tokenname)
                        {
                            return [
                                'exists' => true,
                                'txid' => $transaction
                            ];
                        }
                    }
                    
                } else {
                
                    foreach($transaction_info['vout'] as $vout)
                    {
                        if($vout['value'] == $amount && $vout['scriptPubKey']['addresses'][0] == $address)
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
            $addressEndpoint = $this->explorer_url . "address/$address";            
            $transactions = $this->client->request('GET', $addressEndpoint);

            //convert response into array
            $transactions_array = (json_decode($transactions->getBody()->getContents(), true))['txids'];
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
