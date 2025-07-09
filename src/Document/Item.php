<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\EmbeddedDocument]
class Item{

    #[MongoDB\Id]
    protected ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Descrição é obrigatória")]
    protected ?string $description = null;

    #[MongoDB\Field(type: 'float')]
    #[Assert\NotNull(message: "Preço inválido")]
    #[Assert\Positive(message: "Preço inválido")]
    protected ?float $price = null; 

    #[MongoDB\Field(type: 'int')]
    #[Assert\NotNull(message: "Quantidade inválida")]
    #[Assert\GreaterThan(value: 0, message: "Quantidade inválida")]
    protected ?int $quantity = null; 


    public function getId(): ?string{
        return $this->id;
    }
    public function setId(string $id): self {
        $this->id = $id;
        return $this;
    }

    public function getDescription(): ?string{
        return $this->description;
    }
    public function setDescription(string $description): self{
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?float{
        return $this->price;
    }
    public function setPrice(float $price): self{
        $this->price = round($price, 2); 
        return $this;
    }

    public function getQuantity(): ?int{
        return $this->quantity;
    }
    public function setQuantity(int $quantity): self{
        $this->quantity = $quantity;
        return $this;
    }

}