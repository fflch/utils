<?php
namespace Tests;

use Uspdev\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
  public function test_partially_email()
  {
    $from = 'maria.jose.gomes@gmail.com';
    $to = 'maria.jo********@gmail.com';
    $this->assertEquals($to, Utils::partially_email($from));
  }
}