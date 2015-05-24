<?php

namespace Entity;

/**
 * Irs990ez
 *
 * @Table(name="irs_990ez", indexes={@Index(name="irs_990ez_ein_idx", columns={"ein"})})
 * @Entity
 */
class Irs990ez extends BaseEntity {
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
     * @Column(name="tax_pd", type="bigint", nullable=true)
     */
    private $taxPd;

    /**
     * @var integer
     *
     * @Column(name="subseccd", type="bigint", nullable=true)
     */
    private $subseccd;

    /**
     * @var integer
     *
     * @Column(name="totcntrbs", type="bigint", nullable=true)
     */
    private $totcntrbs;

    /**
     * @var integer
     *
     * @Column(name="prgmservrev", type="bigint", nullable=true)
     */
    private $prgmservrev;

    /**
     * @var integer
     *
     * @Column(name="duesassesmnts", type="bigint", nullable=true)
     */
    private $duesassesmnts;

    /**
     * @var integer
     *
     * @Column(name="othrinvstinc", type="bigint", nullable=true)
     */
    private $othrinvstinc;

    /**
     * @var integer
     *
     * @Column(name="grsamtsalesastothr", type="bigint", nullable=true)
     */
    private $grsamtsalesastothr;

    /**
     * @var integer
     *
     * @Column(name="basisalesexpnsothr", type="bigint", nullable=true)
     */
    private $basisalesexpnsothr;

    /**
     * @var integer
     *
     * @Column(name="gnsaleofastothr", type="bigint", nullable=true)
     */
    private $gnsaleofastothr;

    /**
     * @var integer
     *
     * @Column(name="grsincgaming", type="bigint", nullable=true)
     */
    private $grsincgaming;

    /**
     * @var integer
     *
     * @Column(name="grsrevnuefndrsng", type="bigint", nullable=true)
     */
    private $grsrevnuefndrsng;

    /**
     * @var integer
     *
     * @Column(name="direxpns", type="bigint", nullable=true)
     */
    private $direxpns;

    /**
     * @var integer
     *
     * @Column(name="netincfndrsng", type="bigint", nullable=true)
     */
    private $netincfndrsng;

    /**
     * @var integer
     *
     * @Column(name="grsalesminusret", type="bigint", nullable=true)
     */
    private $grsalesminusret;

    /**
     * @var integer
     *
     * @Column(name="costgoodsold", type="bigint", nullable=true)
     */
    private $costgoodsold;

    /**
     * @var integer
     *
     * @Column(name="grsprft", type="bigint", nullable=true)
     */
    private $grsprft;

    /**
     * @var integer
     *
     * @Column(name="othrevnue", type="bigint", nullable=true)
     */
    private $othrevnue;

    /**
     * @var integer
     *
     * @Column(name="totrevnue", type="bigint", nullable=true)
     */
    private $totrevnue;

    /**
     * @var integer
     *
     * @Column(name="totexpns", type="bigint", nullable=true)
     */
    private $totexpns;

    /**
     * @var integer
     *
     * @Column(name="totexcessyr", type="bigint", nullable=true)
     */
    private $totexcessyr;

    /**
     * @var integer
     *
     * @Column(name="othrchgsnetassetfnd", type="bigint", nullable=true)
     */
    private $othrchgsnetassetfnd;

    /**
     * @var integer
     *
     * @Column(name="networthend", type="bigint", nullable=true)
     */
    private $networthend;

    /**
     * @var integer
     *
     * @Column(name="totassetsend", type="bigint", nullable=true)
     */
    private $totassetsend;

    /**
     * @var integer
     *
     * @Column(name="totliabend", type="bigint", nullable=true)
     */
    private $totliabend;

    /**
     * @var integer
     *
     * @Column(name="totnetassetsend", type="bigint", nullable=true)
     */
    private $totnetassetsend;

    /**
     * @var string
     *
     * @Column(name="actvtynotprevrptcd", type="string", length=1, nullable=true)
     */
    private $actvtynotprevrptcd;

    /**
     * @var string
     *
     * @Column(name="chngsinorgcd", type="string", length=1, nullable=true)
     */
    private $chngsinorgcd;

