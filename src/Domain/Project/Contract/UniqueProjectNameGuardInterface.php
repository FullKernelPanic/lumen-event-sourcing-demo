<?php

declare(strict_types=1);

namespace Domain\Project\Contract;

interface UniqueProjectNameGuardInterface
{
    public function isUnique(string $name): bool;
}
