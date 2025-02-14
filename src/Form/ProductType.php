<?php
namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'required' => true,
            ])
            ->add('brand', TextType::class, [
                'label' => 'Brand',
                'required' => true,
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price',
                'currency' => 'PLN',
                'required' => true,
            ])
            ->add('imgPath', TextType::class, [
                'label' => 'Image Path',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'category_name',
                'label' => 'Category',
                'placeholder' => 'Choose a category',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add Product',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
