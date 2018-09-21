<?php

namespace Flyimg\Http\Security;

interface SecurityRuleInterface
{
    public function isAllowedSourceUrl(string $url): bool;
}
