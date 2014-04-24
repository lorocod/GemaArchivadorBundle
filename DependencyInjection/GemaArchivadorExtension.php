<?php

namespace Gema\ArchivadorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GemaArchivadorExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        
        
        if ($config['archivo']['activo']) {
            $container->setParameter('gema_archivador.archivo.tam_maximo', $config['archivo']['tam_maximo']);
            $container->setParameter('gema_archivador.archivo.ruta', $config['archivo']['ruta']);
            $container->setParameter('gema_archivador.archivo.nombre', $config['archivo']['nombre']);
            $container->setParameter('gema_archivador.archivo.tags', $config['archivo']['tags']);
//            $loader->load('pdf.xml');
//            $container->setParameter('knp_snappy.pdf.binary', $config['pdf']['binary']);
//            $container->setParameter('knp_snappy.pdf.options', $config['pdf']['options']);
//            $container->setParameter('knp_snappy.pdf.env', $config['pdf']['env']);
        }
    }
}
