<?php
/**
 * Created by JetBrains PhpStorm.
 * User: juanda
 * Date: 4/11/13
 * Time: 0:17
 * To change this template use File | Settings | File Templates.
 */

namespace Jazzyweb\CmfBundle\Command;


use PHPCR\Util\NodeHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CmfPagesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('jwcmf:routes-content-menu:load')
            ->setDescription('An example')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $documentManager = $this->getContainer()->get('doctrine_phpcr')->getManager();

        ////////////////////
        // Rutas traducibles
        ////////////////////
        $routeRoot = $documentManager->find(null, '/cms/routes');

        $routeHome_es = new Route();
        $routeHome_es->setPosition($routeRoot, 'inicio');
        // Esto es imprescindible para que se pinte el contenido en el idioma correcto
        $routeHome_es->setDefault('_locale','es');
        // Esto es imprescindible para que se genere la ruta a partir del contenido en el idioma correcto
        $routeHome_es->setRequirement('_locale', 'es');

        $documentManager->persist($routeHome_es);

        $routeHome_en = new Route();
        $routeHome_en->setPosition($routeRoot, 'hello');
        $routeHome_en->setDefault('_locale','en');
        $routeHome_en->setRequirement('_locale', 'en');

        $documentManager->persist($routeHome_en);

        $routeAbout = new Route();
        $routeAbout->setPosition($routeRoot, 'about');

        $documentManager->persist($routeAbout);

        ////////////////////////////////
        // Contenido estático traducible
        ////////////////////////////////
        $contentRoot = $documentManager->find(null, '/cms/content');

        $contentHome = new StaticContent();
        $contentHome->setTitle('Homepage');
        $contentHome->setBody('This is the homepage. In english, of course');
        $contentHome->setParent($contentRoot);
        $contentHome->setName('homepage');

        // las dos rutas asociadas a este contenido, una para inglés otra para español
        $contentHome->addRoute($routeHome_en);
        $contentHome->addRoute($routeHome_es);


        $documentManager->persist($contentHome);
        $documentManager->bindTranslation($contentHome, 'en');

        // Añadimos la traducción del contenido en español
        $contentHome->setTitle('Página de inicio');
        $contentHome->setBody('Esta es la página de inicio');
        $documentManager->bindTranslation($contentHome, 'es');

        // Otro contenido (extension del StaticContent)
        $contentAbout = new \Jazzyweb\CmfBundle\Document\StaticContent();
        $contentAbout->setTitle('About');
        $contentAbout->setBody('About us');
        $contentAbout->setParent($contentRoot);
        $contentAbout->setName('about');
        $contentAbout->addRoute($routeAbout);

        $documentManager->persist($contentAbout);

        ////////////////////
        // Menús traducibles
        ////////////////////
        $menuRoot = $documentManager->find(null, '/cms/menu');

        // El menú principal
        $menu = new Menu();
        $menu->setName('main-menu');
        $menu->setLabel('Main Menu');
        $menu->setParent($menuRoot);

        $documentManager->persist($menu);

        // Item home
        $menuHome = new MenuNode();
        $menuHome->setName('home');
        $menuHome->setLabel('Home');
        $menuHome->setParent($menu);
        $menuHome->setContent($contentHome);

        $documentManager->persist($menuHome);
        $documentManager->bindTranslation($menuHome, 'en');

        $menuHome->setLabel('Inicio');
        $documentManager->bindTranslation($menuHome, 'es');

        $menuAbout = new MenuNode();
        $menuAbout->setName('about');
        $menuAbout->setLabel('About');
        $menuAbout->setParent($menu);
        $menuAbout->setContent($contentAbout);

        $documentManager->persist($menuAbout);

        $documentManager->flush(); // sav
    }


}