    /**
     * @var string
     *
     * @Column(name="unrelbusincd", type="string", length=1, nullable=true)
     */
    private $unrelbusincd;

    /**
     * @var string
     *
     * @Column(name="filedf990tcd", type="string", length=1, nullable=true)
     */
    private $filedf990tcd;

    /**
     * @var string
     *
     * @Column(name="contractioncd", type="string", length=1, nullable=true)
     */
    private $contractioncd;

    /**
     * @var integer
     *
     * @Column(name="politicalexpend", type="bigint", nullable=true)
     */
    private $politicalexpend;

    /**
     * @var string
     *
     * @Column(name="filedf1120polcd", type="string", length=1, nullable=true)
     */
    private $filedf1120polcd;

    /**
     * @var string
     *
     * @Column(name="loanstoofficerscd", type="string", length=1, nullable=true)
     */
    private $loanstoofficerscd;

    /**
     * @var integer
     *
     * @Column(name="loanstoofficers", type="bigint", nullable=true)
     */
    private $loanstoofficers;

    /**
     * @var integer
     *
     * @Column(name="initiationfee", type="bigint", nullable=true)
     */
    private $initiationfee;

    /**
     * @var integer
     *
     * @Column(name="grspublicrcpts", type="bigint", nullable=true)
     */
    private $grspublicrcpts;

    /**
     * @var string
     *
     * @Column(name="s4958excessbenefcd", type="string", length=1, nullable=true)
     */
    private $s4958excessbenefcd;

    /**
     * @var string
     *
     * @Column(name="prohibtdtxshltrcd", type="string", length=1, nullable=true)
     */
    private $prohibtdtxshltrcd;

    /**
     * @var integer
     *
     * @Column(name="nonpfrea", type="bigint", nullable=true)
     */
    private $nonpfrea;

    /**
     * @var integer
     *
     * @Column(name="totnooforgscnt", type="bigint", nullable=true)
     */
    private $totnooforgscnt;

    /**
     * @var integer
     *
     * @Column(name="totsupport", type="bigint", nullable=true)
     */
    private $totsupport;

    /**
     * @var integer
     *
     * @Column(name="gftgrntsrcvd170", type="bigint", nullable=true)
     */
    private $gftgrntsrcvd170;

    /**
     * @var integer
     *
     * @Column(name="txrevnuelevied170", type="bigint", nullable=true)
     */
    private $txrevnuelevied170;

    /**
     * @var integer
     *
     * @Column(name="srvcsval170", type="bigint", nullable=true)
     */
    private $srvcsval170;

    /**
     * @var integer
     *
     * @Column(name="pubsuppsubtot170", type="bigint", nullable=true)
     */
    private $pubsuppsubtot170;

    /**
     * @var integer
     *
     * @Column(name="exceeds2pct170", type="bigint", nullable=true)
     */
    private $exceeds2pct170;

    /**
     * @var integer
     *
     * @Column(name="pubsupplesspct170", type="bigint", nullable=true)
     */
    private $pubsupplesspct170;

    /**
     * @var integer
     *
     * @Column(name="samepubsuppsubtot170", type="bigint", nullable=true)
     */
    private $samepubsuppsubtot170;

    /**
     * @var integer
     *
     * @Column(name="grsinc170", type="bigint", nullable=true)
     */
    private $grsinc170;

    /**
     * @var integer
     *
     * @Column(name="netincunreltd170", type="bigint", nullable=true)
     */
    private $netincunreltd170;

    /**
     * @var integer
     *
     * @Column(name="othrinc170", type="bigint", nullable=true)
     */
    private $othrinc170;

    /**
     * @var integer
     *
     * @Column(name="totsupp170", type="bigint", nullable=true)
     */
    private $totsupp170;

    /**
     * @var integer
     *
     * @Column(name="grsrcptsrelated170", type="bigint", nullable=true)
     */
    private $grsrcptsrelated170;

    /**
     * @var integer
     *
     * @Column(name="totgftgrntrcvd509", type="bigint", nullable=true)
     */
    private $totgftgrntrcvd509;

    /**
     * @var integer
     *
     * @Column(name="grsrcptsadmissn509", type="bigint", nullable=true)
     */
    private $grsrcptsadmissn509;

