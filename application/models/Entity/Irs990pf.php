<?php

namespace Entity;

/**
 * Irs990pf
 *
 * @Table(name="irs_990pf", indexes={@Index(name="irs_990pf_ein_idx", columns={"ein"})})
 * @Entity
 */
class Irs990pf extends BaseEntity {
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
     * @var string
     *
     * @Column(name="tax_prd", type="string", length=6, nullable=true)
     */
    private $taxPrd;

    /**
     * @var string
     *
     * @Column(name="eostatus", type="string", length=2, nullable=true)
     */
    private $eostatus;

    /**
     * @var integer
     *
     * @Column(name="tax_yr", type="bigint", nullable=true)
     */
    private $taxYr;

    /**
     * @var string
     *
     * @Column(name="operatingcd", type="string", length=1, nullable=true)
     */
    private $operatingcd;

    /**
     * @var string
     *
     * @Column(name="subcd", type="string", length=2, nullable=true)
     */
    private $subcd;

    /**
     * @var integer
     *
     * @Column(name="fairmrktvalamt", type="bigint", nullable=true)
     */
    private $fairmrktvalamt;

    /**
     * @var integer
     *
     * @Column(name="grscontrgifts", type="bigint", nullable=true)
     */
    private $grscontrgifts;

    /**
     * @var string
     *
     * @Column(name="schedbind", type="string", length=1, nullable=true)
     */
    private $schedbind;

    /**
     * @var integer
     *
     * @Column(name="intrstrvnue", type="bigint", nullable=true)
     */
    private $intrstrvnue;

    /**
     * @var integer
     *
     * @Column(name="dividndsamt", type="bigint", nullable=true)
     */
    private $dividndsamt;

    /**
     * @var integer
     *
     * @Column(name="grsrents", type="bigint", nullable=true)
     */
    private $grsrents;

    /**
     * @var integer
     *
     * @Column(name="grsslspramt", type="bigint", nullable=true)
     */
    private $grsslspramt;

    /**
     * @var integer
     *
     * @Column(name="costsold", type="bigint", nullable=true)
     */
    private $costsold;

    /**
     * @var integer
     *
     * @Column(name="grsprofitbus", type="bigint", nullable=true)
     */
    private $grsprofitbus;

    /**
     * @var integer
     *
     * @Column(name="otherincamt", type="bigint", nullable=true)
     */
    private $otherincamt;

    /**
     * @var integer
     *
     * @Column(name="totrcptperbks", type="bigint", nullable=true)
     */
    private $totrcptperbks;

    /**
     * @var integer
     *
     * @Column(name="compofficers", type="bigint", nullable=true)
     */
    private $compofficers;

    /**
     * @var integer
     *
     * @Column(name="pensplemplbenf", type="bigint", nullable=true)
     */
    private $pensplemplbenf;

    /**
     * @var integer
     *
     * @Column(name="legalfeesamt", type="bigint", nullable=true)
     */
    private $legalfeesamt;

    /**
     * @var integer
     *
     * @Column(name="accountingfees", type="bigint", nullable=true)
     */
    private $accountingfees;

    /**
     * @var integer
     *
     * @Column(name="interestamt", type="bigint", nullable=true)
     */
    private $interestamt;

    /**
     * @var integer
     *
     * @Column(name="depreciationamt", type="bigint", nullable=true)
     */
    private $depreciationamt;

    /**
     * @var integer
     *
     * @Column(name="occupancyamt", type="bigint", nullable=true)
     */
    private $occupancyamt;

    /**
     * @var integer
     *
     * @Column(name="travlconfmtngs", type="bigint", nullable=true)
     */
    private $travlconfmtngs;

    /**
     * @var integer
     *
     * @Column(name="printingpubl", type="bigint", nullable=true)
     */
    private $printingpubl;

    /**
     * @var integer
     *
     * @Column(name="topradmnexpnsa", type="bigint", nullable=true)
     */
    private $topradmnexpnsa;

    /**
     * @var integer
     *
     * @Column(name="contrpdpbks", type="bigint", nullable=true)
     */
    private $contrpdpbks;

    /**
     * @var integer
     *
     * @Column(name="totexpnspbks", type="bigint", nullable=true)
     */
    private $totexpnspbks;

    /**
     * @var integer
     *
     * @Column(name="excessrcpts", type="bigint", nullable=true)
     */
    private $excessrcpts;

    /**
     * @var integer
     *
     * @Column(name="totrcptnetinc", type="bigint", nullable=true)
     */
    private $totrcptnetinc;

    /**
     * @var integer
     *
     * @Column(name="topradmnexpnsb", type="bigint", nullable=true)
     */
    private $topradmnexpnsb;

    /**
     * @var integer
     *
     * @Column(name="totexpnsnetinc", type="bigint", nullable=true)
     */
    private $totexpnsnetinc;

    /**
     * @var integer
     *
     * @Column(name="netinvstinc", type="bigint", nullable=true)
     */
    private $netinvstinc;

    /**
     * @var integer
     *
     * @Column(name="trcptadjnetinc", type="bigint", nullable=true)
     */
    private $trcptadjnetinc;

    /**
     * @var integer
     *
     * @Column(name="totexpnsadjnet", type="bigint", nullable=true)
     */
    private $totexpnsadjnet;

    /**
     * @var integer
     *
     * @Column(name="adjnetinc", type="bigint", nullable=true)
     */
    private $adjnetinc;

