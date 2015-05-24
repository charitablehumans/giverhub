<?php

namespace Entity;

/**
 * IrsEins
 *
 * @Table(name="irs_eins", indexes={@Index(name="irs_eins_ein_idx", columns={"ein"})})
 * @Entity
 */
class IrsEins extends BaseEntity {
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @Column(name="ein", type="string", length=9, nullable=false)
     */
    private $ein;

    /**
     * @var string
     *
     * @Column(name="primary_name_of_organization", type="string", length=255, nullable=false)
     */
    private $primaryNameOfOrganization;

    /**
     * @var string
     *
     * @Column(name="in_care_of_name", type="string", length=255, nullable=true)
     */
    private $inCareOfName;

    /**
     * @var string
     *
     * @Column(name="street_address", type="string", length=255, nullable=true)
     */
    private $streetAddress;

    /**
     * @var string
     *
     * @Column(name="city", type="string", length=22, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @Column(name="state", type="string", length=4, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @Column(name="zip_code", type="string", length=10, nullable=false)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @Column(name="group_exemption_number", type="string", length=4, nullable=false)
     */
    private $groupExemptionNumber;

    /**
     * @var string
     *
     * @Column(name="subsection_code", type="string", length=2, nullable=false)
     */
    private $subsectionCode;

    /**
     * @var integer
     *
     * @Column(name="affiliation_code", type="integer", nullable=false)
     */
    private $affiliationCode;

    /**
     * @var integer
     *
     * @Column(name="classification_codes", type="integer", nullable=false)
     */
    private $classificationCodes;

    /**
     * @var integer
     *
     * @Column(name="ruling_date", type="integer", nullable=false)
     */
    private $rulingDate;

    /**
     * @var integer
     *
     * @Column(name="deductibility_code", type="integer", nullable=false)
     */
    private $deductibilityCode;

    /**
     * @var string
     *
     * @Column(name="foundation_code", type="string", length=2, nullable=false)
     */
    private $foundationCode;

    /**
     * @var string
     *
     * @Column(name="activity_codes", type="string", length=9, nullable=false)
     */
    private $activityCodes;

    /**
     * @var integer
     *
     * @Column(name="organization_code_", type="integer", nullable=false)
     */
    private $organizationCode;

    /**
     * @var string
     *
     * @Column(name="exempt_organization_status_code_new", type="string", length=2, nullable=false)
     */
    private $exemptOrganizationStatusCodeNew;

    /**
     * @var integer
     *
     * @Column(name="tax_period", type="integer", nullable=true)
     */
    private $taxPeriod;

    /**
     * @var integer
     *
     * @Column(name="asset_code", type="integer", nullable=false)
     */
    private $assetCode;

    /**
     * @var integer
     *
     * @Column(name="income_code", type="integer", nullable=false)
     */
    private $incomeCode;

    /**
     * @var string
     *
     * @Column(name="filing_requirement_code", type="string", length=2, nullable=false)
     */
    private $filingRequirementCode;

    /**
     * @var integer
     *
     * @Column(name="pf_filing_requirement_code_new", type="integer", nullable=false)
     */
    private $pfFilingRequirementCodeNew;

    /**
     * @var string
     *
     * @Column(name="accounting_period", type="string", length=2, nullable=false)
     */
    private $accountingPeriod;

    /**
     * @var string
     *
     * @Column(name="asset_amount", type="decimal", precision=11, scale=2, nullable=true)
     */
    private $assetAmount;

    /**
     * @var string
     *
     * @Column(name="income_amount", type="decimal", precision=11, scale=2, nullable=true)
     */
    private $incomeAmount;

    /**
     * @var string
     *
     * @Column(name="form_990_revenue_amount", type="decimal", precision=11, scale=2, nullable=true)
     */
    private $form990RevenueAmount;

    /**
     * @var string
     *
     * @Column(name="national_taxonomy_of_exempt_entities_ntee_code", type="string", length=4, nullable=true)
     */
    private $nationalTaxonomyOfExemptEntitiesNteeCode;

    /**
     * @var string
     *
     * @Column(name="sort_name_secondary_name_line", type="string", length=255, nullable=true)
     */
    private $sortNameSecondaryNameLine;

