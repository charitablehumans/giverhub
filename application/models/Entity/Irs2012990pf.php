<?php

namespace Entity;

/**
 * Irs2012990pf
 *
 * @Table(name="irs_2012_990pf", indexes={@Index(name="irs_2012_990pf_ein_idx", columns={"ein"})})
 * @Entity
 */
class Irs2012990pf extends BaseEntity {
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
     * @var integer
     *
     * @Column(name="eostatus", type="bigint", nullable=false)
     */
    private $eostatus;

    /**
     * @var integer
     *
     * @Column(name="tax_yr", type="bigint", nullable=false)
     */
    private $taxYr;

    /**
     * @var string
     *
     * @Column(name="operatingcd", type="string", length=1, nullable=false)
     */
    private $operatingcd;

    /**
     * @var integer
     *
     * @Column(name="assetcdgen", type="bigint", nullable=false)
     */
    private $assetcdgen;

    /**
     * @var integer
     *
     * @Column(name="transinccd", type="bigint", nullable=false)
     */
    private $transinccd;

    /**
     * @var string
     *
     * @Column(name="subcd", type="string", length=2, nullable=false)
     */
    private $subcd;

    /**
     * @var integer
     *
     * @Column(name="grscontrgifts", type="bigint", nullable=false)
     */
    private $grscontrgifts;

    /**
     * @var integer
     *
     * @Column(name="intrstrvnue", type="bigint", nullable=false)
     */
    private $intrstrvnue;

    /**
     * @var integer
     *
     * @Column(name="dividndsamt", type="bigint", nullable=false)
     */
    private $dividndsamt;

    /**
     * @var integer
     *
     * @Column(name="totexcapgn", type="bigint", nullable=false)
     */
    private $totexcapgn;

    /**
     * @var integer
     *
     * @Column(name="totexcapls", type="bigint", nullable=false)
     */
    private $totexcapls;

    /**
     * @var integer
     *
     * @Column(name="grsprofitbus", type="bigint", nullable=false)
     */
    private $grsprofitbus;

    /**
     * @var integer
     *
     * @Column(name="otherincamt", type="bigint", nullable=false)
     */
    private $otherincamt;

    /**
     * @var integer
     *
     * @Column(name="compofficers", type="bigint", nullable=false)
     */
    private $compofficers;

    /**
     * @var integer
     *
     * @Column(name="contrpdpbks", type="bigint", nullable=false)
     */
    private $contrpdpbks;

    /**
     * @var integer
     *
     * @Column(name="totrcptperbks", type="bigint", nullable=false)
     */
    private $totrcptperbks;

    /**
     * @var integer
     *
     * @Column(name="totexpnspbks", type="bigint", nullable=false)
     */
    private $totexpnspbks;

    /**
     * @var integer
     *
     * @Column(name="excessrcpts", type="bigint", nullable=false)
     */
    private $excessrcpts;

    /**
     * @var integer
     *
     * @Column(name="totexpnsexempt", type="bigint", nullable=false)
     */
    private $totexpnsexempt;

    /**
     * @var integer
     *
     * @Column(name="netinvstinc", type="bigint", nullable=false)
     */
    private $netinvstinc;

    /**
     * @var integer
     *
     * @Column(name="totaxpyr", type="bigint", nullable=false)
     */
    private $totaxpyr;

    /**
     * @var integer
     *
     * @Column(name="adjnetinc", type="bigint", nullable=false)
     */
    private $adjnetinc;

    /**
     * @var integer
     *
     * @Column(name="totassetsend", type="bigint", nullable=false)
     */
    private $totassetsend;

    /**
     * @var integer
     *
     * @Column(name="invstgovtoblig", type="bigint", nullable=false)
     */
    private $invstgovtoblig;

    /**
     * @var integer
     *
     * @Column(name="invstcorpstk", type="bigint", nullable=false)
     */
    private $invstcorpstk;

    /**
     * @var integer
     *
     * @Column(name="invstcorpbnd", type="bigint", nullable=false)
     */
    private $invstcorpbnd;

    /**
     * @var integer
     *
     * @Column(name="totinvstsec", type="bigint", nullable=false)
     */
    private $totinvstsec;

    /**
     * @var integer
     *
     * @Column(name="totliabend", type="bigint", nullable=false)
     */
    private $totliabend;

    /**
     * @var integer
     *
     * @Column(name="fairmrktvalamt", type="bigint", nullable=false)
     */
    private $fairmrktvalamt;

    /**
     * @var integer
     *
     * @Column(name="undistribincyr", type="bigint", nullable=false)
     */
    private $undistribincyr;

    /**
     * @var integer
     *
     * @Column(name="cmpmininvstret", type="bigint", nullable=false)
     */
    private $cmpmininvstret;

    /**
     * @var string
     *
     * @Column(name="sec4940notxcd", type="string", length=1, nullable=false)
     */
    private $sec4940notxcd;

    /**
     * @var string
     *
     * @Column(name="sec4940redtxcd", type="string", length=1, nullable=false)
     */
    private $sec4940redtxcd;

    /**
     * @var string
     *
     * @Column(name="infleg", type="string", length=1, nullable=false)
     */
    private $infleg;

    /**
     * @var string
     *
     * @Column(name="contractncd", type="string", length=1, nullable=false)
     */
    private $contractncd;

    /**
     * @var string
     *
     * @Column(name="claimstatcd", type="string", length=1, nullable=false)
     */
    private $claimstatcd;

    /**
     * @var string
     *
     * @Column(name="propexchcd", type="string", length=1, nullable=false)
     */
    private $propexchcd;

    /**
     * @var string
     *
     * @Column(name="brwlndmnycd", type="string", length=1, nullable=false)
     */
    private $brwlndmnycd;

    /**
     * @var string
     *
     * @Column(name="furngoodscd", type="string", length=1, nullable=false)
     */
    private $furngoodscd;

    /**
     * @var string
     *
     * @Column(name="paidcmpncd", type="string", length=1, nullable=false)
     */
    private $paidcmpncd;

    /**
     * @var string
     *
     * @Column(name="trnsothasstscd", type="string", length=1, nullable=false)
     */
    private $trnsothasstscd;

    /**
     * @var string
     *
     * @Column(name="agremkpaycd", type="string", length=1, nullable=false)
     */
    private $agremkpaycd;

