#WorldPay
**A PHP 5.3+ wrapper for the [WorldPay](http://worldpay.com) payment gateway**

WorldPay is an easy to use payment gateway that is widely recognised and trusted. However, just about everything about the service is out of date. One of the most frustrating things about using WorldPay is the woeful lack of good documentation or official libraries. The result of this is, WorldPay's documentation is fragmented or incomplete and code examples are completely inadequate.

WorldPay also seems to make everything a lot more complicated than it needs to be.

I decided to make this package to abstract a lot of these complications away and instead provide a clear and easy to use API for creating WorldPay requests and listening for responses. This package will also be well unit tested and available through PHP Composer as a framework agnostic package.

**Note:** If you are looking to integrate many payment gateways into your application, and you aren't going to be using all of WorldPay's features (e.g FuturePay or custom parameters) you should probably use [Omnipay](https://github.com/adrianmacneil/omnipay) instead.

##Installation
Add `philipbrown/worldpay` as a requirement to `composer.json`:

```json
{
  "require": {
    "philipbrown/worldpay": "dev-master"
  }
}
```
Update your packages with `composer update`.

##How does WorldPay work?
Creating a new payment using the WorldPay gateway basically follows these three steps:

1. You create a **Request** with information about the transaction. This could be as little as the amount of the transaction all the way up to a complete profile of your customer.
2. The customer is redirected to WorldPay's secure servers to enter their payment details. No customer details are ever stored on your server.
3. WorldPay will then send an optional **Response** back to your server as a callback. You can use this callback to update your database or set any processes you need to run post transaction.

This WorldPay package allows you to easily create a new **Request** and capture the resulting **Response**

###Creating a Request
Creating a new request is as simple as instantiating a new ```Request``` object and passing it your transaction details.
```php
// Get a new WorldPay object
$wp = new Philipbrown\Worldpay\Worldpay;

// Create a Request
$request = $wp->request(array(
  'instId' => '123456',
  'cartId' => 'my_shop',
  'currency' => 'GBP',
  'amount' => 9.99,
));

// Send it to WorldPay
$request->send();
```

###Accepting a Response
WorldPay will send a ```POST``` request to your server with details of the transaction. You simply need to capture this request and pass it to the ```Response``` object.
```php
// Get a new WorldPay object
$wp = new Philipbrown\Worldpay\Worldpay;

// Pass the $_POST to the Response object
$response = $wp->response($_POST);

// You now have an easy to work with object
$response->isSuccess(); // Returns TRUE / FALSE
```

##Environments
By default, WorldPay has development and production environments. This allows you to test your application using the test environment without having to process real payments.

In order to receive a WorldPay response, you must provide a callback URL in your WorldPay account.

For example, your ```production``` URL might be ```example.com``` and your dev URL might be ```dev.example.com```.

You can set your environment by simply using the ```setConfig``` method on the ```Worldpay``` object:
```php
$wp->setConfig(array('env' => 'development'));
```

If you are using Laravel 4, you could set the environment automatically like this:
```php
$wp->setConfig(array('env' => App::environment()));
```

However, it is often the case that you need to have multiple environments beyond just ```dev``` and ```production```.

For example, you might want to have a ```local``` environment or a ```test``` environment that do not actually hit the WorldPay servers.

In this case, you can pass any environment names that you want as configuration items and an override URL that will be used instead of the default WorldPay URLs:
```php
$wp->setConfig(array(
  'env' => 'local'
  'local' => 'example.local/callbacks/worldpay'
));
```

##Creating a Request
To create a new request, simply pass an array of your transaction parameters to the ```request``` method on the ```Worldpay``` object:
```php
$request = $wp->request(array(
  'instId' => '123456789',
  'cartId' => 'worldpay-php',
  'currency' => 'GBP',
  'environment' => 'development',
  'name' => 'Philip Brown',
  'email' => 'phil@ipbrown.com',
  'address_line_1' => '101 Blah Blah Lane',
  'town' => 'London',
  'postcode' => 'E20 123',
  'country' => 'GB',
  'telephone' => '123456789',
  'payment_type' => 'VISA',
  'amount' => '99.99'
));
```

### Request Parameters
```instId``` **Required**
The installation id is used to match this request to your specific installation.

```cartId``` **Required**
The cart id is used as a reference for where the transaction originated.

```currency``` **Required**
The currency shortcode of this transaction.

```amount``` **Required**
The total amount of this transaction.

```environment``` **Required**
The environment of this transaction. This will default to ```development``` if not supplied.

```name``` **Optional**
The name of the customer

```email``` **Optional**
The email address of the customer

```address_line_1``` **Optional**
The first line of the customer's address.

You can also optionally specify the ```address_line_2``` and ```address_line_3``` parameters.

```town``` **Optional**
The customer's town.

```country``` **Optional**
The customer's country.

The country should be provided as a specific shortcode.

```telephone``` **Optional**
The customer's telephone number.

```payment_type``` **Optional**
The customer's chosen payment type.

By setting the ```payment_type``` parameter you can bypass the payment selection screen when the customer hits the WorldPay servers.

##Custom Parameters
WorldPay allows you to include custom parameters in your request which will be available to you when you receive the response. This is useful for when you need to match up a response with a record in your database.

To set a custom parameter, simply prepend it with wither ```MC_``` or ```CM_```.
```php
array(
  'CM_order_id' => 432,
  'MC_customer_id' => '34'
);
```
There is actually no difference between ```MC_``` and ```CM_``` parameters. WorldPay also allows you to supply either ```C_``` or ```M_``` parameters which are only available in your result pages or you callback script respectively. However, I have left them out of this package for simplicity. If you really need access to these parameters, open an issue and I'll add support for them too.

##Setting a Secret
To prevent unauthorised tampering of transaction requests, WorldPay allows you to set a secret key. This key is then used as part of the encryption of the transaction signature that you must send to WorldPay for each request.

To set a secret, go into your WorldPay Account and choose **Installations** from the menu.

Next choose your installation and complete the field marked **MD5 secret for transactions**.

When using the WorldPay package, simply set your secret using the ```setSecret``` method:
```php
$request->setSecret('my_secret');
```

##Setting the Signature Fields
The Signature Fields are a colon separated string that is used by WorldPay to authorise requests.

The signature follows this following format: ```secret:field_1:field_2:field_3```

The string is then hashed using the **MD5 hashing algorithm**.

When WorldPay recieves your transaction request, the transaction parameters are checked against the signature to ensure that nothing has been tampered with. **It is therefore important** that you ensure that all sensitive fields are included in your signature.

To specify which fields will be included in your signature, go into your WorldPay account, choose **Installations** from the menu and then choose the installation you are working. Complete the field marked **SignatureFields** with a colon seperated list.

By default, this WorldPay package will force you to use the default parameters of ```instId```, ```cartId```, ```currency```, ```amount```.

You can add your own fields by using the ```setSignatureFields``` method:
```php
$request->setSignatureFields(array('email'));
```

##Sending requests to WorldPay
You can either send your requests to WorldPay synchronously or asynchronously.

To send a request synchronously, use the ```send``` method:
```php
$request->send();
```
This allows you to create the request and send it in one page request.

If you want to provide a customer confirmation page, you can use the ```prepare``` method to grab a copy of the request data. You can then create a hidden form so that the customer can confirm the transaction before sending it to WorldPay:
```php
$worldpay = $request->prepare();
```

```html
<h1>Confirm your purchase</h1>

<p>Thank you {$customer->first_name} for choosing to buy with us.</p>
<p>To confirm your purchase click the button below.</p>
<p>You will be taken to WorldPay's secure server where you can complete your transaction.</p>

<form action="{$worldpay['endpoint']}" method="POST">
  {foreach $worldpay['data'] as $key => $value}
    <input type="hidden" name="{$key}" value="{$value}">
  {endforeach}
  <input type="hidden" name="signature" value="{$worldpay['signature']}">
  <input type="submit" value="Complete your purchase!">
</form>
```

## FuturePay
...urgh I'll come back to this...

##Capturing a WorldPay response
A WorldPay response is simply a ```POST``` request to a URL that you define in your WorldPay account.

To set your callback URL, log in to your WorldPay account, choose the installation and complete the field marked **Payment Response URL**. Also make sure you have **Payment Response enabled?** checked.

To capture a response, simply pass the ```POST``` request to the ```response``` method:
```php
// Get a new WorldPay object
$wp = new Philipbrown\Worldpay\Worldpay;

// Pass the $_POST to the Response object
$response = $wp->response($_POST);
```

###Response parameters
Once you have captured the WorldPay resonse, all of the parameters are available to your on the ```Response``` object:
```php
$response->instId;          // '123456'
$response->cartId;          // 'My Shop'
$response->amount;          // '9.99'
$response->currency;        // 'GBP'
$response->description;     // 'Description of the transaction'
$response->name;            // 'Philip Brown'
$response->town;            // 'London'
$response->postcode;        // 'E20 123'
$response->country;         // 'GB'
$response->email;           // 'phil@ipbrown.com'
$response->environment;     // 'development'
$response->password;        // 'password_123'
$response->transaction_id;  // '123456789'
$response->card_type;       // 'MasterCard'
$response->ip_address;      // '123.456.789'
$response->company_name;    // 'Acme PHP Ltd'
$response->address_line_1;  // '101 Blah Lane'
$response->telephone;       // '123456789'
$response->fax;             // '123456789'
$response->country_string;  // 'United Kingdom'
$response->timestamp;       // '2013-10-13 16:42:11'
```

###Response helper methods
There are a number of helper methods on the ```Response``` object to make working withh the response easier:

#### Is Valid?
WorldPay allows you to set a callback password to ensure only authorised ```POST``` requests are accessed by your callback script.

To set your callback password, go into your WorldPay account, choose the correct installation and enter your chosen password into the required field.

WorldPay will send that password as part of the ```POST``` request.

You can use the ```isValid``` method to check that this is an authorised request:
```php
$response->isValid(); // TRUE / FALSE
```

#### Is Success?
WorldPay will send you a callback request if the payment was either successful or if the customer decided to cancel the request. To check to see if this transaction was successful, use the ```isSuccess``` method:
```php
$response->isSuccess(); // TRUE / FALSE
```

#### Is Cancelled?
You can check to see if the transaction was cancelled by the customer with the ```isCancelled``` method:
```php
$response->isCancelled(); // TRUE / FALSE
```

#### Is FuturePay?
To check to see if this was a FuturePay transaction, you can use the ```isFuturePay``` method:
```php
$response->isFuturePay(); // TRUE / FALSE
```

#### Is Production?
To check to see if this was a live transaction, you can use the ```isProduction``` method:
```php
$response->isProduction(); // TRUE / FALSE
```

#### Is Development?
Alternatively, to check to see if this was a development transaction, you can use the ```isDevelopment``` method:
```php
$response->isDevelopment(); // TRUE / FALSE
```

## License
The MIT License (MIT)

Copyright (c) 2013 Philip Brown

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
