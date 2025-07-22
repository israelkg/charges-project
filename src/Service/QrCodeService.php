<?php
namespace App\Service;

use Endroid\QrCode\Builder\BuilderInterface;

class QrCodeService{
    private BuilderInterface $builder;

    public function __construct(BuilderInterface $builder){
        $this->builder = $builder;
    }

    public function generateQrCode(string $data): string{
        $result = $this->builder
            ->data($data)
            ->size(300)
            ->margin(10)
            ->build();

        return $result->getDataUri(); // base64 pronto para <img>
    }
}
