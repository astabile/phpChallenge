<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserFormType;
use App\Entity\Entry;
use App\Form\EntryFormType;

/**
 * @Route("/admin")
 */
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
     * @Route("/user/create", name="user_create")
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

    /**
     * @Route("/create-entry", name="admin_create_entry")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createEntryAction(Request $request)
    {
        $entry = new Entry();

        $user = $this->userRepository->findOneByUsername($this->getUser()->getUserName());
        $entry->setUser($user);

        //Generate slug from title 
        $slug = $entry->generateSeoURL($entry->getTitle(), 50);
        $entry->setSlug($slug);

        $form = $this->createForm(EntryFormType::class, $entry);
        $form->handleRequest($request);

        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entry);
            $this->entityManager->flush($entry);

            $this->addFlash('success', 'Your entry was created');

            return $this->redirectToRoute('admin_entries');
        }

        return $this->render('admin/entry_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="admin_index")
     * @Route("/entries", name="admin_entries")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function entriesAction()
    {
        $user = $this->userRepository->findOneByUsername($this->getUser()->getUserName());

        $entries = [];

        if ($user) {
            $entries = $this->entryRepository->findByUser($user);
        }

        return $this->render('admin/entries.html.twig', [
            'entries' => $entries
        ]);
    }

    /**
     * @Route("/edit-entry/{entryId}", name="admin_edit_entry")
     *
     * @param $entryId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editEntryAction($entryId)
    {
        $entry = $this->entryRepository->findOneById($entryId);
        $user = $this->userRepository->findOneByUsername($this->getUser()->getUserName());

        if (!$entry || $user !== $entry->getUser()) {
            $this->addFlash('error', 'Unable to edit entry.');

            return $this->redirectToRoute('admin_entries');
        }

        $this->entityManager->refresh($entry);
        $this->entityManager->flush();

        $this->addFlash('success', 'Entry was edited.');

        return $this->redirectToRoute('admin_entries');
    }

    /**
     * @Route("/delete-entry/{entryId}", name="admin_delete_entry")
     *
     * @param $entryId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteEntryAction($entryId)
    {
        $entry = $this->entryRepository->findOneById($entryId);
        $user = $this->userRepository->findOneByUsername($this->getUser()->getUserName());

        if (!$entry || $user !== $entry->getUser()) {
            $this->addFlash('error', 'Unable to remove entry.');

            return $this->redirectToRoute('admin_entries');
        }

        $this->entityManager->remove($entry);
        $this->entityManager->flush();

        $this->addFlash('success', 'Entry was removed.');

        return $this->redirectToRoute('admin_entries');
    }
}