<?php
/**
 * Static Content Extension example.
 *
 * This class is mapped in Resources/config/doctrine/StaticContent.phpcr.xml
 * Every document of this class is rendered by JwCmfBundle::jwstatic.html.twig as
 * defined in config.yml
 */
namespace Jazzyweb\CmfBundle\Document;

use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent as BaseStaticContent;


class StaticContent extends BaseStaticContent {

}