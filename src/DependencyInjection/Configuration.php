<?php

namespace KnpU\LoremIpsumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @see http://disq.us/p/2f6i3sp for 'use_default_provider' option explanation
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('knpu_lorem_ipsum');
        $rootNode = $treeBuilder->getRootNode();
        
        $rootNode
            ->children()
                ->booleanNode('unicorns_are_real')
                    ->defaultTrue()
                    ->info('Whether or not you believe in unicorns')
                    ->end()
                ->integerNode('min_sunshine')
                    ->defaultValue(3)
                    ->info('How much do you like sunshine?')
                    ->end()
                ->booleanNode('use_default_provider')
                    ->defaultTrue()
                    ->info('Do you want to use the default word provider?')
                    ->end()
// Commented out because of compiler pass implementation (see https://symfonycasts.com/screencast/symfony-bundle/tags-compiler-pass )
//                ->scalarNode('word_provider')
//                    ->defaultNull()
//                    ->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}
