<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\Booking as BookingAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ApiResource(
 *     collectionOperations={"get", "post", "find_between" = {
 *          "method"="GET",
 *          "path"="/bookings/find_between",
 *          "openapi_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "startAt",
 *                          "in" = "query",
 *                          "description" = "Start date & time for bookings retrieval",
 *                          "required" = "true",
 *                          "type" : "string",
 *                          "format" : "date-time"
 *                      },
 *                      {
 *                          "name" = "endAt",
 *                          "in" = "query",
 *                          "description" = "End date & time for bookings retrieval",
 *                          "required" = "true",
 *                          "type" : "string",
 *                          "format" : "date-time"
 *                      }
 *                  }
 *               }
 *          }
 *     },
 * )
 * @BookingAssert
 */
class Booking
{
    const DATE_FORMAT = 'Y-m-d H:i';
    
    const START_HOUR = 8;
    
    const END_HOUR = 20;
    
    const MIN_DURATION_IN_MINUTES = 30;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=1, max=2)
     */
    private $barber_id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $start_at;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $end_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBarberId(): ?int
    {
        return $this->barber_id;
    }

    public function setBarberId(int $barber_id): self
    {
        $this->barber_id = $barber_id;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->start_at;
    }

    public function setStartAt(\DateTimeInterface $start_at): self
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeInterface $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }
}
