<?php namespace Worldpay;

class Helper {

  /**
   * Convert a string to camelcase
   *
   * e.g hello_world -> helloWorld
   *
   * @var string $str
   * @return string
   */
  public static function camelise($str)
  {
    return preg_replace_callback('/_([a-z0-9])/', function ($m) {
        return strtoupper($m[1]);
      },
      $str
    );
  }

  /**
   * Convert a Paramater key into a full method name
   *
   * e.g first_name -> getFirstNameParameter
   *
   * @var string $type ('get'|'set')
   * @var string $key
   * @return string
   */
  public static function convertParamMethodName($type = 'get', $key)
  {
    return $type.ucfirst(self::camelise($key)).'Parameter';
  }

}
