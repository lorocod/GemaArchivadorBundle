<?php

namespace Gema\ArchivadorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\DependencyInjection\Container;

class ArchivoType extends AbstractType
{
    private $container;
    private $opciones;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->opciones['tam_maximo'] = $this->container->getParameter('gema_archivador.archivo.tam_maximo');
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	
        var_dump($this->opciones);
        
	$subscriber = new \Gema\ArchivadorBundle\EventListener\ArchivoFormularioSubscriber($builder->getFormFactory());
		
        $builder
            ->add('archivo','file')
            ->add('nombre')                        
            ->add('formtags')
            ->add('tam_maximo','hidden',array('data'=> $this->opciones['tam_maximo'] ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gema\ArchivadorBundle\Entity\Archivo'
        ));
    }

    public function getParent()
    {
        return 'form';
    }
    
    public function getName()
    {
        return 'archivo';
    }
}
