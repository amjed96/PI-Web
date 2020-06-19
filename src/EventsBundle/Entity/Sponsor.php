<?php

namespace EventsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Sponsor
 *
 * @Vich\Uploadable
 * @ORM\Table(name="sponsor")
 * @ORM\Entity(repositoryClass="EventsBundle\Repository\SponsorRepository")
 */
class Sponsor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idSponsor;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @Vich\UploadableField(mapping="logo_photo", fileNameProperty="photo")
     *
     * @var File
     */
    private $sponsorphoto;

    /**
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    private $photoUpdatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * Sponsor constructor.
     */
    public function __construct()
    {
        $this->photoUpdatedAt = new \DateTime('now');
    }


    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Sponsor
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }



    /**
     * Set description
     *
     * @param string $description
     *
     * @return Sponsor
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getIdSponsor()
    {
        return $this->idSponsor;
    }

    /**
     * @param int $idSponsor
     */
    public function setIdSponsor($idSponsor)
    {
        $this->idSponsor = $idSponsor;
    }

    /**
     * @return File
     */
    public function getSponsorphoto()
    {
        return $this->sponsorphoto;
    }

    /**
     * @param File $sponsorphoto
     */
    public function setSponsorphoto($sponsorphoto)
    {
        $this->sponsorphoto = $sponsorphoto;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return \DateTime
     */
    public function getPhotoUpdatedAt()
    {
        return $this->photoUpdatedAt;
    }

    /**
     * @param \DateTime $photoUpdatedAt
     */
    public function setPhotoUpdatedAt($photoUpdatedAt)
    {
        $this->photoUpdatedAt = $photoUpdatedAt;
    }








}
