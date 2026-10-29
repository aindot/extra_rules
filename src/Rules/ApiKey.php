<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates API keys by their known service prefixes.
 * Used for full-value detection of common provider key formats.
 */
class ApiKey implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Regex patterns for known service key formats.
     *
     * @var list<string>
     */
    private const PATTERNS = [
        // AWS access key IDs
        '/\AA(?:KIA|SIA|GPA|IDA|ROA|IPA|NPA|NVA|PKA|BIA|CCA)[A-Z0-9]{16}\z/',
        // Stripe secret and restricted keys
        '/\A[sr]k_(?:live|test)_[A-Za-z0-9]{20,}\z/',
        // GitHub tokens and fine-grained PATs
        '/\A(?:gh[pousr]_[A-Za-z0-9]{36,}|github_pat_[A-Za-z0-9_]{22,})\z/',
        // GitLab personal access tokens
        '/\Aglpat-[A-Za-z0-9_-]{20,}\z/',
        // Slack tokens
        '/\Axox[abposr]-[A-Za-z0-9-]{10,}\z/',
        // Google API keys
        '/\AAIza[A-Za-z0-9_-]{35}\z/',
        // OpenAI keys
        '/\Ask-(?:proj-)?[A-Za-z0-9_-]{20,}\z/',
        // Anthropic keys
        '/\Ask-ant-[A-Za-z0-9_-]{20,}\z/',
        // SendGrid keys
        '/\ASG\.[A-Za-z0-9_-]{22}\.[A-Za-z0-9_-]{43}\z/',
        // Mailgun keys
        '/\Akey-[A-Za-z0-9]{32}\z/',
        // Twilio API keys
        '/\ASK[0-9a-fA-F]{32}\z/',
        // Shopify tokens
        '/\Ashp(?:at|ca|pa|ss)_[a-fA-F0-9]{32}\z/',
        // Square tokens
        '/\A(?:sq0atp|sq0csp)-[A-Za-z0-9_-]{22,}\z/',
        '/\AEAAA[A-Za-z0-9_-]{60,}\z/',
        // npm tokens
        '/\Anpm_[A-Za-z0-9]{36}\z/',
        // PyPI tokens
        '/\Apypi-[A-Za-z0-9_-]{50,}\z/',
        // DigitalOcean tokens
        '/\Adop_v1_[a-f0-9]{64}\z/',
        // Hugging Face tokens
        '/\Ahf_[A-Za-z0-9]{34,}\z/',
        // Netlify tokens
        '/\Anfp_[A-Za-z0-9]{40,}\z/',
        // New Relic keys
        '/\ANRAK-[A-Z0-9]{27}\z/',
        // Airtable PATs
        '/\Apat[A-Za-z0-9]{14}\.[a-f0-9]{64}\z/',
        // Telegram bot tokens
        '/\A[0-9]{8,10}:[A-Za-z0-9_-]{35}\z/',
        // Facebook app tokens
        '/\AEAACEdEose0cBA[A-Za-z0-9]+\z/',
    ];

    /**
     * Runs API key prefix validation against known services.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        foreach (self::PATTERNS as $pattern) {
            if (preg_match($pattern, $value) === 1) {
                return;
            }
        }

        $this->reject($fail, 'extra-rules::validation.api_key');
    }
}
