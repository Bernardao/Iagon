<?php
// src/Cupon/TiendaBundle/Controller/DefaultController.php
namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class DefaultController extends Controller{
    
    public function portadaAction($ciudad, $tienda){
        $em= $this->getDoctrine()->getEntityManager();
        
        $ciudad= $em->getRepository('CiudadBundle:Ciudad')->findOneBySlug($ciudad);
        
        $tienda= $em->getRepository('TiendaBundle:Tienda')->findOneBy(array(
            'slug'   => $tienda,
            'ciudad' => $ciudad->getId()
        ));
        
        if(!$tienda){
            throw $this->createNotFoundException('No existe esta tienda');
        }
        
        $ofertas=$em->getRepository('TiendaBundle:Tienda')->findUltimasOfertasPublicadas($tienda->getId());
        
        $cercanas= $em->getRepository('TiendaBundle:Tienda')->findCercanas($tienda->getSlug(),
                                                                            $tienda->getCiudad()->getSlug()
                );
        //para rss
        $formato= $this->get('request')->getRequestFormat();
        
        return $this->render('TiendaBundle:Default:portada.'.$formato.'.twig', array(
            'tienda'   => $tienda,
            'ofertas'  => $ofertas,
            'cercanas' => $cercanas
        ));
    }
}
