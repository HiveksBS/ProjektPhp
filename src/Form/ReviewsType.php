<?php
namespace App\Form;

use App\Entity\Reviews;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class ReviewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        // ->add('rating' ,ChoiceType::class, [
        //     'choices' => [
        //         '1 Star' => 1,
        //         '2 Stars' => 2,
        //         '3 Stars' => 3,
        //         '4 Stars' => 4,
        //         '5 Stars' => 5,
        //     ],
        //     'expanded' => true, 
        //     'multiple' => false,
        //     'label' => 'Ocena',
        // ])
            ->add('rating', IntegerType::class, [
                'label' => 'Twoja Ocena  (0-10)',
                'attr' => ['min' => 0, 'max' => 10, 'step' => 1],
                'constraints' => [
                    new Range(['min' => 0, 'max' => 10]),
                ],
            ])
            ->add('review_text', TextareaType::class, [
                'label' => 'Recenzja',
                'attr' => ['rows' => 5, 'placeholder' => 'Napisz swoją recenzje...'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Dodaj',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}
