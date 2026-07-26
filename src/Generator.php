<?php

namespace Aindot\ExtraRules;

use InvalidArgumentException;
use RuntimeException;

class Generator
{
    /**
     * Generates one valid sample value for the given rule type.
     */
    public function make(string|RuleType $rule): string
    {
        $type = RuleType::fromMixed($rule);

        $value = match ($type) {
            RuleType::Bic => $this->bic(),
            RuleType::Cnpj => $this->cnpj(),
            RuleType::Cpf => $this->cpf(),
            RuleType::CreditCard => $this->creditCard(),
            RuleType::Cvv => $this->digits(3),
            RuleType::Doi => '10.'.$this->digits(4).'/'.$this->slugPart(8),
            RuleType::E164 => '+1'.$this->digits(10),
            RuleType::Ean13, RuleType::Gtin13, RuleType::Isbn13 => $this->ean13('978'),
            RuleType::Ean8, RuleType::Gtin8 => $this->ean8(),
            RuleType::EgyptianNid => $this->egyptianNid(),
            RuleType::Email => $this->email(),
            RuleType::EmiratesId => $this->emiratesId(),
            RuleType::Esn => strtoupper(bin2hex(random_bytes(4))),
            RuleType::Gtin14, RuleType::Itf14 => $this->gtin(14),
            RuleType::HexColor => '#'.substr(bin2hex(random_bytes(3)), 0, 6),
            RuleType::Iban => $this->iban(),
            RuleType::Iccid => $this->iccid(),
            RuleType::Imei => $this->imei(),
            RuleType::ImeiSv => $this->digits(16),
            RuleType::Imsi => '310'.$this->digits(12),
            RuleType::Iqama => $this->iqama(),
            RuleType::Isan => $this->isan(),
            RuleType::Isbn10 => $this->isbn10(),
            RuleType::Ismn => $this->ean13('9790'),
            RuleType::Isni => $this->isni(),
            RuleType::Isrc => 'US'.strtoupper($this->alnum(3)).$this->digits(7),
            RuleType::Issn => $this->issn(),
            RuleType::Istc => $this->istc(),
            RuleType::Iswc => $this->iswc(),
            RuleType::Jwt => $this->jwt(),
            RuleType::KuwaitCivilId => $this->kuwaitCivilId(),
            RuleType::MacAddress => $this->mac(),
            RuleType::Meid => $this->meid(),
            RuleType::Nbn => 'urn:nbn:de:bvb:19-'.$this->digits(6),
            RuleType::PassportMrz => $this->passportMrz(),
            RuleType::Slug => $this->slugPart(5).'-'.$this->slugPart(5),
            RuleType::Sscc => $this->gtin(18),
            RuleType::Ssn => $this->ssn(),
            RuleType::Ulid => $this->ulid(),
            RuleType::UpcA => $this->upcA(),
            RuleType::UpcE => $this->upcE(),
            RuleType::Urn => 'urn:example:'.$this->slugPart(8),
            RuleType::Uuid => $this->uuid(),
            RuleType::Vin => $this->vin(),
        };

        if (! $this->passes($type, $value)) {
            throw new RuntimeException("Failed to generate a valid value for [{$type->value}].");
        }

        return $value;
    }

    /**
     * Generates multiple valid sample values for the given rule type.
     *
     * @return list<string>
     */
    public function makeMany(string|RuleType $rule, int $count = 1): array
    {
        if ($count < 1) {
            throw new InvalidArgumentException('Count must be at least 1.');
        }

        $values = [];

        for ($i = 0; $i < $count; $i++) {
            $values[] = $this->make($rule);
        }

        return $values;
    }

    private function passes(RuleType $type, string $value): bool
    {
        $rule = new ($type->ruleClass());
        $failed = false;

        $rule->validate('value', $value, function () use (&$failed): void {
            $failed = true;
        });

        return ! $failed;
    }

    private function digits(int $length): string
    {
        $out = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= (string) random_int(0, 9);
        }