    /**
     * @var integer
     *
     * @Column(name="topradmnexpnsd", type="bigint", nullable=true)
     */
    private $topradmnexpnsd;

    /**
     * @var integer
     *
     * @Column(name="totexpnsexempt", type="bigint", nullable=true)
     */
    private $totexpnsexempt;

    /**
     * @var integer
     *
     * @Column(name="othrcashamt", type="bigint", nullable=true)
     */
    private $othrcashamt;

    /**
     * @var integer
     *
     * @Column(name="invstgovtoblig", type="bigint", nullable=true)
     */
    private $invstgovtoblig;

    /**
     * @var integer
     *
     * @Column(name="invstcorpstk", type="bigint", nullable=true)
     */
    private $invstcorpstk;

    /**
     * @var integer
     *
     * @Column(name="invstcorpbnd", type="bigint", nullable=true)
     */
    private $invstcorpbnd;

    /**
     * @var integer
     *
     * @Column(name="totinvstsec", type="bigint", nullable=true)
     */
    private $totinvstsec;

    /**
     * @var integer
     *
     * @Column(name="mrtgloans", type="bigint", nullable=true)
     */
    private $mrtgloans;

    /**
     * @var integer
     *
     * @Column(name="othrinvstend", type="bigint", nullable=true)
     */
    private $othrinvstend;

    /**
     * @var integer
     *
     * @Column(name="othrassetseoy", type="bigint", nullable=true)
     */
    private $othrassetseoy;

    /**
     * @var integer
     *
     * @Column(name="totassetsend", type="bigint", nullable=true)
     */
    private $totassetsend;

    /**
     * @var integer
     *
     * @Column(name="mrtgnotespay", type="bigint", nullable=true)
     */
    private $mrtgnotespay;

    /**
     * @var integer
     *
     * @Column(name="othrliabltseoy", type="bigint", nullable=true)
     */
    private $othrliabltseoy;

    /**
     * @var integer
     *
     * @Column(name="totliabend", type="bigint", nullable=true)
     */
    private $totliabend;

    /**
     * @var integer
     *
     * @Column(name="tfundnworth", type="bigint", nullable=true)
     */
    private $tfundnworth;

    /**
     * @var integer
     *
     * @Column(name="fairmrktvaleoy", type="bigint", nullable=true)
     */
    private $fairmrktvaleoy;

    /**
     * @var integer
     *
     * @Column(name="totexcapgnls", type="bigint", nullable=true)
     */
    private $totexcapgnls;

    /**
     * @var integer
     *
     * @Column(name="totexcapgn", type="bigint", nullable=true)
     */
    private $totexcapgn;

    /**
     * @var integer
     *
     * @Column(name="totexcapls", type="bigint", nullable=true)
     */
    private $totexcapls;

    /**
     * @var integer
     *
     * @Column(name="invstexcisetx", type="bigint", nullable=true)
     */
    private $invstexcisetx;

    /**
     * @var string
     *
     * @Column(name="sec4940notxcd", type="string", length=1, nullable=true)
     */
    private $sec4940notxcd;

    /**
     * @var string
     *
     * @Column(name="sec4940redtxcd", type="string", length=1, nullable=true)
     */
    private $sec4940redtxcd;

    /**
     * @var integer
     *
     * @Column(name="sect511tx", type="bigint", nullable=true)
     */
    private $sect511tx;

    /**
     * @var integer
     *
     * @Column(name="subtitleatx", type="bigint", nullable=true)
     */
    private $subtitleatx;

    /**
     * @var integer
     *
     * @Column(name="totaxpyr", type="bigint", nullable=true)
     */
    private $totaxpyr;

    /**
     * @var integer
     *
     * @Column(name="esttaxcr", type="bigint", nullable=true)
     */
    private $esttaxcr;

    /**
     * @var integer
     *
     * @Column(name="txwithldsrc", type="bigint", nullable=true)
     */
    private $txwithldsrc;

    /**
     * @var integer
     *
     * @Column(name="txpaidf2758", type="bigint", nullable=true)
     */
    private $txpaidf2758;

    /**
     * @var integer
     *
     * @Column(name="erronbkupwthld", type="bigint", nullable=true)
     */
    private $erronbkupwthld;

    /**
     * @var integer
     *
     * @Column(name="estpnlty", type="bigint", nullable=true)
     */
    private $estpnlty;

    /**
     * @var integer
     *
     * @Column(name="taxdue", type="bigint", nullable=true)
     */
    private $taxdue;

    /**
     * @var integer
     *
     * @Column(name="overpay", type="bigint", nullable=true)
     */
    private $overpay;

    /**
     * @var integer
     *
     * @Column(name="crelamt", type="bigint", nullable=true)
     */
    private $crelamt;

    /**
     * @var string
     *
     * @Column(name="infleg", type="string", length=1, nullable=true)
     */
    private $infleg;

    /**
     * @var string
     *
     * @Column(name="actnotpr", type="string", length=1, nullable=true)
     */
    private $actnotpr;

    /**
     * @var string
     *
     * @Column(name="chgnprvrptcd", type="string", length=1, nullable=true)
     */
    private $chgnprvrptcd;

    /**
     * @var string
     *
     * @Column(name="filedf990tcd", type="string", length=1, nullable=true)
     */
    private $filedf990tcd;

    /**
     * @var string
     *
     * @Column(name="contractncd", type="string", length=1, nullable=true)
     */
    private $contractncd;

    /**
     * @var string
     *
     * @Column(name="furnishcpycd", type="string", length=1, nullable=true)
     */
    private $furnishcpycd;

