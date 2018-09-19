<?php

namespace Tests\Core\Service;

use Core\Entity\AppParameters;
use Core\Exception\SecurityException;
use Core\Handler\SecurityCheck;
use Tests\Core\BaseTest;

class SecurityHandlerTest extends BaseTest
{
    /**
     *
     */
    public function testRestrictedDomains()
    {
        $this->expectException(SecurityException::class);
        $this->expectExceptionMessage("Restricted domains enabled, the domain your fetching from is not allowed:");
        /** @var AppParameters $appParameters */
        $appParameters = clone $this->app['params'];
        $appParameters->addParameter('restricted_domains', true);
        $securityHandler = new SecurityCheck($appParameters);

        $securityHandler->checkRestrictedDomains(parent::PNG_TEST_IMAGE);
    }

    /**
     *
     */
    public function testCheckSecurityHashFail()
    {
        $this->expectException(SecurityException::class);
        $this->expectExceptionMessage(
            "Security Key enabled: Requested URL doesn't match with the hashed Security key !"
        );
        /** @var AppParameters $appParameters */
        $appParameters = clone $this->app['params'];
        $appParameters->addParameter('security_key', 'TestSecurityKey');
        $appParameters->addParameter('security_iv', 'TestSecurityIVXXXX');
        $this->checkSecurityHash($appParameters);
    }

    /**
     *
     */
    public function testSecurityKeyMissing()
    {
        $this->expectException(SecurityException::class);
        $this->expectExceptionMessage(
            "security_key in empty im parameters.yml!"
        );
        /** @var AppParameters $appParameters */
        $appParameters = clone $this->app['params'];
        $appParameters->addParameter('security_key', '');
        $securityHandler = new SecurityCheck($appParameters);
        $securityHandler->encrypt(parent::OPTION_URL.'/'.parent::JPG_TEST_IMAGE);
    }

    /**
     *
     */
    public function testCheckSecurityIvMissing()
    {
        $this->expectException(SecurityException::class);
        $this->expectExceptionMessage("Security iv is not set in parameters.yml (security_iv)");
        /** @var AppParameters $appParameters */
        $appParameters = clone $this->app['params'];
        $appParameters->addParameter('security_key', 'TestSecurityKey');
        $appParameters->addParameter('security_iv', '');
        $this->checkSecurityHash($appParameters);
    }

    /**
     *
     */
    public function testCheckSecurityHashSuccess()
    {
        /** @var AppParameters $appParameters */
        $appParameters = clone $this->app['params'];
        $appParameters->addParameter('security_key', 'TestSecurityKey');
        $appParameters->addParameter('security_iv', 'TestSecurityIVXXXX');
        $securityHandler = new SecurityCheck($appParameters);
        $options = parent::OPTION_URL;
        $imageSrc = parent::JPG_TEST_IMAGE;
        $hash = $securityHandler->encrypt($options.'/'.$imageSrc);
        list($hashedOptions, $hashedImageSrc) = $securityHandler->checkSecurityHash($hash, $imageSrc);
        $this->assertEquals($hashedOptions, $options);
        $this->assertEquals($hashedImageSrc, $imageSrc);
    }

    /**
     *
     */
    public function testEncryptionDecryption()
    {
        /** @var AppParameters $appParameters */
        $appParameters = clone $this->app['params'];
        $appParameters->addParameter('security_key', 'TestSecurityKey');
        $appParameters->addParameter('security_iv', 'TestSecurityIVXXXX');

        $securityHandler = new SecurityCheck($appParameters);
        $randomString = str_shuffle('AKALEOCJCNXMSOLWO5#KXMw');
        $hashedString = $securityHandler->encrypt($randomString);
        $decryptedString = $securityHandler->decrypt($hashedString);
        $this->assertEquals($decryptedString, $randomString);
    }

    /**
     * @param $appParameters
     */
    protected function checkSecurityHash($appParameters): void
    {
        $securityHandler = new SecurityCheck($appParameters);
        $options = parent::OPTION_URL;
        $imageSrc = parent::JPG_TEST_IMAGE;
        $securityHandler->checkSecurityHash($options, $imageSrc);
    }
}
