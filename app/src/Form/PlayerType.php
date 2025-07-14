<?php

namespace App\Form;

use App\Entity\Planet;
use App\Entity\Player;
use App\Service\PlanetService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerType extends AbstractType
{
    public function __construct(private readonly PlanetService $planetService) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('planets', EntityType::class, [
                'class' => Planet::class,
                'choice_label' => 'name',
                'choice_attr' => function (Planet $planet) {
                    return [
                        'data-position' => $planet->getPositionX().' '.$planet->getPositionY(),
//                        'data-bonuses' => implode('|', $planet->getBonuses()),
                    ];
                },
                'choices' => $this->planetService->suggestPlanetsToColonize(),
                'multiple' => false,
                'expanded' => true,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
