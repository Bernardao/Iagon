<?php
// src/Cupon/OfertaBundle/Entity/OfertaRepository.php
namespace Cupon\OfertaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OfertaRepository extends EntityRepository{
    public function findOfertaDelDia($ciudad){
        $em= $this->getEntityManager();
        
        $dql= 'SELECT o, c, t
                FROM OfertaBundle:Oferta o
                JOIN o.ciudad c JOIN o.tienda t
                WHERE o.revisada=true
                AND o.fecha_publicacion<:fecha
                AND c.slug=:ciudad
                ORDER BY o.fecha_publicacion DESC';
        
        $consulta= $em->createQuery($dql);
        $consulta->setParameter('fecha', new \DateTime('now'));
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->setMaxResults(1);
        
        return $consulta->getSingleResult();
    }
}
?>