        return $out;
    }

    private function slugPart(int $length): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $out = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $out;
    }

    private function alnum(int $length): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $out = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $out;
    }

    private function luhnAppend(string $body): string
    {
        for ($d = 0; $d <= 9; $d++) {
            $candidate = $body.(string) $d;
            if ($this->luhnValid($candidate)) {
                return $candidate;
            }
        }

        throw new RuntimeException('Unable to compute Luhn check digit.');
    }

    private function luhnValid(string $value): bool
    {
        $str = '';
        foreach (str_split(strrev($value)) as $i => $d) {
            $str .= $i % 2 !== 0 ? $d * 2 : $d;
        }

        return array_sum(str_split($str)) % 10 === 0;
    }

    private function gs1AppendLeft(string $body): string
    {
        $sum = 0;
        $len = strlen($body);
        $startWithThree = in_array($len, [7, 11, 13, 17], true);

        for ($i = 0; $i < $len; $i++) {
            $weight = $startWithThree
                ? ($i % 2 === 0 ? 3 : 1)
                : ($i % 2 === 0 ? 1 : 3);
            $sum += (int) $body[$i] * $weight;
        }

        return $body.(string) ((10 - ($sum % 10)) % 10);
    }

    private function ean13(string $prefix): string
    {
        $body = $prefix.$this->digits(12 - strlen($prefix));

        return $this->gs1AppendLeft($body);
    }

    private function ean8(): string
    {
        return $this->gs1AppendLeft($this->digits(7));
    }

    private function gtin(int $length): string
    {
        return $this->gs1AppendLeft($this->digits($length - 1));
    }

    private function upcA(): string
    {
        return $this->gs1AppendLeft($this->digits(11));
    }

    private function upcE(): string
    {
        // Generate valid 8-digit UPC-E via expansion check
        for ($attempt = 0; $attempt < 100; $attempt++) {
            $ns = '0';
            $body = $this->digits(6);
            $manufacturer = $this->expandUpcE($body);
            $check = substr($this->gs1AppendLeft($ns.$manufacturer), -1);
            $value = $ns.$body.$check;
            if ($this->passes(RuleType::UpcE, $value)) {
                return $value;
            }
        }

        return '04252614';
    }

    private function expandUpcE(string $body): string
    {
        $d = str_split($body);
        $last = $d[5];

        if ($last >= '0' && $last <= '2') {
            return $d[0].$d[1].$last.'0000'.$d[2].$d[3].$d[4];
        }
        if ($last === '3') {
            return $d[0].$d[1].$d[2].'00000'.$d[3].$d[4];
        }
        if ($last === '4') {
            return $d[0].$d[1].$d[2].$d[3].'00000'.$d[4];
        }

        return $d[0].$d[1].$d[2].$d[3].$d[4].'0000'.$last;
    }

    private function imei(): string
    {
        return $this->luhnAppend($this->digits(14));
    }

    private function iccid(): string
    {
        return $this->luhnAppend('89'.$this->digits(16));
    }

    private function creditCard(): string
    {
        return $this->luhnAppend('4'.$this->digits(14));
    }

    private function meid(): string
    {
        $body = strtoupper(bin2hex(random_bytes(7)));
        for ($c = 0; $c < 16; $c++) {
            $h = strtoupper(dechex($c));
            $candidate = $body.$h;
            if ($this->passes(RuleType::Meid, $candidate)) {
                return $candidate;
            }
        }

        return 'A10000009296FD';
    }

    private function bic(): string
    {
        return 'DEUTDEFF';
    }

    private function iban(): string
    {
        // GB IBAN: GB + check + WEST + 14 digits-ish bank account
        $bban = 'WEST'.$this->digits(14);
        for ($i = 0; $i < 100; $i++) {
            $check = str_pad((string) random_int(0, 99), 2, '0', STR_PAD_LEFT);
            $iban = 'GB'.$check.$bban;
            if ($this->passes(RuleType::Iban, $iban)) {
                return $iban;
            }
        }

        return 'GB82WEST12345698765432';
    }

    private function cpf(): string
    {
        $n = [];
        for ($i = 0; $i < 9; $i++) {
            $n[] = random_int(0, 9);
        }
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $n[$i] * (10 - $i);
        }
        $d1 = ($sum * 10) % 11;
        $d1 = $d1 === 10 ? 0 : $d1;
        $n[] = $d1;
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $n[$i] * (11 - $i);
        }
        $d2 = ($sum * 10) % 11;
        $d2 = $d2 === 10 ? 0 : $d2;
        $n[] = $d2;

        return implode('', $n);
    }

    private function cnpj(): string
    {
        $n = [];
        for ($i = 0; $i < 12; $i++) {
            $n[] = random_int(0, 9);
        }
        $w1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $w2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $n[$i] * $w1[$i];
        }
        $d1 = $sum % 11;
        $d1 = $d1 < 2 ? 0 : 11 - $d1;
        $n[] = $d1;
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += $n[$i] * $w2[$i];
        }
        $d2 = $sum % 11;
        $d2 = $d2 < 2 ? 0 : 11 - $d2;
        $n[] = $d2;

        return implode('', $n);
    }

    private function iqama(): string
    {
        $prefix = (string) random_int(1, 2);
        for ($attempt = 0; $attempt < 50; $attempt++) {
            $body = $prefix.$this->digits(8);
            for ($d = 0; $d <= 9; $d++) {
                $candidate = $body.(string) $d;
                if ($this->passes(RuleType::Iqama, $candidate)) {
                    return $candidate;
                }
            }
        }

        return '1023456781';
    }

    private function emiratesId(): string
    {
        return $this->luhnAppend('784'.(string) random_int(1980, 2010).$this->digits(7));
    }

    private function egyptianNid(): string
    {
        $century = '3';
        $year = str_pad((string) random_int(0, 24), 2, '0', STR_PAD_LEFT);
        $month = str_pad((string) random_int(1, 12), 2, '0', STR_PAD_LEFT);
        $day = str_pad((string) random_int(1, 28), 2, '0', STR_PAD_LEFT);
        $gov = '01';

        return $century.$year.$month.$day.$gov.$this->digits(5);
    }

    private function kuwaitCivilId(): string
    {
        for ($attempt = 0; $attempt < 100; $attempt++) {
            $body = (string) random_int(2, 3).$this->digits(10);
            for ($d = 0; $d <= 9; $d++) {
                $candidate = $body.(string) $d;
                if (strlen($candidate) === 12 && $this->passes(RuleType::KuwaitCivilId, $candidate)) {
                    return $candidate;
                }
            }
        }

        return '282021514199';
    }

    private function isbn10(): string
    {
        $body = $this->digits(9);
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (10 - $i) * (int) $body[$i];
        }
        $remainder = $sum % 11;
        $check = (11 - $remainder) % 11;
        $checkChar = $check === 10 ? 'X' : (string) $check;

        // Validator uses different check - verify via passes
        $candidate = $body.$checkChar;
        if ($this->passes(RuleType::Isbn10, $candidate)) {
            return $candidate;
        }

        for ($c = 0; $c <= 10; $c++) {
            $ch = $c === 10 ? 'X' : (string) $c;
            if ($this->passes(RuleType::Isbn10, $body.$ch)) {
                return $body.$ch;
            }
        }

        return '0306406152';
    }

    private function issn(): string
    {
        $body = $this->digits(7);
        $sum = 0;
        for ($i = 0; $i < 7; $i++) {
            $sum += (8 - $i) * (int) $body[$i];
        }
        $check = (11 - ($sum % 11)) % 11;
        $checkChar = $check === 10 ? 'X' : (string) $check;

        return $body.$checkChar;
    }

    private function isni(): string
    {
        $body = $this->digits(15);
        for ($c = 0; $c <= 10; $c++) {
            $ch = $c === 10 ? 'X' : (string) $c;
            if ($this->passes(RuleType::Isni, $body.$ch)) {
                return $body.$ch;
            }
        }

        return '000000012281955X';
    }

    private function iswc(): string
    {
        $digits = $this->digits(9);
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ($i + 1) * (int) $digits[$i];
        }

        return 'T'.$digits.(string) ($sum % 10);
    }

    private function istc(): string
    {
        $body = strtoupper(bin2hex(random_bytes(7))).'0';
        $body = substr($body, 0, 15);
        for ($c = 0; $c < 16; $c++) {
            $h = strtoupper(dechex($c));
            if ($this->passes(RuleType::Istc, $body.$h)) {
                return $body.$h;
            }
        }

        return '0A9200212B4A1053';
    }

    private function isan(): string
    {
        return '00000000D07A0090Q';
    }

    private function mac(): string
    {
        $parts = [];
        for ($i = 0; $i < 6; $i++) {
            $parts[] = strtoupper(str_pad(dechex(random_int(0, 255)), 2, '0', STR_PAD_LEFT));
        }

        return implode(':', $parts);
    }

    private function ssn(): string
    {
        $area = str_pad((string) random_int(1, 665), 3, '0', STR_PAD_LEFT);
        if ($area === '666') {
            $area = '665';
        }
        $group = str_pad((string) random_int(1, 99), 2, '0', STR_PAD_LEFT);
        $serial = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);

        return $area.'-'.$group.'-'.$serial;
    }

    private function uuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        $hex = bin2hex($data);

        return substr($hex, 0, 8).'-'.substr($hex, 8, 4).'-'.substr($hex, 12, 4).'-'.substr($hex, 16, 4).'-'.substr($hex, 20, 12);
    }

    private function ulid(): string
    {
        $chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
        $out = '';
        for ($i = 0; $i < 26; $i++) {
            $out .= $chars[random_int(0, 31)];
        }

        return $out;
    }

    private function jwt(): string
    {
        $header = rtrim(strtr(base64_encode('{"alg":"none"}'), '+/', '-_'), '=');
        $payload = rtrim(strtr(base64_encode('{"sub":"'.$this->digits(6).'"}'), '+/', '-_'), '=');

        return $header.'.'.$payload.'.';
    }

    private function vin(): string
    {
        $chars = 'ABCDEFGHJKLMNPRSTUVWXYZ0123456789';
        for ($attempt = 0; $attempt < 50; $attempt++) {
            $left = '';
            $right = '';
            for ($i = 0; $i < 8; $i++) {
                $left .= $chars[random_int(0, strlen($chars) - 1)];
                $right .= $chars[random_int(0, strlen($chars) - 1)];
            }
            for ($d = 0; $d <= 10; $d++) {
                $check = $d === 10 ? 'X' : (string) $d;
                $vin = $left.$check.$right;
                if ($this->passes(RuleType::Vin, $vin)) {
                    return $vin;
                }
            }
        }

        return '1HGCM82633A004352';
    }

    private function passportMrz(): string
    {
        return 'L898902C36UTO7408122F1204159ZE184226B<<<<<10';
    }

    private function email(): string
    {
        $localParts = [
            $this->slugPart(5),
            $this->slugPart(3).'.'.$this->slugPart(3),
            $this->slugPart(4).'_'.$this->slugPart(2),
            $this->alnum(4).$this->digits(2),
            'test.'.$this->slugPart(4),
        ];

        $domains = [
            'gmail.com',
            'outlook.com',
            'yahoo.com',
            'hotmail.com',
            'protonmail.com',
            'icloud.com',
            'aol.com',
            'zoho.com',
            'mail.com',
            'gmx.com',
        ];

        $randomDomain = $domains[random_int(0, count($domains) - 1)];
        $randomLocal = $localParts[random_int(0, count($localParts) - 1)];

        return $randomLocal.'@'.$randomDomain;
    }
}
