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

  public function test_formata_periodo()
  {
    $this->assertEquals('1 dia', Generic::formata_periodo('01/01/2020','02/01/2020'));
    $this->assertEquals('1 ano', Generic::formata_periodo('01/01/2020','01/01/2021'));
    $this->assertEquals('1 ano, 1 mês e 2 dias', Generic::formata_periodo('01/01/2020','03/02/2021'));
    $this->assertEquals('2 anos, 4 meses e 2 dias', Generic::formata_periodo('01/01/2020','03/05/2022'));
    $this->assertEquals('4 meses e 1 dia', Generic::formata_periodo('01/01/2020','02/05/2020'));
    $this->assertEquals('4 meses', Generic::formata_periodo('01/01/2020','01/05/2020'));
    $this->assertEquals('1 ano e 3 meses', Generic::formata_periodo('01/01/2020','01/04/2021'));
  }
}
