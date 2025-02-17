<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class Republica
{
    public $id;
    public $uuid;
    public $nome;
    public $anoInicio;
    public $anoFim;
    public $sinopse;

    public function __construct(
        int $id,
        string $uuid,
        string $nome,
        int $anoInicio,
        ?int $anoFim = null,
        ?string $sinopse = null
    ) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->anoInicio = $anoInicio;
        $this->anoFim = $anoFim;
        $this->sinopse = $sinopse;
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['uuid'],
            $data['nome'],
            $data['ano_inicio'],
            $data['ano_fim'] ?? null,
            $data['sinopse'] ?? null
        );
    }

    public static function all(): array
    {
        return [
            self::create(['id' => 1, 'uuid' => '', 'nome' => 'Monarquia Constitucional', 'ano_inicio' => 1834, 'ano_fim' => 1910]),
            self::create(['id' => 2, 'uuid' => '', 'nome' => 'Primeira República', 'ano_inicio' => 1910, 'ano_fim' => 1926]),
            self::create(['id' => 3, 'uuid' => '', 'nome' => 'Segunda República', 'ano_inicio' => 1926, 'ano_fim' => 1974]),
            self::create(['id' => 4, 'uuid' => '', 'nome' => 'Terceira República', 'ano_inicio' => 1974]),
        ];
    }
}
