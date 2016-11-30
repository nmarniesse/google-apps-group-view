<?php

namespace GoogleGroupView\Responder;

/**
 * Generic responder
 */
class Responder
{
    protected $twig;

    /**
     * @param Silex\Application $app
     */
    public function __construct($app)
    {
        $this->twig = $app['twig'];
    }
}
