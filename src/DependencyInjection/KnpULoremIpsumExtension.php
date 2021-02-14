<?php

namespace KnpU\LoremIpsumBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use KnpU\LoremIpsumBundle\WordProviderInterface;

class KnpULoremIpsumExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // Опр-е $loader может идти и после опр-я $config, т.к. $config отвечает за конф-цию,
        // а $loader - за загрузку сервисов, опр-ённых в 'services.xml', т.е. опр-е этих блоков
        // не связано м/у собой.
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        // My Configuration instance (KnpU\LoremIpsumBundle\DependencyInjection\Configuration instance).
        // Если заглянуть в метод getConfiguration() в ядре, то видно, что $configs не исп-ется.
        $configuration = $this->getConfiguration($configs, $container);
        
        // The processConfiguration() method uses the configuration tree you’ve defined 
        // in the Configuration class to validate, normalize and merge all the configuration arrays together;
        // pass the configuration object and the original, raw array of $configs.
        $config = $this->processConfiguration($configuration, $configs);
        
        // pass the service id. This returns a Definition object, which holds 
        // the service's class name, arguments and a bunch of other stuff. 
        $definition = $container->getDefinition('knpu_lorem_ipsum.knpu_ipsum');

// Commented out because of compiler pass implementation (see https://symfonycasts.com/screencast/symfony-bundle/tags-compiler-pass )
//        if (null !== $config['word_provider']) {
//            //$definition->setArgument(0, [new Reference($config['word_provider'])]);
//            
//            // Вместо явной установки арг-та 'word_provider' как сервиса массива с сервисами new Reference($config['word_provider'])
//            // мы создаём алиас 'knpu_lorem_ipsum.word_provider' д/сервиса App\Service\CustomWordProvider.
//            // А в LoremIpsumBundle\src\Resources\config\services.xml как раз первый аргумент должен иметь
//            // алиас "knpu_lorem_ipsum.word_provider". И LoremIpsumBundle\src\KnpUIpsum.php автоматически
//            // подтянет нужный сервис по этому алиасу.
//            // Обратить вним-е, что пар-р $wordProviders в KnpU\LoremIpsumBundle\KnpUIpsum - это массив. Если исп-вать
//            // вар-т с new Reference(...), то нужно передавать именно массив - [new Reference($config['word_provider'].
//            // В случае же setAlias() мы просто создаём алиас 'knpu_lorem_ipsum.word_provider' д/каждого сервиса 
//            // App\Service\CustomWordProvider в коллекции (см. "lorem-ipsum-bundle\src\Resources\config\services.xml",
//            // эл-т <argument type="tagged_iterator" ... />).
//            $container->setAlias('knpu_lorem_ipsum.word_provider', $config['word_provider']);
//            $container
//                ->registerForAutoconfiguration($config['word_provider'])
//                ->addTag('knpu_ipsum_word_provider')
//            ;
//        }
        $definition->replaceArgument(1, $config['unicorns_are_real']);
        $definition->replaceArgument(2, $config['min_sunshine']);
        
        $container
            ->registerForAutoconfiguration(WordProviderInterface::class)
            ->addTag('knpu_ipsum_word_provider')
        ;
    }
    
    public function getAlias()
    {
        return 'knpu_lorem_ipsum';
    }
}
