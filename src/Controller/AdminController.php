<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserFormType;

class AdminController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $entryRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $userRepository;

    /**
    * @param EntityManagerInterface $entityManager
    */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entryRepository = $entityManager->getRepository('App:Entry');
        $this->userRepository = $entityManager->getRepository('App:User');
    }
    
    /**
     * @Route("/admin/user/create", name="user_create")
     */
    public function createUserAction(Request $request)
    {
        if ($this->userRepository->findOneByUsername($this->getUser()->getUserName())) {
            // Redirect to dashboard.
            $this->addFlash('error', 'User already exists.');

            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $user->setUsername($this->getUser()->getUserName());

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush($user);

            $request->getSession()->set('user_is_user', true);
            $this->addFlash('success', 'You are now an user.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('admin/create_user.html.twig', [
            'form' => $form->createView()
        ]);
    }
}