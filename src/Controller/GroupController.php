<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/groups")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/", name="group_index", methods={"GET"})
     */
    public function index(GroupRepository $groupRepository): Response
    {
        return $this->render('group/index.html.twig', [
            'groups' => $groupRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="group_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group->addUser($userRepository->findOneBy(['id' => $request->get('userId')]));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profile_index',
            ['id' => $request->get('userId'), 'page' => $request->get('page')]);
    }

    /**
     * @Route("/{id}", name="group_show", methods={"GET"})
     */
    public function show(Group $group): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="group_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Group $group): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_index',
                ['id' => $request->get('userId'), 'page' => $request->get('page')]);
        }

        return $this->render('profile/group_edit.html.twig', [
            'group' => $form->createView(),
            'userId' => $request->get('userId'),
            'page' => $request->get('page'),
        ]);
    }

    /**
     * @Route("/{id}/group_delete", name="group_delete", methods={"GET","DELETE"})
     */
    public function delete(Request $request, Group $group): Response
    {
        //if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($group);
        $entityManager->flush();
        //}

        return $this->redirectToRoute('profile_index',
            ['id' => $request->get('userId'), 'page' => $request->get('page')]);
    }

    /**
     * @Route("/{id}/remove_user", name="group_remove_user", methods={"GET","POST"})
     */
    public function remove_user(Request $request, Group $group, UserRepository $userRepository): Response
    {
        $group->removeUser($userRepository->findOneBy(['id' => $request->get('userId')]));
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('profile_index',
            ['id' => $request->get('userId'), 'page' => $request->get('page')]);
    }
}
