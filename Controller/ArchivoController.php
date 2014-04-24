<?php

namespace Gema\ArchivadorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/archivo")
 * 
*/

class ArchivoController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * @Route("/nuevo", name="archivo_nuevo")
     * @Template()
     */
    public function nuevoAction()
    {
        $archivo = new \Gema\ArchivadorBundle\Entity\Archivo();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new \Gema\ArchivadorBundle\Form\ArchivoType, $archivo);

        return array(	    
            'form' => $form->createView(),
                'archivos' => array()
        );
    }
    /**
     * @Route("/form/nuevo", name="archivo_form_nuevo")
     * @Template()
     */
    public function nuevoFormAction()
    {
        $archivo = new \Gema\ArchivadorBundle\Entity\Archivo();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new \Gema\ArchivadorBundle\Form\FormularioArchivoType(), null);

        return array(	    
            'form' => $form->createView(),
                'archivos' => array()
        );
    }

    /**
     * @Route("/crear", name="archivo_crear")
     * 
     */
    public function crearAction(Request $request)
    {
	echo "<pre>";
	$archivo = new \Gema\ArchivadorBundle\Entity\Archivo();
	$em = $this->getDoctrine()->getManager();
	$form = $this->createForm(new \Gema\ArchivadorBundle\Form\ArchivoType, $archivo);
	$form->bind($request);
	
	if ($form->isValid()) {
	    // subo archivo.
//	    echo "<pre>";	    
	    //echo sha1($archivo->getFile()->getPathname());
//	    echo sha1_file ($archivo->getFile()->getPathname());
//	    echo $archivo->getIdunico();
//	    var_dump($archivo->getFile());
//	    echo "</pre>";
//	  //  $archivo->upload();	
	    $tags = $tags = $em->getRepository("GemaArchivadorBundle:Tag")->seleccionar($archivo->getFormtags());
	    echo "se encontraron coincidencias en ".count ($tags) ." tags de [{$archivo->getFormtags()}]";
	    \Gema\ArchivadorBundle\Entity\Tag::setTagsDeBase($tags);
	    
	   // die();
	    //$a = new UploadedFile();
	    //var_dump($archivo->getTags());
	   // die; 
	    $em->persist($archivo);
            if($archivo->esImagen()){$imagen = new \Gema\ArchivadorBundle\Entity\Imagen($archivo); $em->persist($imagen);}
            $em->flush();

           return new \Symfony\Component\HttpFoundation\Response("se subio el archivo.");
	}
	return new \Symfony\Component\HttpFoundation\Response("Ocurrio un Error.");
    }
    /**
     * @Route("/hash/{id}", name="archivo_hash")
     * 
     */
    public function pruebahashAction($id)
    {
	$em = $this->getDoctrine()->getManager();
	$archivo = $em->getRepository("GemaArchivadorBundle:Archivo")->find($id);
	
	echo sha1_file ($archivo->getRutaAbsoluta());
	return new \Symfony\Component\HttpFoundation\Response();
    }
    /**
     * @Route("/lista", name="archivo_lista")
     * @Template()
     */
    public function listaAction()
    {
	$em = $this->getDoctrine()->getManager();
	$archivos = $em->getRepository("GemaArchivadorBundle:Archivo")->findAll();
        
	return array('archivos' => $archivos);
    }
    
    
    /**
     * @Route("/buscar", name="archivo_buscar")
     * @Template()
     */
    public function buscarAction()
    {	
	if($this->getRequest()->getMethod() != "POST"){
	    return array();	    
	}
	else{
	    $finder = new \Symfony\Component\Finder\Finder();
	    $finder->in(__DIR__."/..");
	    //$comando = $this->getRequest()->get('comando');
	    $buscar = $this->getRequest()->get('comando');
	    try {
		 //$resp = eval($buscar);
		 $aux= '$finder->name("' .$buscar. '");';
		 echo $aux;
		 $resp = eval($aux);
		 // $resp = eval('$finder->name("*.php");');
		 //$resp = $finder->name('*.php');
		 //$resp = $finder->directories();
		 $this->mostrar($finder);
		if($buscar == 'dale'){
		    $this->mostrar($finder);
		}
	    }catch(Exception $e){
		
	    }
	    return array('salida' => $resp);	    
	}
	
    }
    
    public function mostrar($finder){
	echo "<pre>";		    
	foreach ($finder as $file) {
	    // Imprime la ruta absoluta
	    print $file->getRealpath()."\n";
	}
	echo "</pre>";
	
    }
    
     /**
     * @Route("/borrar/{id}", name="archivo_borrar")
     * 
     */
    public function borrarAction($id)
    {
	$em = $this->getDoctrine()->getManager();
	$archivo = $em->getRepository("GemaArchivadorBundle:Archivo")->find($id);
	
	$em->remove($archivo);
	$em->flush();
	return $this->redirect($this->generateUrl("archivo_lista"));
    }
     /**
     * @Route("/busqueda", name="archivo_busqueda")
     * @Template()
     */
    public function busquedaAction()
    {
	if($this->getRequest()->getMethod() != "POST"){
	    return array();
	}
	else {
	    
	$buscado = $this->getRequest()->get("buscado");
	$em = $this->getDoctrine()->getManager();
	$archivos = $em->getRepository("GemaArchivadorBundle:Archivo")->buscar($buscado);
	echo count($archivos)	;
	    return array('archivos' => $archivos);
	}
    }
     /**
     * @Route("/tags/busqueda", name="archivo_tags_busqueda")
     * @Template()
     */
    public function tagsbusquedaAction()
    {
	echo "<pre>";
	if($this->getRequest()->getMethod() != "POST"){
	    return array();
	}
	else {
	     
	$buscado = $this->getRequest()->get("buscado");
	$em = $this->getDoctrine()->getManager();
	//$archivos = $em->getRepository("GemaArchivadorBundle:Tag")->buscarPorTags($buscado);
	//$tags = $em->getRepository("GemaArchivadorBundle:Tag")->buscarArchivos($buscado);
	$tags = $em->getRepository("GemaArchivadorBundle:Tag")->seleccionarJoined($buscado);
	//$archivos = new \Doctrine\Common\Collections\ArrayCollection();
	$archivos = array();
	foreach ($tags as $tag) {
	    foreach ($tag->getArchivos() as $archivo) {
		//$archivos->add($archivo);
		$archivos[$archivo->getId()] = $archivo;  // pa no repetir las que estan en los mismos tags, ver como implementar con coleccion.
	    }
	    
	    
	}
//	echo count($archivos  );
//	\Doctrine\Common\Util\Debug::dump($tags ,3);
	    //die;
	echo "</pre>";
	    return array('tags' => $tags,'archivos' => $archivos);
	}
    }
     /**
     * @Route("/lista/imagenes", name="archivo_lista_imagenes")
     * @Template()
     */
    public function listaimagenesAction()
    {    
        $em = $this->getDoctrine()->getManager();
	
	$imagenes = $em->getRepository("GemaArchivadorBundle:Imagen")->findAll();
	
        return array('imagenes' => $imagenes);
    }
     /**
     * @Route("/sidebar", name="archivo_sidebar")
     * @Template("GemaArchivadorBundle:Plantilla:sidebarestilo1.html.twig")
     */
    public function sidebarAction()
    {    
        $em = $this->getDoctrine()->getManager();
	
	$archivos = $em->getRepository("GemaArchivadorBundle:Archivo")->Ultimos();
	
        return array('archivos' => $archivos);
    }    
}
