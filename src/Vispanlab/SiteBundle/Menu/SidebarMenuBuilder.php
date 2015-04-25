<?php
namespace Vispanlab\SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class SidebarMenuBuilder
{
    private $factory;
    /**
     * @var EntityManager
     */
    private $em;

    private $securityContext;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, EntityManager $entityManager, $securityContext)
    {
        $this->factory = $factory;
        $this->em = $entityManager;
        $this->securityContext = $securityContext;
    }

    public function createSideMenu(Request $request)
    {
        $areasofexpertise = $this->em->getRepository('Vispanlab\SiteBundle\Entity\AreaOfExpertise')->findAll();
        $menu = $this->factory->createItem('root');

        foreach($areasofexpertise as $curArea) {
            $areaMenu = $menu->addChild($curArea->getName(), array('uri' => '#', 'attributes' => array('class' => 'home')));
            $areaMenu->addChild('Βιβλιοθήκη Εννοιών', array('route' => 'concept_library', 'routeParameters' => array('aoe' => $curArea->getUrl()), 'attributes' => array('class' => 'home')));
            $areaMenu->addChild('Εικονικές Ασκήσεις', array('route' => 'virtual_exercises', 'routeParameters' => array('aoe' => $curArea->getUrl()), 'attributes' => array('class' => 'home')));
        }

        return $menu;
    }

    public function createBottomMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        if($this->securityContext->isGranted('ROLE_ADMIN') || $this->securityContext->isGranted('ROLE_AREA_ADMIN')) {
            if(strpos($request->getRequestUri(), 'admin') === false) {
                $menu->addChild('common.admin_env_link', array('route' => 'sonata_admin_dashboard', 'attributes' => array('class' => 'home')));
            } else {
                $menu->addChild('common.user_env_link', array('route' => 'home', 'attributes' => array('class' => 'home')));
            }
        }
        $menu->addChild('Οδηγίες Χρήσης', array('uri' => '#docs', 'attributes' => array('class' => 'home')));

        return $menu;
    }
}