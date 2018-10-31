<?php
/**
 * Created by PhpStorm.
 * User: Radhwen
 * Date: 24/10/2018
 * Time: 15:50
 */

namespace GroceryBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GroceryBundle\Entity\Category;
use GroceryBundle\Entity\Product;
use GroceryBundle\Entity\User;
use function rand;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        return $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        $category = new Category();
        for ($i=0 ; $i <= 5; $i++)
        {
            $category->setProduct('Cat'. $i);

            $manager->persist($category);
        }

        $product = new Product();

        for ($i=0 ; $i <= 5; $i++)
        {
            $product->setProduct('Prod'. $i);
            $product->setPrice(rand(1, 200));
            $product->setLibelle('Libelle'. $i);
            $product->setProduct('Prod');
            $product->setCategory($category);

            $manager->persist($product);
        }

        $user = new User();

        for ($j = 0 ; $j<= 5; $i++)
        {
            $user->setEmail('admin'.$i.'@gmail.com');
            $manager->persist($user);
        }

        $manager->flush();
    }
}