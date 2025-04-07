<?php

namespace Tykfyr\Cpr;

use Carbon\Carbon;

class CprValidator
{
    /**
     * Validate a CPR number.
     *
     * @param string $cpr The CPR number to validate.
     * @return bool True if the CPR number is valid, false otherwise.
     */
    public static function isValid(string $cpr): bool
    {
        $cpr = preg_replace('/[^0-9]/', '', $cpr);

        if (!preg_match('/^\d{10}$/', $cpr)) {
            return false;
        }

        $day = (int)substr($cpr, 0, 2);
        $month = (int)substr($cpr, 2, 2);
        $year = (int)substr($cpr, 4, 2);

        $century = self::getCenturyFromSerial((int)substr($cpr, 6, 4), $year);
        if ($century === null) {
            return false;
        }

        $fullYear = $century + $year;

        return Carbon::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $fullYear, $month, $day), 'UTC') !== false;
    }

    /**
     * Get the birthdate from a CPR number.
     *
     * @param string $cpr The CPR number.
     * @return Carbon|null The birthdate as a Carbon instance, or null if invalid.
     */
    public static function getBirthdate(string $cpr): ?Carbon
    {
        if (!self::isValid($cpr)) {
            return null;
        }

        $cpr = preg_replace('/[^0-9]/', '', $cpr);

        $day = (int)substr($cpr, 0, 2);
        $month = (int)substr($cpr, 2, 2);
        $year = (int)substr($cpr, 4, 2);
        $serial = (int)substr($cpr, 6, 4);
        $century = self::getCenturyFromSerial($serial, $year);

        if ($century === null) {
            return null;
        }

        return Carbon::createFromDate($century + $year, $month, $day);
    }

    /**
     * @param  string  $cpr
     * @return string|null
     */
    public static function getGender(string $cpr): ?string
    {
        $cpr = preg_replace('/[^0-9]/', '', $cpr);

        if (!preg_match('/^\d{10}$/', $cpr)) {
            return null;
        }

        $lastDigit = (int)substr($cpr, -1);
        return $lastDigit % 2 === 0 ? 'female' : 'male';
    }

    /**
     * @param  int  $serial
     * @param  int  $year
     * @return int|null
     */
    private static function getCenturyFromSerial(int $serial, int $year): ?int
    {
        if ($serial >= 0 && $serial <= 3999) {
            return 1900;
        }

        if ($serial >= 4000 && $serial <= 4999) {
            return $year >= 37 ? 1900 : 2000;
        }

        if ($serial >= 5000 && $serial <= 8999) {
            return $year >= 00 && $year <= 57 ? 2000 : 1800;
        }

        if ($serial >= 9000 && $serial <= 9999) {
            return $year >= 00 && $year <= 36 ? 2000 : 1900;
        }

        return null;
    }
}