    /**
     * @var string
     *
     * @Column(name="claimstatcd", type="string", length=1, nullable=true)
     */
    private $claimstatcd;

    /**
     * @var string
     *
     * @Column(name="cntrbtrstxyrcd", type="string", length=1, nullable=true)
     */
    private $cntrbtrstxyrcd;

    /**
     * @var string
     *
     * @Column(name="acqdrindrintcd", type="string", length=1, nullable=true)
     */
    private $acqdrindrintcd;

    /**
     * @var string
     *
     * @Column(name="orgcmplypubcd", type="string", length=1, nullable=true)
     */
    private $orgcmplypubcd;

    /**
     * @var string
     *
     * @Column(name="filedlf1041ind", type="string", length=1, nullable=true)
     */
    private $filedlf1041ind;

    /**
     * @var string
     *
     * @Column(name="propexchcd", type="string", length=1, nullable=true)
     */
    private $propexchcd;

    /**
     * @var string
     *
     * @Column(name="brwlndmnycd", type="string", length=1, nullable=true)
     */
    private $brwlndmnycd;

    /**
     * @var string
     *
     * @Column(name="furngoodscd", type="string", length=1, nullable=true)
     */
    private $furngoodscd;

    /**
     * @var string
     *
     * @Column(name="paidcmpncd", type="string", length=1, nullable=true)
     */
    private $paidcmpncd;

    /**
     * @var string
     *
     * @Column(name="transfercd", type="string", length=1, nullable=true)
     */
    private $transfercd;

    /**
     * @var string
     *
     * @Column(name="agremkpaycd", type="string", length=1, nullable=true)
     */
    private $agremkpaycd;

    /**
     * @var string
     *
     * @Column(name="exceptactsind", type="string", length=1, nullable=true)
     */
    private $exceptactsind;

    /**
     * @var string
     *
     * @Column(name="prioractvcd", type="string", length=1, nullable=true)
     */
    private $prioractvcd;

    /**
     * @var string
     *
     * @Column(name="undistrinccd", type="string", length=1, nullable=true)
     */
    private $undistrinccd;

    /**
     * @var string
     *
     * @Column(name="applyprovind", type="string", length=1, nullable=true)
     */
    private $applyprovind;

    /**
     * @var string
     *
     * @Column(name="dirindirintcd", type="string", length=1, nullable=true)
     */
    private $dirindirintcd;

    /**
     * @var string
     *
     * @Column(name="excesshldcd", type="string", length=1, nullable=true)
     */
    private $excesshldcd;

    /**
     * @var string
     *
     * @Column(name="invstjexmptcd", type="string", length=1, nullable=true)
     */
    private $invstjexmptcd;

    /**
     * @var string
     *
     * @Column(name="prevjexmptcd", type="string", length=1, nullable=true)
     */
    private $prevjexmptcd;

    /**
     * @var string
     *
     * @Column(name="propgndacd", type="string", length=1, nullable=true)
     */
    private $propgndacd;

    /**
     * @var string
     *
     * @Column(name="ipubelectcd", type="string", length=1, nullable=true)
     */
    private $ipubelectcd;

    /**
     * @var string
     *
     * @Column(name="grntindivcd", type="string", length=1, nullable=true)
     */
    private $grntindivcd;

    /**
     * @var string
     *
     * @Column(name="nchrtygrntcd", type="string", length=1, nullable=true)
     */
    private $nchrtygrntcd;

    /**
     * @var string
     *
     * @Column(name="nreligiouscd", type="string", length=1, nullable=true)
     */
    private $nreligiouscd;

    /**
     * @var string
     *
     * @Column(name="excptransind", type="string", length=1, nullable=true)
     */
    private $excptransind;

    /**
     * @var string
     *
     * @Column(name="rfprsnlbnftind", type="string", length=1, nullable=true)
     */
    private $rfprsnlbnftind;

    /**
     * @var string
     *
     * @Column(name="pyprsnlbnftind", type="string", length=1, nullable=true)
     */
    private $pyprsnlbnftind;

    /**
     * @var integer
     *
     * @Column(name="tfairmrktunuse", type="bigint", nullable=true)
     */
    private $tfairmrktunuse;

    /**
     * @var integer
     *
     * @Column(name="valncharitassets", type="bigint", nullable=true)
     */
    private $valncharitassets;

    /**
     * @var integer
     *
     * @Column(name="cmpmininvstret", type="bigint", nullable=true)
     */
    private $cmpmininvstret;

    /**
     * @var integer
     *
     * @Column(name="distribamt", type="bigint", nullable=true)
     */
    private $distribamt;

    /**
     * @var integer
     *
     * @Column(name="undistribincyr", type="bigint", nullable=true)
     */
    private $undistribincyr;

    /**
     * @var integer
     *
     * @Column(name="adjnetinccola", type="bigint", nullable=true)
     */
    private $adjnetinccola;

    /**
     * @var integer
     *
     * @Column(name="adjnetinccolb", type="bigint", nullable=true)
     */
    private $adjnetinccolb;

    /**
     * @var integer
     *
     * @Column(name="adjnetinccolc", type="bigint", nullable=true)
     */
    private $adjnetinccolc;

    /**
     * @var integer
     *
     * @Column(name="adjnetinccold", type="bigint", nullable=true)
     */
    private $adjnetinccold;

    /**
     * @var integer
     *
     * @Column(name="adjnetinctot", type="bigint", nullable=true)
     */
    private $adjnetinctot;

