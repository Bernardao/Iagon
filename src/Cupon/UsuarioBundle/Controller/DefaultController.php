<?php
// src/Cupon/UsuarioBundle/Controller/Default.php
namespace Cupon\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Cupon\UsuarioBundle\Entity\Usuario;
use Cupon\UsuarioBundle\Form\Frontend\UsuarioType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultController extends Controller{
    
    public function comprasAction(){
        
        $em=$this->getDoctrine()->getEntityManager();
        
        $usuario = $this->get('security.context')->getToken()->getUser();
        
        $cercanas= $em->getRepository('CiudadBundle:Ciudad')->findCercanas($usuario->getCiudad()->getId());
        
        $compras= $em->getRepository('UsuarioBundle:Usuario')->findTodasLasCompras($usuario->getId());
        
        return $this->render('UsuarioBundle:Default:compras.html.twig', array(
                'compras' => $compras,
                'cercanas' => $cercanas
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
    
    public function registroAction(){
        //return $this->render('UsuarioBundle:Default:registro.html.twig');
        $peticion= $this->getRequest();
        
        $usuario= new Usuario();
        
        //$usuario->setPermiteEmail(true);
        //$usuario->setFechaNacimiento(new \DateTime('today - 18 years'));
        
        $formulario= $this->createForm(new UsuarioType(), $usuario);
        
        if ($peticion->getMethod()=='POST'){
            //Validar los datos enviados y guardados en la bbdd
            $formulario->bindRequest($peticion);
            
            if($formulario->isValid()) {
                //guardar la información en la bbdd
                $encoder= $this->get('security.encoder_factory')
                               ->getEncoder($usuario);
                $usuario->setSalt(md5(time()));
                $passwordCodificado=$encoder->encodePassword(
                        $usuario->getPassword(),
                        $usuario->getSalt()
                );
                $usuario->setPassword($passwordCodificado);
                
                $em= $this->getDoctrine()->getEntityManager();
                $em->persist($usuario);
                $em->flush();
                
                //mensaje Flash de confirmación de registro en bbdd
                $this->get('session')->setFlash('info',
                        '¡Enhorabuena! Te has resgistrado correctamente en Cupon'
                );
                
                $token= new UsernamePasswordToken(
                    $usuario,
                    $usuario->getPassword(),
                    'usuarios',
                    $usuario->getRoles()
                );
                $this->container->get('security.context')->setToken($token);
                
                
                return $this->redirect($this->generateUrl('portada', array('ciudad'=> 'madrid')));
            }
        }
        
        return $this->render('UsuarioBundle:Default:registro.html.twig',
            array('formulario'=>$formulario->createView())
        );
    }
}
