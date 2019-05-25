QrCodeComponent
---------------

This Yii2 Application Component can truly ease the task to create QrCodes to Yii developers. It wraps all the methods 
and functionality of `Da\QrCode\QrCode` class and developers do not need to deal with initialization process every time 
they use it.

Usage
-----

```php 

// in components config of Yii2 app

'components' => [
// ... 
    'qr' => [
        'class' => '\Da\QrCode\Component\QrCodeComponent',
        // ... you can configure more properties of the component here
    ]
// ...
]

```
Once configured, you can access the component like this: 

```php 
// on your controller's action

$qr = Yii::$app->get('qr');

Yii::$app->response->format = Response::FORMAT_RAW;
Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

return $qr
    ->setText('https://2amigos.us')
    ->setLabel('2amigos consulting group llc')
    ->writeString();

```


© [2amigos](http://www.2amigos.us/) 2013-2017
