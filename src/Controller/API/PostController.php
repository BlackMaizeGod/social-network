<?php

namespace App\Controller\API;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Security\PostVoter;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/posts" , name="api")
 */
class PostController extends AbstractFOSRestController
{
    /**
     * @Route("/", name="api_posts_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->json($posts);
    }

    /**
     * @Route("/{id}/show", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->json($post);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        if ($userRepository->findOneBy(['id' => $request->get('userId')]) === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $post = new Post();
            $form = $this->createForm(PostType::class, $post);
            $form->submit(json_decode($request->getContent(), true));
            $form->handleRequest($request);

            $post->setUser($userRepository->findOneBy(['id' => $request->get('userId')]));
            if ($form->isSubmitted()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($post);
                $entityManager->flush();

                return $this->json('Ok');
            }
        }

        return $this->json('Not Ok');
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->submit(json_decode($request->getContent(), true));
        $form->handleRequest($request);

        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->json('Ok');
        }

        return $this->json('Not Ok');
    }

    /**
     * @Route("/{id}/delete", name="post_delete", methods={"POST","DELETE"})
     */
    public function delete(Post $post): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->json('Ok');
    }
}