    /**
     * @var integer
     *
     * @Column(name="grsrcptsactivities509", type="bigint", nullable=true)
     */
    private $grsrcptsactivities509;

    /**
     * @var integer
     *
     * @Column(name="txrevnuelevied509", type="bigint", nullable=true)
     */
    private $txrevnuelevied509;

    /**
     * @var integer
     *
     * @Column(name="srvcsval509", type="bigint", nullable=true)
     */
    private $srvcsval509;

    /**
     * @var integer
     *
     * @Column(name="pubsuppsubtot509", type="bigint", nullable=true)
     */
    private $pubsuppsubtot509;

    /**
     * @var integer
     *
     * @Column(name="rcvdfrmdisqualsub509", type="bigint", nullable=true)
     */
    private $rcvdfrmdisqualsub509;

    /**
     * @var integer
     *
     * @Column(name="exceeds1pct509", type="bigint", nullable=true)
     */
    private $exceeds1pct509;

    /**
     * @var integer
     *
     * @Column(name="subtotpub509", type="bigint", nullable=true)
     */
    private $subtotpub509;

    /**
     * @var integer
     *
     * @Column(name="pubsupplesub509", type="bigint", nullable=true)
     */
    private $pubsupplesub509;

    /**
     * @var integer
     *
     * @Column(name="samepubsuppsubtot509", type="bigint", nullable=true)
     */
    private $samepubsuppsubtot509;

    /**
     * @var integer
     *
     * @Column(name="grsinc509", type="bigint", nullable=true)
     */
    private $grsinc509;

    /**
     * @var integer
     *
     * @Column(name="unreltxincls511tx509", type="bigint", nullable=true)
     */
    private $unreltxincls511tx509;

    /**
     * @var integer
     *
     * @Column(name="subtotsuppinc509", type="bigint", nullable=true)
     */
    private $subtotsuppinc509;

    /**
     * @var integer
     *
     * @Column(name="netincunrelatd509", type="bigint", nullable=true)
     */
    private $netincunrelatd509;

    /**
     * @var integer
     *
     * @Column(name="othrinc509", type="bigint", nullable=true)
     */
    private $othrinc509;

    /**
     * @var integer
     *
     * @Column(name="totsupp509", type="bigint", nullable=true)
     */
    private $totsupp509;

    /**
     * @param string $actvtynotprevrptcd
     */
    public function setActvtynotprevrptcd($actvtynotprevrptcd)
    {
        $this->actvtynotprevrptcd = $actvtynotprevrptcd;
    }

    /**
     * @return string
     */
    public function getActvtynotprevrptcd()
    {
        return $this->actvtynotprevrptcd;
    }

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
     * @param string $chngsinorgcd
     */
    public function setChngsinorgcd($chngsinorgcd)
    {
        $this->chngsinorgcd = $chngsinorgcd;
    }

    /**
     * @return string
     */
    public function getChngsinorgcd()
    {
        return $this->chngsinorgcd;
    }

    /**
     * @param string $contractioncd
     */
    public function setContractioncd($contractioncd)
    {
        $this->contractioncd = $contractioncd;
    }

