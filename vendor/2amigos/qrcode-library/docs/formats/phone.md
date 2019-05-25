PhoneFormat
-----------

A tel URI should be used to encode a telephone number, to ensure that the digits are understood as a telephone number. 
Further, it is advisable to include prefixes that make the number accessible internationally. For example, to encode the 
US phone number 212-555-1212, one should encode tel:+1-212-555-1212. This tel URI includes a "+1" prefix that will make 
it usable outside the United States.

Readers should invoke the device's dialer, if applicable, and pre-fill it with the given number, but not automatically 
initiate a call.

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\PhoneFormat; 

$format = new PhoneFormat(['phone' => 657657657]);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
