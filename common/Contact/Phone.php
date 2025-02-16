<?php

declare(strict_types=1);

namespace app\common\Contact;

use InvalidArgumentException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * VO для работы с телефонным номером.
 */
final class Phone
{
    /**
     * @var PhoneNumber
     */
    private PhoneNumber $phoneNumber;

    /**
     * @param string $phone
     * @param string $region
     * @throws NumberParseException
     */
    private function __construct(string $phone, string $region)
    {
        /** @var PhoneNumber $phoneNumber */
        $phoneNumber = PhoneNumberUtil::getInstance()->parse(trim($phone), $region, null, true);

        if (!PhoneNumberUtil::getInstance()->isValidNumber($phoneNumber)) {
            throw new InvalidArgumentException("Номер телефона $phone невалидный");
        }

        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @param string $phone
     * @param string $region
     * @return static
     * @throws NumberParseException
     */
    public static function create(string $phone, string $region = 'RU'): Phone
    {
        return new Phone($phone, $region);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this->phoneNumber->getRawInput();
    }

    /**
     * @param bool $usePlusSign
     * @return string
     * @example +79991110022, 79991110022
     */
    public function getE164Format(bool $usePlusSign = true): string
    {
        $phoneNumber = PhoneNumberUtil::getInstance()->format($this->phoneNumber, PhoneNumberFormat::E164);

        return $usePlusSign ? $phoneNumber : str_replace('+', '', $phoneNumber);
    }

    /**
     * @return string
     * @example 9991110022
     */
    public function getNationalFormat(): string
    {
        return PhoneNumberUtil::getInstance()->getNationalSignificantNumber($this->phoneNumber);
    }
}
