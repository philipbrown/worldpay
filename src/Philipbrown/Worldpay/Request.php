<?php namespace Philipbrown\Worldpay;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\RedirectResponse as HttpRedirectResponse;
use Philipbrown\Worldpay\Exceptions\InvalidRequestException as InvalidRequestException;

class Request extends AbstractWorldpay {

  /**
   * @var array
   */
  protected $endpoints = array(
    'production'  => 'https://secure.worldpay.com/wcc/purchase',
    'development' => 'https://secure-test.worldpay.com/wcc/purchase'
  );

  /**
   * @var array
   */
  protected $config;

  /**
   * @var string
   */
  protected $secret;

  /**
   * @var array
   */
  protected $fields;

  /**
   * Construct
   *
   * @var array $config
   * @var array $parameters
   * @return void
   */
  public function __construct($config, $parameters)
  {
    parent::__construct();

    $this->config = $config;

    $this->initialise($parameters);

    $this->setEnvironmentParameter(($config['env']) ? $config['env'] : 'development');
  }

  /**
   * Set Secret
   *
   * @var string $secret
   * @return void
   */
  public function setSecret($secret)
  {
    $this->secret = $secret;
  }

  /**
   * Set Signature Fields
   *
   * @var array $fields
   * @return void
   */
  public function setSignatureFields($fields)
  {
    $this->fields = $fields;
  }

  /**
   * Get Signature Fields
   *
   * @return array
   */
  protected function getSignatureFields()
  {
    $fields = ($this->fields) ? $this->fields : array();

    $default = array_merge($this->getDefaultParameters(), $fields);

    if($this->parameters->has('futurePayType'))
    {
      return array_merge($default, $this->getDefaultFuturePayParameters());
    }

    return $default;
  }

  /**
   * Send
   *
   * Send the request straight to WorldPay
   *
   * @return HttpRedirectResponse
   */
  public function send()
  {
    return HttpRedirectResponse::create($this->createRequest())->send();
  }

  /**
   * Prepare
   *
   * Prepare the request so that it can be sent asynchronously
   *
   * @return array
   */
  public function prepare()
  {
    return array(
      'endpoint'  => $this->getEndPoint(),
      'data'      => $this->getData(),
      'signature' => $this->getSignature()
    );
  }

  /**
   * Get Default Parameters
   *
   * @return array
   */
  protected function getDefaultParameters()
  {
    return array('instId', 'cartId', 'currency', 'amount');
  }

  /**
   * Get Default FuturePay Parameters
   *
   * @return array
   */
  protected function getDefaultFuturePayParameters()
  {
    return array('futurePayType');
  }

  /**
   * Get Data
   *
   * Return all the parameters from the ParameterBag
   *
   * @return array
   */
  protected function getData()
  {
    return $this->parameters->all();
  }

  /**
   * Get Signature
   *
   * @return string
   */
  protected function getSignature()
  {
    $fields = $this->getSignatureFieldsData($this->getSignatureFields(), $this->getData());

    return md5($this->secret.':'.implode(':', $fields));
  }

  /**
   * Get Signature Fields Data
   *
   * @var array $fields
   * @var array $data
   * @return array
   */
  protected function getSignatureFieldsData($fields, $data)
  {
    foreach($fields as $field)
    {
      $signature[$field] = $data[$field];
    }

    return $signature;
  }

  /**
   * Create Request
   *
   * Concatenate the endpoint and the data to send
   *
   * @return string
   */
  protected function createRequest()
  {
    return $this->getEndpoint().$this->getQueryString();
  }

  /**
   * Get Endpoint
   *
   * Returns the correct endpoint based on the environement
   * If a custom environment is set, the request URL must be
   * set in the configuration array.
   *
   * @return string
   */
  protected function getEndpoint()
  {
    if(!$this->isCustomEnv())
    {
      return $this->endpoints[$env];
    }

    if(isset($this->config['url']))
    {
      return $this->config['url'];
    }

    throw new InvalidRequestException('You need to set a callback URL');
  }

  /**
   * Get Query String
   *
   * @return string
   */
  protected function getQueryString()
  {
    return '?signature='.$this->getSignature().'?'.http_build_query($this->getData());
  }

  /**
   * Set Environment Parameter
   *
   * @var string $value
   * @return void
   */
  protected function setEnvironmentParameter($value)
  {
    $this->parameters->set('testMode', Translate::setTestMode($value));
  }

  /**
   * Set Description Parameter
   *
   * The description of this transaction for your reference
   *
   * @var string $value
   * @return void
   */
  protected function setDescriptionParameter($value)
  {
    $this->parameters->set('desc', $value);
  }

  /**
   * Set Address Line 1 Parameter
   *
   * @var string $value
   * @return void
   */
  protected function setAddressLine1Parameter($value)
  {
    $this->parameters->set('address1', $value);
  }

  /**
   * Set Address Line 2 Parameter
   *
   * @var string $value
   * @return void
   */
  protected function setAddressLine2Parameter($value)
  {
    $this->parameters->set('address2', $value);
  }

  /**
   * Set Address Line 3 Parameter
   *
   * @var string $value
   * @return void
   */
  protected function setAddressLine3Parameter($value)
  {
    $this->parameters->set('address3', $value);
  }

