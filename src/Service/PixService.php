<?php

namespace App\Service;

class PixService{
    public function gerarPayload(
        string $chavePix,
        float $valor,
        string $nomeRecebedor,
        string $cidade,
        string $txid
    ): string {
        $nomeRecebedor = strtoupper($nomeRecebedor);
        $cidade = strtoupper($cidade);
        $valorFormatado = number_format($valor, 2, '.', '');

        $payload =
            '000201' .
            '26330014BR.GOV.BCB.PIX' .
            '01' . str_pad(strlen($chavePix), 2, '0', STR_PAD_LEFT) . $chavePix .
            '52040000' .
            '5303986' .
            '54' . str_pad(strlen($valorFormatado), 2, '0', STR_PAD_LEFT) . $valorFormatado .
            '5802BR' .
            '59' . str_pad(strlen($nomeRecebedor), 2, '0', STR_PAD_LEFT) . $nomeRecebedor .
            '60' . str_pad(strlen($cidade), 2, '0', STR_PAD_LEFT) . $cidade .
            '62' . str_pad(strlen("05" . $txid), 2, '0', STR_PAD_LEFT) . "05" . $txid;

        $payloadComCRC = $payload . '6304';
        $crc = strtoupper(dechex($this->crc16($payloadComCRC)));
        $crc = str_pad($crc, 4, '0', STR_PAD_LEFT);

        return $payloadComCRC . $crc;
    }

    private function crc16(string $str): int{
        $polinomio = 0x1021;
        $resultado = 0xFFFF;

        foreach (str_split($str) as $char) {
            $resultado ^= (ord($char) << 8);
            for ($bit = 0; $bit < 8; $bit++) {
                $resultado = ($resultado & 0x8000)
                    ? (($resultado << 1) ^ $polinomio)
                    : ($resultado << 1);
                $resultado &= 0xFFFF;
            }
        }

        return $resultado;
    }
}
