<?php
// src/Cupon/OfertaBundle/DataFixtures/ORM/ofertas.php
namespace Cupon\OfertaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Cupon\OfertaBundle\Entity\Oferta;

class ofertas implements FixtureInterface{
    public function load($manager){
        for ($i = 0; $i < 400; $i++){
            $entidad = new Oferta();
            $entidad->setNombre('Oferta '.$i);
            $entidad->setPrecio(rand(1, 100));
            $entidad->setFechaPublicacion(new \DateTime());
            // ...
            $manager->persist($entidad);
        }
    $manager->flush();
    }
}

?>
