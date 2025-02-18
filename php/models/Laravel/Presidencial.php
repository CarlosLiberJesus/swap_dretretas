<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class Presidencial
{
    public $id;
    public $uuid;
    public $republicaId;
    public $eleicoes;
    public $posse;
    public $termino;
    public $sinopse;

    public function __construct(
        int $id,
        string $uuid,
        int $republicaId,
        ?string $eleicoes,
        string $posse,
        ?string $termino = null,
        ?string $sinopse = null
    ) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->republicaId = $republicaId;
        $this->eleicoes = $eleicoes;
        $this->posse = $posse;
        $this->termino = $termino;
        $this->sinopse = $sinopse;
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['uuid'],
            $data['republica_id'],
            $data['eleicoes'] ?? null,
            $data['posse'],
            $data['termino'] ?? null,
            $data['sinopse'] ?? null
        );
    }

    public static function all(): array
    {
        return [
            self::create(['id' => 1, 'uuid' => '', 'republica_id' => 4, 'posse' => '15-05-1974', 'termino' => '30-09-1974']),
            self::create(['id' => 2, 'uuid' => '', 'republica_id' => 4, 'posse' => '30-09-1974', 'termino' => '14-07-1976']),
            self::create(['id' => 3, 'uuid' => '', 'republica_id' => 4, 'eleicoes' => '1976-06-27', 'posse' => '14-07-1976', 'termino' => '09-03-1986']),
            self::create(['id' => 4, 'uuid' => '', 'republica_id' => 4, 'eleicoes' => '1986-01-26', 'posse' => '09-03-1986', 'termino' => '09-03-1996']),
            self::create(['id' => 5, 'uuid' => '', 'republica_id' => 4, 'eleicoes' => '1996-01-14', 'posse' => '09-03-1996', 'termino' => '09-03-2006']),
            self::create(['id' => 6, 'uuid' => '', 'republica_id' => 4, 'eleicoes' => '2006-01-22', 'posse' => '09-03-2006', 'termino' => '09-03-2016']),
            self::create(['id' => 7, 'uuid' => '', 'republica_id' => 4, 'eleicoes' => '2016-01-24', 'posse' => '09-03-2016']),
        ];
    }

    public static function getByNome(string $nome): ?self
    {
        $presidenciais = self::all();
        foreach ($presidenciais as $presidencial) {
            if (stripos($presidencial->nome, $nome) !== false) {
                return $presidencial;
            }
        }
        return null;
    }

    public static function getDistinctNome(): array
    {
        $presidenciais = self::all();
        $distinctNome = [];
        foreach ($presidenciais as $presidencial) {
            if (!in_array($presidencial->nome, $distinctNome)) {
                $distinctNome[] = $presidencial->nome;
            }
        }
        return $distinctNome;
    }
}