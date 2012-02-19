<?php
// src/Cupon/UsuarioBundle/Controller/Default.php
namespace Cupon\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Cupon\UsuarioBundle\Entity\Usuario;
use Cupon\UsuarioBundle\Form\Frontend\UsuarioType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    
    public function perfilAction(){
        //Obtener los datos del usuario logueado y utilizarlos para rellenar un formulario de registro
        
        //para saber el tipo de petición POST o GET
        $peticion= $this->getRequest();
        
        //Si la petición es GET, mostrar el formulario
        //Si la petición es POST, actualizar la información del usuario con
        //los nuevos datos obtenidos del formulario
        $usuario= $this->get('security.context')->getToken()->getUser();
        //se pasa un objeto con la info del usuario
        $formulario= $this->createForm(new UsuarioType(), $usuario);       
        
        if ($peticion->getMethod() == 'POST'){
            //guardamos passowrd antes de ejecutar bindRequest
            $passwordOriginal= $formulario->getData()->getPassword();
            
            $formulario->bindRequest($peticion);
            
            if ($formulario->isValid()){
                //actualizar el perfil del usuario
                
                if (null == $usuario->getPassword()){
                    // miramos si user ha decidido cambiar pwd
                    $usuario->setPassword($passwordOriginal);
                }else{
                    // user quiere cambiar pwd
                    $encoder= $this->get('security.encoder_factory')
                                    ->getEncoder($usuario);
                    $passwordCodificado= $encoder->encodePassword(
                            $usuario->getPassword(),
                            $usuario->getSalt()
                    );
                    $usuario->setPassword($passwordCodificado);
                }
                //enviamos a bbdd actualización de perfil
                $em= $this->getDoctrine()->getEntityManager();
                $em->persist($usuario);
                $em->flush();
                
                $this->get('session')->setFlash('info', 'Los datos de tu perfil se han actualizado correctamente');
                
                return $this->redirect($this->generateUrl('usuario_perfil'));
            }
        }
        
        return $this->render('UsuarioBundle:Default:perfil.html.twig', array(
            'usuario' => $usuario,
            'formulario' => $formulario->createView()
        ));
    }
}