    /**
     * @var string
     *
     * @Column(name="deductability_descrip", type="string", length=62, nullable=true)
     */
    private $deductabilityDescrip;

    /**
     * @var string
     *
     * @Column(name="foundation_descrip", type="string", length=255, nullable=true)
     */
    private $foundationDescrip;

    /**
     * @var string
     *
     * @Column(name="subsection_descrip", type="string", length=255, nullable=true)
     */
    private $subsectionDescrip;

    /**
     * @var string
     *
     * @Column(name="activity_broad_descrip", type="string", length=255, nullable=true)
     */
    private $activityBroadDescrip;

    /**
     * @var string
     *
     * @Column(name="activity_descrip", type="string", length=255, nullable=true)
     */
    private $activityDescrip;

    /**
     * @var string
     *
     * @Column(name="exempt_broad_descrip", type="string", length=55, nullable=true)
     */
    private $exemptBroadDescrip;

    /**
     * @var string
     *
     * @Column(name="exempt_descrip", type="string", length=255, nullable=true)
     */
    private $exemptDescrip;

    /**
     * @var string
     *
     * @Column(name="mostrecent", type="string", length=1, nullable=true)
     */
    private $mostrecent = '0';

    /**
     * @var string
     *
     * @Column(name="has_990", type="string", length=1, nullable=true)
     */
    private $has990 = '0';

    /**
     * @var string
     *
     * @Column(name="has_990ez", type="string", length=1, nullable=true)
     */
    private $has990ez = '0';

    /**
     * @var string
     *
     * @Column(name="has_990pf", type="string", length=1, nullable=true)
     */
    private $has990pf = '0';

    /**
     * @param string $accountingPeriod
     */
    public function setAccountingPeriod($accountingPeriod)
    {
        $this->accountingPeriod = $accountingPeriod;
    }

    /**
     * @return string
     */
    public function getAccountingPeriod()
    {
        return $this->accountingPeriod;
    }

    /**
     * @param string $activityBroadDescrip
     */
    public function setActivityBroadDescrip($activityBroadDescrip)
    {
        $this->activityBroadDescrip = $activityBroadDescrip;
    }

    /**
     * @return string
     */
    public function getActivityBroadDescrip()
    {
        return $this->activityBroadDescrip;
    }

    /**
     * @param string $activityCodes
     */
    public function setActivityCodes($activityCodes)
    {
        $this->activityCodes = $activityCodes;
    }

    /**
     * @return string
     */
    public function getActivityCodes()
    {
        return $this->activityCodes;
    }

    /**
     * @param string $activityDescrip
     */
    public function setActivityDescrip($activityDescrip)
    {
        $this->activityDescrip = $activityDescrip;
    }

    /**
     * @return string
     */
    public function getActivityDescrip()
    {
        return $this->activityDescrip;
    }

    /**
     * @param int $affiliationCode
     */
    public function setAffiliationCode($affiliationCode)
    {
        $this->affiliationCode = $affiliationCode;
    }

    /**
     * @return int
     */
    public function getAffiliationCode()
    {
        return $this->affiliationCode;
    }

    /**
     * @param string $assetAmount
     */
    public function setAssetAmount($assetAmount)
    {
        $this->assetAmount = $assetAmount;
    }

    /**
     * @return string
     */
    public function getAssetAmount()
    {
        return $this->assetAmount;
    }

    /**
     * @param int $assetCode
     */
    public function setAssetCode($assetCode)
    {
        $this->assetCode = $assetCode;
    }

