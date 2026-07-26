<?php

namespace Aindot\ExtraRules;

use Aindot\ExtraRules\Rules\Bic;
use Aindot\ExtraRules\Rules\Cnpj;
use Aindot\ExtraRules\Rules\Cpf;
use Aindot\ExtraRules\Rules\CreditCard;
use Aindot\ExtraRules\Rules\Cvv;
use Aindot\ExtraRules\Rules\Doi;
use Aindot\ExtraRules\Rules\E164;
use Aindot\ExtraRules\Rules\Ean13;
use Aindot\ExtraRules\Rules\Ean8;
use Aindot\ExtraRules\Rules\EgyptianNid;
use Aindot\ExtraRules\Rules\EmiratesId;
use Aindot\ExtraRules\Rules\Esn;
use Aindot\ExtraRules\Rules\Gtin13;
use Aindot\ExtraRules\Rules\Gtin14;
use Aindot\ExtraRules\Rules\Gtin8;
use Aindot\ExtraRules\Rules\HexColor;
use Aindot\ExtraRules\Rules\Iban;
use Aindot\ExtraRules\Rules\Iccid;
use Aindot\ExtraRules\Rules\Imei;
use Aindot\ExtraRules\Rules\ImeiSv;
use Aindot\ExtraRules\Rules\Imsi;
use Aindot\ExtraRules\Rules\Iqama;
use Aindot\ExtraRules\Rules\Isan;
use Aindot\ExtraRules\Rules\Isbn10;
use Aindot\ExtraRules\Rules\Isbn13;
use Aindot\ExtraRules\Rules\Ismn;
use Aindot\ExtraRules\Rules\Isni;
use Aindot\ExtraRules\Rules\Isrc;
use Aindot\ExtraRules\Rules\Issn;
use Aindot\ExtraRules\Rules\Istc;
use Aindot\ExtraRules\Rules\Iswc;
use Aindot\ExtraRules\Rules\Itf14;
use Aindot\ExtraRules\Rules\Jwt;
use Aindot\ExtraRules\Rules\KuwaitCivilId;
use Aindot\ExtraRules\Rules\MacAddress;
use Aindot\ExtraRules\Rules\Meid;
use Aindot\ExtraRules\Rules\Nbn;
use Aindot\ExtraRules\Rules\PassportMrz;
use Aindot\ExtraRules\Rules\Slug;
use Aindot\ExtraRules\Rules\Sscc;
use Aindot\ExtraRules\Rules\Ssn;
use Aindot\ExtraRules\Rules\Ulid;
use Aindot\ExtraRules\Rules\UpcA;
use Aindot\ExtraRules\Rules\UpcE;
use Aindot\ExtraRules\Rules\Urn;
use Aindot\ExtraRules\Rules\Uuid;
use Aindot\ExtraRules\Rules\Vin;
use Aindot\ExtraRules\Rules\Email;
use Aindot\ExtraRules\Rules\Url;
use Aindot\ExtraRules\Rules\Ipv4;
use Aindot\ExtraRules\Rules\Ipv6;
use Aindot\ExtraRules\Rules\PhoneNumber;
use InvalidArgumentException;

enum RuleType: string
{
    case Bic = 'bic';
    case Cnpj = 'cnpj';
    case Cpf = 'cpf';
    case CreditCard = 'credit_card';
    case Cvv = 'cvv';
    case Doi = 'doi';
    case E164 = 'e164';
    case Ean13 = 'ean13';
    case Ean8 = 'ean8';
    case EgyptianNid = 'egyptian_nid';
    case EmiratesId = 'emirates_id';
    case Esn = 'esn';
    case Gtin13 = 'gtin13';
    case Gtin14 = 'gtin14';
    case Gtin8 = 'gtin8';
    case HexColor = 'hex_color';
    case Iban = 'iban';
    case Iccid = 'iccid';
    case Imei = 'imei';
    case ImeiSv = 'imeisv';
    case Imsi = 'imsi';
    case Iqama = 'iqama';
    case Isan = 'isan';
    case Isbn10 = 'isbn10';
    case Isbn13 = 'isbn13';
    case Ismn = 'ismn';
    case Isni = 'isni';
    case Isrc = 'isrc';
    case Issn = 'issn';
    case Istc = 'istc';
    case Iswc = 'iswc';
    case Itf14 = 'itf14';
    case Jwt = 'jwt';
    case KuwaitCivilId = 'kuwait_civil_id';
    case MacAddress = 'mac_address';
    case Meid = 'meid';
    case Nbn = 'nbn';
    case PassportMrz = 'passport_mrz';
    case Slug = 'slug';
    case Sscc = 'sscc';
    case Ssn = 'ssn';
    case Ulid = 'ulid';
    case UpcA = 'upc_a';
    case UpcE = 'upc_e';
    case Urn = 'urn';
    case Uuid = 'uuid';
    case Vin = 'vin';
    case Email = 'email';
    case Url = 'url';
    case Ipv4 = 'ipv4';
    case Ipv6 = 'ipv6';
    case PhoneNumber = 'phone_number';