  /**
   * Set Telephone Parameter
   *
   * @var string $value
   * @return void
   */
  protected function setTelephoneParameter($value)
  {
    $this->parameters->set('telephone', $value);
  }

  /**
   * Set FuturePay Type Parameter
   *
   * Required for FuturePay transactions.
   *
   * @var string $value
   * @return void
   */
  protected function setFuturePayTypeParameter($value)
  {
    if($value == 'limited' || $value == 'regular')
    {
      $this->parameters->set('futurePayType', $value);
    }else{
      throw new InvalidRequestException('Invalid FuturePay type');
    }
  }

  /**
   * Set Option Parameter
   *
   * Required for FuturePay transactions.
   *
   * @var int $value
   * @return void
   */
  protected function setOptionParameter($value)
  {
    $this->parameters->set('option', $value);
  }

  /**
   * Set Start Date Parameter
   *
   * This is the date from which all payments can occur.
   *
   * Required for both 'limited' and 'regular' Agreement Types.
   * Required for all Options.
   *
   * The date must be set to a date in the future.
   *
   * Format: yyyy-mm-dd.
   *
   * @var string $value
   * @return void
   */
  protected function setStartDateParameter($value)
  {
    $date = new Carbon($value);

    if($date->isFuture())
    {
      $this->parameters->set('startDate', $date->toDateString());
    }
  }

  /**
   * Number of Payments Parameter
   *
   * Required for both 'limited' and 'regular' Agreement Types.
   * Required for all 'regular' options and Options 1, 2 of 'limited' agreements.
   * Leave unset for unlimited payments.
   * Must be a positive integer.
   *
   * @var int $value
   * @return void
   */
  protected function setNumberOfPaymentsParameter($value)
  {
    if($value > 0)
    {
      $this->parameters->set('noOfPayments', $value);
    }
  }

  /**
   * Set Interval Parameter
   *
   * The interval between payments. This method accepts a string
   * which is then converted into the unit and multiplier.
   *
   * Can be set for both 'limited' and 'regular' Agreement Types.
   *
   * e.g '2 weeks' = intervalUnit = 2
   *               = intervalMult = 2
   *
   * intervalUnit
   * ------------
   * Regular - Must be set except when the number of payments is 1,
   *           in which case it cannot be set. Options 1, 2 have a
   *           minium of 2 weeks
   * Limited - Option 0 - Can be left unset. Must not be set if number of payments is 1
   *           Option 1 - Must be set
   *           Option 2 - N/A, must NOT be set
   *           Option 3 - Must be set
   *
   * intervalMult
   * ------------
   * Regular - If set, must be >= 1
   * Limited - Option 0 - Can be left unset. Must not be set if number of payments is 1
   *           Option 1 - Must always be set to 1
   *           Option 2 - Must not be set
   *           Option 3 - Must always be set to 1
   *
   * @var string $value
   * @return void
   */
  protected function setIntervalParameter($value)
  {
    $chunks = explode(' ', $value);

    // if not integer throw exception
    $this->parameters->set('intervalMult', $chunks[0]);
    $this->parameters->set('intervalUnit', Translate::unit($chunks[1]));
  }

  /**
   * Set Initial Amount Parameter
   *
   * The initial amount to charge the customer
   *
   * Only required for 'regular' Agreement Type
   * If it is not set, the first payment will be for the normal amount
   *
   * Option 0 - Optional
   * Option 1 - Optional
   * Option 2 - Cannot be set
   *
   * @var decimal $value
   * @return void
   */
  protected function setInitialAmount($value)
  {
    $this->parameters->set('initialAmount', $value);
  }

  /**
   * Set Normal Amount Parameter
   *
   * The normal amount to charge the customer
   *
   * Only required for 'regular' Agreement Type
   *
   * Option 0 - Required
   * Option 1 - Required
   * Option 2 - Cannot be set. You have to set it before each payment.
   *
   * @var decimal $value
   * @return void
   */
  protected function setNormalAmount($value)
  {
    $this->parameters->set('normalAmount', $value);
  }

  /**
   * Set Amount Limit Parameter
   *
   * Payment amount limit
   *
   * Only required for 'limited' Agreement Type
   *
   * Option 0 - Individual payment amount limit
   *            Leave unset for unlimited
   * Option 1 - Individual payment amount limit
   *            Must be set to a value greater than zero
   * Option 2 - Agreement payment amount limit
   *            Must be set to a value greater than zero
   * Option 3 - Payment amount limit for interval period
   *            Must be set to a value greater than zero
   *
   * @var decimal $value
   * @return void
   */
  protected function setAmountLimitParameter($value)
  {
    $this->parameters->set('amountLimit', $value);
  }

  /**
   * Set End Date Parameter
   *
   * The End date of the agreement, past which no payments are possible
   *
   * Only required for 'limited' Agreement Type
   *
   * Format: yyyy-mm-dd
   * @var string $value
   * @return void
   */
  protected function setEndDateParameter($value)
  {
    $date = new Carbon($value);

    if($date->isFuture())
    {
      $this->parameters->set('endDate', $date->toDateString());
    }
  }

}