    /**
     * @var string
     *
     * @Column(name="undistrinccd", type="string", length=1, nullable=false)
     */
    private $undistrinccd;

    /**
     * @var string
     *
     * @Column(name="dirindirintcd", type="string", length=1, nullable=false)
     */
    private $dirindirintcd;

    /**
     * @var string
     *
     * @Column(name="invstjexmptcd", type="string", length=1, nullable=false)
     */
    private $invstjexmptcd;

    /**
     * @var string
     *
     * @Column(name="propgndacd", type="string", length=1, nullable=false)
     */
    private $propgndacd;

    /**
     * @var string
     *
     * @Column(name="excesshldcd", type="string", length=1, nullable=false)
     */
    private $excesshldcd;

    /**
     * @var string
     *
     * @Column(name="grntindivcd", type="string", length=1, nullable=false)
     */
    private $grntindivcd;

    /**
     * @var string
     *
     * @Column(name="nchrtygrntcd", type="string", length=1, nullable=false)
     */
    private $nchrtygrntcd;

    /**
     * @var string
     *
     * @Column(name="nreligiouscd", type="string", length=1, nullable=false)
     */
    private $nreligiouscd;

    /**
     * @var integer
     *
     * @Column(name="grsrents", type="bigint", nullable=false)
     */
    private $grsrents;

    /**
     * @var integer
     *
     * @Column(name="costsold", type="bigint", nullable=false)
     */
    private $costsold;

    /**
     * @var integer
     *
     * @Column(name="totrcptnetinc", type="bigint", nullable=false)
     */
    private $totrcptnetinc;

    /**
     * @var integer
     *
     * @Column(name="trcptadjnetinc", type="bigint", nullable=false)
     */
    private $trcptadjnetinc;

    /**
     * @var integer
     *
     * @Column(name="topradmnexpnsa", type="bigint", nullable=false)
     */
    private $topradmnexpnsa;

    /**
     * @var integer
     *
     * @Column(name="topradmnexpnsb", type="bigint", nullable=false)
     */
    private $topradmnexpnsb;

    /**
     * @var integer
     *
     * @Column(name="topradmnexpnsd", type="bigint", nullable=false)
     */
    private $topradmnexpnsd;

    /**
     * @var integer
     *
     * @Column(name="totexpnsnetinc", type="bigint", nullable=false)
     */
    private $totexpnsnetinc;

    /**
     * @var integer
     *
     * @Column(name="totexpnsadjnet", type="bigint", nullable=false)
     */
    private $totexpnsadjnet;

    /**
     * @var integer
     *
     * @Column(name="othrcashamt", type="bigint", nullable=false)
     */
    private $othrcashamt;

    /**
     * @var integer
     *
     * @Column(name="mrtgloans", type="bigint", nullable=false)
     */
    private $mrtgloans;

    /**
     * @var integer
     *
     * @Column(name="othrinvstend", type="bigint", nullable=false)
     */
    private $othrinvstend;

    /**
     * @var integer
     *
     * @Column(name="fairmrktvaleoy", type="bigint", nullable=false)
     */
    private $fairmrktvaleoy;

    /**
     * @var integer
     *
     * @Column(name="mrtgnotespay", type="bigint", nullable=false)
     */
    private $mrtgnotespay;

    /**
     * @var integer
     *
     * @Column(name="tfundnworth", type="bigint", nullable=false)
     */
    private $tfundnworth;

    /**
     * @var integer
     *
     * @Column(name="invstexcisetx", type="bigint", nullable=false)
     */
    private $invstexcisetx;

    /**
     * @var integer
     *
     * @Column(name="sect511tx", type="bigint", nullable=false)
     */
    private $sect511tx;

    /**
     * @var integer
     *
     * @Column(name="subtitleatx", type="bigint", nullable=false)
     */
    private $subtitleatx;

    /**
     * @var integer
     *
     * @Column(name="esttaxcr", type="bigint", nullable=false)
     */
    private $esttaxcr;

    /**
     * @var integer
     *
     * @Column(name="txwithldsrc", type="bigint", nullable=false)
     */
    private $txwithldsrc;

    /**
     * @var integer
     *
     * @Column(name="txpaidf2758", type="bigint", nullable=false)
     */
    private $txpaidf2758;

    /**
     * @var integer
     *
     * @Column(name="erronbkupwthld", type="bigint", nullable=false)
     */
    private $erronbkupwthld;

    /**
     * @var integer
     *
     * @Column(name="estpnlty", type="bigint", nullable=false)
     */
    private $estpnlty;

    /**
     * @var integer
     *
     * @Column(name="balduopt", type="bigint", nullable=false)
     */
    private $balduopt;

    /**
     * @var integer
     *
     * @Column(name="crelamt", type="bigint", nullable=false)
     */
    private $crelamt;

    /**
     * @var integer
     *
     * @Column(name="tfairmrktunuse", type="bigint", nullable=false)
     */
    private $tfairmrktunuse;

    /**
     * @var integer
     *
     * @Column(name="distribamt", type="bigint", nullable=false)
     */
    private $distribamt;

    /**
     * @var integer
     *
     * @Column(name="adjnetinccola", type="bigint", nullable=false)
     */
    private $adjnetinccola;

    /**
     * @var integer
     *
     * @Column(name="adjnetinccolb", type="bigint", nullable=false)
     */
    private $adjnetinccolb;

    /**
     * @var integer
     *
     * @Column(name="adjnetinccolc", type="bigint", nullable=false)
     */
    private $adjnetinccolc;

    /**
     * @var integer
     *
     * @Column(name="adjnetinccold", type="bigint", nullable=false)
     */
    private $adjnetinccold;

    /**
     * @var integer
     *
     * @Column(name="adjnetinctot", type="bigint", nullable=false)
     */
    private $adjnetinctot;

    /**
     * @var integer
     *
     * @Column(name="qlfydistriba", type="bigint", nullable=false)
     */
    private $qlfydistriba;

    /**
     * @var integer
     *
     * @Column(name="qlfydistribb", type="bigint", nullable=false)
     */
    private $qlfydistribb;

    /**
     * @var integer
     *
     * @Column(name="qlfydistribc", type="bigint", nullable=false)
     */
    private $qlfydistribc;

