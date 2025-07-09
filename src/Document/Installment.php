<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\EmbeddedDocument]
class Installment{
    #[MongoDB\Field(type: 'int')]
    #[Assert\NotNull(message: "Número da parcela é obrigatório")]
    #[Assert\Positive(message: "Número da parcela inválido")]
    private int $number;

    #[MongoDB\Field(type: 'date_immutable')]
    #[Assert\GreaterThanOrEqual("today", message: "Data de vencimento inválida")]
    private \DateTimeInterface $dueDate;

    #[MongoDB\Field(type: 'float')]
    #[Assert\NotNull(message: "Adicione um valor de parcela")]
    #[Assert\Positive(message: "Valor da parcela inválido")]
    #[Assert\Range(min: 0.01, minMessage: "O valor mínimo da parcela é R$ 0.01")]
    private float $amount;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Adicione um status para cobrança")]
    #[Assert\Choice(choices: ['pending', 'paid', 'overdue'], message: "Status inválido. Use: pending, paid ou overdue")]
    private string $status = 'pending'; // 'pending', 'paid', 'overdue'

    // Getters e Setters
    public function getNumber(): int{
        return $this->number;
    }
    public function setNumber(int $number): self{
        $this->number = $number;
        return $this;
    }

    public function getDueDate(): \DateTimeInterface {
        return $this->dueDate;
    }
    public function setDueDate(\DateTimeInterface $dueDate): self {
        $this->dueDate = $dueDate;
        return $this;
    }
    
    public function getAmount(): float {
        return $this->amount;
    }
    public function setAmount(float $amount): self {
        $this->amount = $amount;
        return $this;
    }
    
    public function getStatus(): string {
        return $this->status;
    }
    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

}