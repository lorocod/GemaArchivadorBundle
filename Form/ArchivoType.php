<?php

namespace Gema\ArchivadorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArchivoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	
	$subscriber = new \Gema\ArchivadorBundle\EventListener\ArchivoFormularioSubscriber($builder->getFormFactory());
		
        $builder
            ->add('nombre')            
            ->add('file')
            ->add('formtags')
// se soluciono en prePersist	    ->addEventSubscriber($subscriber)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gema\ArchivadorBundle\Entity\Archivo'
        ));
    }

    public function getName()
    {
        return 'magyp_mensajebundle_archivotype';
    }
}
