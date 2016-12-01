<?php

namespace GoogleGroupView\Cache;

use GoogleGroupView\Action\ActionInterface;

class CacheAction implements ActionInterface
{
    /**
     * @var ActionInterface
     */
    private $decorated;

    /**
     * @var Silex\Application
     */
    private $app;

    private $cacheFilename;

    /**
     * @param ActionInterface $decorated
     */
    public function __construct(ActionInterface $decorated)
    {
        $this->decorated     = $decorated;
        $this->app           = $decorated->getApp();
        $this->cacheFilename = $this->calcCacheFilename();
    }

    /**
     * @return Silex\Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param  string $domainRestrict 
     * @return string
     */
    public function __invoke()
    {
        $arguments = func_get_args();

        if ($this->useCache()) {
            return $this->loadCache();
        }
        
        $decorated = $this->decorated;
        $response  = call_user_func_array($this->decorated, $arguments);

        if ($this->cacheIsActivated()) {
            $this->saveCache($response);
        }

        return $response;
    }

    /**
     * Return if we can use cache or not
     * @return bool
     */
    protected function useCache()
    {
        return file_exists($this->cacheFilename) && (time() - filemtime($this->cacheFilename) < $this->app['app_config']->getCache());
    }

    /**
     * Load response from cache
     * @return string
     */
    protected function loadCache()
    {
        return file_exists($this->cacheFilename) ? file_get_contents($this->cacheFilename) : '';
    }

    /**
     * Save response to cache
     * @param  string $response
     */
    protected function saveCache($response)
    {
        file_put_contents($this->cacheFilename, $response);
    }

    /**
     * Return if the cache is activated in config
     * @return bool
     */
    protected function cacheIsActivated()
    {
        return $this->app['app_config']->cacheIsActivated();
    }

    private function calcCacheFilename()
    {
        return $this->app['cache_folder'] . strtolower(str_replace('\\', '_', get_class($this->decorated))) . '.txt';
    }
}
