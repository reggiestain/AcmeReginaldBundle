<?php

namespace Acme\ReginaldBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Output
 *
 * @ORM\Table(name="output")
 * @ORM\Entity(repositoryClass="Acme\ReginaldBundle\Repository\OutputRepository")
 */
class Output
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="launch_url", type="string", length=255)
     */
    private $launchUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="Icon_url", type="string", length=255)
     */
    private $iconUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;


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
     * @return Output
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
     * @return Output
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
     * Set launchUrl
     *
     * @param string $launchUrl
     *
     * @return Output
     */
    public function setLaunchUrl($launchUrl)
    {
        $this->launchUrl = $launchUrl;

        return $this;
    }

    /**
     * Get launchUrl
     *
     * @return string
     */
    public function getLaunchUrl()
    {
        return $this->launchUrl;
    }

    /**
     * Set iconUrl
     *
     * @param string $iconUrl
     *
     * @return Output
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * Get iconUrl
     *
     * @return string
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Output
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
}

