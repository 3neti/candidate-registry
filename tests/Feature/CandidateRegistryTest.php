<?php

use LBHurtado\CandidateRegistry\Domain\Candidate;
use LBHurtado\CandidateRegistry\Domain\CandidateRegistry;

it('creates a deterministic candidate registry', function (): void {
    $registry = new CandidateRegistry('2025NLE', [
        new Candidate('SEN-001', 'Candidate One', 'Candidate One', 'SENATOR', 'PARTY-1'),
    ], 'CLC2025');

    expect($registry->toArray()['package_type'])->toBe('candidate-registry')
        ->and($registry->hash())->toHaveLength(64);
});

it('rejects duplicate candidate codes', function (): void {
    expect(fn (): CandidateRegistry => new CandidateRegistry('2025NLE', [
        new Candidate('DUP', 'One', 'One', 'SENATOR'),
        new Candidate('DUP', 'Two', 'Two', 'SENATOR'),
    ], 'CLC2025'))->toThrow(InvalidArgumentException::class);
});
