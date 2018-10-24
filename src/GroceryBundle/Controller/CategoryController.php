<?php

namespace GroceryBundle\Controller;

use GroceryBundle\Entity\Category;
use GroceryBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{

    /**
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/grocery/new-category", name="new_category")
     */
    public function addCategoryAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('Success', 'Nouvelle categorie est ajouyer!');

            return $this->redirectToRoute('list_category');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/grocery/list-categories", name="list_category")
     */
    public function categoriesListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('GroceryBundle:Category')->findAll();

        return $this->render('category/list.html.twig', [
            'categories' => $category
        ]);
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/grocery/edit/{id}/edit-grocery", name="edit_category")
     */
    public function editCategorieAction(Request $request, Category $category)
    {
        $form = $this->createForm('GroceryBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('Success', 'La categorie est esditer!');

            return $this->redirectToRoute('list_category');
        }
        return $this->render('category/edit.html.twig', [
           'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/grocery/remove/category/{id}", name="delete_category")
     */
    public function deleteCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('GroceryBundle:Category')->find($id);

        $em->remove($category);
        $em->flush();
        $this->addFlash('Success', 'Category est supprimer');

        return $this->redirectToRoute('list_category');
    }

}
