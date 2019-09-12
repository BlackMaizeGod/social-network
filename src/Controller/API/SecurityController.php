<?php


namespace App\Controller\API;

use App\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends FOSRestController
{
    /**
     * @Rest\Post("/login", name="api_login")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return JsonResponse
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $login = $request->get('login');
        $password = $request->get('password');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(
                [
                    'login' => $login,
                ]
            );

        if (!$user) {
            return new JsonResponse(
                [
                    'error' => 'no such user',
                ]
            );
        }

        if (false == $passwordEncoder->isPasswordValid($user, $password)) {
            return new JsonResponse(
                [
                    'error' => 'wrong password',
                ]
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $token = md5(date('l dS of F Y h:i:s A'));
        $user->setApiToken($token);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(
            [
                'token' => $token,
            ]
        );
    }
}
