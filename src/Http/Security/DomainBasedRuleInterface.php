<?php

namespace Flyimg\Http\Security;

interface DomainBasedRuleInterface extends SecurityRuleInterface
{
    public function hasDomains(): bool;

    public function countDomains(): int;

    public function walkDomains(): iterable;
}
