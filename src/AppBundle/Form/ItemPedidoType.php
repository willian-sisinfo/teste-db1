<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ItemPedidoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantidade', IntegerType::class, array('attr' => array('placeholder' => 'Quantidade', 'required' => true)))
            ->add('percentualDesconto', IntegerType::class, array('attr' => array('placeholder' => 'Percentual desconto', 'required' => true, 'value' => 0)))
            ->add('produto', 'entity',  array('class' => 'AppBundle:Produto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p');

                }, 'choice_label' => 'getNome'), array('attr' => array('placeholder' => 'Selecione um produto', 'required' => true)))
            ->add('inserir_item', 'submit', array('label' => 'Adicionar'));

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ItemPedido'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'form_item_pedido';
    }
}
