<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserApiController extends Controller
{
    /**
     * @return array|\GroceryBundle\Entity\User[]|JsonResponse
     *
     * @Rest\View()
     * @Rest\Get("users/")
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('GroceryBundle:User')->findAll();

        if (empty($users))
        {
            return new JsonResponse(['message', 'Products not found'], Response::HTTP_NOT_FOUND);
        }
        return $users;
    }

    /**
     *
     * @Rest\View()
     * @Rest\Post("/users/login")
     */
    public function findUserPasswordAction(Request $request)
    {
        $username = $request->get("username");
        $password = $request->get("password");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GroceryBundle:User')->findOneBy(array("username" => $username));

        if ($user)
        {
            if ($this->get('security.password_encoder')->isPasswordValid($user, $password))
            {
                return $password;
            }
        }
        return new JsonResponse(['message' => 'not found'], Response::HTTP_NOT_FOUND);
    }
}
