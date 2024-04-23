<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Company;

class Company
{
    private ?string $name = null;
    private ?string $about = null;
    private ?string $type = null;
    private ?string $address = null;
    private ?string $city = null;
    private ?string $country = null;
    private ?string $zipcode = null;
    private ?string $department = null;
    private ?string $addrLongitude = null;
    private ?string $addrLatitude = null;
    private ?string $phone = null;
    private ?string $mobile = null;
    private ?string $fax = null;
    private ?string $phoneIndicatif = null;
    private ?string $director = null;
    private ?string $website = null;
    private ?string $mail = null;
    private ?string $siret = null;
    private ?string $ape = null;
    private ?string $aprm = null;
    private ?string $rm = null;
    private ?string $rcs = null;
    private ?string $rcPro = null;
    private ?string $tva = null;
    private ?string $facebook = null;
    private ?string $twitter = null;
    private ?string $instagram = null;
    private ?string $linkedin = null;
    private ?string $logo = null;
    private bool $isDefault = true;
    private ?\DateTime $createdAt = null;
    private ?\DateTime $updatedAt = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Company
    {
        $this->name = $name;
        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): Company
    {
        $this->about = $about;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Company
    {
        $this->type = $type;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): Company
    {
        $this->address = $address;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): Company
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): Company
    {
        $this->country = $country;
        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): Company
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): Company
    {
        $this->department = $department;
        return $this;
    }

    public function getAddrLongitude(): ?string
    {
        return $this->addrLongitude;
    }

    public function setAddrLongitude(?string $addrLongitude): Company
    {
        $this->addrLongitude = $addrLongitude;
        return $this;
    }

    public function getAddrLatitude(): ?string
    {
        return $this->addrLatitude;
    }

    public function setAddrLatitude(?string $addrLatitude): Company
    {
        $this->addrLatitude = $addrLatitude;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): Company
    {
        $this->phone = $phone;
        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): Company
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): Company
    {
        $this->fax = $fax;
        return $this;
    }

    public function getPhoneIndicatif(): ?string
    {
        return $this->phoneIndicatif;
    }

    public function setPhoneIndicatif(?string $phoneIndicatif): Company
    {
        $this->phoneIndicatif = $phoneIndicatif;
        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(?string $director): Company
    {
        $this->director = $director;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): Company
    {
        $this->website = $website;
        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): Company
    {
        $this->mail = $mail;
        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): Company
    {
        $this->siret = $siret;
        return $this;
    }

    public function getApe(): ?string
    {
        return $this->ape;
    }

    public function setApe(?string $ape): Company
    {
        $this->ape = $ape;
        return $this;
    }

    public function getAprm(): ?string
    {
        return $this->aprm;
    }

    public function setAprm(?string $aprm): Company
    {
        $this->aprm = $aprm;
        return $this;
    }

    public function getRm(): ?string
    {
        return $this->rm;
    }

    public function setRm(?string $rm): Company
    {
        $this->rm = $rm;
        return $this;
    }

    public function getRcs(): ?string
    {
        return $this->rcs;
    }

    public function setRcs(?string $rcs): Company
    {
        $this->rcs = $rcs;
        return $this;
    }

    public function getRcPro(): ?string
    {
        return $this->rcPro;
    }

    public function setRcPro(?string $rcPro): Company
    {
        $this->rcPro = $rcPro;
        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(?string $tva): Company
    {
        $this->tva = $tva;
        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): Company
    {
        $this->facebook = $facebook;
        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): Company
    {
        $this->twitter = $twitter;
        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): Company
    {
        $this->instagram = $instagram;
        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): Company
    {
        $this->linkedin = $linkedin;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): Company
    {
        $this->logo = $logo;
        return $this;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): Company
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): Company
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): Company
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
