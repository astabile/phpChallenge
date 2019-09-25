<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    /** @var integer */
    const POST_LIMIT = 3;
    
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
     * @Route("/", name="homepage")
     * @Route("/entries", name="entries")
     */
    public function entriesAction(Request $request)
    {
        $page = 1;

        if ($request->get('page')) {
            $page = $request->get('page');
        }

        $entries = $this->entryRepository->getAllEntries($page, self::POST_LIMIT);
        foreach($entries as $entry)
        {
            $entry->setContent(strlen($entry->getContent()) > 500 ? substr($entry->getContent(),0,500)." ..." : $entry->getContent());
        }

        return $this->render('blog/entries.html.twig', [
            'entries' => $entries,
            'totalEntries' => $this->entryRepository->getEntryCount(),
            'page' => $page,
            'entryLimit' => self::POST_LIMIT
        ]);
    }

    /**
     * @Route("/entry/{slug}", name="entry")
     */
    public function entryAction($slug)
    {
        $entry = $this->entryRepository->findOneBySlug($slug);

        if (!$entry) {
            $this->addFlash('error', 'Unable to find entry.');

            return $this->redirectToRoute('entries');
        }

        return $this->render('blog/entry.html.twig', array(
            'entry' => $entry
        ));
    }

    /**
     * @Route("/user/{name}", name="user")
     */
    public function userAction($name)
    {
        $user = $this->userRepository->findOneByUsername($name);

        if (!$user) {
            $this->addFlash('error', 'Unable to find user.');
            return $this->redirectToRoute('entries');
        }

        $entries = $this->entryRepository->findByUser($user);

        foreach($entries as $entry)
        {
            $entry->setContent(strlen($entry->getContent()) > 500 ? substr($entry->getContent(),0,500)." ..." : $entry->getContent());
        }
        
        return $this->render('blog/user.html.twig', [
            'user' => $user,
            'entries' => $entries
        ]);
    }
}
