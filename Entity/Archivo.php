<?php

namespace Gema\ArchivadorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Archivo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gema\ArchivadorBundle\Entity\ArchivoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Archivo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */private $id;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="ruta", type="string", length=255)
     */
    private $ruta;

    /**
     * @var \DateTime $fecha     
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @Assert\File(maxSize="12000000")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="idunico", type="string", length=255)
     */
    private $idunico;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="nombrearchivo", type="string", length=255)
     */
    private $nombrearchivo;       
    /**
     * @var integer
     *
     * @ORM\Column(name="tam", type="integer")
     */
    private $tam;       
   

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="archivos", cascade={"persist"})
     * @ORM\JoinTable(name="Archivo_Tag")
     */
    private $tags;       
    
    /**
     * @var string
     * 
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;    
    
    /**
     * @var string
     * 
     * @ORM\Column(name="mimetype", type="string", length=255)
     */
    private $mimetype;    
    
    private $temp;
    private $formtags;
    
    private $tam_maximo;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Archivo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     * @return Archivo
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
    
        return $this;
    }

    /**
     * Get ruta
     *
     * @return string 
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set fecha
     *
     * @param string $fecha
     * @return Archivo
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return string 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    
    
    public function getNombrearchivo() {
	return $this->nombrearchivo;
    }

    public function setNombrearchivo($nombrearchivo) {
	$this->nombrearchivo = $nombrearchivo;
    }

    public function getTam() {
	return $this->tam;
    }

    public function setTam($tam) {
	$this->tam = $tam;
    }

            /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
	
        $this->file = $file;
        var_dump($this->getFile()->getExtension());
//	echo "<pre>";	
//	var_dump($file); //archivos como de 7 megas no aparece el pathname.
//	echo "</pre>";
//	die();
	/* si se va setear muchas veces el file poner en otra parte. */
	$this->setIdunico(sha1_file ($this->file->getPathname()));
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    public function getIdunico() {
	return $this->idunico;
    }

    public function setIdunico($idunico) {
	$this->idunico = $idunico;
    }

    public function getRutaAbsoluta()
    {
        return null === $this->getRuta()
            ? null
            : $this->getDirectorioUploadRoot().'/'.$this->getNombrearchivo();
    }
    
     public function getRutaWeb()
    {
        return null === $this->getRuta()
            ? null
            : $this->getDirectorioUpload().'/'.$this->getNombrearchivo();
    }
    
     public function getDirectorioUploadRoot()
    {
	// los ../.. son por q el directorio actual esta en Entity, osea src/algo/fulanitoBundle/Entity, necesita bajar 4 niveles.
        return __DIR__.'/../../../../web/'.$this->getDirectorioUpload();
    }
    
     public function getDirectorioUpload()
    {
        return 'subidos/archivos';
    }
    
    function __construct() {
	$this->setFecha( new \DateTime());
	$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
	
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
	    $this->ruta = $this->getDirectorioUpload()."/";
	    $this->setNombrearchivo($this->getFile()->getClientOriginalName());
	    $this->setTam($this->getFile()->getClientSize());    
	    $this->setExtension($this->getFile()->getExtension());
            $ext = $this->getFile()->getMimeType();
            $this->setMimetype($this->getFile()->getMimeType());
            var_dump($this->getFile());
            var_dump($ext);
	    $tags = split(" ", $this->getFormtags());	
	    foreach ($tags as $tag) {		
            $this->addTag( Tag::obtenerTag($tag));
	    }

	    }
    }    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    function upload() {

    if (null === $this->getFile()) {
        return;
    }

    $this->getFile()->move(
        $this->getDirectorioUploadRoot(),
        $this->getFile()->getClientOriginalName()
    );
    
    // libera el archivo.
    $this->file = null;
    }

    public function getLink(){
	return "/" . $this->getRutaWeb();
    }
    public function getTamTexto(){
	$tam = $this->getTam();
	if($tam > 1024){
	    $tam = round($tam/1024);
	    $t_tam = $tam . "K"; 
	    if($tam > 1024)$t_tam = round($tam/1024) . "M"; 
	}else{
	    $t_tam = $tam . " bytes";
	}
	    
	return $t_tam;
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
	try {
	    $a = new \Symfony\Component\HttpFoundation\File\File($this->getAbsolutePath());
	    if ($a->isFile()){
		$this->temp = $this->getAbsolutePath();
	    }
	} catch (\Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException $exc) {
	    echo $exc->getTraceAsString();
	}

	
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
	    try {
		
		unlink($this->temp);
	    } catch (Exception $exc) {
		echo $exc->getTraceAsString();
	    }

	    
        }
    }
    
    public function getAbsolutePath()
    {
	//if (file_exists($this->getRutaAbsoluta()) == true) echo  "existe";
	//else echo "no existe";
        return null === $this->ruta
            ? null
            : $this->getRutaAbsoluta();
	
    }
    
    public function getTags() {
	return $this->tags;
    }

    public function setTags($tags) {
	$this->tags = $tags;
    }

    public function addTag(Tag $tag)
    {
        $tag->addArchivo($this); // synchronously updating inverse side
        $this->tags[] = $tag;
    }
    
    public function getTemp() {
	return $this->temp;
    }

    public function setTemp($temp) {
	$this->temp = $temp;
    }

    public function getFormtags() {
	return $this->formtags;
    }

    public function setFormtags($formtags) {
	$this->formtags = $formtags;
	
    }
    public function texttags() {
	// revisar como implementar mejor esto. arraycolecction a string, usando metodo tag.nombre
	$tags ="";
	//$array = $this->getTags()->toArray();
	$array = $this->getTags();
	foreach ($array as $tag) {
	    $tags.= $tag->getNombre(). " ";
	}
	//echo $tags;
	return $tags;
//	return count($array );
//	return count($this->getTags());
	//implode(", ", ->toArray());
    }
    
    public function getExtension() {
        return $this->extension;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
    }

    public function getMimetype() {
        return $this->mimetype;
    }

    public function setMimetype($mimetype) {
        $this->mimetype = $mimetype;
    }

    
    function esImagen() {
        if(strstr($this->getMimetype(), 'image')!= false) return true;
        return false;
        echo '\tienee '. $this->getExtension();
        $ext = $this->getFile()->getExtension();
        if( $ext == 'jpg' || $ext == 'gif' || $ext == 'png') return true;
        echo 'falseeeee    '. $ext;
        return false;
    }
    
    public function getTam_maximo() {
        return $this->tam_maximo;
    }

    public function setTam_maximo($tam_maximo) {
        $this->tam_maximo = $tam_maximo;
    }


}
