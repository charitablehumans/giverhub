<?php

namespace Entity;

/**
 * Irs2012990ez
 *
 * @Table(name="irs_2012_990ez", indexes={@Index(name="irs_2012_990ez_ein_idx", columns={"ein"})})
 * @Entity
 */
class Irs2012990ez extends BaseEntity {
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
     * @Column(name="ein", type="string", length=9, nullable=true)
     */
    private $ein;

    /**
     * @var integer
     *
     * @Column(name="tax_prd", type="integer", nullable=true)
     */
    private $taxPrd;

    /**
     * @var string
     *
     * @Column(name="subseccd", type="string", length=2, nullable=true)
     */
    private $subseccd;

    /**
     * @var integer
     *
     * @Column(name="totcntrbs", type="integer", nullable=true)
     */
    private $totcntrbs;

    /**
     * @var integer
     *
     * @Column(name="prgmservrev", type="integer", nullable=true)
     */
    private $prgmservrev;

    /**
     * @var integer
     *
     * @Column(name="duesassesmnts", type="integer", nullable=true)
     */
    private $duesassesmnts;

    /**
     * @var integer
     *
     * @Column(name="othrinvstinc", type="integer", nullable=true)
     */
    private $othrinvstinc;

    /**
     * @var integer
     *
     * @Column(name="grsamtsalesastothr", type="integer", nullable=true)
     */
    private $grsamtsalesastothr;

    /**
     * @var integer
     *
     * @Column(name="basisalesexpnsothr", type="integer", nullable=true)
     */
    private $basisalesexpnsothr;

    /**
     * @var integer
     *
     * @Column(name="gnsaleofastothr", type="integer", nullable=true)
     */
    private $gnsaleofastothr;

    /**
     * @var integer
     *
     * @Column(name="grsincgaming", type="integer", nullable=true)
     */
    private $grsincgaming;

    /**
     * @var integer
     *
     * @Column(name="grsrevnuefndrsng", type="integer", nullable=true)
     */
    private $grsrevnuefndrsng;

    /**
     * @var integer
     *
     * @Column(name="direxpns", type="integer", nullable=true)
     */
    private $direxpns;

    /**
     * @var integer
     *
     * @Column(name="netincfndrsng", type="integer", nullable=true)
     */
    private $netincfndrsng;

    /**
     * @var integer
     *
     * @Column(name="grsalesminusret", type="integer", nullable=true)
     */
    private $grsalesminusret;

    /**
     * @var integer
     *
     * @Column(name="costgoodsold", type="integer", nullable=true)
     */
    private $costgoodsold;

    /**
     * @var integer
     *
     * @Column(name="grsprft", type="integer", nullable=true)
     */
    private $grsprft;

    /**
     * @var integer
     *
     * @Column(name="othrevnue", type="integer", nullable=true)
     */
    private $othrevnue;

    /**
     * @var integer
     *
     * @Column(name="totrevnue", type="integer", nullable=true)
     */
    private $totrevnue;

    /**
     * @var integer
     *
     * @Column(name="totexpns", type="integer", nullable=true)
     */
    private $totexpns;

    /**
     * @var integer
     *
     * @Column(name="totexcessyr", type="integer", nullable=true)
     */
    private $totexcessyr;

    /**
     * @var integer
     *
     * @Column(name="othrchgsnetassetfnd", type="integer", nullable=true)
     */
    private $othrchgsnetassetfnd;

    /**
     * @var integer
     *
     * @Column(name="totassetsend", type="integer", nullable=true)
     */
    private $totassetsend;

    /**
     * @var integer
     *
     * @Column(name="totliabend", type="integer", nullable=true)
     */
    private $totliabend;

    /**
     * @var integer
     *
     * @Column(name="totnetassetsend", type="integer", nullable=true)
     */
    private $totnetassetsend;

    /**
     * @var string
     *
     * @Column(name="unrelbusincd", type="string", length=1, nullable=true)
     */
    private $unrelbusincd;

    /**
     * @var integer
     *
     * @Column(name="initiationfee", type="integer", nullable=true)
     */
    private $initiationfee;

    /**
     * @var integer
     *
     * @Column(name="grspublicrcpts", type="integer", nullable=true)
     */
    private $grspublicrcpts;

    /**
     * @var string
     *
     * @Column(name="nonpfrea", type="string", length=2, nullable=true)
     */
    private $nonpfrea;

