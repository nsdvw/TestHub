<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AnswerForm extends AbstractType
{
    protected function setCommonFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question_id', HiddenType::class)
            ->add('attempt_id', HiddenType::class)
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Далее',
                    'attr' =>
                        [
                            'class' => 'btn-default pull-right'
                        ]
                ]
            );
    }
}
