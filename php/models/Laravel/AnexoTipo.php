<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class AnexoTipo
{
    public $id;
    public $tipo;
    public $description;
    public $params;

    public function __construct(int $id, string $tipo, string $description, ?string $params = null)
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->description = $description;
        $this->params = $params;
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['tipo'],
            $data['description'],
            $data['params'] ?? null
        );
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
}
