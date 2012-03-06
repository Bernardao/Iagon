<?php
// src/Cupon/TiendaBundle/Controller/ExtranetController.php
namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\Permission\MaskBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Cupon\TiendaBundle\Form\Extranet\TiendaType;
use Cupon\OfertaBundle\Form\Extranet\OfertaType;
use Cupon\OfertaBundle\Entity\Oferta;

class ExtranetController extends Controller{
    public function loginAction(){
        $peticion= $this->getRequest();
        $sesion= $peticion->getSession();
        
        $error= $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR,
                                        $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
                );
        
        return $this->render('TiendaBundle:Extranet:login.html.twig', array(
                                    'error' => $error
        ));
    }
    
    public function portadaAction(){
        $em= $this->getDoctrine()->getEntityManager();
        
        $tienda= $this->get('security.context')->getToken()->getUser();
        $ofertas= $em->getRepository('TiendaBundle:Tienda')->findOfertasRecientes($tienda->getId());
    
        return $this->render('TiendaBundle:Extranet:portada.html.twig', array(
            'ofertas'=> $ofertas
        ));
    }
    public function ofertaNuevaAction(){
        $peticion= $this->getRequest();
        
        $oferta= new Oferta();
        $formulario= $this->createForm(new OfertaType(), $oferta);
        
        if ($peticion->getMethod() == 'POST') {
           $formulario->bindRequest($peticion); 
        
            if($formulario->isValid()){
                // Completar las propiedades de la oferta que una tienda no puede establecer
                $tienda=$this->get('security.context')->getToken()->getUser();
                
                $oferta->setCompras(0);
                $oferta->setTienda($tienda);
                $oferta->setCiudad($tienda->getCiudad());
                
                //copiar la foto subida y guardar la ruta
                $oferta->subirFoto(
                    $this->container->getParameter('cupon.directorio.imagenes')
                );
                
                $em= $this->getDoctrine()->getEntityManager();
                
                $em->persist($oferta);
                $em->flush();

                return $this->redirect($this->generateUrl('extranet_portada'));
            }
        }
        return $this->render('TiendaBundle:Extranet:nueva.html.twig', array(
            'formulario' => $formulario->createView()
        ));
    }
    
    public function ofertaVentasAction($id){
        $em= $this->getDoctrine()->getEntityManager();
        
        $ventas= $em->getRepository('OfertaBundle:Oferta')->findVentasByOferta($id);
        
        return $this->render('TiendaBundle:Extranet:ventas.html.twig', array(
            'oferta' => $ventas[0]->getOferta(),
            'ventas' => $ventas
        ));
    }
    
    public function perfilAction(){
        $peticion= $this->getRequest();
        
        $tienda= $this->get('security.context')->getToken()->getUser();
        $formulario= $this->createForm(new TiendaType(), $tienda);
        
        if($peticion->getMethod()=='POST'){
            //por si no cambia la pwd
            $passwordOriginal= $formulario->getData()->getPassword();
            
            //procesar formulario
            $formulario->blindRequest($peticion);
            
            if($formulario->isValid()){
                //La tienda no cambia su pwd, utilizar original
                if(null==$tienda->setPassword()){
                    $tienda->setPassword($passwordOriginal);
                }else{
                    //La tienda cambia su pwd, codificar su valor
                    $encoder= $this->get('security.encoder_factory')
                                   ->getEncoder($tienda);
                    $passwordCodificado=$encoder->encodePassword(
                            $tienda->getPassword(),
                            $tienda->getSalt()
                    );
                    $tienda->setPassword($passwordCodificado);
                }
                
                $em= $this->getDoctrine()->getEntityManager();
                $em->persist($tienda);
                $em->flush();
                
                $this->get('session')->setFlash('info',
                        'Los datos de tu perfil se han actualizado correctamente');
                return $this->redirect(
                        $this->generateUrl('extranet_portada')
                );
            }
        }
        
        return $this->render('TiendaBundle:Extranet:perfil.html.twig', array(
            'tienda' => $tienda,
            'formulario' => $formulario->createView()
        ));
    }
}
?>
