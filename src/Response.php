<?php namespace PhilipBrown\WorldPay;

class Response
{
    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $body;

    /**
     * Create a new Response instance
     *
     * @param string $password
     * @param array $body
     * @return void
     */
    public function __construct($password, array $body)
    {
        $this->password = $password;
        $this->body     = $body;
    }

    /**
     * Assert if the response is valid
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->callbackPW === (string) $this->password;
    }

    /**
     * Assert if the request was a success
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->transStatus === 'Y';
    }

    /**
     * Assert if the request is valid
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->transStatus === 'C';
    }

    /**
     * Assert if the response is from the production environment
     *
     * @return bool
     */
    public function isProduction()
    {
        return $this->testMode == 0;
    }

    /**
     * Assert if the response is from the development environment
     *
     * @return bool
     */
    public function isDevelopment()
    {
        return $this->testMode == 100;
    }

    /**
     * Get the private attributes
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if(isset($this->body[$key])) {
            return $this->body[$key];
        }
    }
}
