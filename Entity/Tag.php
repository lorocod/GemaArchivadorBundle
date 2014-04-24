<?php

namespace Gema\ArchivadorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gema\ArchivadorBundle\Entity\TagRepository")
 */
class Tag
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var Archivo
     *
     * @ORM\ManyToMany(targetEntity="Archivo", mappedBy="tags")
     */
    private $archivos;

    static private $TAGS;
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
     * @return Tag
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Tag
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    
    public function getArchivos() {
	return $this->archivos;
    }

    public function setArchivos(Archivo $archivos) {
	$this->archivos = $archivos;
    }

    function __construct($nombre = null) {
	$this->descripcion= "vacia";
	$this->nombre= "vacio";
	if(!is_null($nombre))$this->nombre = $nombre;
	$this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addArchivo($archivo)
    {
        $this->archivos[] = $archivo;
    }
    
    public function __toString() {
	return $this->nombre;
    }
    
    public static function obtenerTag($nombre) {
	count(self::$TAGS);
	if ( isset(self::$TAGS[$nombre]) ){
	   // echo 'existe ' . $nombre . ".";
	    return self::$TAGS[$nombre];
	}
	else{
	    //echo 'nuevo ' . $nombre . ".";
	    return new Tag($nombre);
	}
    }
    
    public static function setTagsDeBase($tags){
	$tags_array = array();
	foreach ($tags as $tag) {
	    $tags_array[$tag->getNombre()] = $tag;
	}
	self::$TAGS = $tags_array;
    }
    
}
