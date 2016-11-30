<?php

namespace GoogleGroupView\Action;

use GoogleGroupView\Responder\GetGroupsAndMembersResponder;

final class GetGroupsAndMembers
{
    const FILE_CACHE = 'get_groups_and_members_cache.txt';

    /**
     * @var Silex\Application
     */
    private $app;
    private $responder;

    /**
     * @param Silex\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->responder = new GetGroupsAndMembersResponder($app);
    }

    /**
     * @param  string $domainRestrict 
     * @return string
     */
    public function __invoke($domainRestrict = '')
    {
        if ($this->useCache()) {
            return $this->loadCache();
        }
        $listGroup = $this->app['group_service']->getGroups([], $domainRestrict);

        $responder = $this->responder;

        $response = $responder($listGroup);
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
        return file_exists(self::FILE_CACHE) && (time() - filemtime(self::FILE_CACHE) < $this->app['app_config']->getCache());
    }

    /**
     * Load response from cache
     * @return string
     */
    protected function loadCache()
    {
        return file_exists(self::FILE_CACHE) ? file_get_contents(self::FILE_CACHE) : '';
    }

    /**
     * Save response to cache
     * @param  string $response
     */
    protected function saveCache($response)
    {
        file_put_contents(self::FILE_CACHE, $response);
    }

    /**
     * Return if the cache is activated in config
     * @return bool
     */
    protected function cacheIsActivated()
    {
        return $this->app['app_config']->cacheIsActivated();
    }
}
