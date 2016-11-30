<?php

namespace GoogleGroupView\Responder;

final class GetGroupsAndMembersResponder extends Responder
{
    /**
     * @param  array $groups
     * @return string
     */
    public function __invoke($groups)
    {
        return $this->twig->render('group/groups.html.twig', [
            'groups' => $groups
        ]);
    }
}