    /**
     * @var integer
     *
     * @Column(name="qlfydistribd", type="bigint", nullable=false)
     */
    private $qlfydistribd;

    /**
     * @var integer
     *
     * @Column(name="qlfydistribtot", type="bigint", nullable=false)
     */
    private $qlfydistribtot;

    /**
     * @var integer
     *
     * @Column(name="valassetscola", type="bigint", nullable=false)
     */
    private $valassetscola;

    /**
     * @var integer
     *
     * @Column(name="valassetscolb", type="bigint", nullable=false)
     */
    private $valassetscolb;

    /**
     * @var integer
     *
     * @Column(name="valassetscolc", type="bigint", nullable=false)
     */
    private $valassetscolc;

    /**
     * @var integer
     *
     * @Column(name="valassetscold", type="bigint", nullable=false)
     */
    private $valassetscold;

    /**
     * @var integer
     *
     * @Column(name="valassetstot", type="bigint", nullable=false)
     */
    private $valassetstot;

    /**
     * @var integer
     *
     * @Column(name="qlfyasseta", type="bigint", nullable=false)
     */
    private $qlfyasseta;

    /**
     * @var integer
     *
     * @Column(name="qlfyassetb", type="bigint", nullable=false)
     */
    private $qlfyassetb;

    /**
     * @var integer
     *
     * @Column(name="qlfyassetc", type="bigint", nullable=false)
     */
    private $qlfyassetc;

    /**
     * @var integer
     *
     * @Column(name="qlfyassetd", type="bigint", nullable=false)
     */
    private $qlfyassetd;

    /**
     * @var integer
     *
     * @Column(name="qlfyassettot", type="bigint", nullable=false)
     */
    private $qlfyassettot;

    /**
     * @var integer
     *
     * @Column(name="endwmntscola", type="bigint", nullable=false)
     */
    private $endwmntscola;

    /**
     * @var integer
     *
     * @Column(name="endwmntscolb", type="bigint", nullable=false)
     */
    private $endwmntscolb;

    /**
     * @var integer
     *
     * @Column(name="endwmntscolc", type="bigint", nullable=false)
     */
    private $endwmntscolc;

    /**
     * @var integer
     *
     * @Column(name="endwmntscold", type="bigint", nullable=false)
     */
    private $endwmntscold;

    /**
     * @var integer
     *
     * @Column(name="endwmntstot", type="bigint", nullable=false)
     */
    private $endwmntstot;

    /**
     * @var integer
     *
     * @Column(name="totsuprtcola", type="bigint", nullable=false)
     */
    private $totsuprtcola;

    /**
     * @var integer
     *
     * @Column(name="totsuprtcolb", type="bigint", nullable=false)
     */
    private $totsuprtcolb;

    /**
     * @var integer
     *
     * @Column(name="totsuprtcolc", type="bigint", nullable=false)
     */
    private $totsuprtcolc;

    /**
     * @var integer
     *
     * @Column(name="totsuprtcold", type="bigint", nullable=false)
     */
    private $totsuprtcold;

    /**
     * @var integer
     *
     * @Column(name="totsuprttot", type="bigint", nullable=false)
     */
    private $totsuprttot;

    /**
     * @var integer
     *
     * @Column(name="pubsuprtcola", type="bigint", nullable=false)
     */
    private $pubsuprtcola;

    /**
     * @var integer
     *
     * @Column(name="pubsuprtcolb", type="bigint", nullable=false)
     */
    private $pubsuprtcolb;

    /**
     * @var integer
     *
     * @Column(name="pubsuprtcolc", type="bigint", nullable=false)
     */
    private $pubsuprtcolc;

    /**
     * @var integer
     *
     * @Column(name="pubsuprtcold", type="bigint", nullable=false)
     */
    private $pubsuprtcold;

    /**
     * @var integer
     *
     * @Column(name="pubsuprttot", type="bigint", nullable=false)
     */
    private $pubsuprttot;

    /**
     * @var integer
     *
     * @Column(name="grsinvstinca", type="bigint", nullable=false)
     */
    private $grsinvstinca;

    /**
     * @var integer
     *
     * @Column(name="grsinvstincb", type="bigint", nullable=false)
     */
    private $grsinvstincb;

    /**
     * @var integer
     *
     * @Column(name="grsinvstincc", type="bigint", nullable=false)
     */
    private $grsinvstincc;

    /**
     * @var integer
     *
     * @Column(name="grsinvstincd", type="bigint", nullable=false)
     */
    private $grsinvstincd;

    /**
     * @var integer
     *
     * @Column(name="grsinvstinctot", type="bigint", nullable=false)
     */
    private $grsinvstinctot;

    /**
     * @param int $adjnetinc
     */
    public function setAdjnetinc($adjnetinc)
    {
        $this->adjnetinc = $adjnetinc;
    }

    /**
     * @return int
     */
    public function getAdjnetinc()
    {
        return $this->adjnetinc;
    }

    /**
     * @param int $adjnetinccola
     */
    public function setAdjnetinccola($adjnetinccola)
    {
        $this->adjnetinccola = $adjnetinccola;
    }

    /**
     * @return int
     */
    public function getAdjnetinccola()
    {
        return $this->adjnetinccola;
    }

    /**
     * @param int $adjnetinccolb
     */
    public function setAdjnetinccolb($adjnetinccolb)
    {
        $this->adjnetinccolb = $adjnetinccolb;
    }

    /**
     * @return int
     */
    public function getAdjnetinccolb()
    {
        return $this->adjnetinccolb;
    }

    /**
     * @param int $adjnetinccolc
     */
    public function setAdjnetinccolc($adjnetinccolc)
    {
        $this->adjnetinccolc = $adjnetinccolc;
    }

    /**
     * @return int
     */
    public function getAdjnetinccolc()
    {
        return $this->adjnetinccolc;
    }

    /**
     * @param int $adjnetinccold
     */
    public function setAdjnetinccold($adjnetinccold)
    {
        $this->adjnetinccold = $adjnetinccold;
    }

    /**
     * @return int
     */
    public function getAdjnetinccold()
    {
        return $this->adjnetinccold;
    }

    /**
     * @param int $adjnetinctot
     */
    public function setAdjnetinctot($adjnetinctot)
    {
        $this->adjnetinctot = $adjnetinctot;
    }

