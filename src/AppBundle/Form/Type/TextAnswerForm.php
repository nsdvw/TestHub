<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TextAnswerForm extends AnswerForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('answer', TextType::class);
        $this->setCommonFields($builder, $options);
    }
}
