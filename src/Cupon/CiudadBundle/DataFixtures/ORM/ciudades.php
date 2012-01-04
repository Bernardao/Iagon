<?php
// src/Cupon/CiudadBundle/DataFixtures/ORM/ciudades.php
namespace Cupon\CiudadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Cupon\CiudadBundle\Entity\Ciudad;

class ciudades implements FixtureInterface{
    public function load($manager){
        $ciudades=array(
            'Madrid',
            'Barcelona',
            'Valencia',
            'Sevilla',
            'Zaragoza',
            'Málaga',
            'Murcia',
            'Palma de Mallorca',
            'Las Palmas de Gran Canaria',
            'Bilbao',
            'Alicante',
            'Córdoba',
            'Valladolid',
            'Vigo',
            'Gijón',
            'Hospitalet de Llobregat',
            'La Coruña',
            'Granada',
            'Vitoria-Gasteiz',
            'Elche',
            'Oviedo',
            'Santa Cruz de Tenerife',
            'Badalona',
            'Cartagena',
            'Terrasa',
            );

        foreach ($ciudades as $nombre){
            $ciudad=new Ciudad();

            $ciudad->setNombre($nombre);

            $manager->persist($ciudad);
        }
        $manager->flush();
    }
}
?>