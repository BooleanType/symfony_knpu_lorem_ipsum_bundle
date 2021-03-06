<?php

namespace KnpU\LoremIpsumBundle;

use KnpU\LoremIpsumBundle\DependencyInjection\KnpULoremIpsumExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\AddEventAliasesPass;
use KnpU\LoremIpsumBundle\Event\FilterApiResponseEvent;
use KnpU\LoremIpsumBundle\DependencyInjection\Compiler\WordProviderCompilerPass;

class KnpULoremIpsumBundle extends Bundle
{
    /**
     * $this->extension is overridden to allow for the custom extension alias.
     * Otherwise getContainerExtension() from D:\server\www\first_project\vendor\symfony\http-kernel\Bundle\Bundle.php
     * is called. And there is a check:
     * 
     *  $basename = preg_replace('/Bundle$/', '', $this->getName()); // 'KnpULoremIpsum'
     *  $expectedAlias = Container::underscore($basename); // 'knp_u_lorem_ipsum'
     * 
     *  // $extension->getAlias() below is overriden in D:\server\www\first_project\lib\LoremIpsumBundle\src\DependencyInjection\KnpULoremIpsumExtension.php
     *  // and its val is 'knpu_lorem_ipsum'.
     *  if ($expectedAlias != $extension->getAlias()) { // 'knp_u_lorem_ipsum' != 'knpu_lorem_ipsum'
     *      throw new \LogicException(sprintf('Users will expect the alias of the default extension...
     * 
     * We don't want this check, so just override getContainerExtension().
     * Method below does the same thing as the parent method, but without that sanity check.
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            // $this->extension = $this->createContainerExtension(); // This will work too.
            $this->extension = new KnpULoremIpsumExtension();
        }
        return $this->extension;
    }
    
    public function build(ContainerBuilder $container)
    {
        // See src/EventSubscriber/AddMessageToIpsumApiSubscriber::getSubscribedEvents().
        $container->addCompilerPass(new AddEventAliasesPass([
            FilterApiResponseEvent::class => 'knpu_lorem_ipsum.filter_api',
        ]));
        
        $container->addCompilerPass(new WordProviderCompilerPass());
    }
}
