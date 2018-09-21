<?php

namespace Flyimg\Http\Security;

class ExcludeSecurityRuleDelegate implements SecurityRuleInterface
{
    /**
     * @var SecurityRuleInterface[]
     */
    private $rules;

    /**
     * @param SecurityRuleInterface[] $rules
     */
    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    /**
     * @return SecurityRuleInterface[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param SecurityRuleInterface $rule
     */
    public function addRule(SecurityRuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * @param SecurityRuleInterface[] $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function isAllowedSourceUrl(string $url): bool
    {
        foreach ($this->rules as $rule) {
            if (!$rule->isAllowedSourceUrl($url)) {
                return false;
            }
        }

        return true;
    }
}
