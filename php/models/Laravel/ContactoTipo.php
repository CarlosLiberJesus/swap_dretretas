<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class ContactoTipo
{
    public $id;
    public $nome;
    public $params;
    public $description;

    public function __construct(int $id, string $nome, ?string $description = null, ?string $params = null)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->description = $description;
        $this->params = $params;
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['nome'],
            $data['description'] ?? null,
            $data['params'] ?? null
        );
    }

    public static function all(): array
    {
        return [
            self::create(['id' => 1, 'nome' => 'Website']),
            self::create(['id' => 2, 'nome' => 'Email']),
            self::create(['id' => 3, 'nome' => 'Telefone']),
            self::create(['id' => 4, 'nome' => 'Fax']),
            self::create([
                'id' => 5,
                'nome' => 'X',
                'description' => 'Redes Sociais',
                'params' => json_encode([
                    'background' => ['hex' => '#000000', 'bootstrap' => 'black'],
                    'color' => ['hex' => '#FFFFFF', 'bootstrap' => 'white'],
                    'icon' => 'fa fa-x',
                ]),
            ]),
            self::create([
                'id' => 6,
                'nome' => 'Facebook',
                'description' => 'Facebook',
                'params' => json_encode([
                    'background' => ['hex' => '#3b5998', 'bootstrap' => 'facebook'],
                    'color' => ['hex' => '#FFFFFF', 'bootstrap' => 'white'],
                    'icon' => 'fa fa-facebook',
                ]),
            ]),
        ];
    }

    public static function findById(int $id): ?self
    {
        $contactos = self::all();

        foreach ($contactos as $contacto) {
            if ($contacto->id === $id) {
                return $contacto;
            }
        }

        return null;
    }
}
