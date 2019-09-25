<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Twitter
 *
 * @ORM\Table(name="Twitter")
 * @ORM\Entity(repositoryClass="App\Repository\TwitterRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Twitter
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_id", type="string", length=20)
     */
    private $twitterId;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param int $user_id
     *
     * @return Twitter
     */
    public function setUserId($user_id)
    {
        $this->userId = $user_id;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set twitterId
     *
     * @param string $twitter_id
     *
     * @return Twitter
     */
    public function setTwitterId($twitter_id)
    {
        $this->twitterId = $twitter_id;

        return $this;
    }

    /**
     * Get twitterId
     *
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }
}