    /**
     * @return int
     */
    public function getAssetCode()
    {
        return $this->assetCode;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param int $classificationCodes
     */
    public function setClassificationCodes($classificationCodes)
    {
        $this->classificationCodes = $classificationCodes;
    }

    /**
     * @return int
     */
    public function getClassificationCodes()
    {
        return $this->classificationCodes;
    }

    /**
     * @param string $deductabilityDescrip
     */
    public function setDeductabilityDescrip($deductabilityDescrip)
    {
        $this->deductabilityDescrip = $deductabilityDescrip;
    }

    /**
     * @return string
     */
    public function getDeductabilityDescrip()
    {
        return $this->deductabilityDescrip;
    }

    /**
     * @param int $deductibilityCode
     */
    public function setDeductibilityCode($deductibilityCode)
    {
        $this->deductibilityCode = $deductibilityCode;
    }

    /**
     * @return int
     */
    public function getDeductibilityCode()
    {
        return $this->deductibilityCode;
    }

    /**
     * @param string $ein
     */
    public function setEin($ein)
    {
        $this->ein = $ein;
    }

    /**
     * @return string
     */
    public function getEin()
    {
        return $this->ein;
    }

    /**
     * @param string $exemptBroadDescrip
     */
    public function setExemptBroadDescrip($exemptBroadDescrip)
    {
        $this->exemptBroadDescrip = $exemptBroadDescrip;
    }

    /**
     * @return string
     */
    public function getExemptBroadDescrip()
    {
        return $this->exemptBroadDescrip;
    }

    /**
     * @param string $exemptDescrip
     */
    public function setExemptDescrip($exemptDescrip)
    {
        $this->exemptDescrip = $exemptDescrip;
    }

    /**
     * @return string
     */
    public function getExemptDescrip()
    {
        return $this->exemptDescrip;
    }

    /**
     * @param string $exemptOrganizationStatusCodeNew
     */
    public function setExemptOrganizationStatusCodeNew($exemptOrganizationStatusCodeNew)
    {
        $this->exemptOrganizationStatusCodeNew = $exemptOrganizationStatusCodeNew;
    }

    /**
     * @return string
     */
    public function getExemptOrganizationStatusCodeNew()
    {
        return $this->exemptOrganizationStatusCodeNew;
    }

    /**
     * @param string $filingRequirementCode
     */
    public function setFilingRequirementCode($filingRequirementCode)
    {
        $this->filingRequirementCode = $filingRequirementCode;
    }

    /**
     * @return string
     */
    public function getFilingRequirementCode()
    {
        return $this->filingRequirementCode;
    }

    /**
     * @param string $form990RevenueAmount
     */
    public function setForm990RevenueAmount($form990RevenueAmount)
    {
        $this->form990RevenueAmount = $form990RevenueAmount;
    }

    /**
     * @return string
     */
    public function getForm990RevenueAmount()
    {
        return $this->form990RevenueAmount;
    }

    /**
     * @param string $foundationCode
     */
    public function setFoundationCode($foundationCode)
    {
        $this->foundationCode = $foundationCode;
    }

    /**
     * @return string
     */
    public function getFoundationCode()
    {
        return $this->foundationCode;
    }

    /**
     * @param string $foundationDescrip
     */
    public function setFoundationDescrip($foundationDescrip)
    {
        $this->foundationDescrip = $foundationDescrip;
    }

    /**
     * @return string
     */
    public function getFoundationDescrip()
    {
        return $this->foundationDescrip;
    }

    /**
     * @param string $groupExemptionNumber
     */
    public function setGroupExemptionNumber($groupExemptionNumber)
    {
        $this->groupExemptionNumber = $groupExemptionNumber;
    }

    /**
     * @return string
     */
    public function getGroupExemptionNumber()
    {
        return $this->groupExemptionNumber;
    }

    /**
     * @param string $has990
     */
    public function setHas990($has990)
    {
        $this->has990 = $has990;
    }

    /**
     * @return string
     */
    public function getHas990()
    {
        return $this->has990;
    }

    /**
     * @param string $has990ez
     */
    public function setHas990ez($has990ez)
    {
        $this->has990ez = $has990ez;
    }

    /**
     * @return string
     */
    public function getHas990ez()
    {
        return $this->has990ez;
    }

    /**
     * @param string $has990pf
     */
    public function setHas990pf($has990pf)
    {
        $this->has990pf = $has990pf;
    }

    /**
     * @return string
     */
    public function getHas990pf()
    {
        return $this->has990pf;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $inCareOfName
     */
    public function setInCareOfName($inCareOfName)
    {
        $this->inCareOfName = $inCareOfName;
    }

    /**
     * @return string
     */
    public function getInCareOfName()
    {
        return $this->inCareOfName;
    }

    /**
     * @param string $incomeAmount
     */
    public function setIncomeAmount($incomeAmount)
    {
        $this->incomeAmount = $incomeAmount;
    }

    /**
     * @return string
     */
    public function getIncomeAmount()
    {
        return $this->incomeAmount;
    }

    /**
     * @param int $incomeCode
     */
    public function setIncomeCode($incomeCode)
    {
        $this->incomeCode = $incomeCode;
    }

    /**
     * @return int
     */
    public function getIncomeCode()
    {
        return $this->incomeCode;
    }

    /**
     * @param string $mostrecent
     */
    public function setMostrecent($mostrecent)
    {
        $this->mostrecent = $mostrecent;
    }

    /**
     * @return string
     */
    public function getMostrecent()
    {
        return $this->mostrecent;
    }

    /**
     * @param string $nationalTaxonomyOfExemptEntitiesNteeCode
     */
    public function setNationalTaxonomyOfExemptEntitiesNteeCode($nationalTaxonomyOfExemptEntitiesNteeCode)
    {
        $this->nationalTaxonomyOfExemptEntitiesNteeCode = $nationalTaxonomyOfExemptEntitiesNteeCode;
    }

    /**
     * @return string
     */
    public function getNationalTaxonomyOfExemptEntitiesNteeCode()
    {
        return $this->nationalTaxonomyOfExemptEntitiesNteeCode;
    }

    /**
     * @param int $organizationCode
     */
    public function setOrganizationCode($organizationCode)
    {
        $this->organizationCode = $organizationCode;
    }

    /**
     * @return int
     */
    public function getOrganizationCode()
    {
        return $this->organizationCode;
    }

    /**
     * @param int $pfFilingRequirementCodeNew
     */
    public function setPfFilingRequirementCodeNew($pfFilingRequirementCodeNew)
    {
        $this->pfFilingRequirementCodeNew = $pfFilingRequirementCodeNew;
    }

    /**
     * @return int
     */
    public function getPfFilingRequirementCodeNew()
    {
        return $this->pfFilingRequirementCodeNew;
    }

    /**
     * @param string $primaryNameOfOrganization
     */
    public function setPrimaryNameOfOrganization($primaryNameOfOrganization)
    {
        $this->primaryNameOfOrganization = $primaryNameOfOrganization;
    }

    /**
     * @return string
     */
    public function getPrimaryNameOfOrganization()
    {
        return $this->primaryNameOfOrganization;
    }

    /**
     * @param int $rulingDate
     */
    public function setRulingDate($rulingDate)
    {
        $this->rulingDate = $rulingDate;
    }

    /**
     * @return int
     */
    public function getRulingDate()
    {
        return $this->rulingDate;
    }

    /**
     * @param string $sortNameSecondaryNameLine
     */
    public function setSortNameSecondaryNameLine($sortNameSecondaryNameLine)
    {
        $this->sortNameSecondaryNameLine = $sortNameSecondaryNameLine;
    }

    /**
     * @return string
     */
    public function getSortNameSecondaryNameLine()
    {
        return $this->sortNameSecondaryNameLine;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $streetAddress
     */
    public function setStreetAddress($streetAddress)
    {
        $this->streetAddress = $streetAddress;
    }

    /**
     * @return string
     */
    public function getStreetAddress()
    {
        return $this->streetAddress;
    }

    /**
     * @param string $subsectionCode
     */
    public function setSubsectionCode($subsectionCode)
    {
        $this->subsectionCode = $subsectionCode;
    }

    /**
     * @return string
     */
    public function getSubsectionCode()
    {
        return $this->subsectionCode;
    }

    /**
     * @param string $subsectionDescrip
     */
    public function setSubsectionDescrip($subsectionDescrip)
    {
        $this->subsectionDescrip = $subsectionDescrip;
    }

    /**
     * @return string
     */
    public function getSubsectionDescrip()
    {
        return $this->subsectionDescrip;
    }

    /**
     * @param int $taxPeriod
     */
    public function setTaxPeriod($taxPeriod)
    {
        $this->taxPeriod = $taxPeriod;
    }

    /**
     * @return int
     */
    public function getTaxPeriod()
    {
        return $this->taxPeriod;
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }


}
