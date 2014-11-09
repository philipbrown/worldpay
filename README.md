# WorldPay

**A PHP 5.4+ wrapper for the [WorldPay](http://worldpay.com) payment gateway.**

[![Build Status](https://travis-ci.org/philipbrown/worldpay.png?branch=master)](https://travis-ci.org/philipbrown/worldpay)
[![Code Coverage](https://scrutinizer-ci.com/g/philipbrown/worldpay/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/philipbrown/worldpay/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/philipbrown/worldpay/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/philipbrown/worldpay/?branch=master)


WorldPay is an easy to use payment gateway that is widely recognised and trusted. However, just about everything about the service is out of date. One of the most frustrating things about using WorldPay is the woeful lack of good documentation or official libraries. The result of this is, WorldPay's documentation is fragmented or incomplete and code examples are completely inadequate.

WorldPay also seems to make everything a lot more complicated than it needs to be.

I decided to make this package to abstract a lot of these complications away and instead provide a clear and easy to use API for creating WorldPay requests and listening for responses. This package will also be well unit tested and available through PHP Composer as a framework agnostic package.

**Note:** If you are looking to integrate many payment gateways into your application, and you aren't going to be using all of WorldPay's features (e.g FuturePay or custom parameters) you should probably use [Omnipay](https://github.com/adrianmacneil/omnipay) instead.

## Installation
Add `philipbrown/worldpay` as a requirement to `composer.json`:

```json
{
  "require": {
    "philipbrown/worldpay": "~3.0"
  }
}
```
Update your packages with `composer update`.

## How does WorldPay work?
Creating a new payment using the WorldPay gateway basically follows these three steps:

1. You create a **Request** with information about the transaction. This could be as little as the basic details of the transaction all the way up to a complete profile of your customer.
2. The customer is redirected to WorldPay's secure servers to enter their payment details. No customer details are ever stored on your server.
3. WorldPay will then send an optional **Response** back to your server as a callback. You can use this callback to update your database or set any processes you need to run after the transaction has been completed.

This WorldPay package allows you to easily create a new **Request** and capture the resulting **Response**

## WorldPay Environments, Routes and Callbacks
By default, WorldPay has `development` and `production` environments. This allows you to test your application using the `development` environment without having to process real payments.

However, it is often the case that you need to have multiple environments beyond just `development` and `production`.

For example, you might want to have a `local` environment or a `test` environment that do not actually hit the WorldPay servers.

If you send a WorldPay request in the `production` environment, your request body must include a `testMode` parameter of `0`. In every other environment, this parameter must be set to `100`.

To set your environment:
```php
use PhilipBrown\WorldPay\Environment;

$env = Environment::set('production');
$env->asInt(); // 0

$env = Environment::set('development');
$env->asInt(); // 100

$env = Environment::set('local');
$env->asInt(); // 100
```

You must state where you want the request to be sent to by creating a new route and passing it to the request.

## Installation and Cart Ids
When you create a new installation in your WorldPay account, it will be automatically assigned an `instId`. When making a request to WorldPay you need to provide this id.

WorldPay also allows you to set a `cartId` that will be attached to the request. This will make it easier to dertermine where transactions originate from.

## Currencies
When you send a request to WorldPay you are required to include a string representation of the currency of the transaction. A list of these currencies can be found under `/src/currencies.php`.

To set the currency:
```php
use PhilipBrown\WorldPay\Currency;

$currency = Currency::set('GBP');
```

## Transaction Value
A request should include the total value of the transaction as a single amount. This should be set as an string value.

## Transaction Secret
To prevent unauthorised tampering of transaction requests, WorldPay allows you to set a secret key. This key is then used as part of the hashing of the transaction signature that you must send to WorldPay for each request.

To set a secret, go into your WorldPay Account and choose **Installations** from the menu.

Next choose your installation and complete the field marked **MD5 secret for transactions**.

## Callback Password
After a transaction, WorldPay will (optionally) send a callback request to your server. This allows you to run any after-transaction processes you might have.

In order to authenticate this request, WorldPay will include a callback password in the body of the request. You can set this password through your installation dashboard in your merchant account.

## Creating a Request
To send a request to WorldPay, create a new instance of `PhilipBrown\WorldPay\Request`:
```php
$request = new Request(
  Environment::set('testing'),          // Environment
  '123',                                // InstId
  'My shop',                            // CartId
  'my secret',                          // Secret
  '10.00',                              // Value
  Currency::set('GBP'),                 // Currency
  http://shop.test/callbacks/worldpay', // Route
  ['name' => 'Philip Brown']            // Data
);
```

### Setting the Signature Fields
By default you will be required to include `instId`, `cartId`, `currency`, `amount` fields in your transaction signature hash.

You can add additional fields to the signature by passing an array of field names to the `setSignatureFields()` method:
```php
$request->setSignatureFields(['name']);
```

## Sending the request to WorldPay
There are two ways you can send a request to WorldPay.

Firstly, you can automatically redirect the customer straight to WorldPay once you have created the `Request` object:
```php
$request->send();
```
This will return an instance of `Symfony\Component\HttpFoundation\RedirectResponse`.

Secondly, you can prepare the request so you can display a confirmation page to the customer before they are redirected to WorldPay. This confirmation page must have a hidden form with a submit button that will take the customer to WorldPay:
```php
$body = $request->prepare();
```

This will return an instance of `PhilipBrown\WorldPay\Body`, which is an immutable object.

You can now create a confirmation page like the one below:
```html
<h1>Confirm your purchase</h1>

<p>Thank you {{ $customer->first_name }} for choosing to buy with us.</p>
<p>To confirm your purchase click the button below.</p>
<p>You will be taken to WorldPay's secure server where you can complete your transaction.</p>

<form action="{{ $body->route }}" method="POST">
  @foreach ($body->data as $key => $value)
    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
  @endforeach
  <input type="hidden" name="signature" value="{{ $body->signature }}">
  <input type="submit" value="Complete your purchase!">
</form>
```

## Accepting a Response
WorldPay can optionally send you a payment response whenever a transaction occurs. This payment response is sent as a `POST` request to an endpoint on your server.

WorldPay will include your callback password in the body of the response so that you can authenticate that the request is actually from WorldPay.

To create a new response, instantiate a new instance of `Response`, pass it your callback password and the body of the `POST` request:
```php
use PhilipBrown\WorldPay\Response;

$response = new Response('qwerty', $_POST);
```

The `Response` is an immutable object that gives you access to the body of the `POST` request:
```php
echo $response->name; // 'Philip Brown'
```

The `Response` object also has a number of helper methods:
```php
// Asserts the response is from WorldPay
$response->isValid();

// Asserts the transaction was successful
$response->isSuccess();

// Asserts the transaction was cancelled
$response->isCancelled();

// Asserts the transaction was in the production environment
$response->isProduction();

// Asserts the transaction was in the development environment
$response->isDevelopment();
```
All of the above methods return a `bool` response.
