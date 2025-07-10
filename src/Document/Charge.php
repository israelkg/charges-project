<?php

namespace App\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use App\Document\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[MongoDB\Document(collection: "charges", repositoryClass: "App\Repository\ChargeRepository")]
class Charge{
    public const PAYMENT_TYPES = ['cash', 'installment', 'subscription'];
    public const PAYMENT_METHODS = ['credit_card', 'boleto', 'pix', 'trasnfer'];
    public const STATUSES = ['PENDENTE', 'PAGO', 'VENCIDO', 'CANCELADO'];


    #[MongoDB\Id]
    protected ?string $id = null;

    #[MongoDB\ReferenceOne(targetDocument: User::class)]
    private ?User $owner = null;
    
    #[MongoDB\Field(type: "string")]
    private string $userId;

    // Informações da Cobrança
    #[MongoDB\Field(type: 'float')]
    #[Assert\NotNull(message: "Valor de Cobrança inválido")]
    #[Assert\Positive(message: "Valor de Cobrança inválido")]
    #[Assert\GreaterThanOrEqual( value: 0.01, message: "O valor mínimo é R$ 0.01")]
    #[Assert\LessThanOrEqual( value: 1000000, message: "O valor máximo permitido é R$ 1.000.000" )]
    protected float $amount; 

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Descrição é obrigatória")]
    protected ?string $descriptionCharge = null;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Selecione o tipo de pagamento")]
    #[Assert\Choice(
        choices: self::PAYMENT_TYPES,
        message: "Tipo de pagamento inválido."
    )]
    protected ?string $paymentType = null;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Selecione a forma de pagamento")]
    #[Assert\Choice(
        choices: ['credit_card', 'boleto', 'pix'],
        message: "Forma de pagamento inválida."
    )]
    protected ?string $paymentMethod = null;

    #[MongoDB\Field(type: 'date_immutable')]
    #[Assert\NotNull(message: "Data de Vencimento inválida")]
    #[Assert\GreaterThanOrEqual(value: "today", message: "Data de Vencimento inválida")]
    protected \DateTimeImmutable $dueDate;

    #[MongoDB\Field(type: 'date_immutable')]
    protected ?\DateTimeImmutable $paidAt = null;

    #[MongoDB\Field(type: 'date_immutable')]
    protected ?\DateTimeImmutable $createdAt = null;

    // // Campos para parcelamento
    // #[MongoDB\EmbedMany(targetDocument: Installment::class)]
    // protected Collection $installments;

    #[MongoDB\Field(type: 'int')]
    #[Assert\PositiveOrZero]
    protected ?int $installmentsCount = null;

    // Status e metadados
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Status inválido")]
    #[Assert\Choice(choices: ['PENDENTE', 'PAGO', 'VENCIDO', 'CANCELADO'], message: "Status inválido")]
    protected string $status; 

    // Itens da cobrança
    #[MongoDB\EmbedMany(targetDocument: Item::class)]
    #[Assert\Valid] 
    #[Assert\Count(min: 1, minMessage: "Adicione um item")]
    protected Collection $items;


    // Informações do Cliente
    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Nome do Cliente é obrigatório")]
    #[Assert\Length(min: 3, max: 255, minMessage: "O nome do cliente deve ter pelo menos {{ limit }} caracteres", maxMessage: "O nome do cliente não pode ter mais de {{ limit }} caracteres")]
    protected string $customerName; 

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Email do cliente é obrigatório")]
    #[Assert\Email(message: "Email do cliente inválido")]
    protected string $customerEmail;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Cpf/Cnpj do cliente é obrigatório")]
    protected ?string $cpfCnpj = null;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank(message: "Número de celular inválido")]
    #[Assert\Regex(
        pattern: '/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/',
        message: 'Número de celular inválido'
    )]
    protected ?string $numberPhone = null;

    #[MongoDB\Field(type: "collection")]
    #[Assert\All([
        new Assert\Choice(choices: ['whatsapp', 'email', 'sms'], message: 'Escolha inválida.')
    ])]
    private array $deliveryMethods = [];



    public function __construct(){
        $this->status = 'PENDENTE';
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        // $this->installments = new ArrayCollection();
        // $this->installmentsCount = null;
    }

    public function getId(): ?string{
        return $this->id;
    }

    public function getUserId(): string{
        return $this->userId;
    }
    public function setUserId(string $userId): void{
        $this->userId = $userId;
    }

    public function getOwner(): ?User{
        return $this->owner;
    }
    public function setOwner(?User $owner): void{
        $this->owner = $owner;
    }

    //Todos os Campos
    public function getDescriptionCharge(): ?string{
        return $this->descriptionCharge;
    }
    public function setDescriptionCharge(string $descriptionCharge): self{
        $this->descriptionCharge = $descriptionCharge;
        return $this;
    }

    public function getCustomerName(): string{
        return $this->customerName;
    }
    public function setCustomerName(string $customerName): self{
        $this->customerName = $customerName;
        return $this;
    }

    public function getCustomerEmail(): string{
        return $this->customerEmail;
    }
    public function setCustomerEmail(string $customerEmail): self{
        $this->customerEmail = $customerEmail;
        return $this;
    }

    public function getCpfCnpj(): ?string{
        return $this->cpfCnpj;
    }
    public function setCpfCnpj(string $cpfCnpj): self{
        $this->cpfCnpj = $cpfCnpj;
        return $this;
    }

    public function getNumberPhone(): ?string{
        return $this->numberPhone;
    }
    public function setNumberPhone(string $numberPhone): self{
        $this->numberPhone = $numberPhone;
        return $this;
    }

    public function getDeliveryMethods(): array{
        return $this->deliveryMethods;
    }
    public function setDeliveryMethods(array $deliveryMethods): self{
        $this->deliveryMethods = $deliveryMethods;
        return $this;
    }

    
    public function getAmount(): float{
        return $this->amount;
    }
    public function setAmount(float $amount): self{
        $this->amount = $amount;
        return $this;
    }

    public function getDueDate(): \DateTimeImmutable{
        return $this->dueDate;
    }
    public function setDueDate(\DateTimeImmutable $dueDate): self{
        $this->dueDate = $dueDate;
        return $this;
    }

    public function getPaidAt(): ?\DateTimeImmutable{
        return $this->paidAt;
    }
    public function setPaidAt(?\DateTimeImmutable $paidAt): self{
        $this->paidAt = $paidAt;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable{
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self{
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getStatus(): string{
        return $this->status;
    }
    public function setStatus(string $status): self{
        $this->status = $status;
        return $this;
    }

    public function getPaymentType(): ?string{
        return $this->paymentType;
    }
    public function setPaymentType(string $paymentType): self{
        $this->paymentType = $paymentType;
        return $this;
    }

    public function getPaymentMethod(): ?string{
        return $this->paymentMethod;
    }
    public function setPaymentMethod(string $paymentMethod): self{
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getInstallmentsCount(): ?int{
        return $this->installmentsCount;
    }
    public function setInstallmentsCount(?int $installmentsCount): self{
        $this->installmentsCount = $installmentsCount;
        return $this;
    }

    #[MongoDB\EmbedMany(targetDocument: Installment::class)]
    protected Collection $installments;

    public function getInstallments(): Collection {
        return $this->installments;
    }

    
    #[Assert\Callback]
    public function validateInstallments(ExecutionContextInterface $context){
        if ($this->paymentType === 'installment'){
            if($this->amount < 20){
                $context->buildViolation('Para pagamento parcelado, o valor mínimo é R$ 20,00')
                ->atPath('amount')
                ->addViolation();
            }
            if ($this->installmentsCount && $this->amount / $this->installmentsCount < 5) {
                $context->buildViolation('Valor mínimo por parcela: R$ 5,00')
                    ->atPath('amount')
                    ->addViolation();
            }
        }
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection{
        return $this->items;
    }
    public function addItem(Item $item): self{
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }
        return $this;
    }
    public function removeItem(Item $item): self{
        $this->items->removeElement($item);
        return $this;
    }


    #[Assert\Callback]
    public function validateCpfCnpj(ExecutionContextInterface $context): void{
        $value = preg_replace('/\D/', '', $this->cpfCnpj);

        if (strlen($value) === 11 && !$this->isValidCpf($value)) {
            $context->buildViolation('CPF inválido')
                ->atPath('cpfCnpj')
                ->addViolation();
        }

        if (strlen($value) === 14 && !$this->isValidCnpj($value)) {
            $context->buildViolation('CNPJ inválido')
                ->atPath('cpfCnpj')
                ->addViolation();
        }
    }
    private function isValidCpf(string $cpf): bool{
        if (preg_match('/(\d)\1{10}/', $cpf)) return false; // evita sequência tipo 11111111111

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }

    private function isValidCnpj(string $cnpj): bool{
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

        $tamanho = strlen($cnpj) - 2;
        $numeros = substr($cnpj, 0, $tamanho);
        $digitos = substr($cnpj, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;

        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) $pos = 9;
        }

        $resultado = ($soma % 11 < 2) ? 0 : 11 - $soma % 11;
        if ($resultado != $digitos[0]) return false;

        $tamanho += 1;
        $numeros = substr($cnpj, 0, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;

        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) $pos = 9;
        }

        $resultado = ($soma % 11 < 2) ? 0 : 11 - $soma % 11;
        return $resultado == $digitos[1];
    }
}
