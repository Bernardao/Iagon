<?php
// src/Cupon/TiendaBundle/Entity/TiendaRepository.php

namespace Cupon\TiendaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TiendaRepository extends EntityRepository{
    
    public function findUltimasOfertasPublicadas($tienda_id, $limite=10){
        $em= $this-getEntityManager();
        
        $consulta= $em-createQuery('SELECT o, t
                                    FROM OfertaBundle:Oferta o
                                    JOIN o.tienda t
                                    WHERE o.revisada=true
                                    AND o.fecha_publicacion<:fecha
                                    AND o.tienda=:id
                                    ORDER BY o.fecha_expiracion DESC');
        $consulta->setMaxResults($limite);
        $consulta->setParameter('id', $tienda_id);
        $consulta->setParameter('fecha', new \DateTime('now'));
        
        return $consulta->getResult();
    }
    
    public function findCercanas($tienda, $ciudad){
        $em= $this->getEntityManager();
        
        $consulta= $em->createQuery('SELECT t, c 
                                    FROM TiendaBundle:Tienda t
                                    JOIN t.ciudad c 
                                    WHERE c.slug=:ciudad
                                    AND t.slug != :tienda');
        $consulta->setMaxResults(5);
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->setParameter('tienda', $tienda);
        
        return $consulta->getResult();
    }
}

?>
