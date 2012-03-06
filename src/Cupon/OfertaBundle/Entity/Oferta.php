<?php

namespace Cupon\OfertaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cupon\OfertaBundle\Util\Util;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cupon\OfertaBundle\Entity\Oferta
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cupon\OfertaBundle\Entity\OfertaRepository")

 */
class Oferta{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @var text $descripcion
     *
     * @ORM\Column(name="descripcion", type="text")
     * @Assert\NotBlank()
     * @Assert\MinLength(30)
     */
    private $descripcion;

    /**
     * @var text $condiciones
     *
     * @ORM\Column(name="condiciones", type="text")
     */
    private $condiciones;

    /**
     * @var string $foto
     *
     * @ORM\Column(name="foto", type="string", length=255)
     * @Assert\Image(maxSize= "500k")
     */
    private $foto;

    /**
     * @var decimal $precio
     *
     * @ORM\Column(name="precio", type="decimal")
     * @Assert\Min(0)
     */
    private $precio;

    /**
     * @var decimal $descuento
     *
     * @ORM\Column(name="descuento", type="decimal", scale=2)
     */
    private $descuento;

    /**
     * @var datetime $fecha_publicacion
     *
     * @ORM\Column(name="fecha_publicacion", type="datetime", nullable=true)
     * @Assert\DateTime
     */
    private $fecha_publicacion;

    /**
     * @var datetime $fecha_expiracion
     *
     * @ORM\Column(name="fecha_expiracion", type="datetime")
     * @Assert\DateTime
     */
    private $fecha_expiracion;

    /**
     * @var integer $compras
     *
     * @ORM\Column(name="compras", type="integer")
     */
    private $compras;

    /**
     * @var integer $umbral
     *
     * @ORM\Column(name="umbral", type="integer")
     * @Assert\Min(0)
     */
    private $umbral;

    /**
     * @var boolean $revisada
     *
     * @ORM\Column(name="revisada", type="boolean")
     */
    private $revisada;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Cupon\CiudadBundle\Entity\Ciudad")
     */
    private $ciudad;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Cupon\TiendaBundle\Entity\Tienda")
     */
    private $tienda;


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
     */
    public function setNombre($nombre){
        $this->nombre = $nombre;
        $this->slug=Util::getSlug($nombre);
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
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set descripcion
     *
     * @param text $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Get descripcion
     *
     * @return text 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set condiciones
     *
     * @param text $condiciones
     */
    public function setCondiciones($condiciones)
    {
        $this->condiciones = $condiciones;
    }

    /**
     * Get condiciones
     *
     * @return text 
     */
    public function getCondiciones()
    {
        return $this->condiciones;
    }

    /**
     * Set foto
     *
     * @param string $foto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set precio
     *
     * @param decimal $precio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    /**
     * Get precio
     *
     * @return decimal 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set descuento
     *
     * @param decimal $descuento
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
    }

    /**
     * Get descuento
     *
     * @return decimal 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set fecha_publicacion
     *
     * @param datetime $fechaPublicacion
     */
    public function setFechaPublicacion($fechaPublicacion)
    {
        $this->fecha_publicacion = $fechaPublicacion;
    }

    /**
     * Get fecha_publicacion
     *
     * @return datetime 
     */
    public function getFechaPublicacion()
    {
        return $this->fecha_publicacion;
    }

    /**
     * Set fecha_expiracion
     *
     * @param datetime $fechaExpiracion
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fecha_expiracion = $fechaExpiracion;
    }

    /**
     * Get fecha_expiracion
     *
     * @return datetime 
     */
    public function getFechaExpiracion()
    {
        return $this->fecha_expiracion;
    }

    /**
     * Set compras
     *
     * @param integer $compras
     */
    public function setCompras($compras)
    {
        $this->compras = $compras;
    }

    /**
     * Get compras
     *
     * @return integer 
     */
    public function getCompras()
    {
        return $this->compras;
    }

    /**
     * Set umbral
     *
     * @param integer $umbral
     */
    public function setUmbral($umbral)
    {
        $this->umbral = $umbral;
    }

    /**
     * Get umbral
     *
     * @return integer 
     */
    public function getUmbral()
    {
        return $this->umbral;
    }

    /**
     * Set revisada
     *
     * @param boolean $revisada
     */
    public function setRevisada($revisada)
    {
        $this->revisada = $revisada;
    }

    /**
     * Get revisada
     *
     * @return boolean 
     */
    public function getRevisada()
    {
        return $this->revisada;
    }

    /**
     *
     */
    public function setCiudad(\Cupon\CiudadBundle\Entity\Ciudad $ciudad)
    {
        $this->ciudad = $ciudad;
    }

    /**
     * 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set tienda
     *
     * @param string $tienda
     */
    public function setTienda(\Cupon\TiendaBundle\Entity\Tienda $tienda){
        $this->tienda = $tienda;
    }

    /**
     * Get tienda
     *
     * @return string 
     */
    public function getTienda(){
        return $this->tienda;
    }
    
    public function __toString(){
        return $this->getNombre();
    }
    /**
     * @Assert\True(message= "La fecha de expiración debe ser posterior a la fecha de publicación")
     */
    public function isFechaValida(){
        if ($this->fecha_publicacion == null || $this->fecha_expiracion == null) {
            return true;
        }
        return $this->fecha_expiracion> $this->fecha_publicacion;
    }
    
    public function subirFoto($directorioDestino){
        if (null ===$this->foto){
            return;
        }
        //$directorioDestino= __DIR__.'/../../../../web/uploads/images';
        $nombreArchivoFoto= uniqid('cupon-').'-foto1.jpg';
        
        $this->foto->move($directorioDestino, $nombreArchivoFoto);
        
        $this->setFoto($nombreArchivoFoto);
    }
    
}
?>