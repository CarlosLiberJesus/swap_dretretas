<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Tretas;

class DocumentDReText {
    private $id;
    private $documentId;
    private $textUrl;
    private $text;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->documentId = $data['document_id'];
        $this->textUrl = $data['text_url'];
        $this->text = $data['text'];
    }

    public function getDocumentId(): int {
        return $this->documentId;
    }

    public function getTextUrl(): string {
        return $this->textUrl;
    }

    public function getText(): string {
        return $this->text;
    }

    public function generateContent(): string {
        return sprintf(
            "************************\nText URL: %s\nText: %s\n************************\n",
            $this->textUrl,
            $this->text
        );
    }
}