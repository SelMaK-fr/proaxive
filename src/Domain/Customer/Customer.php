<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Customer;

use Envms\FluentPDO\Literal;
use Selmak\Proaxive2\Domain\BaseDTO;

class Customer extends BaseDTO
{
    private ?int $id = null;
    private string $mail;
    private string $civility = "M.";
    private string $firstname;
    private string $lastname;
    private string $fullname;
    private ?int $activated = 0;
    private string $login_id = '';
    private string $phone;
    private ?string $favorite_contact = null;
    private ?string $mobile = null;
    private ?string $profil_type = null;
    private ?string $contact_status = null;
    private ?string $contact_phone = null;
    private ?string $contact_fullname = null;
    private ?string $contact_mail = null;
    private ?string $contact_address = null;
    private string $address;
    private ?string $addr_longitude = null;
    private ?string $addr_latitude = null;
    private string $zipcode;
    private string $city;
    private string $department = '';
    private ?string $type_housing = null;
    private ?string $h_floor = null;
    private ?string $h_digicode = null;
    private ?string $h_about = null;
    private Literal $created_at;
    private Literal $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Customer
    {
        $this->id = $id;
        return $this;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): Customer
    {
        $this->mail = $mail;
        return $this;
    }

    public function getCivility(): string
    {
        return $this->civility;
    }

    public function setCivility(string $civility): Customer
    {
        $this->civility = $civility;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): Customer
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastname;
    }

    public function setLastName(string $lastname): Customer
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): Customer
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function getActivated(): ?int
    {
        return $this->activated;
    }

    public function setActivated(?int $activated): Customer
    {
        $this->activated = $activated;
        return $this;
    }

    public function getLoginId(): string
    {
        return $this->login_id;
    }

    public function setLoginId(string $login_id): Customer
    {
        $this->login_id = $login_id;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): Customer
    {
        $this->phone = $phone;
        return $this;
    }

    public function getFavoriteContact(): ?string
    {
        return $this->favorite_contact;
    }

    public function setFavoriteContact(?string $favorite_contact): Customer
    {
        $this->favorite_contact = $favorite_contact;
        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): Customer
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function getProfilType(): ?string
    {
        return $this->profil_type;
    }

    public function setProfilType(?string $profil_type): Customer
    {
        $this->profil_type = $profil_type;
        return $this;
    }

    public function getContactStatus(): ?string
    {
        return $this->contact_status;
    }

    public function setContactStatus(?string $contact_status): Customer
    {
        $this->contact_status = $contact_status;
        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    public function setContactPhone(?string $contact_phone): Customer
    {
        $this->contact_phone = $contact_phone;
        return $this;
    }

    public function getContactFullname(): ?string
    {
        return $this->contact_fullname;
    }

    public function setContactFullname(?string $contact_fullname): Customer
    {
        $this->contact_fullname = $contact_fullname;
        return $this;
    }

    public function getContactMail(): ?string
    {
        return $this->contact_mail;
    }

    public function setContactMail(?string $contact_mail): Customer
    {
        $this->contact_mail = $contact_mail;
        return $this;
    }

    public function getContactAddress(): ?string
    {
        return $this->contact_address;
    }

    public function setContactAddress(?string $contact_address): Customer
    {
        $this->contact_address = $contact_address;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): Customer
    {
        $this->address = $address;
        return $this;
    }

    public function getAddrLongitude(): ?string
    {
        return $this->addr_longitude;
    }

    public function setAddrLongitude(?string $addr_longitude): Customer
    {
        $this->addr_longitude = $addr_longitude;
        return $this;
    }

    public function getAddrLatitude(): ?string
    {
        return $this->addr_latitude;
    }

    public function setAddrLatitude(?string $addr_latitude): Customer
    {
        $this->addr_latitude = $addr_latitude;
        return $this;
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): Customer
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): Customer
    {
        $this->city = $city;
        return $this;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function setDepartment(string $department): Customer
    {
        $this->department = $department;
        return $this;
    }

    public function getTypeHousing(): ?string
    {
        return $this->type_housing;
    }

    public function setTypeHousing(?string $type_housing): Customer
    {
        $this->type_housing = $type_housing;
        return $this;
    }

    public function getHFloor(): ?string
    {
        return $this->h_floor;
    }

    public function setHFloor(?string $h_floor): Customer
    {
        $this->h_floor = $h_floor;
        return $this;
    }

    public function getHDigicode(): ?string
    {
        return $this->h_digicode;
    }

    public function setHDigicode(?string $h_digicode): Customer
    {
        $this->h_digicode = $h_digicode;
        return $this;
    }

    public function getHAbout(): ?string
    {
        return $this->h_about;
    }

    public function setHAbout(?string $h_about): Customer
    {
        $this->h_about = $h_about;
        return $this;
    }

    public function getCreatedAt(): Literal
    {
        return $this->created_at;
    }

    public function setCreatedAt(Literal $created_at): Customer
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): Literal
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(Literal $updated_at): Customer
    {
        $this->updated_at = $updated_at;
        return $this;
    }

}