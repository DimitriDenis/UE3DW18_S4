<?php

namespace Watson\Controller;

use Silex\Application;
use Watson\Domain\Link;
use Watson\Domain\User;
use Watson\Domain\Tag;
use Watson\Form\Type\LinkType;
use Watson\Form\Type\UserType;



class RssController
{

    /**
     * RSS controller.
     *
     * @param Application $app Silex application
     */
    public function functionRss(Application $app)
    {
        $links = $app['dao.link']->findLastLinks();
        return $app['twig']->render('rss.xml.twig', array('links' => $links));
    }
}