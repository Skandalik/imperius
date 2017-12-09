<?php
declare(strict_types=1);
namespace App\Form;

use App\Entity\Room;
use App\Entity\Sensor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SensorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'uuid',
                TextType::class,
                [
                    'empty_data' => 'Set uuid',
                ]
            )
            ->add(
                'room',
                EntityType::class,
                [
                    'class'        => Room::class,
                    'choice_label' => 'room',
                ]
            )
            ->add(
                'valueType',
                TextType::class,
                [
                    'empty_data' => 'Set Value Type',
                ]
            )
            ->add(
                'switchable',
                CheckboxType::class,
                [
                    'label'    => 'Is sensor switchable?',
                    'required' => true,
                ]
            )
            ->add(
                'sensorIp',
                TextType::class,
                [
                    'empty_data' => 'Set IP',
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Sensor::class,
            ]
        );
    }

}