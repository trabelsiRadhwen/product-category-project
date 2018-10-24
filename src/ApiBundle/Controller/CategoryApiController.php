<?php

namespace ApiBundle\Controller;

use GroceryBundle\Entity\Category;
use GroceryBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryApiController extends Controller
{

    /**
     * @Rest\View()
     * @Rest\Get("categories/")
     */
    public function getCategoriesAction()
    {
        $categories = $this->getDoctrine()->getManager()->getRepository('GroceryBundle:Category')->findAll();

        if (empty($categories))
        {
            return new JsonResponse(['message' => 'Categories not found'], Response::HTTP_NOT_FOUND);
        }
        return $categories;
    }


    /**
     * @param $id
     * @return \GroceryBundle\Entity\Category|null|object|JsonResponse
     *
     * @Rest\View()
     * @Rest\Get("category/get/{id}")
     */
    public function getCategoryAction($id)
    {
        $category = $this->getDoctrine()->getManager()->getRepository('GroceryBundle:Category')->find($id);

        if (empty($category))
        {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
        return $category;
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/grocery/categories/new")
     * @throws \Doctrine\ORM\ORMException
     */
    public function addProductAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class);
        $form->submit($request->request->all());

        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($category);
            $em->flush();

            return $category;
        }else{
            return $form;
        }
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/grocery/categpry/remove/{id}")
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeProdcutAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $category = $em->getRepository('GroceryBundle:Category')
            ->find($request->request->get('id'));

        if ($category)
        {
            $em->remove($category);
            $em->flush();
        }
    }


    /**
     * @Rest\View()
     * @Rest\Put("/grocery/category/edit/{id}")
     */
    public function updateProductAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $category = $em->getRepository('GroceryBundle:Category')
            ->find($request->request->get('id'));

        if (empty($category))
        {
            return new JsonResponse(['message', 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(CategoryType::class);

        if ($form->isValid())
        {
            $em->merge($category);
            $em->flush();

            return $category;
        }else{
            return $form;
        }
    }
}
