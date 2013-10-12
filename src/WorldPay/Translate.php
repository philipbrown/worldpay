<?php namespace WorldPay;

class Translate {

  /**
   * Set Test Mode
   *
   * Convert WorldPay environment from string to integer
   *
   * @var $input string
   * @return integer
   */
  public static function setTestMode($input)
  {
    if($input == 'production'){return 0;}

    return 100;
  }

  /**
   * Get Test Mode
   *
   * Convert WorldPay environment from integer to string
   *
   * @var $input integer
   * @return string
   */
  public static function getTestMode($input)
  {
    if($input == 0){return 'production';}

    return 'development';
  }

  public static function unit($input)
  {
    $units = array('day' => 1, 'week' => 2, 'month' => 3, 'year' => 4);

    return $units[rtrim($input, 's')];
  }

}