    /**
     * @var integer
     *
     * @Column(name="gftgrntrcvd170", type="integer", nullable=true)
     */
    private $gftgrntrcvd170;

    /**
     * @var integer
     *
     * @Column(name="txrevnuelevied170", type="integer", nullable=true)
     */
    private $txrevnuelevied170;

    /**
     * @var integer
     *
     * @Column(name="srvcsval170", type="integer", nullable=true)
     */
    private $srvcsval170;

    /**
     * @var integer
     *
     * @Column(name="grsinc170", type="integer", nullable=true)
     */
    private $grsinc170;

    /**
     * @var integer
     *
     * @Column(name="grsrcptsrelatd170", type="integer", nullable=true)
     */
    private $grsrcptsrelatd170;

    /**
     * @var integer
     *
     * @Column(name="totgftgrntrcvd509", type="integer", nullable=true)
     */
    private $totgftgrntrcvd509;

    /**
     * @var integer
     *
     * @Column(name="grsrcptsadmiss509", type="integer", nullable=true)
     */
    private $grsrcptsadmiss509;

    /**
     * @var integer
     *
     * @Column(name="txrevnuelevied509", type="integer", nullable=true)
     */
    private $txrevnuelevied509;

    /**
     * @var integer
     *
     * @Column(name="srvcsval509", type="integer", nullable=true)
     */
    private $srvcsval509;

    /**
     * @var integer
     *
     * @Column(name="subtotsuppinc509", type="integer", nullable=true)
     */
    private $subtotsuppinc509;

    /**
     * @var integer
     *
     * @Column(name="totsupp509", type="integer", nullable=true)
     */
    private $totsupp509;

    /**
     * @param int $basisalesexpnsothr
     */
    public function setBasisalesexpnsothr($basisalesexpnsothr)
    {
        $this->basisalesexpnsothr = $basisalesexpnsothr;
    }

    /**
     * @return int
     */
    public function getBasisalesexpnsothr()
    {
        return $this->basisalesexpnsothr;
    }

    /**
     * @param int $costgoodsold
     */
    public function setCostgoodsold($costgoodsold)
    {
        $this->costgoodsold = $costgoodsold;
    }

    /**
     * @return int
     */
    public function getCostgoodsold()
    {
        return $this->costgoodsold;
    }

    /**
     * @param int $direxpns
     */
    public function setDirexpns($direxpns)
    {
        $this->direxpns = $direxpns;
    }

    /**
     * @return int
     */
    public function getDirexpns()
    {
        return $this->direxpns;
    }

    /**
     * @param int $duesassesmnts
     */
    public function setDuesassesmnts($duesassesmnts)
    {
        $this->duesassesmnts = $duesassesmnts;
    }

