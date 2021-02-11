<?php

namespace KnpU\LoremIpsumBundle\Tests\Controller;

use KnpU\LoremIpsumBundle\KnpULoremIpsumBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use KnpU\LoremIpsumBundle\Event\FilterApiResponseEvent;
use KnpU\LoremIpsumBundle\Event\KnpULoremIpsumEvents;

class IpsumApiControllerTest extends TestCase
{
    public function testIndex()
    {
        $kernel = new KnpULoremIpsumControllerKernel();
        $client = new HttpKernelBrowser($kernel);
        $client->request('GET', '/api/');
        // Or we can call it like we configured IpsumApiController::index() calling in "knpu_lorem_ipsum.yaml",
        // (but in this case we also should change prefix in configureRoutes() below):
        // $client->request('GET', '/api/ipsum/');
        
        var_dump($client->getResponse()->getContent());
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}

class KnpULoremIpsumControllerKernel extends Kernel implements EventSubscriberInterface
{
    use MicroKernelTrait;
    
    public function __construct()
    {
        parent::__construct('test', true);
    }
    
    public function registerBundles()
    {
        return [
            new KnpULoremIpsumBundle(),
            new FrameworkBundle(),
        ];
    }
    
    /**
     * ->prefix('/api') - это метод Symfony\Component\Routing\Loader\Configurator\ImportConfigurator,
     * кот-ый возвр-ется ->import()'ом. Это то же самое, что исп-вать аннотацию @Route для всего
     * контроллера, кот-ая опр-яет первую, общую часть маршрута для всех экшенов в этом контроллере.
     * @see https://symfony.com/doc/current/routing.html#route-groups-and-prefixes
     * @param RoutingConfigurator $routes
     */
    protected function configureRoutes(RoutingConfigurator $routes)
    {
        $routes->import(__DIR__.'/../../src/Resources/config/routes.xml')->prefix('/api');
        // Or prefix is /api/ipsum, if we want the same config as in "knpu_lorem_ipsum.yaml":
        // $routes->import(__DIR__.'/../../src/Resources/config/routes.xml')->prefix('/api/ipsum');
    }
    
    protected function configureContainer(ContainerConfigurator $c)
    {
        /**
         * Loads the configuration for an extension (like we do it in "knpu_lorem_ipsum.yaml").
         * В данном случае грузим конф-цию для расшир-я 'framework' (см. "config/packages/framework.yaml").
         * Аналогично происх-ит в FunctionalTest::registerContainerConfiguration() (см. $container->loadFromExtension()).
         * @param string $extension The extension alias or namespace
         * @param array  $values    An array of values that customizes the extension
         */
        $c->extension(
            'framework',
            [
                'secret' => 'F00', // Отс-ие этого пар-ра не вызывало ошибку, я просто скопировала это, как в касте.
                'router' => ['utf8' => true],
            ]
        );
    }
    
    // Для тестир-я соб-я KnpULoremIpsumEvents::FILTER_API.
    public static function getSubscribedEvents()
    {
        return [
            KnpULoremIpsumEvents::FILTER_API => 'onFilterApi',
        ];
    }
    
    public function onFilterApi(FilterApiResponseEvent $event)
    {
        $data = $event->getData();
        $data['message'] = 'Have a magical day!';
        $event->setData($data);
    }
    
    public function getCacheDir()
    {
        return './var/cache/'.spl_object_hash($this);
    }
}