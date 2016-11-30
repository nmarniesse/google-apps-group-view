<?php

namespace GoogleGroupView\Action;

use GoogleGroupView\Responder\GetGroupsAndMembersResponder;

final class GetGroupsAndMembers
{
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
        $listGroup = $this->app['group_service']->getGroups([], $domainRestrict);

        $responder = $this->responder;
        return $responder($listGroup);
    }
}
