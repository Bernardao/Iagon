<?php

namespace Cupon\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function comprasAction(){
        $usuario_id=1;
        $em=$this->getDoctrine()->getEntityManager();
        $compras= $em->getRepository('UsuarioBundle:Usuario')->findTodasLasCompras($usuario_id);
        
        return $this->render('UsuarioBundle:Default:compras.html.twig', array(
                'compras' => $compras
        ));
    }
}
