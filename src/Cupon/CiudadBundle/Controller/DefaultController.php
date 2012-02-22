<?php
// src/Cupon/CiudadBundle/Controller/DefaultController.php

namespace Cupon\CiudadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class DefaultController extends Controller{
    
    public function cambiarAction($ciudad){
        
        return new RedirectResponse($this->generateUrl(
                'portada', array('ciudad'=>$ciudad)
                ));
    }
    
    public function listaCiudadesAction($ciudad){
        
        $em= $this->getDoctrine()->getEntityManager();
        $ciudades= $em->getRepository('CiudadBundle:Ciudad')->findAll();
        
        return $this->render(
                'CiudadBundle:Default:listaCiudades.html.twig',
                array(
                    'ciudadActual'=> $ciudad,
                    'ciudades' => $ciudades)
        );
    }
    
    public function recientesAction($ciudad){
        $em= $this->getDoctrine()->getEntityManager();
        
        $ciudad= $em->getRepository('CiudadBundle:Ciudad')->findOneBySlug($ciudad);
        if(!$ciudad){
            throw $this->createNotFoundException('No existe la ciudad');
        }
        
        $cercanas= $em->getRepository('CiudadBundle:Ciudad')->findCercanas($ciudad->getId());
        $ofertas= $em->getRepository('OfertaBundle:Oferta')->findRecientes($ciudad->getId());
        
        //añadido para generar rss
        $formato= $this->get('request')->getRequestFormat();
        
        return $this->render('CiudadBundle:Default:recientes.'.$formato.'.twig',
                            array(
                                'ciudad' => $ciudad,
                                'cercanas' => $cercanas,
                                'ofertas' => $ofertas
                                )
                            );    
    }
}
