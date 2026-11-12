<?php

namespace LBHurtado\CandidateRegistry\Domain;

use LBHurtado\CandidateRegistry\Support\CanonicalJson;

final readonly class CandidateRegistry
{
    /** @param list<Candidate> $candidates */
    public function __construct(
        public string $electionId,
        public array $candidates,
        public string $sourceReference,
        public string $schemaVersion = 'candidate-registry.v1',
    ) {
        if (trim($this->electionId) === '' || trim($this->sourceReference) === '') {
            throw new \InvalidArgumentException('Election and source references must not be empty.');
        }
        $codes = array_map(static fn (Candidate $candidate): string => $candidate->candidateCode, $candidates);
        if (count($codes) !== count(array_unique($codes))) {
            throw new \InvalidArgumentException('Candidate codes must be unique within a registry.');
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'package_type' => 'candidate-registry',
            'schema_version' => $this->schemaVersion,
            'election_id' => $this->electionId,
            'source_reference' => $this->sourceReference,
            'candidates' => array_map(static fn (Candidate $candidate): array => $candidate->toArray(), $this->candidates),
        ];
    }

    public function hash(): string
    {
        return CanonicalJson::sha256($this->toArray());
    }
}