    /**
     * @return int
     */
    public function getAdjnetinctot()
    {
        return $this->adjnetinctot;
    }

    /**
     * @param string $agremkpaycd
     */
    public function setAgremkpaycd($agremkpaycd)
    {
        $this->agremkpaycd = $agremkpaycd;
    }

    /**
     * @return string
     */
    public function getAgremkpaycd()
    {
        return $this->agremkpaycd;
    }

    /**
     * @param int $assetcdgen
     */
    public function setAssetcdgen($assetcdgen)
    {
        $this->assetcdgen = $assetcdgen;
    }

    /**
     * @return int
     */
    public function getAssetcdgen()
    {
        return $this->assetcdgen;
    }

    /**
     * @param int $balduopt
     */
    public function setBalduopt($balduopt)
    {
        $this->balduopt = $balduopt;
    }

    /**
     * @return int
     */
    public function getBalduopt()
    {
        return $this->balduopt;
    }

    /**
     * @param string $brwlndmnycd
     */
    public function setBrwlndmnycd($brwlndmnycd)
    {
        $this->brwlndmnycd = $brwlndmnycd;
    }

    /**
     * @return string
     */
    public function getBrwlndmnycd()
    {
        return $this->brwlndmnycd;
    }

    /**
     * @param string $claimstatcd
     */
    public function setClaimstatcd($claimstatcd)
    {
        $this->claimstatcd = $claimstatcd;
    }

    /**
     * @return string
     */
    public function getClaimstatcd()
    {
        return $this->claimstatcd;
    }

    /**
     * @param int $cmpmininvstret
     */
    public function setCmpmininvstret($cmpmininvstret)
    {
        $this->cmpmininvstret = $cmpmininvstret;
    }

    /**
     * @return int
     */
    public function getCmpmininvstret()
    {
        return $this->cmpmininvstret;
    }

    /**
     * @param int $compofficers
     */
    public function setCompofficers($compofficers)
    {
        $this->compofficers = $compofficers;
    }

    /**
     * @return int
     */
    public function getCompofficers()
    {
        return $this->compofficers;
    }

    /**
     * @param string $contractncd
     */
    public function setContractncd($contractncd)
    {
        $this->contractncd = $contractncd;
    }

    /**
     * @return string
     */
    public function getContractncd()
    {
        return $this->contractncd;
    }

    /**
     * @param int $contrpdpbks
     */
    public function setContrpdpbks($contrpdpbks)
    {
        $this->contrpdpbks = $contrpdpbks;
    }

    /**
     * @return int
     */
    public function getContrpdpbks()
    {
        return $this->contrpdpbks;
    }

    /**
     * @param int $costsold
     */
    public function setCostsold($costsold)
    {
        $this->costsold = $costsold;
    }

    /**
     * @return int
     */
    public function getCostsold()
    {
        return $this->costsold;
    }

    /**
     * @param int $crelamt
     */
    public function setCrelamt($crelamt)
    {
        $this->crelamt = $crelamt;
    }

    /**
     * @return int
     */
    public function getCrelamt()
    {
        return $this->crelamt;
    }

    /**
     * @param string $dirindirintcd
     */
    public function setDirindirintcd($dirindirintcd)
    {
        $this->dirindirintcd = $dirindirintcd;
    }

    /**
     * @return string
     */
    public function getDirindirintcd()
    {
        return $this->dirindirintcd;
    }

    /**
     * @param int $distribamt
     */
    public function setDistribamt($distribamt)
    {
        $this->distribamt = $distribamt;
    }

    /**
     * @return int
     */
    public function getDistribamt()
    {
        return $this->distribamt;
    }

    /**
     * @param int $dividndsamt
     */
    public function setDividndsamt($dividndsamt)
    {
        $this->dividndsamt = $dividndsamt;
    }

