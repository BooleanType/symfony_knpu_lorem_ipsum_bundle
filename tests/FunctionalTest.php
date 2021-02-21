<?php

namespace KnpU\LoremIpsumBundle\Tests;

use KnpU\LoremIpsumBundle\KnpUIpsum;
use KnpU\LoremIpsumBundle\KnpULoremIpsumBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use KnpU\LoremIpsumBundle\WordProviderInterface;

class FunctionalTest extends TestCase
{
    public function testServiceWiring()
    {
        $kernel = new KnpULoremIpsumTestingKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        
        $ipsum = $container->get('knpu_lorem_ipsum.knpu_ipsum');
        $this->assertInstanceOf(KnpUIpsum::class, $ipsum);
        $this->assertIsString($ipsum->getParagraphs());
    }
}

class KnpULoremIpsumTestingKernel extends Kernel
{   
    public function __construct()
    {
        // Params: string $environment, bool $debug.
        parent::__construct('test', true);
    }
    
    public function registerBundles()
    {
        return [
            new KnpULoremIpsumBundle(),
        ];
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function(ContainerBuilder $container) {
            /**
             * Registers a service definition (register our StubWordList as a service).
             * This methods allows for simple registration of service definition
             * with a fluid interface.
             * @return Definition A Definition instance
             */
            $container
                ->register('stub_word_list', StubWordList::class)
                ->addTag('knpu_ipsum_word_provider')
            ;
        });
    }
    
    public function getCacheDir()
    {
        // Cur. dir here is 'D:/server/www/LoremIpsumBundle' (check by getcwd()),
        // when we run test in console:
        //      $ D:\server\www\LoremIpsumBundle>vendor\bin\simple-phpunit
        // So, not '../var/cache/...', but 'var/cache/...' or './var/cache/...'.
        return './var/cache/'.spl_object_hash($this);
    }
}

class StubWordList implements WordProviderInterface
{
    public function getWordList(): array
    {
        return ['stub', 'stub2'];
    }

}