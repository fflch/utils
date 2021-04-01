<?php

namespace Uspdev\Tests;

use Uspdev\Utils\Generic;
use PHPUnit\Framework\TestCase;

class GenericTest extends TestCase
{
  public function test_partially_email()
  {
    $from = 'maria.jose.gomes@gmail.com';
    $to = 'maria.jo********@gmail.com';
    $this->assertEquals($to, Generic::partially_email($from));
  }
}
