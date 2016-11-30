<?php

namespace GoogleGroupView\Domain\Service;

use Google_Client;
use Google_Service_Directory;
use GoogleGroupView\Domain\Model\Group;

class GroupService
{
    /**
     * @var Google_Service_Directory
     */
    private $googleServiceDirectory;

    public function __construct(Google_Client $client)
    {
        $this->googleServiceDirectory = new Google_Service_Directory($client);
    }

    /**
     * @return array of GoogleGroupView\Model\Group
     */
    public function getGroups($optParams = [], $domainRestrict = '')
    {
        if (!isset($optParams['customer'])) {
            $optParams['customer'] = 'my_customer';
        }

        $groups = $this->googleServiceDirectory->groups->listGroups($optParams)->getGroups();
        $res = [];
        foreach ($groups as $group) {
            if ($domainRestrict != '') {
                if (strpos($group->getEmail(), $domainRestrict) === false) {
                    continue;
                }
            }
            $myGroup = new Group($group);
            $myGroup->setMembers($this->getMembers($myGroup->getId()));
            $res[] = $myGroup;

            // break; // @todo : enlever !
        }
        return $res;
    }

    /**
     * @param  string $groupId
     * @return array of Members
     */
    public function getMembers($groupId)
    {
        return $this->googleServiceDirectory->members->listMembers($groupId);
    }
}