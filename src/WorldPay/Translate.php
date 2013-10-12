<?php namespace WorldPay;

class Translate {

  public static function setTestMode($input)
  {
    if($input == 'production'){return 0;}

    return 100;
  }

  public static function getTestMode($input)
  {
    if($input == 0){return 'production';}

    return 'development';
  }

}
