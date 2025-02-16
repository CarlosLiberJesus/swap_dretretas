<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Tretas;

class DocumentDRe {
    private $id;
    private $docType;
    private $number;
    private $emitingBody;
    private $source;
    private $inForce;
    private $conditional;
    private $date;
    private $notes;
    private $drePdf;
    private $timestamp;
    private $drNumber;
    private $series;
    private $part;
    private ?DocumentDReText $documentText = null;
    private ?DocumentDReConnectsTo $documentConnectsTo = null;

    public function __construct(array $data, ?DocumentDReText $documentText = null, ?DocumentDReConnectsTo $documentConnectsTo = null) {
        $this->id = $data['id'];
        $this->docType = $data['doc_type'];
        $this->number = $data['number'];
        $this->emitingBody = $data['emiting_body'];
        $this->source = $data['source'];
        $this->inForce = $data['in_force'];
        $this->conditional = $data['conditional'];
        $this->date = $data['date'];
        $this->notes = $data['notes'];
        $this->drePdf = $data['dre_pdf'];
        $this->timestamp = $data['timestamp'];
        $this->drNumber = $data['dr_number'];
        $this->series = $data['series'];
        $this->part = $data['part'];
        $this->documentText = $documentText;
        $this->documentConnectsTo = $documentConnectsTo;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getDocType(): ?string {
        return $this->docType;
    }

    public function getNumber(): ?string {
        return $this->number;
    }

    public function getEmitingBody(): ?string {
        return $this->emitingBody;
    }

    public function getSource(): ?string {
        return $this->source;
    }

    public function getInForce(): ?bool {
        return $this->inForce;
    }

    public function getConditional(): ?bool {
        return $this->conditional;
    }

    public function getDate(): ?string {
        return $this->date;
    }

    public function getNotes(): ?string {
        return $this->notes;
    }

    public function getDrePdf(): ?string {
        return $this->drePdf;
    }

    public function getTimestamp(): string {
        return $this->timestamp;
    }

    public function getDrNumber(): ?string {
        return $this->drNumber;
    }

    public function getSeries(): int {
        return $this->series;
    }

    public function getPart(): ?string {
        return $this->part;
    }

    public function generateContent(): string {
        $content = sprintf(
            "####################################\nDocument ID: %d\nDocument Type: %s  Number: %s\nEmitting Body: %s\nSource: %s\nDate: %s\n--------------------------------\nNotes: %s\n--------------------------------\nDRE PDF: %s\nDR Number: %s\nSeries: %d\nPart: %s\n--------------------------------\nConditional: %s\nIn Force: %s\nTimestamp: %s\n",
            $this->id,
            $this->docType,
            $this->number,
            $this->emitingBody,
            $this->source,
            $this->date,
            $this->notes,
            $this->drePdf,
            $this->drNumber,
            $this->series,
            $this->part,
            $this->conditional ? 'Yes' : 'No',
            $this->inForce ? 'Yes' : 'No',
            $this->timestamp
        );

        if ($this->documentText) {
            $content .= $this->documentText->generateContent();
        }
        if ($this->documentConnectsTo) {
            $content .= $this->documentConnectsTo->generateContent();
        }

        $content .= "####################################\n";

        return $content;
    }
}