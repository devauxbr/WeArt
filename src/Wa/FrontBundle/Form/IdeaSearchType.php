<?php

namespace Wa\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wa\FrontBundle\Repository\ThemeRepository;

class IdeaSearchType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('discipline', 'entity', array(
                    'class' => 'WaFrontBundle:Discipline',
                    'property' => 'title'))
                ->add('theme', 'entity', array(
                    'class' => 'WaFrontBundle:Theme',
                    'property' => 'title',
                    'query_builder' => function(ThemeRepository $er) {
                return $er->createQueryBuilder('t')
                        ->orderBy('t.week', 'ASC');
            }))
                ->add('tags', 'collection', array('type' => new TagType(),
                    'allow_add' => true,
                    'allow_delete' => true));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Wa\FrontBundle\Entity\Idea'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'wa_frontbundle_search_idea';
    }

}
