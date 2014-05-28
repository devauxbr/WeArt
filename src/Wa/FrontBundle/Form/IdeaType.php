<?php

namespace Wa\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IdeaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea')
            //->add('discipline','text') TODO
            //->add('tags','text') TODO
            ->add('theme', 'text')
            ->add('account','text')
            //->add('uploads','file')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wa\FrontBundle\Entity\Idea'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wa_frontbundle_idea';
    }
}
