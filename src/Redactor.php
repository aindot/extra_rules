<?php

namespace Aindot\ExtraRules;

use Aindot\ExtraRules\Concerns\ValidatesRuleValue;
use InvalidArgumentException;

class Redactor
{
    use ValidatesRuleValue;

    /**
     * Rule types that can be detected and redacted inside arbitrary text.
     *
     * The order also defines overlap priority: earlier types win ties.
     *
     * @var list<RuleType>
     */
    private const TYPES = [
        RuleType::PrivateKey,
        RuleType::BearerToken,
        RuleType::Jwt,
        RuleType::ApiKey,
        RuleType::Iban,
        RuleType::CreditCard,
        RuleType::KuwaitCivilId,
    ];

    /**
     * Candidate patterns keyed by rule type value.
     *
     * @var array<string, list<string>>
     */
    private const PATTERNS = [
        'private_key' => [
            '/-----BEGIN ((?:[A-Z0-9]+ )?PRIVATE KEY(?: BLOCK)?)-----[\r\n]+.*?-----END \1-----/s',
        ],
        'bearer_token' => [
            '/\bBearer\s+[A-Za-z0-9\-._~+\/]+=*/i',
        ],
        'jwt' => [
            '/\beyJ[A-Za-z0-9_-]*\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]*/',
        ],
        'api_key' => [
            '/\b[A-Za-z0-9][A-Za-z0-9_.:\/+\-]{18,}[A-Za-z0-9]\b/',
        ],
        'iban' => [
            '/\b[A-Z]{2}\d{2}(?:[ ]?[A-Z0-9]{4}){2,7}(?:[ ]?[A-Z0-9]{1,4})?\b/i',
            '/\b[A-Z]{2}\d{2}[A-Z0-9]{11,30}\b/i',
        ],
        'credit_card' => [
            '/\b(?:\d[ -]?){12,18}\d\b/',
        ],
        'kuwait_civil_id' => [
            '/\b\d{12}\b/',
        ],
    ];

    /**
     * Replaces detected secrets with type-labelled markers.
     *
     * @param  array<int, string|RuleType>  $types
     */
    public function redact(string $text, array $types = []): string
    {
        foreach (array_reverse($this->findMatches($text, $types)) as $match) {
            $marker = '[REDACTED:'.$match['type']->value.']';

            $text = substr($text, 0, $match['offset'])
                .$marker
                .substr($text, $match['offset'] + $match['length']);
        }

        return $text;
    }

    /**
     * Returns detected secret metadata without exposing the secret values.
     *
     * @param  array<int, string|RuleType>  $types
     * @return list<array{type: RuleType, offset: int, length: int}>
     */
    public function detect(string $text, array $types = []): array
    {
        return $this->findMatches($text, $types);
    }

    /**
     * @param  array<int, string|RuleType>  $types
     * @return list<array{type: RuleType, offset: int, length: int}>
     */
    private function findMatches(string $text, array $types): array
    {
        $allowed = $this->resolveTypes($types);
        $matches = [];

        foreach (self::TYPES as $type) {
            if (! in_array($type, $allowed, true)) {
                continue;
            }

            foreach (self::PATTERNS[$type->value] as $pattern) {
                $found = preg_match_all($pattern, $text, $hits, PREG_OFFSET_CAPTURE);

                if ($found === false) {
                    continue;
                }

                foreach ($hits[0] as [$value, $offset]) {
                    if (! $this->passesRule($type, $value)) {
                        continue;
                    }

                    $matches[] = [
                        'type' => $type,
                        'offset' => $offset,
                        'length' => strlen($value),
                    ];
                }
            }
        }

        return $this->resolveOverlaps($matches);
    }

    /**
     * Keeps the longest match and drops any overlapping lower-priority match.
     *
     * @param  list<array{type: RuleType, offset: int, length: int}>  $matches
     * @return list<array{type: RuleType, offset: int, length: int}>
     */
    private function resolveOverlaps(array $matches): array
    {
        usort($matches, function (array $a, array $b): int {
            return $b['length'] <=> $a['length']
                ?: $this->priority($a['type']) <=> $this->priority($b['type'])
                ?: $a['offset'] <=> $b['offset'];
        });

        $accepted = [];

        foreach ($matches as $match) {
            foreach ($accepted as $kept) {
                $overlaps = $match['offset'] < $kept['offset'] + $kept['length']
                    && $kept['offset'] < $match['offset'] + $match['length'];

                if ($overlaps) {
                    continue 2;
                }
            }

            $accepted[] = $match;
        }

        usort($accepted, static fn (array $a, array $b): int => $a['offset'] <=> $b['offset']);

        return $accepted;
    }

    /**
     * Resolves and validates the requested rule types, preserving fixed priority order.
     *
     * @param  array<int, string|RuleType>  $types
     * @return list<RuleType>
     */
    private function resolveTypes(array $types): array
    {
        if ($types === []) {
            return self::TYPES;
        }

        $resolved = [];

        foreach ($types as $type) {
            if (! is_string($type) && ! $type instanceof RuleType) {
                throw new InvalidArgumentException('Redaction types must be strings or RuleType enums.');
            }

            $resolvedType = RuleType::fromMixed($type);

            if (! in_array($resolvedType, self::TYPES, true)) {
                throw new InvalidArgumentException("Rule type [{$resolvedType->value}] cannot be redacted.");
            }

            $resolved[] = $resolvedType;
        }

        return array_values(array_filter(
            self::TYPES,
            static fn (RuleType $type): bool => in_array($type, $resolved, true)
        ));
    }

    private function priority(RuleType $type): int
    {
        $priority = array_search($type, self::TYPES, true);

        return $priority === false ? PHP_INT_MAX : $priority;
    }
}
