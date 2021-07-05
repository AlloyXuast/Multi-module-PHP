# Multi-Crypto-module-PHP
Multi Crypto package for the Buy Bridge using All in 1 System, Easy install including adding new Cryptos

Example Code:

```php

<?php

require_once('vendor/autoload.php');

use Payments\Crypto\DOGECModule;

$dogec = new DOGECModule();

var_dump($dogec->existsTransaction('DOGECASHWALLETADDRESS', AMOUNT, CORRENT_TIMESTAMP));

OUTPUTS:

[
   'exists' => true,
   'txid' => 'TXIDHERE',
   'conf' => 5
]

```

### LISTS
```php
<?php

require_once('vendor/autoload.php');

//DOGECASH
use Payments\Crypto\DOGECModule;
$dogec = new DOGECModule();

//BITCOIN
use Payments\Crypto\BTCModule;
$btc = new BTCModule();

//ZENZO
use Payments\Crypto\ZNZModule;
$znz = new ZNZModule();

//ETHEREUM
use Payments\Crypto\ETHModule;
$eth = new ETHModule();

//DASH
use Payments\Crypto\DASHModule;
$dash = new DASHModule();

//FLITS
use Payments\Crypto\FLSModule;
$fls = new FLSModule();

//DIVI
use Payments\Crypto\DIVIModule;
$divi = new DIVIModule();

//DIGIBYTE
use Payments\Crypto\DBGModule;
$dbg = new DGBModule();

//DOGECOIN
use Payments\Crypto\DOGEModule;
$doge = new DOGEModule();

//LITECOIN
use Payments\Crypto\LTCModule;
$ltc = new LTCModule();

//PIVX
use Payments\Crypto\PIVXModule;
$pivx = new PIVXModule();

//RPD
use Payments\Crypto\RPDModule;
$rpd = new RPDModule();

//SCC
use Payments\Crypto\SCCModule;
$scc = new SCCModule();

//SIN
use Payments\Crypto\SINModule;
$sin = new SINModule();

//SSS
use Payments\Crypto\SSSModule;
$sss = new SSSModule();

//THETA
use Payments\Crypto\THETAModule;
$theta = new THETAModule();

//TRTT
use Payments\Crypto\TRTTModule;
$trtt = new TRTTModule();

```
