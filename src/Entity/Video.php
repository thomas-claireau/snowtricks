<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\Regex("/<iframe ?.*>(.*)<\/iframe>/")
	 */
	private $url;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Figures", inversedBy="videos")
	 */
	private $figure;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUrl(): ?string
	{
		return $this->url;
	}

	public function setUrl(string $url): self
	{
		$this->url = $url;

		return $this;
	}

	public function getFigure(): ?Figures
	{
		return $this->figure;
	}

	public function setFigure(?Figures $figure): self
	{
		$this->figure = $figure;

		return $this;
	}
}
