<?php
declare(strict_types=1);
namespace App\Form;

use App\Entity\Room;
use App\Entity\Sensor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SensorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Sensor $sensor */
        $sensor = $builder->getData();

        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'data'       => $sensor->getName() ?? $sensor->getName() ?? null,
                    'attr'       => [
                        'placeholder' => 'Set sensor name',
                    ],
                ]
            );
        if (!$sensor->getId()) {
            $builder
                ->add(
                    'uuid',
                    TextType::class,
                    [
                        'disabled'   => $sensor->getUuid() ?? true ?? false,
                        'attr'       => [
                            'placeholder' => 'Set UUID',
                        ],
                    ]
                )
                ->add(
                    'switchable',
                    CheckboxType::class,
                    [
                        'label'    => 'Is sensor switchable?',
                        'required' => true,
                        'disabled' => $sensor->getUuid() ?? true ?? false,
                    ]
                )
                ->add(
                    'multiValue',
                    CheckboxType::class,
                    [
                        'label'    => 'Is sensor multi value (more than 0 and 1)?',
                        'required' => true,
                        'disabled' => $sensor->getUuid() ?? true ?? false,
                    ]
                )
                //TODO jesli jest wybrany switchable to multivalue nie moze być
                //TODO dodanie javascryptu z dynamicznym wyłączaniem pola minimum Value i maximum Value kiedy checkbox multiValue jest zaznaczony
                ->add(
                    'minimumValue',
                    IntegerType::class,
                    [
                        'label'    => 'Minimum value to set in sensor.',
                        'required' => false,
                        'attr'       => [
                            'placeholder' => '0',
                        ],
                    ]
                )
                ->add(
                    'maximumValue',
                    IntegerType::class,
                    [
                        'label'    => 'Maximum value to set in sensor.',
                        'required' => false,
                        'attr'       => [
                            'placeholder' => '100',
                        ],
                    ]
                )
                ->add(
                    'valueType',
                    TextType::class,
                    [
                        'label'    => 'What type of value sensor takes?',
                        'required' => true,
                        'disabled' => $sensor->getUuid() ?? true ?? false,
                        'attr'       => [
                            'placeholder' => 'e.g. Celsius, Fahrenheit, Pascal',
                        ],
                    ]
                )
                ->add(
                    'sensorIp',
                    TextType::class,
                    [
                        'disabled'   => $sensor->getSensorIp() ?? true ?? false,
                        'attr'       => [
                            'placeholder' => 'Set Sensor IP',
                        ],
                    ]
                )
            ;
        }
        $builder
            ->add(
                'room',
                EntityType::class,
                [
                    'placeholder'  => '- Choose room- ',
                    'class'        => Room::class,
                    'choice_label' => 'room',
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'btn btn-success',
                    ],
                ]
            )
            ->add(
                'back',
                SubmitType::class
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