<?php

namespace Gema\ArchivadorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imagen
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gema\ArchivadorBundle\Entity\ImagenRepository")
 */
class Imagen
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
     * @var \Gema\ArchivadorBundle\Entity\Archivo $archivo
     *
     * @ORM\OneToOne(targetEntity="\Gema\ArchivadorBundle\Entity\Archivo")
     * @ORM\JoinColumn(name="archivo_id",referencedColumnName="id")
     */
    private $archivo;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="ancho", type="integer")
     */
    private $ancho;

    /**
     * @var integer
     *
     * @ORM\Column(name="alto", type="integer")
     */
    private $alto;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;


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
     * Set archivo
     *
     * @param \Gema\ArchivadorBundle\Entity\Archivo $archivo
     * @return Imagen
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;
    
        return $this;
    }

    /**
     * Get archivo
     *
     * @return \Gema\ArchivadorBundle\Entity\Archivo
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Imagen
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set ancho
     *
     * @param integer $ancho
     * @return Imagen
     */
    public function setAncho($ancho)
    {
        $this->ancho = $ancho;
    
        return $this;
    }

    /**
     * Get ancho
     *
     * @return integer 
     */
    public function getAncho()
    {
        return $this->ancho;
    }

    /**
     * Set alto
     *
     * @param integer $alto
     * @return Imagen
     */
    public function setAlto($alto)
    {
        $this->alto = $alto;
    
        return $this;
    }

    /**
     * Get alto
     *
     * @return integer 
     */
    public function getAlto()
    {
        return $this->alto;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Imagen
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
    
    function __construct(\Gema\ArchivadorBundle\Entity\Archivo $archivo) {
        $this->archivo = $archivo;
        $this->url = $archivo->getLink();
        $this->alto = 0;
        $this->ancho = 0;
        $this->descripcion = 'vacia';
        
        
    }


}
