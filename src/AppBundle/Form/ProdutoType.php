<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProdutoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo', 'text', array('attr' => array('placeholder' => 'SKU', 'required' => true)))
            ->add('nome', 'text', array('attr' => array('placeholder' => 'Nome', 'required' => true)))
            ->add('precoUnitario', 'money', array('attr' => array('placeholder' => 'Valor Unitário', 'required' => true)))
            ->add('inserir_produto', 'submit', array('label' => 'Salvar'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Produto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'form_produto';
    }
}