    /**
     * @return int
     */
    public function getDuesassesmnts()
    {
        return $this->duesassesmnts;
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
     * @param int $gftgrntrcvd170
     */
    public function setGftgrntrcvd170($gftgrntrcvd170)
    {
        $this->gftgrntrcvd170 = $gftgrntrcvd170;
    }

    /**
     * @return int
     */
    public function getGftgrntrcvd170()
    {
        return $this->gftgrntrcvd170;
    }

    /**
     * @param int $gnsaleofastothr
     */
    public function setGnsaleofastothr($gnsaleofastothr)
    {
        $this->gnsaleofastothr = $gnsaleofastothr;
    }

    /**
     * @return int
     */
    public function getGnsaleofastothr()
    {
        return $this->gnsaleofastothr;
    }

    /**
     * @param int $grsalesminusret
     */
    public function setGrsalesminusret($grsalesminusret)
    {
        $this->grsalesminusret = $grsalesminusret;
    }

    /**
     * @return int
     */
    public function getGrsalesminusret()
    {
        return $this->grsalesminusret;
    }

    /**
     * @param int $grsamtsalesastothr
     */
    public function setGrsamtsalesastothr($grsamtsalesastothr)
    {
        $this->grsamtsalesastothr = $grsamtsalesastothr;
    }

    /**
     * @return int
     */
    public function getGrsamtsalesastothr()
    {
        return $this->grsamtsalesastothr;
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
     * @param int $grsprft
     */
    public function setGrsprft($grsprft)
    {
        $this->grsprft = $grsprft;
    }

    /**
     * @return int
     */
    public function getGrsprft()
    {
        return $this->grsprft;
    }

    /**
     * @param int $grspublicrcpts
     */
    public function setGrspublicrcpts($grspublicrcpts)
    {
        $this->grspublicrcpts = $grspublicrcpts;
    }

    /**
     * @return int
     */
    public function getGrspublicrcpts()
    {
        return $this->grspublicrcpts;
    }

    /**
     * @param int $grsrcptsadmiss509
     */
    public function setGrsrcptsadmiss509($grsrcptsadmiss509)
    {
        $this->grsrcptsadmiss509 = $grsrcptsadmiss509;
    }

    /**
     * @return int
     */
    public function getGrsrcptsadmiss509()
    {
        return $this->grsrcptsadmiss509;
    }

    /**
     * @param int $grsrcptsrelatd170
     */
    public function setGrsrcptsrelatd170($grsrcptsrelatd170)
    {
        $this->grsrcptsrelatd170 = $grsrcptsrelatd170;
    }

    /**
     * @return int
     */
    public function getGrsrcptsrelatd170()
    {
        return $this->grsrcptsrelatd170;
    }

    /**
     * @param int $grsrevnuefndrsng
     */
    public function setGrsrevnuefndrsng($grsrevnuefndrsng)
    {
        $this->grsrevnuefndrsng = $grsrevnuefndrsng;
    }

    /**
     * @return int
     */
    public function getGrsrevnuefndrsng()
    {
        return $this->grsrevnuefndrsng;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $initiationfee
     */
    public function setInitiationfee($initiationfee)
    {
        $this->initiationfee = $initiationfee;
    }

    /**
     * @return int
     */
    public function getInitiationfee()
    {
        return $this->initiationfee;
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
     * @param int $othrchgsnetassetfnd
     */
    public function setOthrchgsnetassetfnd($othrchgsnetassetfnd)
    {
        $this->othrchgsnetassetfnd = $othrchgsnetassetfnd;
    }

    /**
     * @return int
     */
    public function getOthrchgsnetassetfnd()
    {
        return $this->othrchgsnetassetfnd;
    }

    /**
     * @param int $othrevnue
     */
    public function setOthrevnue($othrevnue)
    {
        $this->othrevnue = $othrevnue;
    }

    /**
     * @return int
     */
    public function getOthrevnue()
    {
        return $this->othrevnue;
    }

    /**
     * @param int $othrinvstinc
     */
    public function setOthrinvstinc($othrinvstinc)
    {
        $this->othrinvstinc = $othrinvstinc;
    }

    /**
     * @return int
     */
    public function getOthrinvstinc()
    {
        return $this->othrinvstinc;
    }

    /**
     * @param int $prgmservrev
     */
    public function setPrgmservrev($prgmservrev)
    {
        $this->prgmservrev = $prgmservrev;
    }

    /**
     * @return int
     */
    public function getPrgmservrev()
    {
        return $this->prgmservrev;
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
     * @param int $totcntrbs
     */
    public function setTotcntrbs($totcntrbs)
    {
        $this->totcntrbs = $totcntrbs;
    }

    /**
     * @return int
     */
    public function getTotcntrbs()
    {
        return $this->totcntrbs;
    }

    /**
     * @param int $totexcessyr
     */
    public function setTotexcessyr($totexcessyr)
    {
        $this->totexcessyr = $totexcessyr;
    }

    /**
     * @return int
     */
    public function getTotexcessyr()
    {
        return $this->totexcessyr;
    }

    /**
     * @param int $totexpns
     */
    public function setTotexpns($totexpns)
    {
        $this->totexpns = $totexpns;
    }

    /**
     * @return int
     */
    public function getTotexpns()
    {
        return $this->totexpns;
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
     * @param int $totnetassetsend
     */
    public function setTotnetassetsend($totnetassetsend)
    {
        $this->totnetassetsend = $totnetassetsend;
    }

    /**
     * @return int
     */
    public function getTotnetassetsend()
    {
        return $this->totnetassetsend;
    }

    /**
     * @param int $totrevnue
     */
    public function setTotrevnue($totrevnue)
    {
        $this->totrevnue = $totrevnue;
    }

    /**
     * @return int
     */
    public function getTotrevnue()
    {
        return $this->totrevnue;
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
     * @param string $unrelbusincd
     */
    public function setUnrelbusincd($unrelbusincd)
    {
        $this->unrelbusincd = $unrelbusincd;
    }

    /**
     * @return string
     */
    public function getUnrelbusincd()
    {
        return $this->unrelbusincd;
    }


}
