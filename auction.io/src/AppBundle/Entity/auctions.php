<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * auctions
 *
 * @ORM\Table(name="auctions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\auctionsRepository")
 */
class auctions
{

    //Doctrine:schema:update zaktualizowanie bazy danych
    const STATUS_ACTIVE = "active";
    const STATUS_FINISHED = "finished";
    const STATUS_CANCELLED = "cancelled";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var Offer[]
     * @ORM\OneToMany(targetEntity="Offer" , mappedBy="auction")
     */
    private $offers;

    /**
     * auctions constructor.
     */
    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var float
     * @ORM\Column(name="startingPrice" , type="decimal" , precision=10 , scale=2)
     */
    private $startingPrice;

    /**
     * @var \DateTime
     * @ORM\Column(name="expiresAt" , type="datetime")
     */
    private $expiresAt;

    /**
     * @Gedmo\Timestampable(on="create")
     * @var \DateTime
     * @ORM\Column(name="createdAt" , type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @var \DateTime
     * @ORM\Column(name="updatedAt" , type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     * @ORM\Column(name="status" , type="string" , length=10)
     */
    private $status;
    /**
     * Get id
     *
     * @return int
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return auctions
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return auctions
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
     * Set price
     *
     * @param string $price
     *
     * @return auctions
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $startingPrice
     * @return $this
     */
    public function setStartingPrice($startingPrice){
        $this->startingPrice = $startingPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getStartingPrice(){
        return $this->startingPrice;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt){
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(){
        return $this->createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt){
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(){
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $expiresAt
     * @return $this
     */
    public function setExpiresAt(\DateTime $expiresAt){
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt(){
        return $this->expiresAt;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status){
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * @return Offer[]|ArrayCollection
     */
    public function getOffers(){
        return $this->offers;
    }

    /**
     * @param Offer $offer
     * @return $this
     */
    public function addOffer(Offer $offer){
        $this->offers = $offer;

        return $this;
    }
}

