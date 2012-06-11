<?php
/**
 * 2011-10-24 18:08:33
 * @package package_name
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */
class Person
{
    private $prefix;
    private $givenName;
    private $familyName;
    private $suffix;
    
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
    
    public function getPrefix()
    {
        return $this->prefix;
    }
    
    public function setGivenName($gn)
    {
        $this->givenName = $gn;
    }
    
    public function getGivenName()
    {
        return $this->givenName;
    }
    
    public function setFamilyName($fn)
    {
        $this->familyName = $fn;
    }
    
    public function getFamilyName() 
    {
        return $this->familyName;
    }
    
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }
    
    public function getSuffix()
    {
        return $suffix;
    }
    
}

$person = new Person();
$person->setPrefix("Mr.");
$person->setGivenName("John");

echo($person->getPrefix());
echo($person->getGivenName());
class InvalidPersonNameFormatException extends LogicException {}


class PersonUtils
{
    public static function parsePersonName($format, $val)
    {
        if (! $format) {
            throw new InvalidPersonNameFormatException("Invalid PersonName format.");
        }

        if ((! isset($val)) || strlen($val) == 0) {
            throw new InvalidArgumentException("Must supply a non-null value to parse.");
        }


    }
}

interface PersonProvider
{
    public function getPerson($givenName, $familyName);
}

class DBPersonProvider implements PersonProvider 
{
    public function getPerson($givenName, $familyName)
    {
        /* pretend to go to the database, get the person... */
        $person = new Person();
        $person->setPrefix("Mr.");
        $person->setGivenName("John");
        return $person;
    }
}

class PersonProviderFactory
{
    public static function createProvider($type)
    {
        if ($type == 'database')
        {
            return new DBPersonProvider();
        } else {
            return new NullProvider();
        }
    }
}

$config = 'database';
/* I need to get person data... */
$provider = PersonProviderFactory::createProvider($config);
$person = $provider->getPerson("John", "Doe");

echo($person->getPrefix());
echo($person->getGivenName());

interface AddressFormatter
{
    public function format($addressLine1, $addressLine2, $city, $state, 
        $postalCode, $country);
}

class MultiLineAddressFormatter implements AddressFormatter 
{
    public function format($addressLine1, $addressLine2, $city, $state, 
        $postalCode, $country)
    {
        return sprintf("%s\n%s\n%s, %s %s\n%s", 
            $addressLine1, $addressLine2, $city, $state, $postalCode, $country);
    }
}

class InlineAddressFormatter implements AddressFormatter 
{
    public function format($addressLine1, $addressLine2, $city, $state, 
        $postalCode, $country)
    {
        return sprintf("%s %s, %s, %s %s %s", 
            $addressLine1, $addressLine2, $city, $state, $postalCode, $country);
    }
}

class AddressFormatUtils 
{
    public static function formatAddress($type, $address)
    {
        $formatter = AddressFormatUtils::createAddressFormatter($type);
        
        return $formatter->format($address->getAddressLine1(), 
            $address->getAddressLine2(), 
            $address->getCity(), $address->getState(), 
            $address->getPostalCode(), 
            $address->getCountry());
    }
    
    private static function createAddressFormatter($type)
    {
        if ($type == "inline") {
            $formatter = new InlineAddressFormatter();
        } else if ($type == "multiline") {
            $formatter = new MultilineAddressFormatter();
        } else {
            $formatter = new NullAddressFormatter();
        }
        return $formatter;
    }
}

$addr = new Address();
$addr->setAddressLine1("123 Any St.");
$addr->setAddressLine2("Ste 200");
$addr->setCity("Anytown");
$addr->setState("AY");
$addr->setPostalCode("55555-0000");
$addr->setCountry("US");

echo(AddressFormatUtils::formatAddress("multiline", $addr));
echo("\n");

echo(AddressFormatUtils::formatAddress("inline", $addr));
echo("\n");

