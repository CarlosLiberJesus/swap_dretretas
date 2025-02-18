<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class AnexoTipo
{
    public $id;
    public $tipo;
    public $description;
    public $params;
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
        $anexoTipo = new self($data['id'] ?? 0);
        $anexoTipo->tipo = $data['tipo'];
        $anexoTipo->description = $data['description'];
        $anexoTipo->params = $data['params'];
        return $anexoTipo;
    }

    public static function all(): array
    {
        return [
            self::create(['id' => 1, 'tipo' => 'profile', 'description' => 'Imagem minima para procurar para preencher front-end']),
            self::create(['id' => 2, 'tipo' => 'image', 'description' => 'Imagens em geral, referências em tabela Própria']),
            self::create(['id' => 3, 'tipo' => 'video', 'description' => 'Vídeo']),
            self::create(['id' => 4, 'tipo' => 'audio', 'description' => 'Áudio']),
            self::create(['id' => 5, 'tipo' => 'document', 'description' => 'Documento']),
            self::create(['id' => 6, 'tipo' => 'other', 'description' => 'Outro']),
        ];
    }

    public static function findById(int $id): ?self
    {
        $anexos = self::all();

        foreach ($anexos as $anexo) {
            if ($anexo->id === $id) {
                return $anexo;
            }
        }

        return null;
    }

    public static function getAnexoTipo(string $tipo, string $description): ?self
    {
        $anexos = self::all();
        foreach ($anexos as $anexo) {
            if ($anexo->tipo === $tipo && stripos($anexo->description, $description) !== false) {
                return $anexo;
            }
        }
        return null;
    }

    public static function getDistinctTipo(): array
    {
        $anexos = self::all();
        $distinctTipo = [];
        foreach ($anexos as $anexo) {
            if (!in_array($anexo->tipo, $distinctTipo)) {
                $distinctTipo[] = $anexo->tipo;
            }
        }
        return $distinctTipo;
    }
}