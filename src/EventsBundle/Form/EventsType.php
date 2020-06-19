<?php

namespace EventsBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class EventsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')->add('dateDebut')->add('dateFin')->add('nbPlaces')->add('description')->add('lieu')
            ->add('sponsors',EntityType::class ,array(
                    'class' => 'EventsBundle\Entity\Sponsor',
                    'choice_label'=>'nom',
                    'multiple' => true,
                    'expanded' => true,
                )
            )->add('eventphoto', VichFileType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_link' => true
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventsBundle\Entity\Events'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eventsbundle_events';
    }


}