class AddressUtils
{
    public static function formatAddress($formatType, $address1, 
        $address2, $city, $state)
    {
        return "some address string";
    }
    
    public static function parseAddress($formatType, $val)
    {
        // real implementation would set values, etc...
        return new Address();
    }
    
}

class PersonUtils
{
    public static function formatPersonName($formatType, $givenName, 
        $familyName)
    {
        return "some person name";
    }
    
    public static function parsePersonName($formatType, $val)
    {
        // real implementation would set values, etc...
        return new PersonName();
    }
}


abstract class Person
{
    private $givenName;
    private $familyName;
    
    public function setGivenName($gn)
    {
        $this->givenName = $gn;
    }
    
    public function getGivenName()
    {
        return $this->givenName;
    }
    
    public function setFamilyName($fn)
    {
        $this->familyName = $fn;
    }
    
    public function getFamilyName()
    {
        return $this->familyName;
    }
     
    public function sayHello()
    {
        echo("Hello, I am ");
        $this->introduceSelf();
    }
    
    abstract public function introduceSelf();
    
}

class Employee extends Person
{
    private $role;
    
    public function setRole($r)
    {
        $this->role = $r; 
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function introduceSelf()
    {
        echo($this->getRole() . " " . $this->getGivenName() . " " . 
            $this->getFamilyName());
    }
}

abstract class Person{
	
    private $givenName;
    private $familyName;
    
    public function setGivenName($gn)
    {
        $this->givenName = $gn;
    }
    
    public function getGivenName()
    {
        return $this->givenName;
    }
    
    public function setFamilyName($fn)
    {
        $this->familyName = $fn;
    }
    
    public function getFamilyName()
    {
        return $this->familyName;
    }
     
    public function sayHello()
    {
        echo("Hello, I am ");
        $this->introduceSelf();
    }
    
    abstract public function introduceSelf();
    
}

class Employee extends Person
{
    private $role;
    
    public function setRole($r)
    {
        $this->role = $r; 
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function introduceSelf()
    {
        echo($this->getRole() . " " . $this->getGivenName() . " " . 
            $this->getFamilyName());
    }
}

interface AddressFormatter
{
    public function format($addressLine1, $addressLine2, $city, $state, 
        $postalCode, $country);
}

class MultiLineAddressFormatter implements AddressFormatter 
{
    public function format($addressLine1, $addressLine2, $city, $state, 
        $postalCode, $country)
    {
        return sprintf("%s\n%s\n%s, %s %s\n%s", 
            $addressLine1, $addressLine2, $city, $state, $postalCode, $country);
    }
}

class InlineAddressFormatter implements AddressFormatter 
{
    public function format($addressLine1, $addressLine2, $city, $state, 
        $postalCode, $country)
    {
        return sprintf("%s %s, %s, %s %s %s", 
            $addressLine1, $addressLine2, $city, $state, $postalCode, $country);
    }
}

class AddressFormatUtils 
{
    public static function formatAddress($type, $address)
    {
        $formatter = AddressFormatUtils::createAddressFormatter($type);
        
        return $formatter->format($address->getAddressLine1(), 
            $address->getAddressLine2(), 
            $address->getCity(), $address->getState(), 
            $address->getPostalCode(), 
            $address->getCountry());
    }
    
    private static function createAddressFormatter($type)
    {
        if ($type == "inline") {
            $formatter = new InlineAddressFormatter();
        } else if ($type == "multiline") {
            $formatter = new MultilineAddressFormatter();
        } else {
            $formatter = new NullAddressFormatter();
        }
        return $formatter;
    }
}

$addr = new Address();
$addr->setAddressLine1("123 Any St.");
$addr->setAddressLine2("Ste 200");
$addr->setCity("Anytown");
$addr->setState("AY");
$addr->setPostalCode("55555-0000");
$addr->setCountry("US");

echo(AddressFormatUtils::formatAddress("multiline", $addr));
echo("\n");

echo(AddressFormatUtils::formatAddress("inline", $addr));
echo("\n");