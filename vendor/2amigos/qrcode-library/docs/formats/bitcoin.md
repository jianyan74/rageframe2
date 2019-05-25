BtcFormat
---------

The purpose of this format is to enable users to display a QrCode to easily make payments to the specified wallet. 

Attributes of this class: 

- **name**: Label for that address (e.g. name of receiver)
- **address**: bitcoin address
- **message**: message that describes the transaction to the user (see examples below)
- **amount**: amount of base bitcoin units 

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\BtcFormat; 

$format = new BtcFormat(['address' => '175tWpb8K1S7NmH4Zx6rewF9WQrcZv245W', 'amount' => 1, 'name' => 'antonio']);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
