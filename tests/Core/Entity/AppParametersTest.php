<?php

namespace Tests\Core\Entity;

use Core\Entity\AppParameters;
use Core\Exception\FlyimgException;

class AppParametersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test SaveToTemporaryFileException
     */
    public function testParamFileNotExist()
    {
        $this->expectException(FlyimgException::class);
        $this->expectExceptionMessage('Parameter file not found at : not_existing_file.yml');
        new AppParameters('not_existing_file.yml');
    }
}
