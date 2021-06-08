# Multi-Crypto-module-PHP
Multi Crypto package for the Buy Bridge using All in 1 System, Easy install including adding new Cryptos

Example Code:

```php

<?php

require_once('vendor/autoload.php');

use Payment\Crypto\DOGECModule;

$dogec = new DOGECModule();

var_dump($dogec->existsTransaction('DOGECASHWALLETADDRESS', AMOUNT, CORRENT_TIMESTAMP));

```
