<?php

namespace Gema\ArchivadorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gema_archivador');
        
        $rootNode
                ->children()                    
                    ->arrayNode('archivo')
                        ->children()
                            ->scalarNode('activo')->defaultFalse()->end()
                            ->scalarNode('tam_maximo')->defaultValue('1024')->end()                            
                            ->scalarNode('ruta')->defaultValue('../privado/archivos')->end()                            
                            ->scalarNode('nombre')->defaultFalse()->end()
                            ->scalarNode('tags')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()
        ;  
       
        return $treeBuilder;
    }
}
