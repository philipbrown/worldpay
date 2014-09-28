<?php namespace PhilipBrown\WorldPay;

use PhilipBrown\WorldPay\Money;
use PhilipBrown\WorldPay\Route;
use PhilipBrown\WorldPay\InstId;
use PhilipBrown\WorldPay\CartId;
use PhilipBrown\WorldPay\Secret;
use PhilipBrown\WorldPay\Currency;
use PhilipBrown\WorldPay\Environment;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Request
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var InstId
     */
    private $instId;

    /**
     * @var CartId
     */
    private $cartId;

    /**
     * @var Secret
     */
    private $secret;

    /**
     * @var Money
     */
    private $amount;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var Route
     */
    private $route;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
    */
    private $defaultSignatureFields = ['instId', 'cartId', 'currency', 'amount'];

    /**
     * Create a new Request
     *
     * @param Environment $environment
     * @param InstId $instId
     * @param CartId $cartId
     * @param Secret $secret
     * @param Money $amount
     * @param Currency $currency
     * @param Route $route
     * @param array $parameters
     * @return void
     */
    public function __construct(
        Environment $environment,
        InstId $instId,
        CartId $cartId,
        Secret $secret,
        Money $amount,
        Currency $currency,
        Route $route,
        array $parameters = []
    )
    {
        $this->environment = $environment;
        $this->instId      = $instId;
        $this->cartId      = $cartId;
        $this->secret      = $secret;
        $this->amount      = $amount;
        $this->currency    = $currency;
        $this->parameters  = $parameters;
        $this->route       = $route;
    }

    /**
     * Set the signature fields to use in the signature hash
     *
     * @param array $fields
     * @return Request
     */
    public function setSignatureFields(array $fields)
    {
        $this->defaultSignatureFields = array_merge($this->defaultSignatureFields, $fields);

        return $this;
    }

    /**
     * Send the request to WorldPay
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function send()
    {
        $request = $this->prepare();

        $url = $request->route.'?signature='.$request->signature.'&'.http_build_query($request->data);

        return RedirectResponse::create($url)->send();
    }

    /**
     * Return an object containing the request
     *
     * @return Body
     */
    public function prepare()
    {
        return new Body(
            (string) $this->route,
            $this->generateSignature(),
            $this->getTheRequestParameters()
        );
    }

    /**
     * Generate the signature
     *
     * @return string
     */
    private function generateSignature()
    {
        $defaults = [
            'instId'    => $this->instId,
            'cartId'    => $this->cartId,
            'currency'  => $this->currency,
            'amount'    => $this->amount
        ];

        $parameters = array_intersect_key($this->parameters, array_flip($this->defaultSignatureFields));

        return md5((string) $this->secret.':'.implode(':', array_merge($defaults, $parameters)));
    }

    /**
     * Get the request parameters
     *
     * @return array
     */
    private function getTheRequestParameters()
    {
        return array_merge([
            'instId'    => (string) $this->instId,
            'cartId'    => (string) $this->cartId,
            'currency'  => (string) $this->currency,
            'amount'    => (string) $this->amount,
            'testMode'  => $this->environment->asInt()
        ], $this->parameters);
    }
}
