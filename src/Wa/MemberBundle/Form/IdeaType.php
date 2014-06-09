<?php

namespace Wa\MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use \Wa\FrontBundle\Form\TagType;
use \Wa\MemberBundle\Form\UploadType;

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
            ->add('discipline','entity', array(
                'class' => 'WaFrontBundle:Discipline',
                'property' => 'title'
            ))
            ->add('tags', 'collection', array('type'         => new TagType(),
                                              'allow_add'    => true,
                                              'allow_delete' => true))
            ->add('uploads', 'collection', array('type'         => new UploadType(),
                                              'allow_add'    => true,
                                              'allow_delete' => true)) 
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
