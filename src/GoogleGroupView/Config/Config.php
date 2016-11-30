<?php

namespace GoogleGroupView\Config;

use Symfony\Component\Yaml\Yaml;

class Config
{
    /**
     * @var array
     */
    public $config;

    public function __construct($configFile)
    {
        $this->config = Yaml::parse(file_get_contents($configFile));
    }

    /**
     * Return if the cache is activated in config
     * @return bool
     */
    public function cacheIsActivated()
    {
        return isset($this->config['app']['cache']) && $this->config['app']['cache'];
    }

    /**
     * @return int
     */
    public function getCache()
    {
        if ($this->cacheIsActivated()) {
            return $this->config['app']['cache'];
        }
        return 0;
    }
}
