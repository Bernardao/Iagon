<?php

namespace Cupon\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller{
    
    public function comprasAction(){
        $usuario_id=11;
        $em=$this->getDoctrine()->getEntityManager();
        $compras= $em->getRepository('UsuarioBundle:Usuario')->findTodasLasCompras($usuario_id);
        
        return $this->render('UsuarioBundle:Default:compras.html.twig', array(
                'compras' => $compras
        ));
    }
    
    public function loginAction(){
        $peticion= $this->getRequest();
        $sesion= $peticion->getSession();
        
        $error= $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR,
                $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
        
        return $this->render('UsuarioBundle:Default:login.html.twig', array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error'         => $error
        ));
    }
    
        public function cajaLoginAction(){
        $peticion= $this->getRequest();
        $sesion= $peticion->getSession();
        
        $error= $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR,
                $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
        
        return $this->render('UsuarioBundle:Default:cajaLogin.html.twig', array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error'         => $error
        ));
    }
}
