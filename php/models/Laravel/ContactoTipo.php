<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class ContactoTipo
{
    public $id;
    public $nome;
    public $params;
    public $description;
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
        $contactoTipo = new self($data['id'] ?? 0);
        $contactoTipo->nome = $data['nome'];
        $contactoTipo->description = $data['description'] ?? null;
        $contactoTipo->params = $data['params'] ?? null;
        return $contactoTipo;
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

    public static function getContactoTipo(string $nome, string $description): ?self
    {
        $contactos = self::all();
        foreach ($contactos as $contacto) {
            if ($contacto->nome === $nome && stripos($contacto->description, $description) !== false) {
                return $contacto;
            }
        }
        return null;
    }

    public static function getDistinctNome(): array
    {
        $contactos = self::all();
        $distinctNome = [];
        foreach ($contactos as $contacto) {
            if (!in_array($contacto->nome, $distinctNome)) {
                $distinctNome[] = $contacto->nome;
            }
        }
        return $distinctNome;
    }
}