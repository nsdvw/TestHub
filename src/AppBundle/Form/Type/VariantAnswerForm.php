<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class VariantAnswerForm extends AnswerForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $builder->add('answer', ChoiceType::class, [
            'choices' => $data['choices'],
            'expanded' => $data['expanded'],
            'multiple' => $data['multiple'],
        ]);
        $this->setCommonFields($builder, $options);
    }
}
