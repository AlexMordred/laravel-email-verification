<?php

namespace Voerro\SimplePackage\Test;

use Voerro\SimplePackage\SimplePackage;

class SimplePackageTest extends TestCase
{
    public function testAddition()
    {
        $result = SimplePackage::add(17, 3);

        $this->assertEquals(20, $result);
    }
}
