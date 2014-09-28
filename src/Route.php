<?php namespace PhilipBrown\WorldPay;

use Assert\Assertion;

class Route
{
    /**
     * @var string
     */
    private $url;

    /**
     * @param string $url
     * @return void
     */
    private function __construct($url)
    {
        Assertion::url($url);

        $this->url = $url;
    }

    /**
     * Set the Route
     *
     * @param string $url
     * @return Route
     */
    public static function set($url)
    {
        return new Route($url);
    }

    /**
     * Return the Route when cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->url;
    }
}
