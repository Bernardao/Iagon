<?php
#src/Cupon/OfertaBundle/Controller/DefaultController.php
namespace Cupon\OfertaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller{
    
    public function ayudaAction(){
        return new Response('Ayuda');
        #return $this->render('OfertaBundle:Default:index.html.twig', array('name' => $name));
    }
}