    /**
     * @var integer
     *
     * @Column(name="qlfydistriba", type="bigint", nullable=true)
     */
    private $qlfydistriba;

    /**
     * @var integer
     *
     * @Column(name="qlfydistribb", type="bigint", nullable=true)
     */
    private $qlfydistribb;

    /**
     * @var integer
     *
     * @Column(name="qlfydistribc", type="bigint", nullable=true)
     */
    private $qlfydistribc;

    /**
     * @var integer
     *
     * @Column(name="qlfydistribd", type="bigint", nullable=true)
     */
    private $qlfydistribd;

    /**
     * @var integer
     *
     * @Column(name="qlfydistribtot", type="bigint", nullable=true)
     */
    private $qlfydistribtot;

    /**
     * @var integer
     *
     * @Column(name="valassetscola", type="bigint", nullable=true)
     */
    private $valassetscola;

    /**
     * @var integer
     *
     * @Column(name="valassetscolb", type="bigint", nullable=true)
     */
    private $valassetscolb;

    /**
     * @var integer
     *
     * @Column(name="valassetscolc", type="bigint", nullable=true)
     */
    private $valassetscolc;

    /**
     * @var integer
     *
     * @Column(name="valassetscold", type="bigint", nullable=true)
     */
    private $valassetscold;

    /**
     * @var integer
     *
     * @Column(name="valassetstot", type="bigint", nullable=true)
     */
    private $valassetstot;

    /**
     * @var integer
     *
     * @Column(name="qlfyasseta", type="bigint", nullable=true)
     */
    private $qlfyasseta;

    /**
     * @var integer
     *
     * @Column(name="qlfyassetb", type="bigint", nullable=true)
     */
    private $qlfyassetb;

    /**
     * @var integer
     *
     * @Column(name="qlfyassetc", type="bigint", nullable=true)
     */
    private $qlfyassetc;

    /**
     * @var integer
     *
     * @Column(name="qlfyassetd", type="bigint", nullable=true)
     */
    private $qlfyassetd;

    /**
     * @var integer
     *
     * @Column(name="qlfyassettot", type="bigint", nullable=true)
     */
    private $qlfyassettot;

    /**
     * @var integer
     *
     * @Column(name="endwmntscola", type="bigint", nullable=true)
     */
    private $endwmntscola;

    /**
     * @var integer
     *
     * @Column(name="endwmntscolb", type="bigint", nullable=true)
     */
    private $endwmntscolb;

    /**
     * @var integer
     *
     * @Column(name="endwmntscolc", type="bigint", nullable=true)
     */
    private $endwmntscolc;

    /**
     * @var integer
     *
     * @Column(name="endwmntscold", type="bigint", nullable=true)
     */
    private $endwmntscold;

    /**
     * @var integer
     *
     * @Column(name="endwmntstot", type="bigint", nullable=true)
     */
    private $endwmntstot;

    /**
     * @var integer
     *
     * @Column(name="totsuprtcola", type="bigint", nullable=true)
     */
    private $totsuprtcola;

    /**
     * @var integer
     *
     * @Column(name="totsuprtcolb", type="bigint", nullable=true)
     */
    private $totsuprtcolb;

    /**
     * @var integer
     *
     * @Column(name="totsuprtcolc", type="bigint", nullable=true)
     */
    private $totsuprtcolc;

    /**
     * @var integer
     *
     * @Column(name="totsuprtcold", type="bigint", nullable=true)
     */
    private $totsuprtcold;

    /**
     * @var integer
     *
     * @Column(name="totsuprttot", type="bigint", nullable=true)
     */
    private $totsuprttot;

    /**
     * @var integer
     *
     * @Column(name="pubsuprtcola", type="bigint", nullable=true)
     */
    private $pubsuprtcola;

    /**
     * @var integer
     *
     * @Column(name="pubsuprtcolb", type="bigint", nullable=true)
     */
    private $pubsuprtcolb;

    /**
     * @var integer
     *
     * @Column(name="pubsuprtcolc", type="bigint", nullable=true)
     */
    private $pubsuprtcolc;

    /**
     * @var integer
     *
     * @Column(name="pubsuprtcold", type="bigint", nullable=true)
     */
    private $pubsuprtcold;

    /**
     * @var integer
     *
     * @Column(name="pubsuprttot", type="bigint", nullable=true)
     */
    private $pubsuprttot;

    /**
     * @var integer
     *
     * @Column(name="grsinvstinca", type="bigint", nullable=true)
     */
    private $grsinvstinca;

    /**
     * @var integer
     *
     * @Column(name="grsinvstincb", type="bigint", nullable=true)
     */
    private $grsinvstincb;

    /**
     * @var integer
     *
     * @Column(name="grsinvstincc", type="bigint", nullable=true)
     */
    private $grsinvstincc;

    /**
     * @var integer
     *
     * @Column(name="grsinvstincd", type="bigint", nullable=true)
     */
    private $grsinvstincd;

    /**
     * @var integer
     *
     * @Column(name="grsinvstinctot", type="bigint", nullable=true)
     */
    private $grsinvstinctot;

    /**
     * @var integer
     *
     * @Column(name="grntapprvfut", type="bigint", nullable=true)
     */
    private $grntapprvfut;

    /**
     * @var integer
     *
     * @Column(name="progsrvcacold", type="bigint", nullable=true)
     */
    private $progsrvcacold;

    /**
     * @var integer
     *
     * @Column(name="progsrvcacole", type="bigint", nullable=true)
     */
    private $progsrvcacole;

