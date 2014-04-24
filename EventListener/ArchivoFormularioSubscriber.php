<?php
namespace Gema\ArchivadorBundle\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ArchivoFormularioSubscriber implements EventSubscriberInterface
{
    private $factory;
				    //FormFactoryInterface 
    public function __construct($factory)
    {
        $this->factory = $factory;
    }
    
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData',
		     FormEvents::PRE_BIND => 'preBinData'
		
		);
    }
    
    public function preBinData(FormEvent $event){
	$data = $event->getData();
        $form = $event->getForm();
//	var_dump($data["formtags"]);
	$tags = split(" ", $data["formtags"]);
	$data["tags"] = $tags;
	//var_dump($data);	
//	foreach ($tags as $tag) {
//	    $data->addTag(new \Gema\ArchivadorBundle\Entity\Tag($tag));
//	}
	
	
    }
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
	
	//var_dump($form->getData()->);
	// data es null cuando se crear el formulario.
        if (null === $data) {
            return;
        }
//var_dump($data);
        // comprueba si el objeto es nuevo
        if (!$data->getId()) {
//	    $tags = split(" ", $data->getFormtags());
//	    var_dump($data);
//	    foreach ($tags as $tag) {
//		$data->addTag(new \Gema\ArchivadorBundle\Entity\Tag($tag));
//	    }
        }
    }
}


?>