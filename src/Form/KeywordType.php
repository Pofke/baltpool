<?php

namespace App\Form;

use App\Entity\Keyword;
use App\Entity\Link;
use App\Module\Dto\KeywordDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KeywordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('keyword')
            ->add('link', EntityType::class, [
                'class' => Link::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => KeywordDto::class,
            'validation_groups' => ['required'],
            'csrf_protection' => false,
        ]);
    }
}