    /**
     * @var integer
     *
     * @Column(name="progsrvcbcold", type="bigint", nullable=true)
     */
    private $progsrvcbcold;

    /**
     * @var integer
     *
     * @Column(name="progsrvcbcole", type="bigint", nullable=true)
     */
    private $progsrvcbcole;

    /**
     * @var integer
     *
     * @Column(name="progsrvcccold", type="bigint", nullable=true)
     */
    private $progsrvcccold;

    /**
     * @var integer
     *
     * @Column(name="progsrvcccole", type="bigint", nullable=true)
     */
    private $progsrvcccole;

    /**
     * @var integer
     *
     * @Column(name="progsrvcdcold", type="bigint", nullable=true)
     */
    private $progsrvcdcold;

    /**
     * @var integer
     *
     * @Column(name="progsrvcdcole", type="bigint", nullable=true)
     */
    private $progsrvcdcole;

    /**
     * @var integer
     *
     * @Column(name="progsrvcecold", type="bigint", nullable=true)
     */
    private $progsrvcecold;

    /**
     * @var integer
     *
     * @Column(name="progsrvcecole", type="bigint", nullable=true)
     */
    private $progsrvcecole;

    /**
     * @var integer
     *
     * @Column(name="progsrvcfcold", type="bigint", nullable=true)
     */
    private $progsrvcfcold;

    /**
     * @var integer
     *
     * @Column(name="progsrvcfcole", type="bigint", nullable=true)
     */
    private $progsrvcfcole;

    /**
     * @var integer
     *
     * @Column(name="progsrvcgcold", type="bigint", nullable=true)
     */
    private $progsrvcgcold;

    /**
     * @var integer
     *
     * @Column(name="progsrvcgcole", type="bigint", nullable=true)
     */
    private $progsrvcgcole;

    /**
     * @var integer
     *
     * @Column(name="membershpduesd", type="bigint", nullable=true)
     */
    private $membershpduesd;

    /**
     * @var integer
     *
     * @Column(name="membershpduese", type="bigint", nullable=true)
     */
    private $membershpduese;

    /**
     * @var integer
     *
     * @Column(name="intonsvngsd", type="bigint", nullable=true)
     */
    private $intonsvngsd;

    /**
     * @var integer
     *
     * @Column(name="intonsvngse", type="bigint", nullable=true)
     */
    private $intonsvngse;

    /**
     * @var integer
     *
     * @Column(name="dvdndsintd", type="bigint", nullable=true)
     */
    private $dvdndsintd;

    /**
     * @var integer
     *
     * @Column(name="dvdndsinte", type="bigint", nullable=true)
     */
    private $dvdndsinte;

    /**
     * @var string
     *
     * @Column(name="trnsfrcashcd", type="string", length=1, nullable=true)
     */
    private $trnsfrcashcd;

    /**
     * @var string
     *
     * @Column(name="trnsothasstscd", type="string", length=1, nullable=true)
     */
    private $trnsothasstscd;

    /**
     * @var string
     *
     * @Column(name="salesasstscd", type="string", length=1, nullable=true)
     */
    private $salesasstscd;

    /**
     * @var string
     *
     * @Column(name="prchsasstscd", type="string", length=1, nullable=true)
     */
    private $prchsasstscd;

    /**
     * @var string
     *
     * @Column(name="rentlsfacltscd", type="string", length=1, nullable=true)
     */
    private $rentlsfacltscd;

    /**
     * @var string
     *
     * @Column(name="reimbrsmntscd", type="string", length=1, nullable=true)
     */
    private $reimbrsmntscd;

    /**
     * @var string
     *
     * @Column(name="loansguarcd", type="string", length=1, nullable=true)
     */
    private $loansguarcd;

    /**
     * @var string
     *
     * @Column(name="perfservicescd", type="string", length=1, nullable=true)
     */
    private $perfservicescd;

    /**
     * @var string
     *
     * @Column(name="sharngasstscd", type="string", length=1, nullable=true)
     */
    private $sharngasstscd;

    /**
     * @param int $accountingfees
     */
    public function setAccountingfees($accountingfees)
    {
        $this->accountingfees = $accountingfees;
    }

    /**
     * @return int
     */
    public function getAccountingfees()
    {
        return $this->accountingfees;
    }

    /**
     * @param string $acqdrindrintcd
     */
    public function setAcqdrindrintcd($acqdrindrintcd)
    {
        $this->acqdrindrintcd = $acqdrindrintcd;
    }

    /**
     * @return string
     */
    public function getAcqdrindrintcd()
    {
        return $this->acqdrindrintcd;
    }

    /**
     * @param string $actnotpr
     */
    public function setActnotpr($actnotpr)
    {
        $this->actnotpr = $actnotpr;
    }

    /**
     * @return string
     */
    public function getActnotpr()
    {
        return $this->actnotpr;
    }

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
     * @param string $applyprovind
     */
    public function setApplyprovind($applyprovind)
    {
        $this->applyprovind = $applyprovind;
    }

