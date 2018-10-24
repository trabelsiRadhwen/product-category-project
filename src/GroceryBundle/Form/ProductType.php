<?php

namespace GroceryBundle\Form;

use GroceryBundle\Entity\Category;
use GroceryBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product')
            ->add('libelle')
            ->add('price')
            ->add('category', EntityType::class, [
                'class' => Category::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'grocery_bundle_product_type';
    }
}
