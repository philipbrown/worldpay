<?php namespace PhilipBrown\WorldPay;

use Assert\Assertion;

class InstId
{
    /**
     * @var string
     */
    private $id;

    /**
     * Create a new InstId
     * @param string $id
     * @return void
     */
    private function __construct($id)
    {
        Assertion::string($id);

        $this->id = $id;
    }

    /**
     * Set the InstId
     *
     * @param string $id
     * @return InstId
     */
    public static function set($id)
    {
        return new InstId($id);
    }

    /**
     * Return the InstId when cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }
}
