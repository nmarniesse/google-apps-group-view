<?php

namespace GoogleGroupView\Domain\Model;

use Google_Service_Directory_Group;
use Google_Service_Directory_Members;

/**
 * Decorator for Google_Service_Directory_Group
 */
class Group
{
    /**
     * @var Google_Service_Directory_Group
     */
    public $groupDecorated;

    /**
     * @var Google_Service_Directory_Members
     */
    public $members;

    /**
     * @param Google_Service_Directory_Group $group
     */
    public function __construct(Google_Service_Directory_Group $group)
    {
        $this->groupDecorated = $group;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->groupDecorated, $name)) {
            return call_user_func([$this->groupDecorated, $name], $arguments);
        }
        throw new \Exception("Method $name does not exist");
    }

    /**
     * @return Google_Service_Directory_Members
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * [setMembers description]
     * @param Group $this
     */
    public function setMembers(Google_Service_Directory_Members $members)
    {
        $this->members = $members;

        return $this;
    }
}
