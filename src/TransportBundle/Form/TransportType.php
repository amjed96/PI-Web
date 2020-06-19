<?php

namespace TransportBundle\Form;

use blackknight467\StarRatingBundle\Form\RatingType;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\GoogleMapBundle\Form\Type\PlaceAutocompleteType;

class TransportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('moyen')

            ->add('destination', PlaceAutocompleteType::class)
            ->add('date')
            ->add('x')
            ->add('y')
            ->add('nb')
            ->add('matricule',EntityType::class
                , array("class"=>"TransportBundle:Chauffeur", "choice_label"=>"matricule"))
            ->add('rating', RatingType::class, [
                'label' => 'Rating'
            ])

            ->add('Ajouter', SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TransportBundle\Entity\Transport'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'transportbundle_transport';
    }


}
