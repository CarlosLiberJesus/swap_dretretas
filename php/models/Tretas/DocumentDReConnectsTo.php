<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Tretas;

class DocumentDReConnectsTo {
    private $id;
    private $fromDocumentId;
    private $toDocumentId;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->fromDocumentId = $data['from_document_id'];
        $this->toDocumentId = $data['to_document_id'];
    }

    public function getId(): int {
        return $this->id;
    }

    public function getFromDocumentId(): int {
        return $this->fromDocumentId;
    }

    public function getToDocumentId(): int {
        return $this->toDocumentId;
    }

    public function generateContent(): string {
        return sprintf(
            "************************\nFrom Document ID: %d\nTo Document ID: %d\n************************\n",
            $this->fromDocumentId,
            $this->toDocumentId
        );
    }
}