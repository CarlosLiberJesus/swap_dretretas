<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class DiarioRepublicaPublicacaoLei
{
    private $id;
    private $drPublicacaoId;
    private $leiId;
    private $src;
    private $paginas;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $drPublicacaoId, int $leiId, string $paginas)
    {
        $this->drPublicacaoId = $drPublicacaoId;
        $this->leiId = $leiId;
        $this->paginas = $paginas;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $publicacaoLei = new self($data['dr_publicacao_id'], $data['lei_id'], $data['paginas']);
        $publicacaoLei->src = $data['src'] ?? null;
        return $publicacaoLei;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM diario_republica_publicacao_leis WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM diario_republica_publicacao_leis");
        $publicacaoLeis = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $publicacaoLeis[] = self::create($data);
        }

        return $publicacaoLeis;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO diario_republica_publicacao_leis (dr_publicacao_id, lei_id, src, paginas, created_at, updated_at) VALUES (%d, %d, %s, '%s', '%s', '%s')",
            $this->drPublicacaoId,
            $this->leiId,
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->paginas,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
