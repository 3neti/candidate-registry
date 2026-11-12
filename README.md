# `3neti/candidate-registry`

Framework-neutral candidate and office registry primitives for the WAES EMS.
The package represents a normalized, immutable snapshot of candidate data that
can be consumed by ballot-style resolution and OBT compilation.

## Responsibility

This package owns candidate codes and names, legal and ballot names, office,
party, district, and locality references, source provenance, schema identity,
and deterministic registry serialization and hashing.

It does not own ballot layout, precinct topology, contest limits, election-law
interpretation, eligibility adjudication, device assignment, custody, or EMS
user-interface workflows. It records source data; it does not silently repair
or infer disputed candidate information.

## Domain Model

`Candidate` is an immutable normalized record. Its required identity fields are
`candidate_code`, `ballot_name`, `legal_name`, and `office_code`.
`CandidateRegistry` is an election-scoped collection and candidate codes must
be unique within it:

```json
{
  "package_type": "candidate-registry",
  "schema_version": "candidate-registry.v1",
  "election_id": "2025NLE",
  "source_reference": "CLC2025",
  "candidates": []
}
```

`SourceManifest` records the source reference, retrieval time, parser version,
status, file paths, and file hashes. It is evidence of what was consumed, not
an approval of the candidate data.

## Determinism and Hashing

All public value objects expose `toArray()`. Registry hashes are SHA-256 hashes
of the canonical JSON representation produced by the package. The EMS should
persist the source manifest and registry hash beside every compiled output.
Statuses such as `tentative`, `approved`, `superseded`, and `withdrawn`
are explicit data; their transitions belong to the EMS approval workflow.

## Import Boundary

`CandidateSourceImporter` is the adapter boundary for CLC imports. A future
importer may read a COMELEC PDF, spreadsheet, or other published source, but it
must produce a `SourceManifest` and normalized `CandidateRegistry`. Parsing
logic and source-specific assumptions do not belong in the domain objects.

```php
use LBHurtado\CandidateRegistry\Domain\Candidate;
use LBHurtado\CandidateRegistry\Domain\CandidateRegistry;

$registry = new CandidateRegistry('2025NLE', [
    new Candidate('SEN-001', 'Candidate One', 'Candidate One', 'SENATOR', 'PARTY-1'),
], 'CLC2025');

$registryHash = $registry->hash();
```

## EMS Relationship

```text
CLC source snapshot -> candidate-registry -> ballot-style resolver -> OBT compiler
```

The EMS consumes this package together with `3neti/project-of-precincts` and
`3neti/election-rules`. `3neti/ballot-pdf` consumes the resolved
candidate-to-code mapping later; it does not maintain the CLC registry.

## Current Status

This is a domain scaffold with immutable records, canonical hashing,
validation, an importer port, and focused tests. A production CLC parser,
approval ceremony, and source-format conformance fixtures remain EMS work.

## Development

```bash
composer install
vendor/bin/pest
```

The package is PHP 8.4+, framework-neutral, and uses no external runtime
dependencies.
