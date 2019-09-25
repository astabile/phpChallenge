<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Twitter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

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

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $twitterRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entryRepository = $entityManager->getRepository('App:Entry');
        $this->userRepository = $entityManager->getRepository('App:User');
        $this->twitterRepository = $entityManager->getRepository('App:Twitter');
    }

    /**
     * @Route("/user/{name}/getTwitters", name="getTwitters")
     * @return Twitter[] $tweets
    */
    public function getTwittersAction($name) 
    {
        $user = $this->userRepository->findOneByUsername($name);
        
        /* Twitter connect */
        $connection = new TwitterOAuth(
            "2oudsMtAd5sznIHlbOtdGmoZB", 
            "5z0x1lqqBqU90J6ig9NikAqtRRWoDjwCpTBF6ay4lUS1Q7i2aj", 
            "1176534423359111168-duTP4D0whPSiLlHuJze5ovUJPMMI56", 
            "5ZY6PVgGlUUrcg1Owl8wwasspFMLSCcg3ACjxOlDyM3QE");
        
        // Get tweets from user account
        $contents = $connection->get("statuses/user_timeline", ["screen_name" => $user->getTwitter()]);
        
        // Get previously user hidden tweets
        $twittersToHide = $this->twitterRepository->getHiddenTweetsIdByUser($user->getId());

        $tweets =  [];

        if(is_array($contents)) {
            foreach($contents as $content) {
                $hidden = in_array($content->id_str, $twittersToHide);
                if($hidden && (!$this->getUser() || $user->getUsername() != $this->getUser()->getUsername()))
                    continue;
                    
                    $tweet = array(
                        'id' => $content->id_str, 
                        'text' => $content->text,
                        'hidden' => $hidden
                    );
                    array_push($tweets, $tweet);                      
            }
        }

        return $this->json($tweets);
    }

    
    /**
     * @Route("/user/deleteTweet", name="deleteTweet")
    */
    public function deleteTweet(Request $request)
    {
        $id = -1;
        if ($request->get('id')) {
            $id = $request->get('id');
        }

        $tweet = $this->twitterRepository->findOneByTwitterId($id);
        $user = $this->userRepository->findOneByUsername($this->getUser()->getUserName());

        if (!$tweet || !$user || $tweet->getUserId() != $user->getId()) {
            $this->addFlash('error', 'Unable to show tweet.');
        }

        $this->entityManager->remove($tweet);
        $this->entityManager->flush();

        return $this->json(true);
    }

    /**
     * @Route("/user/addTweet", name="addTweet")
    */
    public function addTweet(Request $request)
    {
        $id = -1;
        if ($request->get('id')) {
            $id = $request->get('id');
        }

        $tweet = new Twitter();
        $user = $this->userRepository->findOneByUsername($this->getUser()->getUserName());

        if (!$user) {
            $this->addFlash('error', 'Unable to hide tweet.');
        }

        $tweet->setUserId($user->getId());
        $tweet->setTwitterId($id);

        $this->entityManager->persist($tweet);
        $this->entityManager->flush($tweet);

        return $this->json(true);
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