    /**
     * @return int
     */
    public function getDividndsamt()
    {
        return $this->dividndsamt;
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
     * @param int $endwmntscola
     */
    public function setEndwmntscola($endwmntscola)
    {
        $this->endwmntscola = $endwmntscola;
    }

    /**
     * @return int
     */
    public function getEndwmntscola()
    {
        return $this->endwmntscola;
    }

    /**
     * @param int $endwmntscolb
     */
    public function setEndwmntscolb($endwmntscolb)
    {
        $this->endwmntscolb = $endwmntscolb;
    }

    /**
     * @return int
     */
    public function getEndwmntscolb()
    {
        return $this->endwmntscolb;
    }

    /**
     * @param int $endwmntscolc
     */
    public function setEndwmntscolc($endwmntscolc)
    {
        $this->endwmntscolc = $endwmntscolc;
    }

    /**
     * @return int
     */
    public function getEndwmntscolc()
    {
        return $this->endwmntscolc;
    }

    /**
     * @param int $endwmntscold
     */
    public function setEndwmntscold($endwmntscold)
    {
        $this->endwmntscold = $endwmntscold;
    }

    /**
     * @return int
     */
    public function getEndwmntscold()
    {
        return $this->endwmntscold;
    }

    /**
     * @param int $endwmntstot
     */
    public function setEndwmntstot($endwmntstot)
    {
        $this->endwmntstot = $endwmntstot;
    }

    /**
     * @return int
     */
    public function getEndwmntstot()
    {
        return $this->endwmntstot;
    }

    /**
     * @param int $eostatus
     */
    public function setEostatus($eostatus)
    {
        $this->eostatus = $eostatus;
    }

    /**
     * @return int
     */
    public function getEostatus()
    {
        return $this->eostatus;
    }

    /**
     * @param int $erronbkupwthld
     */
    public function setErronbkupwthld($erronbkupwthld)
    {
        $this->erronbkupwthld = $erronbkupwthld;
    }

    /**
     * @return int
     */
    public function getErronbkupwthld()
    {
        return $this->erronbkupwthld;
    }

    /**
     * @param int $estpnlty
     */
    public function setEstpnlty($estpnlty)
    {
        $this->estpnlty = $estpnlty;
    }

    /**
     * @return int
     */
    public function getEstpnlty()
    {
        return $this->estpnlty;
    }

    /**
     * @param int $esttaxcr
     */
    public function setEsttaxcr($esttaxcr)
    {
        $this->esttaxcr = $esttaxcr;
    }

    /**
     * @return int
     */
    public function getEsttaxcr()
    {
        return $this->esttaxcr;
    }

    /**
     * @param string $excesshldcd
     */
    public function setExcesshldcd($excesshldcd)
    {
        $this->excesshldcd = $excesshldcd;
    }

    /**
     * @return string
     */
    public function getExcesshldcd()
    {
        return $this->excesshldcd;
    }

    /**
     * @param int $excessrcpts
     */
    public function setExcessrcpts($excessrcpts)
    {
        $this->excessrcpts = $excessrcpts;
    }

    /**
     * @return int
     */
    public function getExcessrcpts()
    {
        return $this->excessrcpts;
    }

    /**
     * @param int $fairmrktvalamt
     */
    public function setFairmrktvalamt($fairmrktvalamt)
    {
        $this->fairmrktvalamt = $fairmrktvalamt;
    }

    /**
     * @return int
     */
    public function getFairmrktvalamt()
    {
        return $this->fairmrktvalamt;
    }

    /**
     * @param int $fairmrktvaleoy
     */
    public function setFairmrktvaleoy($fairmrktvaleoy)
    {
        $this->fairmrktvaleoy = $fairmrktvaleoy;
    }

    /**
     * @return int
     */
    public function getFairmrktvaleoy()
    {
        return $this->fairmrktvaleoy;
    }

    /**
     * @param string $furngoodscd
     */
    public function setFurngoodscd($furngoodscd)
    {
        $this->furngoodscd = $furngoodscd;
    }

    /**
     * @return string
     */
    public function getFurngoodscd()
    {
        return $this->furngoodscd;
    }

    /**
     * @param string $grntindivcd
     */
    public function setGrntindivcd($grntindivcd)
    {
        $this->grntindivcd = $grntindivcd;
    }

    /**
     * @return string
     */
    public function getGrntindivcd()
    {
        return $this->grntindivcd;
    }

    /**
     * @param int $grscontrgifts
     */
    public function setGrscontrgifts($grscontrgifts)
    {
        $this->grscontrgifts = $grscontrgifts;
    }

    /**
     * @return int
     */
    public function getGrscontrgifts()
    {
        return $this->grscontrgifts;
    }

    /**
     * @param int $grsinvstinca
     */
    public function setGrsinvstinca($grsinvstinca)
    {
        $this->grsinvstinca = $grsinvstinca;
    }

    /**
     * @return int
     */
    public function getGrsinvstinca()
    {
        return $this->grsinvstinca;
    }

    /**
     * @param int $grsinvstincb
     */
    public function setGrsinvstincb($grsinvstincb)
    {
        $this->grsinvstincb = $grsinvstincb;
    }

    /**
     * @return int
     */
    public function getGrsinvstincb()
    {
        return $this->grsinvstincb;
    }

    /**
     * @param int $grsinvstincc
     */
    public function setGrsinvstincc($grsinvstincc)
    {
        $this->grsinvstincc = $grsinvstincc;
    }

    /**
     * @return int
     */
    public function getGrsinvstincc()
    {
        return $this->grsinvstincc;
    }

    /**
     * @param int $grsinvstincd
     */
    public function setGrsinvstincd($grsinvstincd)
    {
        $this->grsinvstincd = $grsinvstincd;
    }

    /**
     * @return int
     */
    public function getGrsinvstincd()
    {
        return $this->grsinvstincd;
    }

    /**
     * @param int $grsinvstinctot
     */
    public function setGrsinvstinctot($grsinvstinctot)
    {
        $this->grsinvstinctot = $grsinvstinctot;
    }

    /**
     * @return int
     */
    public function getGrsinvstinctot()
    {
        return $this->grsinvstinctot;
    }

    /**
     * @param int $grsprofitbus
     */
    public function setGrsprofitbus($grsprofitbus)
    {
        $this->grsprofitbus = $grsprofitbus;
    }

    /**
     * @return int
     */
    public function getGrsprofitbus()
    {
        return $this->grsprofitbus;
    }

    /**
     * @param int $grsrents
     */
    public function setGrsrents($grsrents)
    {
        $this->grsrents = $grsrents;
    }

    /**
     * @return int
     */
    public function getGrsrents()
    {
        return $this->grsrents;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $infleg
     */
    public function setInfleg($infleg)
    {
        $this->infleg = $infleg;
    }

    /**
     * @return string
     */
    public function getInfleg()
    {
        return $this->infleg;
    }

    /**
     * @param int $intrstrvnue
     */
    public function setIntrstrvnue($intrstrvnue)
    {
        $this->intrstrvnue = $intrstrvnue;
    }

    /**
     * @return int
     */
    public function getIntrstrvnue()
    {
        return $this->intrstrvnue;
    }

    /**
     * @param int $invstcorpbnd
     */
    public function setInvstcorpbnd($invstcorpbnd)
    {
        $this->invstcorpbnd = $invstcorpbnd;
    }

    /**
     * @return int
     */
    public function getInvstcorpbnd()
    {
        return $this->invstcorpbnd;
    }

    /**
     * @param int $invstcorpstk
     */
    public function setInvstcorpstk($invstcorpstk)
    {
        $this->invstcorpstk = $invstcorpstk;
    }

    /**
     * @return int
     */
    public function getInvstcorpstk()
    {
        return $this->invstcorpstk;
    }

    /**
     * @param int $invstexcisetx
     */
    public function setInvstexcisetx($invstexcisetx)
    {
        $this->invstexcisetx = $invstexcisetx;
    }

    /**
     * @return int
     */
    public function getInvstexcisetx()
    {
        return $this->invstexcisetx;
    }

    /**
     * @param int $invstgovtoblig
     */
    public function setInvstgovtoblig($invstgovtoblig)
    {
        $this->invstgovtoblig = $invstgovtoblig;
    }

    /**
     * @return int
     */
    public function getInvstgovtoblig()
    {
        return $this->invstgovtoblig;
    }

    /**
     * @param string $invstjexmptcd
     */
    public function setInvstjexmptcd($invstjexmptcd)
    {
        $this->invstjexmptcd = $invstjexmptcd;
    }

    /**
     * @return string
     */
    public function getInvstjexmptcd()
    {
        return $this->invstjexmptcd;
    }

    /**
     * @param int $mrtgloans
     */
    public function setMrtgloans($mrtgloans)
    {
        $this->mrtgloans = $mrtgloans;
    }

    /**
     * @return int
     */
    public function getMrtgloans()
    {
        return $this->mrtgloans;
    }

    /**
     * @param int $mrtgnotespay
     */
    public function setMrtgnotespay($mrtgnotespay)
    {
        $this->mrtgnotespay = $mrtgnotespay;
    }

    /**
     * @return int
     */
    public function getMrtgnotespay()
    {
        return $this->mrtgnotespay;
    }

    /**
     * @param string $nchrtygrntcd
     */
    public function setNchrtygrntcd($nchrtygrntcd)
    {
        $this->nchrtygrntcd = $nchrtygrntcd;
    }

    /**
     * @return string
     */
    public function getNchrtygrntcd()
    {
        return $this->nchrtygrntcd;
    }

    /**
     * @param int $netinvstinc
     */
    public function setNetinvstinc($netinvstinc)
    {
        $this->netinvstinc = $netinvstinc;
    }

    /**
     * @return int
     */
    public function getNetinvstinc()
    {
        return $this->netinvstinc;
    }

    /**
     * @param string $nreligiouscd
     */
    public function setNreligiouscd($nreligiouscd)
    {
        $this->nreligiouscd = $nreligiouscd;
    }

    /**
     * @return string
     */
    public function getNreligiouscd()
    {
        return $this->nreligiouscd;
    }

    /**
     * @param string $operatingcd
     */
    public function setOperatingcd($operatingcd)
    {
        $this->operatingcd = $operatingcd;
    }

    /**
     * @return string
     */
    public function getOperatingcd()
    {
        return $this->operatingcd;
    }

    /**
     * @param int $otherincamt
     */
    public function setOtherincamt($otherincamt)
    {
        $this->otherincamt = $otherincamt;
    }

    /**
     * @return int
     */
    public function getOtherincamt()
    {
        return $this->otherincamt;
    }

    /**
     * @param int $othrcashamt
     */
    public function setOthrcashamt($othrcashamt)
    {
        $this->othrcashamt = $othrcashamt;
    }

    /**
     * @return int
     */
    public function getOthrcashamt()
    {
        return $this->othrcashamt;
    }

    /**
     * @param int $othrinvstend
     */
    public function setOthrinvstend($othrinvstend)
    {
        $this->othrinvstend = $othrinvstend;
    }

    /**
     * @return int
     */
    public function getOthrinvstend()
    {
        return $this->othrinvstend;
    }

    /**
     * @param string $paidcmpncd
     */
    public function setPaidcmpncd($paidcmpncd)
    {
        $this->paidcmpncd = $paidcmpncd;
    }

    /**
     * @return string
     */
    public function getPaidcmpncd()
    {
        return $this->paidcmpncd;
    }

    /**
     * @param string $propexchcd
     */
    public function setPropexchcd($propexchcd)
    {
        $this->propexchcd = $propexchcd;
    }

    /**
     * @return string
     */
    public function getPropexchcd()
    {
        return $this->propexchcd;
    }

    /**
     * @param string $propgndacd
     */
    public function setPropgndacd($propgndacd)
    {
        $this->propgndacd = $propgndacd;
    }

    /**
     * @return string
     */
    public function getPropgndacd()
    {
        return $this->propgndacd;
    }

    /**
     * @param int $pubsuprtcola
     */
    public function setPubsuprtcola($pubsuprtcola)
    {
        $this->pubsuprtcola = $pubsuprtcola;
    }

    /**
     * @return int
     */
    public function getPubsuprtcola()
    {
        return $this->pubsuprtcola;
    }

    /**
     * @param int $pubsuprtcolb
     */
    public function setPubsuprtcolb($pubsuprtcolb)
    {
        $this->pubsuprtcolb = $pubsuprtcolb;
    }

    /**
     * @return int
     */
    public function getPubsuprtcolb()
    {
        return $this->pubsuprtcolb;
    }

    /**
     * @param int $pubsuprtcolc
     */
    public function setPubsuprtcolc($pubsuprtcolc)
    {
        $this->pubsuprtcolc = $pubsuprtcolc;
    }

    /**
     * @return int
     */
    public function getPubsuprtcolc()
    {
        return $this->pubsuprtcolc;
    }

    /**
     * @param int $pubsuprtcold
     */
    public function setPubsuprtcold($pubsuprtcold)
    {
        $this->pubsuprtcold = $pubsuprtcold;
    }

    /**
     * @return int
     */
    public function getPubsuprtcold()
    {
        return $this->pubsuprtcold;
    }

    /**
     * @param int $pubsuprttot
     */
    public function setPubsuprttot($pubsuprttot)
    {
        $this->pubsuprttot = $pubsuprttot;
    }

    /**
     * @return int
     */
    public function getPubsuprttot()
    {
        return $this->pubsuprttot;
    }

    /**
     * @param int $qlfyasseta
     */
    public function setQlfyasseta($qlfyasseta)
    {
        $this->qlfyasseta = $qlfyasseta;
    }

    /**
     * @return int
     */
    public function getQlfyasseta()
    {
        return $this->qlfyasseta;
    }

    /**
     * @param int $qlfyassetb
     */
    public function setQlfyassetb($qlfyassetb)
    {
        $this->qlfyassetb = $qlfyassetb;
    }

    /**
     * @return int
     */
    public function getQlfyassetb()
    {
        return $this->qlfyassetb;
    }

    /**
     * @param int $qlfyassetc
     */
    public function setQlfyassetc($qlfyassetc)
    {
        $this->qlfyassetc = $qlfyassetc;
    }

    /**
     * @return int
     */
    public function getQlfyassetc()
    {
        return $this->qlfyassetc;
    }

    /**
     * @param int $qlfyassetd
     */
    public function setQlfyassetd($qlfyassetd)
    {
        $this->qlfyassetd = $qlfyassetd;
    }

    /**
     * @return int
     */
    public function getQlfyassetd()
    {
        return $this->qlfyassetd;
    }

    /**
     * @param int $qlfyassettot
     */
    public function setQlfyassettot($qlfyassettot)
    {
        $this->qlfyassettot = $qlfyassettot;
    }

    /**
     * @return int
     */
    public function getQlfyassettot()
    {
        return $this->qlfyassettot;
    }

    /**
     * @param int $qlfydistriba
     */
    public function setQlfydistriba($qlfydistriba)
    {
        $this->qlfydistriba = $qlfydistriba;
    }

    /**
     * @return int
     */
    public function getQlfydistriba()
    {
        return $this->qlfydistriba;
    }

    /**
     * @param int $qlfydistribb
     */
    public function setQlfydistribb($qlfydistribb)
    {
        $this->qlfydistribb = $qlfydistribb;
    }

    /**
     * @return int
     */
    public function getQlfydistribb()
    {
        return $this->qlfydistribb;
    }

    /**
     * @param int $qlfydistribc
     */
    public function setQlfydistribc($qlfydistribc)
    {
        $this->qlfydistribc = $qlfydistribc;
    }

    /**
     * @return int
     */
    public function getQlfydistribc()
    {
        return $this->qlfydistribc;
    }

    /**
     * @param int $qlfydistribd
     */
    public function setQlfydistribd($qlfydistribd)
    {
        $this->qlfydistribd = $qlfydistribd;
    }

    /**
     * @return int
     */
    public function getQlfydistribd()
    {
        return $this->qlfydistribd;
    }

    /**
     * @param int $qlfydistribtot
     */
    public function setQlfydistribtot($qlfydistribtot)
    {
        $this->qlfydistribtot = $qlfydistribtot;
    }

    /**
     * @return int
     */
    public function getQlfydistribtot()
    {
        return $this->qlfydistribtot;
    }

    /**
     * @param string $sec4940notxcd
     */
    public function setSec4940notxcd($sec4940notxcd)
    {
        $this->sec4940notxcd = $sec4940notxcd;
    }

    /**
     * @return string
     */
    public function getSec4940notxcd()
    {
        return $this->sec4940notxcd;
    }

    /**
     * @param string $sec4940redtxcd
     */
    public function setSec4940redtxcd($sec4940redtxcd)
    {
        $this->sec4940redtxcd = $sec4940redtxcd;
    }

    /**
     * @return string
     */
    public function getSec4940redtxcd()
    {
        return $this->sec4940redtxcd;
    }

    /**
     * @param int $sect511tx
     */
    public function setSect511tx($sect511tx)
    {
        $this->sect511tx = $sect511tx;
    }

    /**
     * @return int
     */
    public function getSect511tx()
    {
        return $this->sect511tx;
    }

    /**
     * @param string $subcd
     */
    public function setSubcd($subcd)
    {
        $this->subcd = $subcd;
    }

    /**
     * @return string
     */
    public function getSubcd()
    {
        return $this->subcd;
    }

    /**
     * @param int $subtitleatx
     */
    public function setSubtitleatx($subtitleatx)
    {
        $this->subtitleatx = $subtitleatx;
    }

    /**
     * @return int
     */
    public function getSubtitleatx()
    {
        return $this->subtitleatx;
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
     * @param int $taxYr
     */
    public function setTaxYr($taxYr)
    {
        $this->taxYr = $taxYr;
    }

    /**
     * @return int
     */
    public function getTaxYr()
    {
        return $this->taxYr;
    }

    /**
     * @param int $tfairmrktunuse
     */
    public function setTfairmrktunuse($tfairmrktunuse)
    {
        $this->tfairmrktunuse = $tfairmrktunuse;
    }

    /**
     * @return int
     */
    public function getTfairmrktunuse()
    {
        return $this->tfairmrktunuse;
    }

    /**
     * @param int $tfundnworth
     */
    public function setTfundnworth($tfundnworth)
    {
        $this->tfundnworth = $tfundnworth;
    }

    /**
     * @return int
     */
    public function getTfundnworth()
    {
        return $this->tfundnworth;
    }

    /**
     * @param int $topradmnexpnsa
     */
    public function setTopradmnexpnsa($topradmnexpnsa)
    {
        $this->topradmnexpnsa = $topradmnexpnsa;
    }

    /**
     * @return int
     */
    public function getTopradmnexpnsa()
    {
        return $this->topradmnexpnsa;
    }

    /**
     * @param int $topradmnexpnsb
     */
    public function setTopradmnexpnsb($topradmnexpnsb)
    {
        $this->topradmnexpnsb = $topradmnexpnsb;
    }

    /**
     * @return int
     */
    public function getTopradmnexpnsb()
    {
        return $this->topradmnexpnsb;
    }

    /**
     * @param int $topradmnexpnsd
     */
    public function setTopradmnexpnsd($topradmnexpnsd)
    {
        $this->topradmnexpnsd = $topradmnexpnsd;
    }

    /**
     * @return int
     */
    public function getTopradmnexpnsd()
    {
        return $this->topradmnexpnsd;
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
     * @param int $totaxpyr
     */
    public function setTotaxpyr($totaxpyr)
    {
        $this->totaxpyr = $totaxpyr;
    }

    /**
     * @return int
     */
    public function getTotaxpyr()
    {
        return $this->totaxpyr;
    }

    /**
     * @param int $totexcapgn
     */
    public function setTotexcapgn($totexcapgn)
    {
        $this->totexcapgn = $totexcapgn;
    }

    /**
     * @return int
     */
    public function getTotexcapgn()
    {
        return $this->totexcapgn;
    }

    /**
     * @param int $totexcapls
     */
    public function setTotexcapls($totexcapls)
    {
        $this->totexcapls = $totexcapls;
    }

    /**
     * @return int
     */
    public function getTotexcapls()
    {
        return $this->totexcapls;
    }

    /**
     * @param int $totexpnsadjnet
     */
    public function setTotexpnsadjnet($totexpnsadjnet)
    {
        $this->totexpnsadjnet = $totexpnsadjnet;
    }

    /**
     * @return int
     */
    public function getTotexpnsadjnet()
    {
        return $this->totexpnsadjnet;
    }

    /**
     * @param int $totexpnsexempt
     */
    public function setTotexpnsexempt($totexpnsexempt)
    {
        $this->totexpnsexempt = $totexpnsexempt;
    }

    /**
     * @return int
     */
    public function getTotexpnsexempt()
    {
        return $this->totexpnsexempt;
    }

    /**
     * @param int $totexpnsnetinc
     */
    public function setTotexpnsnetinc($totexpnsnetinc)
    {
        $this->totexpnsnetinc = $totexpnsnetinc;
    }

    /**
     * @return int
     */
    public function getTotexpnsnetinc()
    {
        return $this->totexpnsnetinc;
    }

    /**
     * @param int $totexpnspbks
     */
    public function setTotexpnspbks($totexpnspbks)
    {
        $this->totexpnspbks = $totexpnspbks;
    }

    /**
     * @return int
     */
    public function getTotexpnspbks()
    {
        return $this->totexpnspbks;
    }

    /**
     * @param int $totinvstsec
     */
    public function setTotinvstsec($totinvstsec)
    {
        $this->totinvstsec = $totinvstsec;
    }

    /**
     * @return int
     */
    public function getTotinvstsec()
    {
        return $this->totinvstsec;
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
     * @param int $totrcptnetinc
     */
    public function setTotrcptnetinc($totrcptnetinc)
    {
        $this->totrcptnetinc = $totrcptnetinc;
    }

    /**
     * @return int
     */
    public function getTotrcptnetinc()
    {
        return $this->totrcptnetinc;
    }

    /**
     * @param int $totrcptperbks
     */
    public function setTotrcptperbks($totrcptperbks)
    {
        $this->totrcptperbks = $totrcptperbks;
    }

    /**
     * @return int
     */
    public function getTotrcptperbks()
    {
        return $this->totrcptperbks;
    }

    /**
     * @param int $totsuprtcola
     */
    public function setTotsuprtcola($totsuprtcola)
    {
        $this->totsuprtcola = $totsuprtcola;
    }

    /**
     * @return int
     */
    public function getTotsuprtcola()
    {
        return $this->totsuprtcola;
    }

    /**
     * @param int $totsuprtcolb
     */
    public function setTotsuprtcolb($totsuprtcolb)
    {
        $this->totsuprtcolb = $totsuprtcolb;
    }

    /**
     * @return int
     */
    public function getTotsuprtcolb()
    {
        return $this->totsuprtcolb;
    }

    /**
     * @param int $totsuprtcolc
     */
    public function setTotsuprtcolc($totsuprtcolc)
    {
        $this->totsuprtcolc = $totsuprtcolc;
    }

    /**
     * @return int
     */
    public function getTotsuprtcolc()
    {
        return $this->totsuprtcolc;
    }

    /**
     * @param int $totsuprtcold
     */
    public function setTotsuprtcold($totsuprtcold)
    {
        $this->totsuprtcold = $totsuprtcold;
    }

    /**
     * @return int
     */
    public function getTotsuprtcold()
    {
        return $this->totsuprtcold;
    }

    /**
     * @param int $totsuprttot
     */
    public function setTotsuprttot($totsuprttot)
    {
        $this->totsuprttot = $totsuprttot;
    }

    /**
     * @return int
     */
    public function getTotsuprttot()
    {
        return $this->totsuprttot;
    }

    /**
     * @param int $transinccd
     */
    public function setTransinccd($transinccd)
    {
        $this->transinccd = $transinccd;
    }

    /**
     * @return int
     */
    public function getTransinccd()
    {
        return $this->transinccd;
    }

    /**
     * @param int $trcptadjnetinc
     */
    public function setTrcptadjnetinc($trcptadjnetinc)
    {
        $this->trcptadjnetinc = $trcptadjnetinc;
    }

    /**
     * @return int
     */
    public function getTrcptadjnetinc()
    {
        return $this->trcptadjnetinc;
    }

    /**
     * @param string $trnsothasstscd
     */
    public function setTrnsothasstscd($trnsothasstscd)
    {
        $this->trnsothasstscd = $trnsothasstscd;
    }

    /**
     * @return string
     */
    public function getTrnsothasstscd()
    {
        return $this->trnsothasstscd;
    }

    /**
     * @param int $txpaidf2758
     */
    public function setTxpaidf2758($txpaidf2758)
    {
        $this->txpaidf2758 = $txpaidf2758;
    }

    /**
     * @return int
     */
    public function getTxpaidf2758()
    {
        return $this->txpaidf2758;
    }

    /**
     * @param int $txwithldsrc
     */
    public function setTxwithldsrc($txwithldsrc)
    {
        $this->txwithldsrc = $txwithldsrc;
    }

    /**
     * @return int
     */
    public function getTxwithldsrc()
    {
        return $this->txwithldsrc;
    }

    /**
     * @param int $undistribincyr
     */
    public function setUndistribincyr($undistribincyr)
    {
        $this->undistribincyr = $undistribincyr;
    }

    /**
     * @return int
     */
    public function getUndistribincyr()
    {
        return $this->undistribincyr;
    }

    /**
     * @param string $undistrinccd
     */
    public function setUndistrinccd($undistrinccd)
    {
        $this->undistrinccd = $undistrinccd;
    }

    /**
     * @return string
     */
    public function getUndistrinccd()
    {
        return $this->undistrinccd;
    }

    /**
     * @param int $valassetscola
     */
    public function setValassetscola($valassetscola)
    {
        $this->valassetscola = $valassetscola;
    }

    /**
     * @return int
     */
    public function getValassetscola()
    {
        return $this->valassetscola;
    }

    /**
     * @param int $valassetscolb
     */
    public function setValassetscolb($valassetscolb)
    {
        $this->valassetscolb = $valassetscolb;
    }

    /**
     * @return int
     */
    public function getValassetscolb()
    {
        return $this->valassetscolb;
    }

    /**
     * @param int $valassetscolc
     */
    public function setValassetscolc($valassetscolc)
    {
        $this->valassetscolc = $valassetscolc;
    }

    /**
     * @return int
     */
    public function getValassetscolc()
    {
        return $this->valassetscolc;
    }

    /**
     * @param int $valassetscold
     */
    public function setValassetscold($valassetscold)
    {
        $this->valassetscold = $valassetscold;
    }

    /**
     * @return int
     */
    public function getValassetscold()
    {
        return $this->valassetscold;
    }

    /**
     * @param int $valassetstot
     */
    public function setValassetstot($valassetstot)
    {
        $this->valassetstot = $valassetstot;
    }

    /**
     * @return int
     */
    public function getValassetstot()
    {
        return $this->valassetstot;
    }


}
