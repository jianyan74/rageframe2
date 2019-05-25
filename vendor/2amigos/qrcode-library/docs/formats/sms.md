SmsFormat
---------

A SMS URI starts with "sms:", followed by the telephone number, is a way similar to email address. For example 
`sms:9052091223`.

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\SmsFormat; 

$format = new SmsFormat(['phone' => 657657657]);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
