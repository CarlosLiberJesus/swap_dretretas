<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class InstituicaoMorada {
    private $instituicaoId;
    private $morada;
    private $codigoPostal;
    private $localidade;
    private $concelhoId;

    public function __construct(int $instituicaoId, string $morada) {
        $this->instituicaoId = $instituicaoId;
        $this->morada = $morada;
    }

    public function setCodigoPostal(?string $codigoPostal): self {
        $this->codigoPostal = $codigoPostal;
        return $this;
    }

    public function setLocalidade(?string $localidade): self {
        $this->localidade = $localidade;
        return $this;
    }

    public function setConcelhoId(?int $concelhoId): self {
        $this->concelhoId = $concelhoId;
        return $this;
    }

    public function toSqlInsert(): string {
        return sprintf(
            "INSERT INTO instituicao_moradas (instituicao_id, morada, codigo_postal, localidade, concelho_id) VALUES (%d, '%s', '%s', '%s', %s)",
            $this->instituicaoId,
            $this->morada,
            $this->codigoPostal ?? 'NULL',
            $this->localidade ?? 'NULL',
            $this->concelhoId !== null ? $this->concelhoId : 'NULL'
        );
    }
}