<?php

namespace GroceryBundle\Controller;

use GroceryBundle\Entity\Product;
use GroceryBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{

    /**
     *
     * @Route("/grocery/new-product", name="new_product")
     */
    public function addProductAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('Success', 'Produit ajouter!');

            return $this->redirectToRoute('list_product');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     *
     * @Route("/grocery/list-products", name="list_product")
     */
    public function productListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('GroceryBundle:Product')->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $product
        ]);
    }


    /**
     *
     * @Route("/grocery/edit/{id}/edit-product", name="edit_product")
     */
    public function editProductAction(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);

            $em->flush();

            $this->addFlash('Success', 'Le produit est modifier!');

            return $this->redirectToRoute('list_product');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/grocery/remove/product/{id}", name="delete_product")
     */
    public function deleteProductAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('GroceryBundle:Product')->find($id);

        $em->remove($product);
        $em->flush();
        $this->addFlash('Success', 'Produit est supprimer');

        return $this->redirectToRoute('list_product');
    }


    /**
     * @Route("/grocery/product/show/{id}", name="show_product")
     */
    public function showProductAction(Product $product)
    {
        $delete = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', [
           'form' => $product,
            'delete' => $delete->createView()
        ]);
    }

    /**
     *
     * @Route("/grocery/remove/product/{id}", name="delete_product")
     */
    public function deleteProdAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();

        }
        return $this->redirectToRoute('list_product');
    }


    public function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_product', ['id' => $product->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
