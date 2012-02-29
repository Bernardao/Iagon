<?php
// src/Cupon/UsuarioBundle/Listener/LoginListener.php
namespace Cupon\UsuarioBundle\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginListener{
    private $contexto, $router, $ciudad = null;
        
    public function __construct(SecurityContext $context, Router $router){
        $this->router=$router;
        $this->contexto= $context;
    }
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event){
        $token = $event->getAuthenticationToken();
        $this->ciudad = $token->getUser()->getCiudad()->getSlug();
    }
        
    public function onKernelResponse(FilterResponseEvent $event){
        if (null != $this->ciudad){
            if($this->contexto->isGranted('ROLE_TIENDA')){
                $portada= $this->router->generate('extranet_portada');
            }else{
                $portada= $this->router->generate('portada', array(
                    'ciudad'=> $this->ciudad
                ));
            }
            $event->setResponse(new RedirectResponse($portada));
            $event->stopPropagation();
        }
    }
}

