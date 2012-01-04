<?php
// src/Cupon/OfertaBundle/Controller/DefaultController.php
namespace Cupon\OfertaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller{
    
    public function ayudaAction(){
        #return new Response('Ayuda');
        return $this->render('OfertaBundle:Default:ayuda.html.twig');
    }
    public function portadaAction(){
        $em= $this->getDoctrine()->getEntityManager();
        
        $oferta= $em->getRepository('OfertaBundle:Oferta')->findOneBy(array(
            'ciudad'            => $this->container->getParameter('cupon.ciudad_por_defecto'),
            //3,
            'fecha_publicacion' => new \DateTime('today')
        ));
        
        if (!$oferta){
            throw $this->createNotFoundException(
                'No se ha encontrado la oferta del día'
            );
        }
            
        return $this->render(
                'OfertaBundle:Default:portada.html.twig',
                array('oferta' => $oferta)
                );
    }
}