    /**
     * @return string
     */
    public function getContractioncd()
    {
        return $this->contractioncd;
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
     * @param int $exceeds1pct509
     */
    public function setExceeds1pct509($exceeds1pct509)
    {
        $this->exceeds1pct509 = $exceeds1pct509;
    }

    /**
     * @return int
     */
    public function getExceeds1pct509()
    {
        return $this->exceeds1pct509;
    }

    /**
     * @param int $exceeds2pct170
     */
    public function setExceeds2pct170($exceeds2pct170)
    {
        $this->exceeds2pct170 = $exceeds2pct170;
    }

    /**
     * @return int
     */
    public function getExceeds2pct170()
    {
        return $this->exceeds2pct170;
    }

    /**
     * @param string $filedf1120polcd
     */
    public function setFiledf1120polcd($filedf1120polcd)
    {
        $this->filedf1120polcd = $filedf1120polcd;
    }

    /**
     * @return string
     */
    public function getFiledf1120polcd()
    {
        return $this->filedf1120polcd;
    }

    /**
     * @param string $filedf990tcd
     */
    public function setFiledf990tcd($filedf990tcd)
    {
        $this->filedf990tcd = $filedf990tcd;
    }

    /**
     * @return string
     */
    public function getFiledf990tcd()
    {
        return $this->filedf990tcd;
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
     * @param int $grsinc509
     */
    public function setGrsinc509($grsinc509)
    {
        $this->grsinc509 = $grsinc509;
    }

    /**
     * @return int
     */
    public function getGrsinc509()
    {
        return $this->grsinc509;
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
     * @param int $grsrcptsactivities509
     */
    public function setGrsrcptsactivities509($grsrcptsactivities509)
    {
        $this->grsrcptsactivities509 = $grsrcptsactivities509;
    }

    /**
     * @return int
     */
    public function getGrsrcptsactivities509()
    {
        return $this->grsrcptsactivities509;
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
     * @param int $loanstoofficers
     */
    public function setLoanstoofficers($loanstoofficers)
    {
        $this->loanstoofficers = $loanstoofficers;
    }

    /**
     * @return int
     */
    public function getLoanstoofficers()
    {
        return $this->loanstoofficers;
    }

    /**
     * @param string $loanstoofficerscd
     */
    public function setLoanstoofficerscd($loanstoofficerscd)
    {
        $this->loanstoofficerscd = $loanstoofficerscd;
    }

    /**
     * @return string
     */
    public function getLoanstoofficerscd()
    {
        return $this->loanstoofficerscd;
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
     * @param int $netincunrelatd509
     */
    public function setNetincunrelatd509($netincunrelatd509)
    {
        $this->netincunrelatd509 = $netincunrelatd509;
    }

    /**
     * @return int
     */
    public function getNetincunrelatd509()
    {
        return $this->netincunrelatd509;
    }

    /**
     * @param int $netincunreltd170
     */
    public function setNetincunreltd170($netincunreltd170)
    {
        $this->netincunreltd170 = $netincunreltd170;
    }

    /**
     * @return int
     */
    public function getNetincunreltd170()
    {
        return $this->netincunreltd170;
    }

    /**
     * @param int $networthend
     */
    public function setNetworthend($networthend)
    {
        $this->networthend = $networthend;
    }

    /**
     * @return int
     */
    public function getNetworthend()
    {
        return $this->networthend;
    }

    /**
     * @param int $nonpfrea
     */
    public function setNonpfrea($nonpfrea)
    {
        $this->nonpfrea = $nonpfrea;
    }

    /**
     * @return int
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
     * @param int $othrinc170
     */
    public function setOthrinc170($othrinc170)
    {
        $this->othrinc170 = $othrinc170;
    }

    /**
     * @return int
     */
    public function getOthrinc170()
    {
        return $this->othrinc170;
    }

    /**
     * @param int $othrinc509
     */
    public function setOthrinc509($othrinc509)
    {
        $this->othrinc509 = $othrinc509;
    }

    /**
     * @return int
     */
    public function getOthrinc509()
    {
        return $this->othrinc509;
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
     * @param int $politicalexpend
     */
    public function setPoliticalexpend($politicalexpend)
    {
        $this->politicalexpend = $politicalexpend;
    }

    /**
     * @return int
     */
    public function getPoliticalexpend()
    {
        return $this->politicalexpend;
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
     * @param string $prohibtdtxshltrcd
     */
    public function setProhibtdtxshltrcd($prohibtdtxshltrcd)
    {
        $this->prohibtdtxshltrcd = $prohibtdtxshltrcd;
    }

    /**
     * @return string
     */
    public function getProhibtdtxshltrcd()
    {
        return $this->prohibtdtxshltrcd;
    }

    /**
     * @param int $pubsupplesspct170
     */
    public function setPubsupplesspct170($pubsupplesspct170)
    {
        $this->pubsupplesspct170 = $pubsupplesspct170;
    }

    /**
     * @return int
     */
    public function getPubsupplesspct170()
    {
        return $this->pubsupplesspct170;
    }

    /**
     * @param int $pubsupplesub509
     */
    public function setPubsupplesub509($pubsupplesub509)
    {
        $this->pubsupplesub509 = $pubsupplesub509;
    }

    /**
     * @return int
     */
    public function getPubsupplesub509()
    {
        return $this->pubsupplesub509;
    }

    /**
     * @param int $pubsuppsubtot170
     */
    public function setPubsuppsubtot170($pubsuppsubtot170)
    {
        $this->pubsuppsubtot170 = $pubsuppsubtot170;
    }

    /**
     * @return int
     */
    public function getPubsuppsubtot170()
    {
        return $this->pubsuppsubtot170;
    }

    /**
     * @param int $pubsuppsubtot509
     */
    public function setPubsuppsubtot509($pubsuppsubtot509)
    {
        $this->pubsuppsubtot509 = $pubsuppsubtot509;
    }

    /**
     * @return int
     */
    public function getPubsuppsubtot509()
    {
        return $this->pubsuppsubtot509;
    }

    /**
     * @param int $rcvdfrmdisqualsub509
     */
    public function setRcvdfrmdisqualsub509($rcvdfrmdisqualsub509)
    {
        $this->rcvdfrmdisqualsub509 = $rcvdfrmdisqualsub509;
    }

    /**
     * @return int
     */
    public function getRcvdfrmdisqualsub509()
    {
        return $this->rcvdfrmdisqualsub509;
    }

    /**
     * @param string $s4958excessbenefcd
     */
    public function setS4958excessbenefcd($s4958excessbenefcd)
    {
        $this->s4958excessbenefcd = $s4958excessbenefcd;
    }

    /**
     * @return string
     */
    public function getS4958excessbenefcd()
    {
        return $this->s4958excessbenefcd;
    }

    /**
     * @param int $samepubsuppsubtot170
     */
    public function setSamepubsuppsubtot170($samepubsuppsubtot170)
    {
        $this->samepubsuppsubtot170 = $samepubsuppsubtot170;
    }

    /**
     * @return int
     */
    public function getSamepubsuppsubtot170()
    {
        return $this->samepubsuppsubtot170;
    }

    /**
     * @param int $samepubsuppsubtot509
     */
    public function setSamepubsuppsubtot509($samepubsuppsubtot509)
    {
        $this->samepubsuppsubtot509 = $samepubsuppsubtot509;
    }

    /**
     * @return int
     */
    public function getSamepubsuppsubtot509()
    {
        return $this->samepubsuppsubtot509;
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
     * @param int $subseccd
     */
    public function setSubseccd($subseccd)
    {
        $this->subseccd = $subseccd;
    }

    /**
     * @return int
     */
    public function getSubseccd()
    {
        return $this->subseccd;
    }

    /**
     * @param int $subtotpub509
     */
    public function setSubtotpub509($subtotpub509)
    {
        $this->subtotpub509 = $subtotpub509;
    }

    /**
     * @return int
     */
    public function getSubtotpub509()
    {
        return $this->subtotpub509;
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
     * @param int $taxPd
     */
    public function setTaxPd($taxPd)
    {
        $this->taxPd = $taxPd;
    }

    /**
     * @return int
     */
    public function getTaxPd()
    {
        return $this->taxPd;
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
     * @param int $totnooforgscnt
     */
    public function setTotnooforgscnt($totnooforgscnt)
    {
        $this->totnooforgscnt = $totnooforgscnt;
    }

    /**
     * @return int
     */
    public function getTotnooforgscnt()
    {
        return $this->totnooforgscnt;
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
     * @param int $totsupp170
     */
    public function setTotsupp170($totsupp170)
    {
        $this->totsupp170 = $totsupp170;
    }

    /**
     * @return int
     */
    public function getTotsupp170()
    {
        return $this->totsupp170;
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
     * @param int $totsupport
     */
    public function setTotsupport($totsupport)
    {
        $this->totsupport = $totsupport;
    }

    /**
     * @return int
     */
    public function getTotsupport()
    {
        return $this->totsupport;
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

    /**
     * @param int $unreltxincls511tx509
     */
    public function setUnreltxincls511tx509($unreltxincls511tx509)
    {
        $this->unreltxincls511tx509 = $unreltxincls511tx509;
    }

    /**
     * @return int
     */
    public function getUnreltxincls511tx509()
    {
        return $this->unreltxincls511tx509;
    }

}
