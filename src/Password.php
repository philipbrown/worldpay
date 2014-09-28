<?php namespace PhilipBrown\WorldPay;

use Assert\Assertion;

class Password
{
    /**
     * @var string
     */
    private $password;

    /**
     * Create a new Password
     * @param string $password
     * @return void
     */
    private function __construct($password)
    {
        Assertion::string($password);

        $this->password = $password;
    }

    /**
     * Set the Password
     *
     * @param string $password
     * @return Password
     */
    public static function set($password)
    {
        return new Password($password);
    }

    /**
     * Return the Password when cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->password;
    }
}
