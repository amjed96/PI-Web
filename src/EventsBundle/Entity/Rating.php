<?php

namespace EventsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity(repositoryClass="EventsBundle\Repository\RatingRepository")
 */
class Rating
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
     * @ORM\Column(name="rate", type="integer")
     */
    private $rate;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rate.
     *
     * @param int $rate
     *
     * @return Rating
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate.
     *
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @ORM\ManyToOne(targetEntity="EventsBundle\Entity\Events")
     * @ORM\JoinColumn(name="id_Event",referencedColumnName="id", onDelete="CASCADE")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_User",referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvents($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUsers($user)
    {
        $this->user = $user;
    }

}
