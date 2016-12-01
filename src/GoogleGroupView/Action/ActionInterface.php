<?php

namespace GoogleGroupView\Action;

interface ActionInterface
{
    public function __invoke();
    public function getApp();
}
