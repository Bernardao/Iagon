<?php
// src/Cupon/TiendaBundle/DataFixtures/tiendas.php
namespace Cupon\TiendaBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder;
use Cupon\CiudadBundle\Entity\Ciudad;
use Cupon\TiendaBundle\Entity\Tienda;


/* Fixtures de la entidad Tienda.
 * Crea entre 2 y 5 tiendas por ciudad
 */
class tiendas extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface{
        public function getOrder(){
        return 20;
    }
    private $container;
    
    public function setContainer(ContainerInterface $container=null){
        $this->container= $container;
    }
       
    public function load($manager){
        //Obtener todas las ciudades de la bbdd
        $ciudades= $manager->getRepository('CiudadBundle:Ciudad')->findAll();
        
        $i=1;
        foreach ($ciudades as $ciudad){
            for ($j=1; $j<=rand(2,5);$j++){
                $id= $ciudad->getId();
                
                $tienda= new Tienda();
                
                $tienda->setNombre($this->getNombre());
                
                $tienda->setLogin('tienda'.$i);
                $tienda->setSalt(md5(time()));
                
                $passwordEnClaro='tienda'.$i;
                $passwordCodificado=$passwordEnClaro;
                
                //$encoder= $this->container->get('security.encoder_factory')->getEncoder($tienda);
                //$passwordCodificado=$encoder->encodePassword($passwordEnClaro, $tienda->getSalt());
                
                $tienda->setPassword($passwordCodificado);
                
                $tienda->setDescripcion($this->getDescripcion());
                $tienda->setDireccion($this->getDireccion($ciudad));
                $tienda->setCiudad($ciudad);
                
                $manager->persist($tienda);
                
                $i++;
            }
        }
        $manager->flush();
    }
    
    /*
     * Generador aleatorio de nombres de tiendas
     */
    private function getNombre(){
        $prefijos= array('Restaurante', 'Cafetería', 'Bar', 'Pub', 'Pizza', 'Burger');
        $nombres= array('Lorem ipsum', 'Sit amet', 'Consectetur', 'Adipiscing elit', 'Nec sapien', 
                        'Tincidunt', 'Facilisis', 'Facilisis', 'Nulla scelerisque', 'Blandit ligula', 
                        'Eget', 'Hendrerit', 'Malesuada', 'Enim sit');
        return $prefijos[rand(0, count($prefijos)-1)].' '.$nombres[rand(0, count($nombres)-1)];
    }
    
    /*
     * Generador aleatorio de descripciones de tiendas
     */
    private function getDescripcion(){
        $descripcion= '';
        
        $frases= array(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Mauris ultricies nunc nec sapien tincidunt facilisis.',
            'Nulla scelerisque blandit ligula eget hendrerit.',
            'Sed malesuada, enim sit amet ultricies semper, elit leo lacinia massa, in tempus nisl ipsum quis libero.',
            'Aliquam molestie neque non augue molestie bibendum.',
            'Pellentesque ultricies erat ac lorem pharetra vulputate.',
            'Donec dapibus blandit odio, in auctor turpis commodo ut.',
            'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
            'Nam rhoncus lorem sed libero hendrerit accumsan.',
            'Maecenas non erat eu justo rutrum condimentum.',
            'Suspendisse leo tortor, tempus in lacinia sit amet, varius eu urna.',
            'Phasellus eu leo tellus, et accumsan libero.',
            'Pellentesque fringilla ipsum nec justo tempus elementum.',
            'Aliquam dapibus metus aliquam ante lacinia blandit.',
            'Donec ornare lacus vitae dolor imperdiet vitae ultricies nibh congue.',
        );
        
        $numeroFrases= rand(3,6);
        for($i=0; $i<$numeroFrases; $i++){
            $descripcion.=$frases[rand(0, count($frases)-1)].' ';
        }
        return $descripcion;
    }
    
    /*
     * Generador aleatorio de direcciones postales
     */
    private function getDireccion($ciudad){
        $prefijos= array('Calle', 'Avenida', 'Plaza');
        $nombres= array('Lorem', 'Ipsum', 'Sitamet', 'Consectetur', 'Adipiscing', 'Necsapien', 
                        'Tincidunt', 'Facilisis', 'Nulla', 'Scelerisque', 'Blandit', 'Ligula', 
                        'Eget', 'Hendrerit', 'Malesuada', 'Enimsit');
        return $prefijos[rand(0, count($prefijos)-1)].' '.$nombres[rand(0, count($nombres)-1)].', '
                .rand(1, 253)."\n".$this->getCodigoPostal().' '.$ciudad->getNombre();
    }
    
    /*
     * Generador aleatorio de códigos postales
     */
    private function getCodigoPostal(){
        return sprintf('%02s%03s', rand(1, 52), rand(0, 999));
    }
}
?>