    /**
     * Returns the validation rule class for this type.
     */
    public function ruleClass(): string
    {
        return match ($this) {
            self::Bic => Bic::class,
            self::Cnpj => Cnpj::class,
            self::Cpf => Cpf::class,
            self::CreditCard => CreditCard::class,
            self::Cvv => Cvv::class,
            self::Doi => Doi::class,
            self::E164 => E164::class,
            self::Ean13 => Ean13::class,
            self::Ean8 => Ean8::class,
            self::EgyptianNid => EgyptianNid::class,
            self::EmiratesId => EmiratesId::class,
            self::Esn => Esn::class,
            self::Gtin13 => Gtin13::class,
            self::Gtin14 => Gtin14::class,
            self::Gtin8 => Gtin8::class,
            self::HexColor => HexColor::class,
            self::Iban => Iban::class,
            self::Iccid => Iccid::class,
            self::Imei => Imei::class,
            self::ImeiSv => ImeiSv::class,
            self::Imsi => Imsi::class,
            self::Iqama => Iqama::class,
            self::Isan => Isan::class,
            self::Isbn10 => Isbn10::class,
            self::Isbn13 => Isbn13::class,
            self::Ismn => Ismn::class,
            self::Isni => Isni::class,
            self::Isrc => Isrc::class,
            self::Issn => Issn::class,
            self::Istc => Istc::class,
            self::Iswc => Iswc::class,
            self::Itf14 => Itf14::class,
            self::Jwt => Jwt::class,
            self::KuwaitCivilId => KuwaitCivilId::class,
            self::MacAddress => MacAddress::class,
            self::Meid => Meid::class,
            self::Nbn => Nbn::class,
            self::PassportMrz => PassportMrz::class,
            self::Slug => Slug::class,
            self::Sscc => Sscc::class,
            self::Ssn => Ssn::class,
            self::Ulid => Ulid::class,
            self::UpcA => UpcA::class,
            self::UpcE => UpcE::class,
            self::Urn => Urn::class,
            self::Uuid => Uuid::class,
            self::Vin => Vin::class,
            self::Email => Email::class,
            self::Url => Url::class,
            self::Ipv4 => Ipv4::class,
            self::Ipv6 => Ipv6::class,
            self::PhoneNumber => PhoneNumber::class,
        };
    }

    /**
     * Resolves a string or enum value into a RuleType.
     */
    public static function fromMixed(string|self $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        $normalized = strtolower(trim($value));
        $normalized = str_replace(['-', ' ', '.'], '_', $normalized);

        $aliases = [
            'creditcard' => self::CreditCard,
            'kuwaitid' => self::KuwaitCivilId,
            'kuwait_id' => self::KuwaitCivilId,
            'civil_id' => self::KuwaitCivilId,
            'mac' => self::MacAddress,
            'imei_sv' => self::ImeiSv,
            'upca' => self::UpcA,
            'upce' => self::UpcE,
            'hex' => self::HexColor,
            'hexcolor' => self::HexColor,
            'swift' => self::Bic,
            'bic_swift' => self::Bic,
            'e_164' => self::E164,
            'phone' => self::E164,
            'mrz' => self::PassportMrz,
            'uae_id' => self::EmiratesId,
            'emirates' => self::EmiratesId,
            'egypt_nid' => self::EgyptianNid,
            'egyptian_id' => self::EgyptianNid,
        ];

        if (isset($aliases[$normalized])) {
            return $aliases[$normalized];
        }

        $type = self::tryFrom($normalized);

        if ($type instanceof self) {
            return $type;
        }

        $byName = self::tryFromName($value);
        if ($byName instanceof self) {
            return $byName;
        }

        throw new InvalidArgumentException("Unknown rule type [{$value}].");
    }

    /**
     * Resolves a rule by its enum case name (e.g. CreditCard).
     */
    private static function tryFromName(string $value): ?self
    {
        foreach (self::cases() as $case) {
            if (strcasecmp($case->name, $value) === 0) {
                return $case;
            }
        }

        return null;
    }
}
