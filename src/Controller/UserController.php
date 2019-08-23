<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\ORM\CreateUser;
use App\Entity\Status;
use App\Entity\User;
use App\Form\CreateUserType;
use App\Form\MediaType;
use App\Form\UserType;
use App\Repository\FriendRepository;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Service\MailSender;
use App\Service\MediaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, MailSender $mailSender, MediaService $mediaService)
    {
        $userModel = new CreateUser();
        $form = $this->createForm(CreateUserType::class, $userModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setName($userModel->getName());
            $user->setSurname($userModel->getSurname());
            $user->setLogin($userModel->getLogin());
            $user->setPassword(password_hash($userModel->getPassword(), PASSWORD_BCRYPT, ['cost' => 11]));
            $user->setEmail($userModel->getEmail());
            $user->setRoles(['ROLE_USER']);
            $user->setToken(md5($userModel->getLogin()));
            $user->setVerification(false);
            $status = new Status();
            $status->setQuote($userModel->getStatus());
            $status->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($status);
            $entityManager->flush();
            $media = new Media();
            if (is_null($userModel->getAvatar())) {
                $media->setFileName('upload/without');
            } else {
                $fileName = $mediaService->upload($userModel->getAvatar(), $user->getId());
                $media->setFileName($fileName);
            }
            $media->addUser($user);
            $media->setOwner($user);
            $entityManager->persist($media);
            $entityManager->flush();
            $mailSender->sendMessage($user, 'emails/verification.html.twig', $user->getEmail());

            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/new_user.html.twig', [
            'createUserForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, GroupRepository $groupRepository): Response
    {
        if (!$request->get('groupId')) {
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
            }
        } else {
            $group = $groupRepository->findOneBy(['id' => $request->get('groupId')]);
            $user->addGroup($group);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('profile_index', ['id' => $user->getId(), 'page' => $request->get('page')]);
    }

    /**
     * @Route("/{id}/delete", name="user_delete", methods={"GET","DELETE"})
     */
    public function delete(User $user, FriendRepository $friendRepository, MailSender $mailSender): Response
    {
        //if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
        $mailSender->sendMessage($user, 'emails/left.html.twig', 'artem.lohvynenko@ekreative.com');
        $entityManager = $this->getDoctrine()->getManager();
        foreach (($friendRepository->findByDiff($user->getId())) as $friend) {
            $entityManager->remove($friend);
        }
        $entityManager->remove($user);
        $entityManager->flush();
        //}

        return $this->redirectToRoute('profile_new_user');
    }

    /**
     * @Route("/{id}/avatar/edit", name="user_edit_avatar", methods={"GET","POST"})
     */
    public function editAvatar(Request $request, Media $medium, UserRepository $userRepository, MediaService $mediaService): Response
    {
        $form = $this->createForm(MediaType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = $mediaService->upload($request->files->get('media')['file'],
                                            $userRepository->findOneBy(['avatar' => $medium->getId()])->getId());
            $medium->setFileName($fileName);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('profile_index',
            ['id' => $userRepository->findOneBy(['avatar' => $medium->getId()])->getId(), 'page' => $request->get('page')]);
    }
}
