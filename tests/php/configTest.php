<?php

// src/public/index.php
namespace CharlieJackson\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigClassExists()
    {
        $config = new \CharlieJackson\Config\Config;
    }

    /**
     * @depends testConfigClassExists
     */
    public function testGetConfigMethodExists()
    {
        $config = new \CharlieJackson\Config\Config;

        $this->assertTrue(
            method_exists($config, 'getConfig'),
            'Class does not have method getConfig'
        );
    }

    /**
     * @depends testGetConfigMethodExists
     */
    public function testGetConfigReturnsObject()
    {
        $config = new \CharlieJackson\Config\Config;
        $config_json = $config->getConfig();

        $this->assertTrue(is_object($config_json));
    }
}
