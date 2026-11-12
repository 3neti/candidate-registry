<?php

namespace LBHurtado\CandidateRegistry\Domain;

final readonly class Candidate
{
    public function __construct(
        public string $candidateCode,
        public string $ballotName,
        public string $legalName,
        public string $officeCode,
        public ?string $partyCode = null,
        public ?string $districtCode = null,
        public ?string $localityCode = null,
        public ?string $sourceReference = null,
    ) {
        foreach (['candidateCode' => $candidateCode, 'ballotName' => $ballotName, 'legalName' => $legalName, 'officeCode' => $officeCode] as $name => $value) {
            if (trim($value) === '') {
                throw new \InvalidArgumentException("{$name} must not be empty.");
            }
        }
    }

    /** @return array<string, ?string> */
    public function toArray(): array
    {
        return [
            'candidate_code' => $this->candidateCode,
            'ballot_name' => $this->ballotName,
            'legal_name' => $this->legalName,
            'office_code' => $this->officeCode,
            'party_code' => $this->partyCode,
            'district_code' => $this->districtCode,
            'locality_code' => $this->localityCode,
            'source_reference' => $this->sourceReference,
        ];
    }
}
