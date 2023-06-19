<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuestionsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [

                'required' => true,
                'constraints' => [new Length([
                    'min' => 5,
                    'minMessage' => 'Используйте минимум {{ limit }} символов для названия вопроса'

                ]),
                    new NotBlank([
                        'message' => 'Пожалуйста введите заголовок вопроса',
                    ]),

                ],
            ])
            ->add('text', TextType::class, [

                'required' => true,
                'constraints' => [new Length([
                    'min' => 5,
                    'minMessage' => 'Используйте минимум {{ limit }} символов для текста вопроса'

                ]),
                    new NotBlank([
                        'message' => 'Пожалуйста введите текст вопроса',
                    ]),

                ],
            ])
            ->add('category', TextType::class, [

                'required' => true,
                'constraints' => [new Length([
                    'min' => 3,
                    'minMessage' => 'Используйте минимум {{ limit }} символов для категории'

                ]),
                    new NotBlank([
                        'message' => 'Пожалуйста введите категорию вопроса',
                    ]),

                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