    /**
     * @return string
     */
    public function getApplyprovind()
    {
        return $this->applyprovind;
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
     * @param string $chgnprvrptcd
     */
    public function setChgnprvrptcd($chgnprvrptcd)
    {
        $this->chgnprvrptcd = $chgnprvrptcd;
    }

    /**
     * @return string
     */
    public function getChgnprvrptcd()
    {
        return $this->chgnprvrptcd;
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
     * @param string $cntrbtrstxyrcd
     */
    public function setCntrbtrstxyrcd($cntrbtrstxyrcd)
    {
        $this->cntrbtrstxyrcd = $cntrbtrstxyrcd;
    }

    /**
     * @return string
     */
    public function getCntrbtrstxyrcd()
    {
        return $this->cntrbtrstxyrcd;
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
     * @param int $depreciationamt
     */
    public function setDepreciationamt($depreciationamt)
    {
        $this->depreciationamt = $depreciationamt;
    }

    /**
     * @return int
     */
    public function getDepreciationamt()
    {
        return $this->depreciationamt;
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
     * @param int $dvdndsintd
     */
    public function setDvdndsintd($dvdndsintd)
    {
        $this->dvdndsintd = $dvdndsintd;
    }

    /**
     * @return int
     */
    public function getDvdndsintd()
    {
        return $this->dvdndsintd;
    }

    /**
     * @param int $dvdndsinte
     */
    public function setDvdndsinte($dvdndsinte)
    {
        $this->dvdndsinte = $dvdndsinte;
    }

    /**
     * @return int
     */
    public function getDvdndsinte()
    {
        return $this->dvdndsinte;
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
     * @param string $eostatus
     */
    public function setEostatus($eostatus)
    {
        $this->eostatus = $eostatus;
    }

    /**
     * @return string
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
     * @param string $exceptactsind
     */
    public function setExceptactsind($exceptactsind)
    {
        $this->exceptactsind = $exceptactsind;
    }

    /**
     * @return string
     */
    public function getExceptactsind()
    {
        return $this->exceptactsind;
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
     * @param string $excptransind
     */
    public function setExcptransind($excptransind)
    {
        $this->excptransind = $excptransind;
    }

    /**
     * @return string
     */
    public function getExcptransind()
    {
        return $this->excptransind;
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
     * @param string $filedlf1041ind
     */
    public function setFiledlf1041ind($filedlf1041ind)
    {
        $this->filedlf1041ind = $filedlf1041ind;
    }

    /**
     * @return string
     */
    public function getFiledlf1041ind()
    {
        return $this->filedlf1041ind;
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
     * @param string $furnishcpycd
     */
    public function setFurnishcpycd($furnishcpycd)
    {
        $this->furnishcpycd = $furnishcpycd;
    }

    /**
     * @return string
     */
    public function getFurnishcpycd()
    {
        return $this->furnishcpycd;
    }

    /**
     * @param int $grntapprvfut
     */
    public function setGrntapprvfut($grntapprvfut)
    {
        $this->grntapprvfut = $grntapprvfut;
    }

    /**
     * @return int
     */
    public function getGrntapprvfut()
    {
        return $this->grntapprvfut;
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
     * @param int $grsslspramt
     */
    public function setGrsslspramt($grsslspramt)
    {
        $this->grsslspramt = $grsslspramt;
    }

    /**
     * @return int
     */
    public function getGrsslspramt()
    {
        return $this->grsslspramt;
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
     * @param int $interestamt
     */
    public function setInterestamt($interestamt)
    {
        $this->interestamt = $interestamt;
    }

    /**
     * @return int
     */
    public function getInterestamt()
    {
        return $this->interestamt;
    }

    /**
     * @param int $intonsvngsd
     */
    public function setIntonsvngsd($intonsvngsd)
    {
        $this->intonsvngsd = $intonsvngsd;
    }

    /**
     * @return int
     */
    public function getIntonsvngsd()
    {
        return $this->intonsvngsd;
    }

    /**
     * @param int $intonsvngse
     */
    public function setIntonsvngse($intonsvngse)
    {
        $this->intonsvngse = $intonsvngse;
    }

    /**
     * @return int
     */
    public function getIntonsvngse()
    {
        return $this->intonsvngse;
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
     * @param string $ipubelectcd
     */
    public function setIpubelectcd($ipubelectcd)
    {
        $this->ipubelectcd = $ipubelectcd;
    }

    /**
     * @return string
     */
    public function getIpubelectcd()
    {
        return $this->ipubelectcd;
    }

    /**
     * @param int $legalfeesamt
     */
    public function setLegalfeesamt($legalfeesamt)
    {
        $this->legalfeesamt = $legalfeesamt;
    }

    /**
     * @return int
     */
    public function getLegalfeesamt()
    {
        return $this->legalfeesamt;
    }

    /**
     * @param string $loansguarcd
     */
    public function setLoansguarcd($loansguarcd)
    {
        $this->loansguarcd = $loansguarcd;
    }

    /**
     * @return string
     */
    public function getLoansguarcd()
    {
        return $this->loansguarcd;
    }

    /**
     * @param int $membershpduesd
     */
    public function setMembershpduesd($membershpduesd)
    {
        $this->membershpduesd = $membershpduesd;
    }

    /**
     * @return int
     */
    public function getMembershpduesd()
    {
        return $this->membershpduesd;
    }

    /**
     * @param int $membershpduese
     */
    public function setMembershpduese($membershpduese)
    {
        $this->membershpduese = $membershpduese;
    }

    /**
     * @return int
     */
    public function getMembershpduese()
    {
        return $this->membershpduese;
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
     * @param int $occupancyamt
     */
    public function setOccupancyamt($occupancyamt)
    {
        $this->occupancyamt = $occupancyamt;
    }

    /**
     * @return int
     */
    public function getOccupancyamt()
    {
        return $this->occupancyamt;
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
     * @param string $orgcmplypubcd
     */
    public function setOrgcmplypubcd($orgcmplypubcd)
    {
        $this->orgcmplypubcd = $orgcmplypubcd;
    }

    /**
     * @return string
     */
    public function getOrgcmplypubcd()
    {
        return $this->orgcmplypubcd;
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
     * @param int $othrassetseoy
     */
    public function setOthrassetseoy($othrassetseoy)
    {
        $this->othrassetseoy = $othrassetseoy;
    }

    /**
     * @return int
     */
    public function getOthrassetseoy()
    {
        return $this->othrassetseoy;
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
     * @param int $othrliabltseoy
     */
    public function setOthrliabltseoy($othrliabltseoy)
    {
        $this->othrliabltseoy = $othrliabltseoy;
    }

    /**
     * @return int
     */
    public function getOthrliabltseoy()
    {
        return $this->othrliabltseoy;
    }

    /**
     * @param int $overpay
     */
    public function setOverpay($overpay)
    {
        $this->overpay = $overpay;
    }

    /**
     * @return int
     */
    public function getOverpay()
    {
        return $this->overpay;
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
     * @param int $pensplemplbenf
     */
    public function setPensplemplbenf($pensplemplbenf)
    {
        $this->pensplemplbenf = $pensplemplbenf;
    }

    /**
     * @return int
     */
    public function getPensplemplbenf()
    {
        return $this->pensplemplbenf;
    }

    /**
     * @param string $perfservicescd
     */
    public function setPerfservicescd($perfservicescd)
    {
        $this->perfservicescd = $perfservicescd;
    }

    /**
     * @return string
     */
    public function getPerfservicescd()
    {
        return $this->perfservicescd;
    }

    /**
     * @param string $prchsasstscd
     */
    public function setPrchsasstscd($prchsasstscd)
    {
        $this->prchsasstscd = $prchsasstscd;
    }

    /**
     * @return string
     */
    public function getPrchsasstscd()
    {
        return $this->prchsasstscd;
    }

    /**
     * @param string $prevjexmptcd
     */
    public function setPrevjexmptcd($prevjexmptcd)
    {
        $this->prevjexmptcd = $prevjexmptcd;
    }

    /**
     * @return string
     */
    public function getPrevjexmptcd()
    {
        return $this->prevjexmptcd;
    }

    /**
     * @param int $printingpubl
     */
    public function setPrintingpubl($printingpubl)
    {
        $this->printingpubl = $printingpubl;
    }

    /**
     * @return int
     */
    public function getPrintingpubl()
    {
        return $this->printingpubl;
    }

    /**
     * @param string $prioractvcd
     */
    public function setPrioractvcd($prioractvcd)
    {
        $this->prioractvcd = $prioractvcd;
    }

    /**
     * @return string
     */
    public function getPrioractvcd()
    {
        return $this->prioractvcd;
    }

    /**
     * @param int $progsrvcacold
     */
    public function setProgsrvcacold($progsrvcacold)
    {
        $this->progsrvcacold = $progsrvcacold;
    }

    /**
     * @return int
     */
    public function getProgsrvcacold()
    {
        return $this->progsrvcacold;
    }

    /**
     * @param int $progsrvcacole
     */
    public function setProgsrvcacole($progsrvcacole)
    {
        $this->progsrvcacole = $progsrvcacole;
    }

    /**
     * @return int
     */
    public function getProgsrvcacole()
    {
        return $this->progsrvcacole;
    }

    /**
     * @param int $progsrvcbcold
     */
    public function setProgsrvcbcold($progsrvcbcold)
    {
        $this->progsrvcbcold = $progsrvcbcold;
    }

    /**
     * @return int
     */
    public function getProgsrvcbcold()
    {
        return $this->progsrvcbcold;
    }

    /**
     * @param int $progsrvcbcole
     */
    public function setProgsrvcbcole($progsrvcbcole)
    {
        $this->progsrvcbcole = $progsrvcbcole;
    }

    /**
     * @return int
     */
    public function getProgsrvcbcole()
    {
        return $this->progsrvcbcole;
    }

    /**
     * @param int $progsrvcccold
     */
    public function setProgsrvcccold($progsrvcccold)
    {
        $this->progsrvcccold = $progsrvcccold;
    }

    /**
     * @return int
     */
    public function getProgsrvcccold()
    {
        return $this->progsrvcccold;
    }

    /**
     * @param int $progsrvcccole
     */
    public function setProgsrvcccole($progsrvcccole)
    {
        $this->progsrvcccole = $progsrvcccole;
    }

    /**
     * @return int
     */
    public function getProgsrvcccole()
    {
        return $this->progsrvcccole;
    }

    /**
     * @param int $progsrvcdcold
     */
    public function setProgsrvcdcold($progsrvcdcold)
    {
        $this->progsrvcdcold = $progsrvcdcold;
    }

    /**
     * @return int
     */
    public function getProgsrvcdcold()
    {
        return $this->progsrvcdcold;
    }

    /**
     * @param int $progsrvcdcole
     */
    public function setProgsrvcdcole($progsrvcdcole)
    {
        $this->progsrvcdcole = $progsrvcdcole;
    }

    /**
     * @return int
     */
    public function getProgsrvcdcole()
    {
        return $this->progsrvcdcole;
    }

    /**
     * @param int $progsrvcecold
     */
    public function setProgsrvcecold($progsrvcecold)
    {
        $this->progsrvcecold = $progsrvcecold;
    }

    /**
     * @return int
     */
    public function getProgsrvcecold()
    {
        return $this->progsrvcecold;
    }

    /**
     * @param int $progsrvcecole
     */
    public function setProgsrvcecole($progsrvcecole)
    {
        $this->progsrvcecole = $progsrvcecole;
    }

    /**
     * @return int
     */
    public function getProgsrvcecole()
    {
        return $this->progsrvcecole;
    }

    /**
     * @param int $progsrvcfcold
     */
    public function setProgsrvcfcold($progsrvcfcold)
    {
        $this->progsrvcfcold = $progsrvcfcold;
    }

    /**
     * @return int
     */
    public function getProgsrvcfcold()
    {
        return $this->progsrvcfcold;
    }

    /**
     * @param int $progsrvcfcole
     */
    public function setProgsrvcfcole($progsrvcfcole)
    {
        $this->progsrvcfcole = $progsrvcfcole;
    }

    /**
     * @return int
     */
    public function getProgsrvcfcole()
    {
        return $this->progsrvcfcole;
    }

    /**
     * @param int $progsrvcgcold
     */
    public function setProgsrvcgcold($progsrvcgcold)
    {
        $this->progsrvcgcold = $progsrvcgcold;
    }

    /**
     * @return int
     */
    public function getProgsrvcgcold()
    {
        return $this->progsrvcgcold;
    }

    /**
     * @param int $progsrvcgcole
     */
    public function setProgsrvcgcole($progsrvcgcole)
    {
        $this->progsrvcgcole = $progsrvcgcole;
    }

    /**
     * @return int
     */
    public function getProgsrvcgcole()
    {
        return $this->progsrvcgcole;
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
     * @param string $pyprsnlbnftind
     */
    public function setPyprsnlbnftind($pyprsnlbnftind)
    {
        $this->pyprsnlbnftind = $pyprsnlbnftind;
    }

    /**
     * @return string
     */
    public function getPyprsnlbnftind()
    {
        return $this->pyprsnlbnftind;
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
     * @param string $reimbrsmntscd
     */
    public function setReimbrsmntscd($reimbrsmntscd)
    {
        $this->reimbrsmntscd = $reimbrsmntscd;
    }

    /**
     * @return string
     */
    public function getReimbrsmntscd()
    {
        return $this->reimbrsmntscd;
    }

    /**
     * @param string $rentlsfacltscd
     */
    public function setRentlsfacltscd($rentlsfacltscd)
    {
        $this->rentlsfacltscd = $rentlsfacltscd;
    }

    /**
     * @return string
     */
    public function getRentlsfacltscd()
    {
        return $this->rentlsfacltscd;
    }

    /**
     * @param string $rfprsnlbnftind
     */
    public function setRfprsnlbnftind($rfprsnlbnftind)
    {
        $this->rfprsnlbnftind = $rfprsnlbnftind;
    }

    /**
     * @return string
     */
    public function getRfprsnlbnftind()
    {
        return $this->rfprsnlbnftind;
    }

    /**
     * @param string $salesasstscd
     */
    public function setSalesasstscd($salesasstscd)
    {
        $this->salesasstscd = $salesasstscd;
    }

    /**
     * @return string
     */
    public function getSalesasstscd()
    {
        return $this->salesasstscd;
    }

    /**
     * @param string $schedbind
     */
    public function setSchedbind($schedbind)
    {
        $this->schedbind = $schedbind;
    }

    /**
     * @return string
     */
    public function getSchedbind()
    {
        return $this->schedbind;
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
     * @param string $sharngasstscd
     */
    public function setSharngasstscd($sharngasstscd)
    {
        $this->sharngasstscd = $sharngasstscd;
    }

    /**
     * @return string
     */
    public function getSharngasstscd()
    {
        return $this->sharngasstscd;
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
     * @param string $taxPrd
     */
    public function setTaxPrd($taxPrd)
    {
        $this->taxPrd = $taxPrd;
    }

    /**
     * @return string
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
     * @param int $taxdue
     */
    public function setTaxdue($taxdue)
    {
        $this->taxdue = $taxdue;
    }

    /**
     * @return int
     */
    public function getTaxdue()
    {
        return $this->taxdue;
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
     * @param int $totexcapgnls
     */
    public function setTotexcapgnls($totexcapgnls)
    {
        $this->totexcapgnls = $totexcapgnls;
    }

    /**
     * @return int
     */
    public function getTotexcapgnls()
    {
        return $this->totexcapgnls;
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
     * @param string $transfercd
     */
    public function setTransfercd($transfercd)
    {
        $this->transfercd = $transfercd;
    }

    /**
     * @return string
     */
    public function getTransfercd()
    {
        return $this->transfercd;
    }

    /**
     * @param int $travlconfmtngs
     */
    public function setTravlconfmtngs($travlconfmtngs)
    {
        $this->travlconfmtngs = $travlconfmtngs;
    }

    /**
     * @return int
     */
    public function getTravlconfmtngs()
    {
        return $this->travlconfmtngs;
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
     * @param string $trnsfrcashcd
     */
    public function setTrnsfrcashcd($trnsfrcashcd)
    {
        $this->trnsfrcashcd = $trnsfrcashcd;
    }

    /**
     * @return string
     */
    public function getTrnsfrcashcd()
    {
        return $this->trnsfrcashcd;
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

    /**
     * @param int $valncharitassets
     */
    public function setValncharitassets($valncharitassets)
    {
        $this->valncharitassets = $valncharitassets;
    }

    /**
     * @return int
     */
    public function getValncharitassets()
    {
        return $this->valncharitassets;
    }


}
