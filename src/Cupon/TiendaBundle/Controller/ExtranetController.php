<?php
// src/Cupon/TiendaBundle/Controller/ExtranetController.php
namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\Permission\MaskBuilder;

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
    public function ofertaNuevaAction(){
        $em =$this->getDoctrine()->getEntityManager();
        
        if($formulario->isValid()){
            $em->persist($oferta);
            $em->flush();
            
            $tienda= $this->get('security.context')->getToken()->getUser();
            
            $idObjeto= ObjectIdentity::fromDomainObject($oferta);
            $idUsuario= UserSecurityIdentity::fromAccount($tienda);
            
            $acl= $this->get('security.acl.provider')->creareAcl($idObjeto);
            $acl->insertObjectAce($idUsuario, MaskBuilder::MASK_OPERATOR);
            $this->get('security.acl.provider')->updateAcl($acl);
        }
        
    }
}
?>
