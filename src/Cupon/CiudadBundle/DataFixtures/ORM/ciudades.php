<?php
// src/Cupon/CiudadBundle/DataFixtures/ORM/ciudades.php
namespace Cupon\CiudadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Cupon\CiudadBundle\Entity\Ciudad;

class ciudades implements FixtureInterface{
    public function load($manager){
        $ciudades=array(
            array('nombre'=>'Madrid'),
            array('nombre'=>'Barcelona'),
            );

        foreach ($ciudades as $ciudad){
            $entidad=new Ciudad();

            $entidad->setNombre($ciudad['nombre']);

            $manager->persist($entidad);
        }
        $manager->flush();
    }
}
?>