<?php

namespace LBHurtado\CandidateRegistry\Contracts;

use LBHurtado\CandidateRegistry\Domain\CandidateRegistry;
use LBHurtado\CandidateRegistry\Domain\SourceManifest;

interface CandidateSourceImporter
{
    public function import(SourceManifest $manifest): CandidateRegistry;
}
