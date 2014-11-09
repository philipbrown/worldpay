<?php namespace PhilipBrown\WorldPay;

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
     * @var string
     */
    private $instId;

    /**
     * @var string
     */
    private $cartId;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var string
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
     * @param string $instId
     * @param string $cartId
     * @param string $secret
     * @param float $amount
     * @param Currency $currency
     * @param string $route
     * @param array $parameters
     * @return void
     */
    public function __construct(Environment $environment, $instId, $cartId, $secret, $amount, Currency $currency, $route, array $parameters = [])
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
            'instId'    => $this->instId,
            'cartId'    => $this->cartId,
            'currency'  => (string) $this->currency,
            'amount'    => $this->amount,
            'testMode'  => $this->environment->asInt()
        ], $this->parameters);
    }
}
