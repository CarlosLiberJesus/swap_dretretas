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

    public function __construct(string $entre, string $nome)
    {
        $this->entre = $entre;
        $this->nome = $nome;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['entre'], $data['nome']);
    }

    public static function all(): array
    {
        return [
            self::create(['entre' => 'cidadaos', 'nome' => 'Pai']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Mãe']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Filho']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Filha']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Irmão']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Irmã']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Avô']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Avó']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Neto']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Neta']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Tio']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Tia']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Primo']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Prima']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Sobrinho']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Sobrinha']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Padrinho Casamento']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Madrinha Casamento']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Padrinho Batizado']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Madrinha Batizado']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Padrinho Crisma']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Madrinha Crisma']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Amigo']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Amiga']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Colega']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Colega de Trabalho']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Vizinho']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Vizinha']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Marido']),
            self::create(['entre' => 'cidadaos', 'nome' => 'Esposa']),
            self::create(['entre' => 'instituicoes', 'nome' => 'Financiado']),
            self::create(['entre' => 'instituicoes', 'nome' => 'Fornecedor']),
            self::create(['entre' => 'instituicoes', 'nome' => 'Cliente']),
            self::create(['entre' => 'instituicoes', 'nome' => 'Parceiro']),
            self::create(['entre' => 'instituicoes', 'nome' => 'Concorrente']),
            self::create(['entre' => 'instituicoes', 'nome' => 'Nomeações']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Financiado']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Cliente']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Parceiro']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Concorrente']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Nomeado']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Sócio']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Acionista']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Administração']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Fiscalização']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Membro']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Colaborador']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Voluntário']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Estagiário']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Estudante']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Professor']),
            self::create(['entre' => 'instituicao_cidadao', 'nome' => 'Patrocinador']),
        ];
    }
}
