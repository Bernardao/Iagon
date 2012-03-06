<?php
// src/Cupon/OfertaBundle/Form/Extranet/OfertaType.php
namespace Cupon\OfertaBundle\Form\Extranet;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
//para hacer el campo "acepto" obligatorio
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;

class OfertaType extends AbstractType{
    public function buildForm(FormBuilder $builder, array $options){
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('condiciones')
            ->add('foto', 'file', array('required'=> false))
            ->add('precio', 'money')
            ->add('descuento', 'money')
            ->add('umbral')
        ;
        // Añadir campo que no se guarda en bbdd, property_path=>false
        $builder->add('acepto', 'checkbox', array('property_path'=> false));
        // Hacer campo obligatorio
        $builder->addValidator(new CallbackValidator(
                function(FormInterface $form){
                    if ($form['acepto']->getData() == false){
                        $form->addError(new FormError(
                            'Debes aceptar las condiciones legales'
                        ));
                    }
                }
        ));
    }
    public function getName(){
        return 'oferta_tienda';
    }
}
?>