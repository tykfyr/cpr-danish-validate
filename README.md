# tykfyr/cpr-danish-validate

Et simpelt PHP-bibliotek til validering og analyse af danske CPR-numre. Understøtter:

- Validering af format og fødselsdato
- Udtræk af fødselsdato som Carbon-instans
- Bestemmelse af køn (mand/kvinde)

> Kræver PHP 8.1+ og [nesbot/carbon](https://github.com/briannesbitt/Carbon)

---

## 🔧 Installation

Installer via Composer:

```bash
composer require tykfyr/cpr-danish-validate
````

## ⚙️ Usage

```php
use Tykfyr\Cpr\CprValidator;

// Valider CPR-nummer
CprValidator::isValid('0101011234'); // true eller false

// Hent fødselsdato som Carbon-instans
$date = CprValidator::getBirthdate('0101011234');
echo $date?->format('Y-m-d'); // 1901-01-01

// Find ud af køn (baseret på sidste ciffer)
echo CprValidator::getGender('0101011235'); // 'male'
echo CprValidator::getGender('0101011234'); // 'female'
```

## 🧠 Hvordan virker det?

- Et dansk CPR-nummer består af 10 cifre: DDMMYY-SSSS
- Fødselsdatoen parses og matches mod gyldige datoer med Carbon
- Serienummeret (SSSS) bruges til at bestemme århundredet (1800/1900/2000)
- Sidste ciffer afgør køn: lige = kvinde, ulige = mand

## ✅ Eksempler på validering

```php
// Gælder: 1. januar 1901 + gyldigt serienummer
CprValidator::isValid('0101011234'); // true

// Ugyldig dato
CprValidator::isValid('3102991234'); // false (31. februar findes ikke)

// Forkert længde
CprValidator::isValid('123456789'); // false
```