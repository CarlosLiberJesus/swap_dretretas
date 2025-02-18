<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class DiarioRepublicaSerie
{
    private $id;
    private $nome;
    private $sinopse;
    private $serieId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $instance = new self($data['id'] ?? 0);
        $instance->nome = $data['nome'];
        $instance->sinopse = $data['sinopse'];
        $instance->serieId = $data['serie_id'] ?? null;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function all(): array
    {
        return [
            self::create(['id' => 1, 'nome' => 'I Série', 'sinopse' => 'Relato fiel e completo do que ocorrer em cada reunião plenária.']),
            self::create(['id' => 2, 'nome' => 'II Série-A', 'sinopse' => 'Textos dos decretos, resoluções e deliberações do Plenário, da Comissão Permanente, da Mesa e da Conferência de Líderes, dos projetos de revisão constitucional.']),
            self::create(['id' => 3, 'nome' => 'II Série-B', 'sinopse' => 'Textos dos votos, interpelações, inquéritos parlamentares e requerimentos de apreciação de decretos-leis.']),
            self::create(['id' => 4, 'nome' => 'II Série-C', 'sinopse' => 'Relatórios da atividade das comissões parlamentares nos termos do Regimento.']),
            self::create(['id' => 5, 'nome' => 'II Série-D', 'sinopse' => 'Intervenções feitas por Deputados, em representação da Assembleia da República, em organizações internacionais, designadamente na União Interparlamentar.']),
            self::create(['id' => 6, 'nome' => 'II Série-E', 'sinopse' => 'Despachos do Presidente da Assembleia e dos Vice-Presidentes.']),
        ];
    }

    public static function findByNome(string $nome): ?self
    {
        $series = self::all();
        foreach ($series as $serie) {
            if (stripos($serie->nome, $nome) !== false) {
                return $serie;
            }
        }
        return null;
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getSinopse(): ?string
    {
        return $this->sinopse;
    }

    public function getSerieId(): ?int
    {
        return $this->serieId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setSinopse(string $sinopse): void
    {
        $this->sinopse = $sinopse;
    }

    public function setSerieId(?int $serieId): void
    {
        $this->serieId = $serieId;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}