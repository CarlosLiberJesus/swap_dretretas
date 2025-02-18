<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class RelacaoTipo
{
    public $id;
    public $entre;
    public $nome;
    public $createdAt;
    public $updatedAt;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $relacaoTipo = new self($data['id']);
        $relacaoTipo->entre = $data['entre'];
        $relacaoTipo->nome = $data['nome'];
        return $relacaoTipo;
    }

    public static function getById(int $id): ?self
    {
        $relacoes = self::all();
        foreach ($relacoes as $relacao) {
            if ($relacao->id === $id) {
                return $relacao;
            }
        }
        return null;
    }

    public static function getRelacaoTipo(int $id): ?self
    {
        $relacoes = self::all();
        foreach ($relacoes as $relacao) {
            if ($relacao->id === $id) {
                return $relacao;
            }
        }
        return null;
    }

    public static function all(): array
    {
        return [
            self::create(['id' => 1, 'entre' => 'cidadaos', 'nome' => 'Pai']),
            self::create(['id' => 2, 'entre' => 'cidadaos', 'nome' => 'Mãe']),
            self::create(['id' => 3, 'entre' => 'cidadaos', 'nome' => 'Filho']),
            self::create(['id' => 4, 'entre' => 'cidadaos', 'nome' => 'Filha']),
            self::create(['id' => 5, 'entre' => 'cidadaos', 'nome' => 'Irmão']),
            self::create(['id' => 6, 'entre' => 'cidadaos', 'nome' => 'Irmã']),
            self::create(['id' => 7, 'entre' => 'cidadaos', 'nome' => 'Avô']),
            self::create(['id' => 8, 'entre' => 'cidadaos', 'nome' => 'Avó']),
            self::create(['id' => 9, 'entre' => 'cidadaos', 'nome' => 'Neto']),
            self::create(['id' => 10, 'entre' => 'cidadaos', 'nome' => 'Neta']),
            self::create(['id' => 11, 'entre' => 'cidadaos', 'nome' => 'Tio']),
            self::create(['id' => 12, 'entre' => 'cidadaos', 'nome' => 'Tia']),
            self::create(['id' => 13, 'entre' => 'cidadaos', 'nome' => 'Primo']),
            self::create(['id' => 14, 'entre' => 'cidadaos', 'nome' => 'Prima']),
            self::create(['id' => 15, 'entre' => 'cidadaos', 'nome' => 'Sobrinho']),
            self::create(['id' => 16, 'entre' => 'cidadaos', 'nome' => 'Sobrinha']),
            self::create(['id' => 17, 'entre' => 'cidadaos', 'nome' => 'Padrinho Casamento']),
            self::create(['id' => 18, 'entre' => 'cidadaos', 'nome' => 'Madrinha Casamento']),
            self::create(['id' => 19, 'entre' => 'cidadaos', 'nome' => 'Padrinho Batizado']),
            self::create(['id' => 20, 'entre' => 'cidadaos', 'nome' => 'Madrinha Batizado']),
            self::create(['id' => 21, 'entre' => 'cidadaos', 'nome' => 'Padrinho Crisma']),
            self::create(['id' => 22, 'entre' => 'cidadaos', 'nome' => 'Madrinha Crisma']),
            self::create(['id' => 23, 'entre' => 'cidadaos', 'nome' => 'Amigo']),
            self::create(['id' => 24, 'entre' => 'cidadaos', 'nome' => 'Amiga']),
            self::create(['id' => 25, 'entre' => 'cidadaos', 'nome' => 'Colega']),
            self::create(['id' => 26, 'entre' => 'cidadaos', 'nome' => 'Colega de Trabalho']),
            self::create(['id' => 27, 'entre' => 'cidadaos', 'nome' => 'Vizinho']),
            self::create(['id' => 28, 'entre' => 'cidadaos', 'nome' => 'Vizinha']),
            self::create(['id' => 29, 'entre' => 'cidadaos', 'nome' => 'Marido']),
            self::create(['id' => 30, 'entre' => 'cidadaos', 'nome' => 'Esposa']),
            self::create(['id' => 31, 'entre' => 'instituicoes', 'nome' => 'Financiado']),
            self::create(['id' => 32, 'entre' => 'instituicoes', 'nome' => 'Fornecedor']),
            self::create(['id' => 33, 'entre' => 'instituicoes', 'nome' => 'Cliente']),
            self::create(['id' => 34, 'entre' => 'instituicoes', 'nome' => 'Parceiro']),
            self::create(['id' => 35, 'entre' => 'instituicoes', 'nome' => 'Concorrente']),
            self::create(['id' => 36, 'entre' => 'instituicoes', 'nome' => 'Nomeações']),
            self::create(['id' => 37, 'entre' => 'instituicao_cidadao', 'nome' => 'Financiado']),
            self::create(['id' => 38, 'entre' => 'instituicao_cidadao', 'nome' => 'Cliente']),
            self::create(['id' => 39, 'entre' => 'instituicao_cidadao', 'nome' => 'Parceiro']),
            self::create(['id' => 40, 'entre' => 'instituicao_cidadao', 'nome' => 'Concorrente']),
            self::create(['id' => 41, 'entre' => 'instituicao_cidadao', 'nome' => 'Nomeado']),
            self::create(['id' => 42, 'entre' => 'instituicao_cidadao', 'nome' => 'Sócio']),
            self::create(['id' => 43, 'entre' => 'instituicao_cidadao', 'nome' => 'Acionista']),
            self::create(['id' => 44, 'entre' => 'instituicao_cidadao', 'nome' => 'Administração']),
            self::create(['id' => 45, 'entre' => 'instituicao_cidadao', 'nome' => 'Fiscalização']),
            self::create(['id' => 46, 'entre' => 'instituicao_cidadao', 'nome' => 'Membro']),
            self::create(['id' => 47, 'entre' => 'instituicao_cidadao', 'nome' => 'Colaborador']),
            self::create(['id' => 48, 'entre' => 'instituicao_cidadao', 'nome' => 'Voluntário']),
            self::create(['id' => 49, 'entre' => 'instituicao_cidadao', 'nome' => 'Estagiário']),
            self::create(['id' => 50, 'entre' => 'instituicao_cidadao', 'nome' => 'Estudante']),
            self::create(['id' => 51, 'entre' => 'instituicao_cidadao', 'nome' => 'Professor']),
            self::create(['id' => 52, 'entre' => 'instituicao_cidadao', 'nome' => 'Patrocinador']),
        ];
    }
}
