<?php

namespace GoogleGroupView\Action;

use GoogleGroupView\Responder\GetGroupsAndMembersResponder;

final class GetGroupsAndMembers implements ActionInterface
{
    /**
     * @var Silex\Application
     */
    private $app;

    /**
     * @var GetGroupsAndMembersResponder
     */
    private $responder;

    /**
     * @param Silex\Application $app
     */
    public function __construct($app)
    {
        $this->app       = $app;
        $this->responder = new GetGroupsAndMembersResponder($app);
    }

    /**
     * @return Silex\Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param  string $domainRestrict Restriction sur le domaine
     * @return string
     */
    public function __invoke($domainRestrict = '')
    {
        $listGroup = $this->app['group_service']->getGroups([], $domainRestrict);
        $responder = $this->responder;

        return $responder($listGroup);
    }
}
