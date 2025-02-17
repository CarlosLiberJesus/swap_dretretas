<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class Legislatura
{
    public $id;
    public $uuid;
    public $nome;
    public $code;
    public $republicaId;
    public $eleicoes;
    public $formacao;
    public $dissolucao;
    public $sinopse;

    public function __construct(
        int $id,
        string $uuid,
        string $nome,
        string $code,
        int $republicaId,
        string $eleicoes,
        string $formacao,
        ?string $dissolucao = null,
        ?string $sinopse = null
    ) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->code = $code;
        $this->republicaId = $republicaId;
        $this->eleicoes = $eleicoes;
        $this->formacao = $formacao;
        $this->dissolucao = $dissolucao;
        $this->sinopse = $sinopse;
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['uuid'],
            $data['nome'],
            $data['code'],
            $data['republica_id'],
            $data['eleicoes'],
            $data['formacao'],
            $data['dissolucao'] ?? null,
            $data['sinopse'] ?? null
        );
    }

    public static function all(): array
    {
        return [
            self::create(['id' => 1, 'uuid' => '', 'nome' => 'Assembleia Constituinte', 'code' => '-', 'republica_id' => 4, 'eleicoes' => '1975-04-25', 'formacao' => '1975-06-02', 'dissolucao' => '1976-04-02']),
            self::create(['id' => 2, 'uuid' => '', 'code' => 'I', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '1976-04-25', 'formacao' => '1976-06-03', 'dissolucao' => '1980-11-12', 'sinopse' => '']),
            self::create(['id' => 3, 'uuid' => '', 'code' => 'II', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '1980-10-05', 'formacao' => '1980-11-13', 'dissolucao' => '1983-05-30', 'sinopse' => '']),
            self::create(['id' => 4, 'uuid' => '', 'code' => 'III', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '1983-04-25', 'formacao' => '1983-05-31', 'dissolucao' => '1985-11-03', 'sinopse' => '']),
            self::create(['id' => 5, 'uuid' => '', 'code' => 'IV', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '1985-10-06', 'formacao' => '1985-11-04', 'dissolucao' => '1987-08-12', 'sinopse' => '']),
            self::create(['id' => 6, 'uuid' => '', 'code' => 'V', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '1987-07-19', 'formacao' => '1987-08-13', 'dissolucao' => '1991-11-03', 'sinopse' => '']),
            self::create(['id' => 7, 'uuid' => '', 'code' => 'VI', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '1991-10-06', 'formacao' => '1991-11-04', 'dissolucao' => '1995-10-26', 'sinopse' => '']),
            self::create(['id' => 8, 'uuid' => '', 'code' => 'VII', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '1995-10-01', 'formacao' => '1995-10-27', 'dissolucao' => '1999-10-24', 'sinopse' => '']),
            self::create(['id' => 9, 'uuid' => '', 'code' => 'VIII', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '1999-10-10', 'formacao' => '1999-10-25', 'dissolucao' => '2002-04-04', 'sinopse' => '']),
            self::create(['id' => 10, 'uuid' => '', 'code' => 'IX', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '2002-03-17', 'formacao' => '2002-04-05', 'dissolucao' => '2005-03-09', 'sinopse' => '']),
            self::create(['id' => 11, 'uuid' => '', 'code' => 'X', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '2005-02-20', 'formacao' => '2005-03-10', 'dissolucao' => '2009-10-14', 'sinopse' => '']),
            self::create(['id' => 12, 'uuid' => '', 'code' => 'XI', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '2009-09-27', 'formacao' => '2009-10-15', 'dissolucao' => '2011-06-20', 'sinopse' => '']),
            self::create(['id' => 13, 'uuid' => '', 'code' => 'XII', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '2011-06-05', 'formacao' => '2011-06-21', 'dissolucao' => '2015-10-22', 'sinopse' => '']),
            self::create(['id' => 14, 'uuid' => '', 'code' => 'XIII', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '2015-10-04', 'formacao' => '2015-10-23', 'dissolucao' => '2019-10-24', 'sinopse' => '']),
            self::create(['id' => 15, 'uuid' => '', 'code' => 'XIV', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '2019-10-06', 'formacao' => '2019-10-25', 'dissolucao' => '2023-01-28', 'sinopse' => '']),
            self::create(['id' => 16, 'uuid' => '', 'code' => 'XV', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '2022-01-30', 'formacao' => '2022-03-29', 'dissolucao' => '2024-03-25']),
            self::create(['id' => 17, 'uuid' => '', 'code' => 'XVI', 'nome' => 'Assembleia da República', 'republica_id' => 4, 'eleicoes' => '2026-10-01', 'formacao' => '2024-03-26', 'dissolucao' => null]),
        ];
    }
}
