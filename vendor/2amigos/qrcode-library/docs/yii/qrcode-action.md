QrCodeAction
------------

This Yii2 Action component provides a very basic way on how to use the QrCodeComponent wrapper in Yii applications. Its 
configuration is quite simple, we just have to configure: 

- **text**: This is the default text to display if no parameter is found. If you don't wish to render anything if not 
passed via the request parameter, then do not set this property.
- **param**: This is the request parameter name that the action will observe to display text. If none is found, then it 
will display the value of `text` if found. 
- **method**: Whether to look for the `param` on `get` or `post` request params. Defaults to `get`.
- **component**: The QrCodeComponent name as configured in our application.

Usage
-----

```php
// On our controller  
public function actions()
{
    return [
        'qr' => [
            'class' => QrCodeAction::className(),
            'text' => 'https://2amigos.us',
            'param' => 'v',
            'commponent' => 'qr' // if configured in our app as `qr` 
        ]
    ];
}
```

See [QrCodComponent](qrcode-component.md).

© [2amigos](http://www.2amigos.us/) 2013-2017
