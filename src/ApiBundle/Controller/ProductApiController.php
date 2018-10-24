<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use GroceryBundle\Entity\Product;
use GroceryBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductApiController extends Controller
{

    /**
     * @return array|\GroceryBundle\Entity\Product[]|JsonResponse
     *
     * @Rest\View()
     * @Rest\Get("products/")
     */
    public function getProductsAction()
    {
        $products = $this->getDoctrine()->getManager()->getRepository('GroceryBundle:Product')->findAll();

        if (empty($products))
        {
            return new JsonResponse(['message', 'Products not found'], Response::HTTP_NOT_FOUND);
        }
        return $products;
    }

    /**
     * @param $id
     * @return \GroceryBundle\Entity\Product|null|object|JsonResponse
     *
     * @Rest\View()
     * @Rest\Get("product/get/{id}")
     */
    public function getProductAction($id)
    {
        $product = $this->getDoctrine()->getManager()->getRepository('GroceryBundle:Product')->find($id);

        if (empty($product))
        {
            return new JsonResponse(['message', 'Products not found'], Response::HTTP_NOT_FOUND);
        }
        return $product;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/grocery/products/new")
     * @throws \Doctrine\ORM\ORMException
     */
    public function addProductAction(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class);
        $form->submit($request->request->all());

        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($product);
            $em->flush();

            return $product;
        }else{
            return $form;
        }
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/grocery/product/remove/{id}")
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeProdcutAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $product = $em->getRepository('GroceryBundle:Product')
            ->find($request->request->get('id'));

        if ($product)
        {
            $em->remove($product);
            $em->flush();
        }
    }


    /**
     * @Rest\View()
     * @Rest\Put("/grocery/product/edit/{id}")
     */
    public function updateProductAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $product = $em->getRepository('GroceryBundle:Product')
            ->find($request->request->get('id'));

        if (empty($product))
        {
            return new JsonResponse(['message', 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(ProductType::class);

        if ($form->isValid())
        {
            $em->merge($product);
            $em->flush();

            return $product;
        }else{
            return $form;
        }
    }
}
