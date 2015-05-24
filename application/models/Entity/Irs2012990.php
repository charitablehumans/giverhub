<?php

namespace Entity;

/**
 * Irs2012990
 *
 * @Table(name="irs_2012_990", indexes={@Index(name="irs_2012_990_ein_idx", columns={"ein"})})
 * @Entity
 */
class Irs2012990 extends BaseEntity {
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="ein", type="string", length=9, nullable=false)
     */
    private $ein;

    /**
     * @var integer
     *
     * @Column(name="tax_prd", type="bigint", nullable=false)
     */
    private $taxPrd;

    /**
     * @var string
     *
     * @Column(name="subseccd", type="string", length=2, nullable=false)
     */
    private $subseccd;

    /**
     * @var string
     *
     * @Column(name="unrelbusinccd", type="string", length=1, nullable=false)
     */
    private $unrelbusinccd;

    /**
     * @var integer
     *
     * @Column(name="initiationfees", type="bigint", nullable=false)
     */
    private $initiationfees;

    /**
     * @var integer
     *
     * @Column(name="grsrcptspublicuse", type="bigint", nullable=false)
     */
    private $grsrcptspublicuse;

    /**
     * @var integer
     *
     * @Column(name="grsincmembers", type="bigint", nullable=false)
     */
    private $grsincmembers;

    /**
     * @var integer
     *
     * @Column(name="grsincother", type="bigint", nullable=false)
     */
    private $grsincother;

    /**
     * @var integer
     *
     * @Column(name="totcntrbgfts", type="bigint", nullable=false)
     */
    private $totcntrbgfts;

    /**
     * @var integer
     *
     * @Column(name="totprgmrevnue", type="bigint", nullable=false)
     */
    private $totprgmrevnue;

    /**
     * @var integer
     *
     * @Column(name="invstmntinc", type="bigint", nullable=false)
     */
    private $invstmntinc;

    /**
     * @var integer
     *
     * @Column(name="txexmptbndsproceeds", type="bigint", nullable=false)
     */
    private $txexmptbndsproceeds;

    /**
     * @var integer
     *
     * @Column(name="royaltsinc", type="bigint", nullable=false)
     */
    private $royaltsinc;

    /**
     * @var integer
     *
     * @Column(name="grsrntsreal", type="bigint", nullable=false)
     */
    private $grsrntsreal;

    /**
     * @var integer
     *
     * @Column(name="grsrntsprsnl", type="bigint", nullable=false)
     */
    private $grsrntsprsnl;

    /**
     * @var integer
     *
     * @Column(name="rntlexpnsreal", type="bigint", nullable=false)
     */
    private $rntlexpnsreal;

    /**
     * @var integer
     *
     * @Column(name="rntlexpnsprsnl", type="bigint", nullable=false)
     */
    private $rntlexpnsprsnl;

    /**
     * @var integer
     *
     * @Column(name="rntlincreal", type="bigint", nullable=false)
     */
    private $rntlincreal;

    /**
     * @var integer
     *
     * @Column(name="rntlincprsnl", type="bigint", nullable=false)
     */
    private $rntlincprsnl;

    /**
     * @var integer
     *
     * @Column(name="netrntlinc", type="bigint", nullable=false)
     */
    private $netrntlinc;

    /**
     * @var integer
     *
     * @Column(name="grsalesecur", type="bigint", nullable=false)
     */
    private $grsalesecur;

    /**
     * @var integer
     *
     * @Column(name="grsalesothr", type="bigint", nullable=false)
     */
    private $grsalesothr;

    /**
     * @var integer
     *
     * @Column(name="cstbasisecur", type="bigint", nullable=false)
     */
    private $cstbasisecur;

    /**
     * @var integer
     *
     * @Column(name="cstbasisothr", type="bigint", nullable=false)
     */
    private $cstbasisothr;

    /**
     * @var integer
     *
     * @Column(name="gnlsecur", type="bigint", nullable=false)
     */
    private $gnlsecur;

    /**
     * @var integer
     *
     * @Column(name="gnlsothr", type="bigint", nullable=false)
     */
    private $gnlsothr;

    /**
     * @var integer
     *
     * @Column(name="netgnls", type="bigint", nullable=false)
     */
    private $netgnls;

    /**
     * @var integer
     *
     * @Column(name="grsincfndrsng", type="bigint", nullable=false)
     */
    private $grsincfndrsng;

    /**
     * @var integer
     *
     * @Column(name="lessdirfndrsng", type="bigint", nullable=false)
     */
    private $lessdirfndrsng;

    /**
     * @var integer
     *
     * @Column(name="netincfndrsng", type="bigint", nullable=false)
     */
    private $netincfndrsng;

    /**
     * @var integer
     *
     * @Column(name="grsincgaming", type="bigint", nullable=false)
     */
    private $grsincgaming;

    /**
     * @var integer
     *
     * @Column(name="lessdirgaming", type="bigint", nullable=false)
     */
    private $lessdirgaming;

    /**
     * @var integer
     *
     * @Column(name="netincgaming", type="bigint", nullable=false)
     */
    private $netincgaming;

    /**
     * @var integer
     *
     * @Column(name="grsalesinvent", type="bigint", nullable=false)
     */
    private $grsalesinvent;

    /**
     * @var integer
     *
     * @Column(name="lesscstofgoods", type="bigint", nullable=false)
     */
    private $lesscstofgoods;

    /**
     * @var integer
     *
     * @Column(name="netincsales", type="bigint", nullable=false)
     */
    private $netincsales;

    /**
     * @var integer
     *
     * @Column(name="miscrevtot11e", type="bigint", nullable=false)
     */
    private $miscrevtot11e;

    /**
     * @var integer
     *
     * @Column(name="totrevenue", type="bigint", nullable=false)
     */
    private $totrevenue;

    /**
     * @var integer
     *
     * @Column(name="compnsatncurrofcr", type="bigint", nullable=false)
     */
    private $compnsatncurrofcr;

    /**
     * @var integer
     *
     * @Column(name="othrsalwages", type="bigint", nullable=false)
     */
    private $othrsalwages;

    /**
     * @var integer
     *
     * @Column(name="payrolltx", type="bigint", nullable=false)
     */
    private $payrolltx;

    /**
     * @var integer
     *
     * @Column(name="profndraising", type="bigint", nullable=false)
     */
    private $profndraising;

    /**
     * @var integer
     *
     * @Column(name="totfuncexpns", type="bigint", nullable=false)
     */
    private $totfuncexpns;

    /**
     * @var integer
     *
     * @Column(name="totassetsend", type="bigint", nullable=false)
     */
    private $totassetsend;

    /**
     * @var integer
     *
     * @Column(name="txexmptbndsend", type="bigint", nullable=false)
     */
    private $txexmptbndsend;

    /**
     * @var integer
     *
     * @Column(name="secrdmrtgsend", type="bigint", nullable=false)
     */
    private $secrdmrtgsend;

    /**
     * @var integer
     *
     * @Column(name="unsecurednotesend", type="bigint", nullable=false)
     */
    private $unsecurednotesend;

    /**
     * @var integer
     *
     * @Column(name="totliabend", type="bigint", nullable=false)
     */
    private $totliabend;

    /**
     * @var integer
     *
     * @Column(name="retainedearnend", type="bigint", nullable=false)
     */
    private $retainedearnend;

    /**
     * @var integer
     *
     * @Column(name="totnetassetend", type="bigint", nullable=false)
     */
    private $totnetassetend;

    /**
     * @var string
     *
     * @Column(name="nonpfrea", type="string", length=2, nullable=false)
     */
    private $nonpfrea;

    /**
     * @var integer
     *
     * @Column(name="gftgrntsrcvd170", type="bigint", nullable=false)
     */
    private $gftgrntsrcvd170;

    /**
     * @var integer
     *
     * @Column(name="txrevnuelevied170", type="bigint", nullable=false)
     */
    private $txrevnuelevied170;

    /**
     * @var integer
     *
     * @Column(name="srvcsval170", type="bigint", nullable=false)
     */
    private $srvcsval170;

    /**
     * @var integer
     *
     * @Column(name="grsinc170", type="bigint", nullable=false)
     */
    private $grsinc170;

    /**
     * @var integer
     *
     * @Column(name="grsrcptsrelated170", type="bigint", nullable=false)
     */
    private $grsrcptsrelated170;

    /**
     * @var integer
     *
     * @Column(name="totgftgrntrcvd509", type="bigint", nullable=false)
     */
    private $totgftgrntrcvd509;

    /**
     * @var integer
     *
     * @Column(name="grsrcptsadmissn509", type="bigint", nullable=false)
     */
    private $grsrcptsadmissn509;

    /**
     * @var integer
     *
     * @Column(name="txrevnuelevied509", type="bigint", nullable=false)
     */
    private $txrevnuelevied509;

    /**
     * @var integer
     *
     * @Column(name="srvcsval509", type="bigint", nullable=false)
     */
    private $srvcsval509;

    /**
     * @var integer
     *
     * @Column(name="subtotsuppinc509", type="bigint", nullable=false)
     */
    private $subtotsuppinc509;

    /**
     * @var integer
     *
     * @Column(name="totsupp509", type="bigint", nullable=false)
     */
    private $totsupp509;

    /**
     * @param int $compnsatncurrofcr
     */
    public function setCompnsatncurrofcr($compnsatncurrofcr)
    {
        $this->compnsatncurrofcr = $compnsatncurrofcr;
    }

    /**
     * @return int
     */
    public function getCompnsatncurrofcr()
    {
        return $this->compnsatncurrofcr;
    }

    /**
     * @param int $cstbasisecur
     */
    public function setCstbasisecur($cstbasisecur)
    {
        $this->cstbasisecur = $cstbasisecur;
    }

    /**
     * @return int
     */
    public function getCstbasisecur()
    {
        return $this->cstbasisecur;
    }

    /**
     * @param int $cstbasisothr
     */
    public function setCstbasisothr($cstbasisothr)
    {
        $this->cstbasisothr = $cstbasisothr;
    }

    /**
     * @return int
     */
    public function getCstbasisothr()
    {
        return $this->cstbasisothr;
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
     * @param int $gftgrntsrcvd170
     */
    public function setGftgrntsrcvd170($gftgrntsrcvd170)
    {
        $this->gftgrntsrcvd170 = $gftgrntsrcvd170;
    }

    /**
     * @return int
     */
    public function getGftgrntsrcvd170()
    {
        return $this->gftgrntsrcvd170;
    }

    /**
     * @param int $gnlsecur
     */
    public function setGnlsecur($gnlsecur)
    {
        $this->gnlsecur = $gnlsecur;
    }

    /**
     * @return int
     */
    public function getGnlsecur()
    {
        return $this->gnlsecur;
    }

    /**
     * @param int $gnlsothr
     */
    public function setGnlsothr($gnlsothr)
    {
        $this->gnlsothr = $gnlsothr;
    }

    /**
     * @return int
     */
    public function getGnlsothr()
    {
        return $this->gnlsothr;
    }

    /**
     * @param int $grsalesecur
     */
    public function setGrsalesecur($grsalesecur)
    {
        $this->grsalesecur = $grsalesecur;
    }

    /**
     * @return int
     */
    public function getGrsalesecur()
    {
        return $this->grsalesecur;
    }

    /**
     * @param int $grsalesinvent
     */
    public function setGrsalesinvent($grsalesinvent)
    {
        $this->grsalesinvent = $grsalesinvent;
    }

    /**
     * @return int
     */
    public function getGrsalesinvent()
    {
        return $this->grsalesinvent;
    }

    /**
     * @param int $grsalesothr
     */
    public function setGrsalesothr($grsalesothr)
    {
        $this->grsalesothr = $grsalesothr;
    }

    /**
     * @return int
     */
    public function getGrsalesothr()
    {
        return $this->grsalesothr;
    }

    /**
     * @param int $grsinc170
     */
    public function setGrsinc170($grsinc170)
    {
        $this->grsinc170 = $grsinc170;
    }

    /**
     * @return int
     */
    public function getGrsinc170()
    {
        return $this->grsinc170;
    }

    /**
     * @param int $grsincfndrsng
     */
    public function setGrsincfndrsng($grsincfndrsng)
    {
        $this->grsincfndrsng = $grsincfndrsng;
    }

    /**
     * @return int
     */
    public function getGrsincfndrsng()
    {
        return $this->grsincfndrsng;
    }

    /**
     * @param int $grsincgaming
     */
    public function setGrsincgaming($grsincgaming)
    {
        $this->grsincgaming = $grsincgaming;
    }

    /**
     * @return int
     */
    public function getGrsincgaming()
    {
        return $this->grsincgaming;
    }

    /**
     * @param int $grsincmembers
     */
    public function setGrsincmembers($grsincmembers)
    {
        $this->grsincmembers = $grsincmembers;
    }

    /**
     * @return int
     */
    public function getGrsincmembers()
    {
        return $this->grsincmembers;
    }

    /**
     * @param int $grsincother
     */
    public function setGrsincother($grsincother)
    {
        $this->grsincother = $grsincother;
    }

    /**
     * @return int
     */
    public function getGrsincother()
    {
        return $this->grsincother;
    }

    /**
     * @param int $grsrcptsadmissn509
     */
    public function setGrsrcptsadmissn509($grsrcptsadmissn509)
    {
        $this->grsrcptsadmissn509 = $grsrcptsadmissn509;
    }

    /**
     * @return int
     */
    public function getGrsrcptsadmissn509()
    {
        return $this->grsrcptsadmissn509;
    }

    /**
     * @param int $grsrcptspublicuse
     */
    public function setGrsrcptspublicuse($grsrcptspublicuse)
    {
        $this->grsrcptspublicuse = $grsrcptspublicuse;
    }

    /**
     * @return int
     */
    public function getGrsrcptspublicuse()
    {
        return $this->grsrcptspublicuse;
    }

    /**
     * @param int $grsrcptsrelated170
     */
    public function setGrsrcptsrelated170($grsrcptsrelated170)
    {
        $this->grsrcptsrelated170 = $grsrcptsrelated170;
    }

    /**
     * @return int
     */
    public function getGrsrcptsrelated170()
    {
        return $this->grsrcptsrelated170;
    }

    /**
     * @param int $grsrntsprsnl
     */
    public function setGrsrntsprsnl($grsrntsprsnl)
    {
        $this->grsrntsprsnl = $grsrntsprsnl;
    }

    /**
     * @return int
     */
    public function getGrsrntsprsnl()
    {
        return $this->grsrntsprsnl;
    }

    /**
     * @param int $grsrntsreal
     */
    public function setGrsrntsreal($grsrntsreal)
    {
        $this->grsrntsreal = $grsrntsreal;
    }

    /**
     * @return int
     */
    public function getGrsrntsreal()
    {
        return $this->grsrntsreal;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $initiationfees
     */
    public function setInitiationfees($initiationfees)
    {
        $this->initiationfees = $initiationfees;
    }

    /**
     * @return int
     */
    public function getInitiationfees()
    {
        return $this->initiationfees;
    }

    /**
     * @param int $invstmntinc
     */
    public function setInvstmntinc($invstmntinc)
    {
        $this->invstmntinc = $invstmntinc;
    }

    /**
     * @return int
     */
    public function getInvstmntinc()
    {
        return $this->invstmntinc;
    }

    /**
     * @param int $lesscstofgoods
     */
    public function setLesscstofgoods($lesscstofgoods)
    {
        $this->lesscstofgoods = $lesscstofgoods;
    }

    /**
     * @return int
     */
    public function getLesscstofgoods()
    {
        return $this->lesscstofgoods;
    }

    /**
     * @param int $lessdirfndrsng
     */
    public function setLessdirfndrsng($lessdirfndrsng)
    {
        $this->lessdirfndrsng = $lessdirfndrsng;
    }

    /**
     * @return int
     */
    public function getLessdirfndrsng()
    {
        return $this->lessdirfndrsng;
    }

    /**
     * @param int $lessdirgaming
     */
    public function setLessdirgaming($lessdirgaming)
    {
        $this->lessdirgaming = $lessdirgaming;
    }

    /**
     * @return int
     */
    public function getLessdirgaming()
    {
        return $this->lessdirgaming;
    }

    /**
     * @param int $miscrevtot11e
     */
    public function setMiscrevtot11e($miscrevtot11e)
    {
        $this->miscrevtot11e = $miscrevtot11e;
    }

    /**
     * @return int
     */
    public function getMiscrevtot11e()
    {
        return $this->miscrevtot11e;
    }

    /**
     * @param int $netgnls
     */
    public function setNetgnls($netgnls)
    {
        $this->netgnls = $netgnls;
    }

    /**
     * @return int
     */
    public function getNetgnls()
    {
        return $this->netgnls;
    }

    /**
     * @param int $netincfndrsng
     */
    public function setNetincfndrsng($netincfndrsng)
    {
        $this->netincfndrsng = $netincfndrsng;
    }

    /**
     * @return int
     */
    public function getNetincfndrsng()
    {
        return $this->netincfndrsng;
    }

    /**
     * @param int $netincgaming
     */
    public function setNetincgaming($netincgaming)
    {
        $this->netincgaming = $netincgaming;
    }

    /**
     * @return int
     */
    public function getNetincgaming()
    {
        return $this->netincgaming;
    }

    /**
     * @param int $netincsales
     */
    public function setNetincsales($netincsales)
    {
        $this->netincsales = $netincsales;
    }

    /**
     * @return int
     */
    public function getNetincsales()
    {
        return $this->netincsales;
    }

    /**
     * @param int $netrntlinc
     */
    public function setNetrntlinc($netrntlinc)
    {
        $this->netrntlinc = $netrntlinc;
    }

    /**
     * @return int
     */
    public function getNetrntlinc()
    {
        return $this->netrntlinc;
    }

    /**
     * @param string $nonpfrea
     */
    public function setNonpfrea($nonpfrea)
    {
        $this->nonpfrea = $nonpfrea;
    }

    /**
     * @return string
     */
    public function getNonpfrea()
    {
        return $this->nonpfrea;
    }

    /**
     * @param int $othrsalwages
     */
    public function setOthrsalwages($othrsalwages)
    {
        $this->othrsalwages = $othrsalwages;
    }

    /**
     * @return int
     */
    public function getOthrsalwages()
    {
        return $this->othrsalwages;
    }

    /**
     * @param int $payrolltx
     */
    public function setPayrolltx($payrolltx)
    {
        $this->payrolltx = $payrolltx;
    }

    /**
     * @return int
     */
    public function getPayrolltx()
    {
        return $this->payrolltx;
    }

    /**
     * @param int $profndraising
     */
    public function setProfndraising($profndraising)
    {
        $this->profndraising = $profndraising;
    }

    /**
     * @return int
     */
    public function getProfndraising()
    {
        return $this->profndraising;
    }

    /**
     * @param int $retainedearnend
     */
    public function setRetainedearnend($retainedearnend)
    {
        $this->retainedearnend = $retainedearnend;
    }

    /**
     * @return int
     */
    public function getRetainedearnend()
    {
        return $this->retainedearnend;
    }

    /**
     * @param int $rntlexpnsprsnl
     */
    public function setRntlexpnsprsnl($rntlexpnsprsnl)
    {
        $this->rntlexpnsprsnl = $rntlexpnsprsnl;
    }

    /**
     * @return int
     */
    public function getRntlexpnsprsnl()
    {
        return $this->rntlexpnsprsnl;
    }

    /**
     * @param int $rntlexpnsreal
     */
    public function setRntlexpnsreal($rntlexpnsreal)
    {
        $this->rntlexpnsreal = $rntlexpnsreal;
    }

    /**
     * @return int
     */
    public function getRntlexpnsreal()
    {
        return $this->rntlexpnsreal;
    }

    /**
     * @param int $rntlincprsnl
     */
    public function setRntlincprsnl($rntlincprsnl)
    {
        $this->rntlincprsnl = $rntlincprsnl;
    }

    /**
     * @return int
     */
    public function getRntlincprsnl()
    {
        return $this->rntlincprsnl;
    }

    /**
     * @param int $rntlincreal
     */
    public function setRntlincreal($rntlincreal)
    {
        $this->rntlincreal = $rntlincreal;
    }

    /**
     * @return int
     */
    public function getRntlincreal()
    {
        return $this->rntlincreal;
    }

    /**
     * @param int $royaltsinc
     */
    public function setRoyaltsinc($royaltsinc)
    {
        $this->royaltsinc = $royaltsinc;
    }

    /**
     * @return int
     */
    public function getRoyaltsinc()
    {
        return $this->royaltsinc;
    }

    /**
     * @param int $secrdmrtgsend
     */
    public function setSecrdmrtgsend($secrdmrtgsend)
    {
        $this->secrdmrtgsend = $secrdmrtgsend;
    }

    /**
     * @return int
     */
    public function getSecrdmrtgsend()
    {
        return $this->secrdmrtgsend;
    }

    /**
     * @param int $srvcsval170
     */
    public function setSrvcsval170($srvcsval170)
    {
        $this->srvcsval170 = $srvcsval170;
    }

    /**
     * @return int
     */
    public function getSrvcsval170()
    {
        return $this->srvcsval170;
    }

    /**
     * @param int $srvcsval509
     */
    public function setSrvcsval509($srvcsval509)
    {
        $this->srvcsval509 = $srvcsval509;
    }

    /**
     * @return int
     */
    public function getSrvcsval509()
    {
        return $this->srvcsval509;
    }

    /**
     * @param string $subseccd
     */
    public function setSubseccd($subseccd)
    {
        $this->subseccd = $subseccd;
    }

    /**
     * @return string
     */
    public function getSubseccd()
    {
        return $this->subseccd;
    }

    /**
     * @param int $subtotsuppinc509
     */
    public function setSubtotsuppinc509($subtotsuppinc509)
    {
        $this->subtotsuppinc509 = $subtotsuppinc509;
    }

    /**
     * @return int
     */
    public function getSubtotsuppinc509()
    {
        return $this->subtotsuppinc509;
    }

    /**
     * @param int $taxPrd
     */
    public function setTaxPrd($taxPrd)
    {
        $this->taxPrd = $taxPrd;
    }

    /**
     * @return int
     */
    public function getTaxPrd()
    {
        return $this->taxPrd;
    }

    /**
     * @param int $totassetsend
     */
    public function setTotassetsend($totassetsend)
    {
        $this->totassetsend = $totassetsend;
    }

    /**
     * @return int
     */
    public function getTotassetsend()
    {
        return $this->totassetsend;
    }

    /**
     * @param int $totcntrbgfts
     */
    public function setTotcntrbgfts($totcntrbgfts)
    {
        $this->totcntrbgfts = $totcntrbgfts;
    }

    /**
     * @return int
     */
    public function getTotcntrbgfts()
    {
        return $this->totcntrbgfts;
    }

    /**
     * @param int $totfuncexpns
     */
    public function setTotfuncexpns($totfuncexpns)
    {
        $this->totfuncexpns = $totfuncexpns;
    }

    /**
     * @return int
     */
    public function getTotfuncexpns()
    {
        return $this->totfuncexpns;
    }

    /**
     * @param int $totgftgrntrcvd509
     */
    public function setTotgftgrntrcvd509($totgftgrntrcvd509)
    {
        $this->totgftgrntrcvd509 = $totgftgrntrcvd509;
    }

    /**
     * @return int
     */
    public function getTotgftgrntrcvd509()
    {
        return $this->totgftgrntrcvd509;
    }

    /**
     * @param int $totliabend
     */
    public function setTotliabend($totliabend)
    {
        $this->totliabend = $totliabend;
    }

    /**
     * @return int
     */
    public function getTotliabend()
    {
        return $this->totliabend;
    }

    /**
     * @param int $totnetassetend
     */
    public function setTotnetassetend($totnetassetend)
    {
        $this->totnetassetend = $totnetassetend;
    }

    /**
     * @return int
     */
    public function getTotnetassetend()
    {
        return $this->totnetassetend;
    }

    /**
     * @param int $totprgmrevnue
     */
    public function setTotprgmrevnue($totprgmrevnue)
    {
        $this->totprgmrevnue = $totprgmrevnue;
    }

    /**
     * @return int
     */
    public function getTotprgmrevnue()
    {
        return $this->totprgmrevnue;
    }

    /**
     * @param int $totrevenue
     */
    public function setTotrevenue($totrevenue)
    {
        $this->totrevenue = $totrevenue;
    }

    /**
     * @return int
     */
    public function getTotrevenue()
    {
        return $this->totrevenue;
    }

    /**
     * @param int $totsupp509
     */
    public function setTotsupp509($totsupp509)
    {
        $this->totsupp509 = $totsupp509;
    }

    /**
     * @return int
     */
    public function getTotsupp509()
    {
        return $this->totsupp509;
    }

    /**
     * @param int $txexmptbndsend
     */
    public function setTxexmptbndsend($txexmptbndsend)
    {
        $this->txexmptbndsend = $txexmptbndsend;
    }

    /**
     * @return int
     */
    public function getTxexmptbndsend()
    {
        return $this->txexmptbndsend;
    }

    /**
     * @param int $txexmptbndsproceeds
     */
    public function setTxexmptbndsproceeds($txexmptbndsproceeds)
    {
        $this->txexmptbndsproceeds = $txexmptbndsproceeds;
    }

    /**
     * @return int
     */
    public function getTxexmptbndsproceeds()
    {
        return $this->txexmptbndsproceeds;
    }

    /**
     * @param int $txrevnuelevied170
     */
    public function setTxrevnuelevied170($txrevnuelevied170)
    {
        $this->txrevnuelevied170 = $txrevnuelevied170;
    }

    /**
     * @return int
     */
    public function getTxrevnuelevied170()
    {
        return $this->txrevnuelevied170;
    }

    /**
     * @param int $txrevnuelevied509
     */
    public function setTxrevnuelevied509($txrevnuelevied509)
    {
        $this->txrevnuelevied509 = $txrevnuelevied509;
    }

    /**
     * @return int
     */
    public function getTxrevnuelevied509()
    {
        return $this->txrevnuelevied509;
    }

    /**
     * @param string $unrelbusinccd
     */
    public function setUnrelbusinccd($unrelbusinccd)
    {
        $this->unrelbusinccd = $unrelbusinccd;
    }

    /**
     * @return string
     */
    public function getUnrelbusinccd()
    {
        return $this->unrelbusinccd;
    }

    /**
     * @param int $unsecurednotesend
     */
    public function setUnsecurednotesend($unsecurednotesend)
    {
        $this->unsecurednotesend = $unsecurednotesend;
    }

    /**
     * @return int
     */
    public function getUnsecurednotesend()
    {
        return $this->unsecurednotesend;
    }


}
