<?php

namespace Entity;

/**
 * Irs990
 *
 * @Table(name="irs_990", indexes={@Index(name="irs_990_ein_idx", columns={"ein"})})
 * @Entity
 */
class Irs990 extends BaseEntity {
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
     * @var string
     *
     * @Column(name="s501c3or4947a1cd", type="string", length=1, nullable=true)
     */
    private $s501c3or4947a1cd;

    /**
     * @var string
     *
     * @Column(name="schdbind", type="string", length=1, nullable=true)
     */
    private $schdbind;

    /**
     * @var string
     *
     * @Column(name="politicalactvtscd", type="string", length=1, nullable=true)
     */
    private $politicalactvtscd;

    /**
     * @var string
     *
     * @Column(name="lbbyingactvtscd", type="string", length=1, nullable=true)
     */
    private $lbbyingactvtscd;

    /**
     * @var string
     *
     * @Column(name="subjto6033cd", type="string", length=1, nullable=true)
     */
    private $subjto6033cd;

    /**
     * @var string
     *
     * @Column(name="dnradvisedfundscd", type="string", length=1, nullable=true)
     */
    private $dnradvisedfundscd;

    /**
     * @var string
     *
     * @Column(name="prptyintrcvdcd", type="string", length=1, nullable=true)
     */
    private $prptyintrcvdcd;

    /**
     * @var string
     *
     * @Column(name="maintwrkofartcd", type="string", length=1, nullable=true)
     */
    private $maintwrkofartcd;

    /**
     * @var string
     *
     * @Column(name="crcounselingqstncd", type="string", length=1, nullable=true)
     */
    private $crcounselingqstncd;

    /**
     * @var string
     *
     * @Column(name="hldassetsintermpermcd", type="string", length=1, nullable=true)
     */
    private $hldassetsintermpermcd;

    /**
     * @var string
     *
     * @Column(name="rptlndbldgeqptcd", type="string", length=1, nullable=true)
     */
    private $rptlndbldgeqptcd;

    /**
     * @var string
     *
     * @Column(name="rptinvstothsecd", type="string", length=1, nullable=true)
     */
    private $rptinvstothsecd;

    /**
     * @var string
     *
     * @Column(name="rptinvstprgrelcd", type="string", length=1, nullable=true)
     */
    private $rptinvstprgrelcd;

    /**
     * @var string
     *
     * @Column(name="rptothasstcd", type="string", length=1, nullable=true)
     */
    private $rptothasstcd;

    /**
     * @var string
     *
     * @Column(name="rptothliabcd", type="string", length=1, nullable=true)
     */
    private $rptothliabcd;

    /**
     * @var string
     *
     * @Column(name="sepcnsldtfinstmtcd", type="string", length=1, nullable=true)
     */
    private $sepcnsldtfinstmtcd;

    /**
     * @var string
     *
     * @Column(name="sepindaudfinstmtcd", type="string", length=1, nullable=true)
     */
    private $sepindaudfinstmtcd;

    /**
     * @var string
     *
     * @Column(name="inclinfinstmtcd", type="string", length=1, nullable=true)
     */
    private $inclinfinstmtcd;

    /**
     * @var string
     *
     * @Column(name="operateschools170cd", type="string", length=1, nullable=true)
     */
    private $operateschools170cd;

    /**
     * @var string
     *
     * @Column(name="frgnofficecd", type="string", length=1, nullable=true)
     */
    private $frgnofficecd;

    /**
     * @var string
     *
     * @Column(name="frgnrevexpnscd", type="string", length=1, nullable=true)
     */
    private $frgnrevexpnscd;

    /**
     * @var string
     *
     * @Column(name="frgngrntscd", type="string", length=1, nullable=true)
     */
    private $frgngrntscd;

    /**
     * @var string
     *
     * @Column(name="frgnaggragrntscd", type="string", length=1, nullable=true)
     */
    private $frgnaggragrntscd;

    /**
     * @var string
     *
     * @Column(name="rptprofndrsngfeescd", type="string", length=1, nullable=true)
     */
    private $rptprofndrsngfeescd;

    /**
     * @var string
     *
     * @Column(name="rptincfnndrsngcd", type="string", length=1, nullable=true)
     */
    private $rptincfnndrsngcd;

    /**
     * @var string
     *
     * @Column(name="rptincgamingcd", type="string", length=1, nullable=true)
     */
    private $rptincgamingcd;

    /**
     * @var string
     *
     * @Column(name="operatehosptlcd", type="string", length=1, nullable=true)
     */
    private $operatehosptlcd;

    /**
     * @var string
     *
     * @Column(name="hospaudfinstmtcd", type="string", length=1, nullable=true)
     */
    private $hospaudfinstmtcd;

    /**
     * @var string
     *
     * @Column(name="rptgrntstogovtcd", type="string", length=1, nullable=true)
     */
    private $rptgrntstogovtcd;

    /**
     * @var string
     *
     * @Column(name="rptgrntstoindvcd", type="string", length=1, nullable=true)
     */
    private $rptgrntstoindvcd;

    /**
     * @var string
     *
     * @Column(name="rptyestocompnstncd", type="string", length=1, nullable=true)
     */
    private $rptyestocompnstncd;

    /**
     * @var string
     *
     * @Column(name="txexmptbndcd", type="string", length=1, nullable=true)
     */
    private $txexmptbndcd;

    /**
     * @var string
     *
     * @Column(name="invstproceedscd", type="string", length=1, nullable=true)
     */
    private $invstproceedscd;

    /**
     * @var string
     *
     * @Column(name="maintescrwaccntcd", type="string", length=1, nullable=true)
     */
    private $maintescrwaccntcd;

    /**
     * @var string
     *
     * @Column(name="actonbehalfcd", type="string", length=1, nullable=true)
     */
    private $actonbehalfcd;

    /**
     * @var string
     *
     * @Column(name="engageexcessbnftcd", type="string", length=1, nullable=true)
     */
    private $engageexcessbnftcd;

    /**
     * @var string
     *
     * @Column(name="awarexcessbnftcd", type="string", length=1, nullable=true)
     */
    private $awarexcessbnftcd;

    /**
     * @var string
     *
     * @Column(name="loantofficercd", type="string", length=1, nullable=true)
     */
    private $loantofficercd;

    /**
     * @var string
     *
     * @Column(name="grantoofficercd", type="string", length=1, nullable=true)
     */
    private $grantoofficercd;

    /**
     * @var string
     *
     * @Column(name="dirbusnreltdcd", type="string", length=1, nullable=true)
     */
    private $dirbusnreltdcd;

    /**
     * @var string
     *
     * @Column(name="fmlybusnreltdcd", type="string", length=1, nullable=true)
     */
    private $fmlybusnreltdcd;

    /**
     * @var string
     *
     * @Column(name="servasofficercd", type="string", length=1, nullable=true)
     */
    private $servasofficercd;

    /**
     * @var string
     *
     * @Column(name="recvnoncashcd", type="string", length=1, nullable=true)
     */
    private $recvnoncashcd;

    /**
     * @var string
     *
     * @Column(name="recvartcd", type="string", length=1, nullable=true)
     */
    private $recvartcd;

    /**
     * @var string
     *
     * @Column(name="ceaseoperationscd", type="string", length=1, nullable=true)
     */
    private $ceaseoperationscd;

    /**
     * @var string
     *
     * @Column(name="sellorexchcd", type="string", length=1, nullable=true)
     */
    private $sellorexchcd;

    /**
     * @var string
     *
     * @Column(name="ownsepentcd", type="string", length=1, nullable=true)
     */
    private $ownsepentcd;

    /**
     * @var string
     *
     * @Column(name="reltdorgcd", type="string", length=1, nullable=true)
     */
    private $reltdorgcd;

    /**
     * @var string
     *
     * @Column(name="intincntrlcd", type="string", length=1, nullable=true)
     */
    private $intincntrlcd;

    /**
     * @var string
     *
     * @Column(name="orgtrnsfrcd", type="string", length=1, nullable=true)
     */
    private $orgtrnsfrcd;

    /**
     * @var string
     *
     * @Column(name="conduct5percentcd", type="string", length=1, nullable=true)
     */
    private $conduct5percentcd;

    /**
     * @var string
     *
     * @Column(name="compltschocd", type="string", length=1, nullable=true)
     */
    private $compltschocd;

    /**
     * @var integer
     *
     * @Column(name="f1096cnt", type="bigint", nullable=true)
     */
    private $f1096cnt;

    /**
     * @var integer
     *
     * @Column(name="fw2gcnt", type="bigint", nullable=true)
     */
    private $fw2gcnt;

    /**
     * @var string
     *
     * @Column(name="wthldngrulescd", type="string", length=1, nullable=true)
     */
    private $wthldngrulescd;

    /**
     * @var integer
     *
     * @Column(name="noemplyeesw3cnt", type="bigint", nullable=true)
     */
    private $noemplyeesw3cnt;

    /**
     * @var string
     *
     * @Column(name="filerqrdrtnscd", type="string", length=1, nullable=true)
     */
    private $filerqrdrtnscd;

    /**
     * @var string
     *
     * @Column(name="unrelbusinccd", type="string", length=1, nullable=true)
     */
    private $unrelbusinccd;

    /**
     * @var string
     *
     * @Column(name="filedf990tcd", type="string", length=1, nullable=true)
     */
    private $filedf990tcd;

    /**
     * @var string
     *
     * @Column(name="frgnacctcd", type="string", length=1, nullable=true)
     */
    private $frgnacctcd;

    /**
     * @var string
     *
     * @Column(name="prohibtdtxshltrcd", type="string", length=1, nullable=true)
     */
    private $prohibtdtxshltrcd;

    /**
     * @var string
     *
     * @Column(name="prtynotifyorgcd", type="string", length=1, nullable=true)
     */
    private $prtynotifyorgcd;

    /**
     * @var string
     *
     * @Column(name="filedf8886tcd", type="string", length=1, nullable=true)
     */
    private $filedf8886tcd;

    /**
     * @var string
     *
     * @Column(name="solicitcntrbcd", type="string", length=1, nullable=true)
     */
    private $solicitcntrbcd;

    /**
     * @var string
     *
     * @Column(name="exprstmntcd", type="string", length=1, nullable=true)
     */
    private $exprstmntcd;

    /**
     * @var string
     *
     * @Column(name="providegoodscd", type="string", length=1, nullable=true)
     */
    private $providegoodscd;

    /**
     * @var string
     *
     * @Column(name="notfydnrvalcd", type="string", length=1, nullable=true)
     */
    private $notfydnrvalcd;

    /**
     * @var string
     *
     * @Column(name="filedf8282cd", type="string", length=1, nullable=true)
     */
    private $filedf8282cd;

    /**
     * @var integer
     *
     * @Column(name="f8282cnt", type="bigint", nullable=true)
     */
    private $f8282cnt;

    /**
     * @var string
     *
     * @Column(name="fndsrcvdcd", type="string", length=1, nullable=true)
     */
    private $fndsrcvdcd;

    /**
     * @var string
     *
     * @Column(name="premiumspaidcd", type="string", length=1, nullable=true)
     */
    private $premiumspaidcd;

    /**
     * @var string
     *
     * @Column(name="filedf8899cd", type="string", length=1, nullable=true)
     */
    private $filedf8899cd;

    /**
     * @var string
     *
     * @Column(name="filedf1098ccd", type="string", length=1, nullable=true)
     */
    private $filedf1098ccd;

    /**
     * @var string
     *
     * @Column(name="excbushldngscd", type="string", length=1, nullable=true)
     */
    private $excbushldngscd;

    /**
     * @var string
     *
     * @Column(name="s4966distribcd", type="string", length=1, nullable=true)
     */
    private $s4966distribcd;

    /**
     * @var string
     *
     * @Column(name="distribtodonorcd", type="string", length=1, nullable=true)
     */
    private $distribtodonorcd;

    /**
     * @var integer
     *
     * @Column(name="initiationfees", type="bigint", nullable=true)
     */
    private $initiationfees;

    /**
     * @var integer
     *
     * @Column(name="grsrcptspublicuse", type="bigint", nullable=true)
     */
    private $grsrcptspublicuse;

    /**
     * @var integer
     *
     * @Column(name="grsincmembers", type="bigint", nullable=true)
     */
    private $grsincmembers;

    /**
     * @var integer
     *
     * @Column(name="grsincother", type="bigint", nullable=true)
     */
    private $grsincother;

    /**
     * @var string
     *
     * @Column(name="filedlieuf1041cd", type="string", length=1, nullable=true)
     */
    private $filedlieuf1041cd;

    /**
     * @var integer
     *
     * @Column(name="txexmptint", type="bigint", nullable=true)
     */
    private $txexmptint;

    /**
     * @var string
     *
     * @Column(name="qualhlthplncd", type="string", length=1, nullable=true)
     */
    private $qualhlthplncd;

    /**
     * @var integer
     *
     * @Column(name="qualhlthreqmntn", type="bigint", nullable=true)
     */
    private $qualhlthreqmntn;

    /**
     * @var integer
     *
     * @Column(name="qualhlthonhnd", type="bigint", nullable=true)
     */
    private $qualhlthonhnd;

    /**
     * @var string
     *
     * @Column(name="rcvdpdtngcd", type="string", length=1, nullable=true)
     */
    private $rcvdpdtngcd;

    /**
     * @var string
     *
     * @Column(name="filedf720cd", type="string", length=1, nullable=true)
     */
    private $filedf720cd;

    /**
     * @var integer
     *
     * @Column(name="totreprtabled", type="bigint", nullable=true)
     */
    private $totreprtabled;

    /**
     * @var integer
     *
     * @Column(name="totcomprelatede", type="bigint", nullable=true)
     */
    private $totcomprelatede;

    /**
     * @var integer
     *
     * @Column(name="totestcompf", type="bigint", nullable=true)
     */
    private $totestcompf;

    /**
     * @var integer
     *
     * @Column(name="noindiv100kcnt", type="bigint", nullable=true)
     */
    private $noindiv100kcnt;

    /**
     * @var integer
     *
     * @Column(name="nocontractor100kcnt", type="bigint", nullable=true)
     */
    private $nocontractor100kcnt;

    /**
     * @var integer
     *
     * @Column(name="totcntrbgfts", type="bigint", nullable=true)
     */
    private $totcntrbgfts;

    /**
     * @var integer
     *
     * @Column(name="prgmservcode2acd", type="bigint", nullable=true)
     */
    private $prgmservcode2acd;

    /**
     * @var integer
     *
     * @Column(name="totrev2acola", type="bigint", nullable=true)
     */
    private $totrev2acola;

    /**
     * @var integer
     *
     * @Column(name="prgmservcode2bcd", type="bigint", nullable=true)
     */
    private $prgmservcode2bcd;

    /**
     * @var integer
     *
     * @Column(name="totrev2bcola", type="bigint", nullable=true)
     */
    private $totrev2bcola;

    /**
     * @var integer
     *
     * @Column(name="prgmservcode2ccd", type="bigint", nullable=true)
     */
    private $prgmservcode2ccd;

    /**
     * @var integer
     *
     * @Column(name="totrev2ccola", type="bigint", nullable=true)
     */
    private $totrev2ccola;

    /**
     * @var integer
     *
     * @Column(name="prgmservcode2dcd", type="bigint", nullable=true)
     */
    private $prgmservcode2dcd;

    /**
     * @var integer
     *
     * @Column(name="totrev2dcola", type="bigint", nullable=true)
     */
    private $totrev2dcola;

    /**
     * @var integer
     *
     * @Column(name="prgmservcode2ecd", type="bigint", nullable=true)
     */
    private $prgmservcode2ecd;

    /**
     * @var integer
     *
     * @Column(name="totrev2ecola", type="bigint", nullable=true)
     */
    private $totrev2ecola;

    /**
     * @var integer
     *
     * @Column(name="totrev2fcola", type="bigint", nullable=true)
     */
    private $totrev2fcola;

    /**
     * @var integer
     *
     * @Column(name="totprgmrevnue", type="bigint", nullable=true)
     */
    private $totprgmrevnue;

    /**
     * @var integer
     *
     * @Column(name="invstmntinc", type="bigint", nullable=true)
     */
    private $invstmntinc;

    /**
     * @var integer
     *
     * @Column(name="txexmptbndsproceeds", type="bigint", nullable=true)
     */
    private $txexmptbndsproceeds;

    /**
     * @var integer
     *
     * @Column(name="royaltsinc", type="bigint", nullable=true)
     */
    private $royaltsinc;

    /**
     * @var integer
     *
     * @Column(name="grsrntsreal", type="bigint", nullable=true)
     */
    private $grsrntsreal;

    /**
     * @var integer
     *
     * @Column(name="grsrntsprsnl", type="bigint", nullable=true)
     */
    private $grsrntsprsnl;

    /**
     * @var integer
     *
     * @Column(name="rntlexpnsreal", type="bigint", nullable=true)
     */
    private $rntlexpnsreal;

    /**
     * @var integer
     *
     * @Column(name="rntlexpnsprsnl", type="bigint", nullable=true)
     */
    private $rntlexpnsprsnl;

    /**
     * @var integer
     *
     * @Column(name="rntlincreal", type="bigint", nullable=true)
     */
    private $rntlincreal;

    /**
     * @var integer
     *
     * @Column(name="rntlincprsnl", type="bigint", nullable=true)
     */
    private $rntlincprsnl;

    /**
     * @var integer
     *
     * @Column(name="netrntlinc", type="bigint", nullable=true)
     */
    private $netrntlinc;

    /**
     * @var integer
     *
     * @Column(name="grsalesecur", type="bigint", nullable=true)
     */
    private $grsalesecur;

    /**
     * @var integer
     *
     * @Column(name="grsalesothr", type="bigint", nullable=true)
     */
    private $grsalesothr;

    /**
     * @var integer
     *
     * @Column(name="cstbasisecur", type="bigint", nullable=true)
     */
    private $cstbasisecur;

    /**
     * @var integer
     *
     * @Column(name="cstbasisothr", type="bigint", nullable=true)
     */
    private $cstbasisothr;

    /**
     * @var integer
     *
     * @Column(name="gnlsecur", type="bigint", nullable=true)
     */
    private $gnlsecur;

    /**
     * @var integer
     *
     * @Column(name="gnlsothr", type="bigint", nullable=true)
     */
    private $gnlsothr;

    /**
     * @var integer
     *
     * @Column(name="netgnls", type="bigint", nullable=true)
     */
    private $netgnls;

    /**
     * @var integer
     *
     * @Column(name="grsincfndrsng", type="bigint", nullable=true)
     */
    private $grsincfndrsng;

    /**
     * @var integer
     *
     * @Column(name="lessdirfndrsng", type="bigint", nullable=true)
     */
    private $lessdirfndrsng;

    /**
     * @var integer
     *
     * @Column(name="netincfndrsng", type="bigint", nullable=true)
     */
    private $netincfndrsng;

    /**
     * @var integer
     *
     * @Column(name="grsincgaming", type="bigint", nullable=true)
     */
    private $grsincgaming;

    /**
     * @var integer
     *
     * @Column(name="lessdirgaming", type="bigint", nullable=true)
     */
    private $lessdirgaming;

    /**
     * @var integer
     *
     * @Column(name="netincgaming", type="bigint", nullable=true)
     */
    private $netincgaming;

    /**
     * @var integer
     *
     * @Column(name="grsalesinvent", type="bigint", nullable=true)
     */
    private $grsalesinvent;

    /**
     * @var integer
     *
     * @Column(name="lesscstofgoods", type="bigint", nullable=true)
     */
    private $lesscstofgoods;

    /**
     * @var integer
     *
     * @Column(name="netincsales", type="bigint", nullable=true)
     */
    private $netincsales;

    /**
     * @var integer
     *
     * @Column(name="miscrev11acd", type="bigint", nullable=true)
     */
    private $miscrev11acd;

    /**
     * @var integer
     *
     * @Column(name="miscrevtota", type="bigint", nullable=true)
     */
    private $miscrevtota;

    /**
     * @var integer
     *
     * @Column(name="miscrev11bcd", type="bigint", nullable=true)
     */
    private $miscrev11bcd;

    /**
     * @var integer
     *
     * @Column(name="miscrevtot11b", type="bigint", nullable=true)
     */
    private $miscrevtot11b;

    /**
     * @var integer
     *
     * @Column(name="miscrev11ccd", type="bigint", nullable=true)
     */
    private $miscrev11ccd;

    /**
     * @var integer
     *
     * @Column(name="miscrevtot11c", type="bigint", nullable=true)
     */
    private $miscrevtot11c;

    /**
     * @var integer
     *
     * @Column(name="miscrevtot11d", type="bigint", nullable=true)
     */
    private $miscrevtot11d;

    /**
     * @var integer
     *
     * @Column(name="miscrevtot11e", type="bigint", nullable=true)
     */
    private $miscrevtot11e;

    /**
     * @var integer
     *
     * @Column(name="totrevenue", type="bigint", nullable=true)
     */
    private $totrevenue;

    /**
     * @var integer
     *
     * @Column(name="grntstogovt", type="bigint", nullable=true)
     */
    private $grntstogovt;

    /**
     * @var integer
     *
     * @Column(name="grnsttoindiv", type="bigint", nullable=true)
     */
    private $grnsttoindiv;

    /**
     * @var integer
     *
     * @Column(name="grntstofrgngovt", type="bigint", nullable=true)
     */
    private $grntstofrgngovt;

    /**
     * @var integer
     *
     * @Column(name="benifitsmembrs", type="bigint", nullable=true)
     */
    private $benifitsmembrs;

    /**
     * @var integer
     *
     * @Column(name="compnsatncurrofcr", type="bigint", nullable=true)
     */
    private $compnsatncurrofcr;

    /**
     * @var integer
     *
     * @Column(name="compnsatnandothr", type="bigint", nullable=true)
     */
    private $compnsatnandothr;

    /**
     * @var integer
     *
     * @Column(name="othrsalwages", type="bigint", nullable=true)
     */
    private $othrsalwages;

    /**
     * @var integer
     *
     * @Column(name="pensionplancontrb", type="bigint", nullable=true)
     */
    private $pensionplancontrb;

    /**
     * @var integer
     *
     * @Column(name="othremplyeebenef", type="bigint", nullable=true)
     */
    private $othremplyeebenef;

    /**
     * @var integer
     *
     * @Column(name="payrolltx", type="bigint", nullable=true)
     */
    private $payrolltx;

    /**
     * @var integer
     *
     * @Column(name="feesforsrvcmgmt", type="bigint", nullable=true)
     */
    private $feesforsrvcmgmt;

    /**
     * @var integer
     *
     * @Column(name="legalfees", type="bigint", nullable=true)
     */
    private $legalfees;

    /**
     * @var integer
     *
     * @Column(name="accntingfees", type="bigint", nullable=true)
     */
    private $accntingfees;

    /**
     * @var integer
     *
     * @Column(name="feesforsrvclobby", type="bigint", nullable=true)
     */
    private $feesforsrvclobby;

    /**
     * @var integer
     *
     * @Column(name="profndraising", type="bigint", nullable=true)
     */
    private $profndraising;

    /**
     * @var integer
     *
     * @Column(name="feesforsrvcinvstmgmt", type="bigint", nullable=true)
     */
    private $feesforsrvcinvstmgmt;

    /**
     * @var integer
     *
     * @Column(name="feesforsrvcothr", type="bigint", nullable=true)
     */
    private $feesforsrvcothr;

    /**
     * @var integer
     *
     * @Column(name="advrtpromo", type="bigint", nullable=true)
     */
    private $advrtpromo;

    /**
     * @var integer
     *
     * @Column(name="officexpns", type="bigint", nullable=true)
     */
    private $officexpns;

    /**
     * @var integer
     *
     * @Column(name="infotech", type="bigint", nullable=true)
     */
    private $infotech;

    /**
     * @var integer
     *
     * @Column(name="royaltsexpns", type="bigint", nullable=true)
     */
    private $royaltsexpns;

    /**
     * @var integer
     *
     * @Column(name="occupancy", type="bigint", nullable=true)
     */
    private $occupancy;

    /**
     * @var integer
     *
     * @Column(name="travel", type="bigint", nullable=true)
     */
    private $travel;

    /**
     * @var integer
     *
     * @Column(name="travelofpublicoffcl", type="bigint", nullable=true)
     */
    private $travelofpublicoffcl;

    /**
     * @var integer
     *
     * @Column(name="converconventmtng", type="bigint", nullable=true)
     */
    private $converconventmtng;

    /**
     * @var integer
     *
     * @Column(name="interestamt", type="bigint", nullable=true)
     */
    private $interestamt;

    /**
     * @var integer
     *
     * @Column(name="pymtoaffiliates", type="bigint", nullable=true)
     */
    private $pymtoaffiliates;

    /**
     * @var integer
     *
     * @Column(name="deprcatndepletn", type="bigint", nullable=true)
     */
    private $deprcatndepletn;

    /**
     * @var integer
     *
     * @Column(name="insurance", type="bigint", nullable=true)
     */
    private $insurance;

    /**
     * @var integer
     *
     * @Column(name="othrexpnsa", type="bigint", nullable=true)
     */
    private $othrexpnsa;

    /**
     * @var integer
     *
     * @Column(name="othrexpnsb", type="bigint", nullable=true)
     */
    private $othrexpnsb;

    /**
     * @var integer
     *
     * @Column(name="othrexpnsc", type="bigint", nullable=true)
     */
    private $othrexpnsc;

    /**
     * @var integer
     *
     * @Column(name="othrexpnsd", type="bigint", nullable=true)
     */
    private $othrexpnsd;

    /**
     * @var integer
     *
     * @Column(name="othrexpnse", type="bigint", nullable=true)
     */
    private $othrexpnse;

    /**
     * @var integer
     *
     * @Column(name="othrexpnsf", type="bigint", nullable=true)
     */
    private $othrexpnsf;

    /**
     * @var integer
     *
     * @Column(name="totfuncexpns", type="bigint", nullable=true)
     */
    private $totfuncexpns;

    /**
     * @var integer
     *
     * @Column(name="nonintcashend", type="bigint", nullable=true)
     */
    private $nonintcashend;

    /**
     * @var integer
     *
     * @Column(name="svngstempinvend", type="bigint", nullable=true)
     */
    private $svngstempinvend;

    /**
     * @var integer
     *
     * @Column(name="pldgegrntrcvblend", type="bigint", nullable=true)
     */
    private $pldgegrntrcvblend;

    /**
     * @var integer
     *
     * @Column(name="accntsrcvblend", type="bigint", nullable=true)
     */
    private $accntsrcvblend;

    /**
     * @var integer
     *
     * @Column(name="currfrmrcvblend", type="bigint", nullable=true)
     */
    private $currfrmrcvblend;

    /**
     * @var integer
     *
     * @Column(name="rcvbldisqualend", type="bigint", nullable=true)
     */
    private $rcvbldisqualend;

    /**
     * @var integer
     *
     * @Column(name="notesloansrcvblend", type="bigint", nullable=true)
     */
    private $notesloansrcvblend;

    /**
     * @var integer
     *
     * @Column(name="invntriesalesend", type="bigint", nullable=true)
     */
    private $invntriesalesend;

    /**
     * @var integer
     *
     * @Column(name="prepaidexpnsend", type="bigint", nullable=true)
     */
    private $prepaidexpnsend;

    /**
     * @var integer
     *
     * @Column(name="lndbldgsequipend", type="bigint", nullable=true)
     */
    private $lndbldgsequipend;

    /**
     * @var integer
     *
     * @Column(name="invstmntsend", type="bigint", nullable=true)
     */
    private $invstmntsend;

    /**
     * @var integer
     *
     * @Column(name="invstmntsothrend", type="bigint", nullable=true)
     */
    private $invstmntsothrend;

    /**
     * @var integer
     *
     * @Column(name="invstmntsprgmend", type="bigint", nullable=true)
     */
    private $invstmntsprgmend;

    /**
     * @var integer
     *
     * @Column(name="intangibleassetsend", type="bigint", nullable=true)
     */
    private $intangibleassetsend;

    /**
     * @var integer
     *
     * @Column(name="othrassetsend", type="bigint", nullable=true)
     */
    private $othrassetsend;

    /**
     * @var integer
     *
     * @Column(name="totassetsend", type="bigint", nullable=true)
     */
    private $totassetsend;

    /**
     * @var integer
     *
     * @Column(name="accntspayableend", type="bigint", nullable=true)
     */
    private $accntspayableend;

    /**
     * @var integer
     *
     * @Column(name="grntspayableend", type="bigint", nullable=true)
     */
    private $grntspayableend;

    /**
     * @var integer
     *
     * @Column(name="deferedrevnuend", type="bigint", nullable=true)
     */
    private $deferedrevnuend;

    /**
     * @var integer
     *
     * @Column(name="txexmptbndsend", type="bigint", nullable=true)
     */
    private $txexmptbndsend;

    /**
     * @var integer
     *
     * @Column(name="escrwaccntliabend", type="bigint", nullable=true)
     */
    private $escrwaccntliabend;

    /**
     * @var integer
     *
     * @Column(name="paybletoffcrsend", type="bigint", nullable=true)
     */
    private $paybletoffcrsend;

    /**
     * @var integer
     *
     * @Column(name="secrdmrtgsend", type="bigint", nullable=true)
     */
    private $secrdmrtgsend;

    /**
     * @var integer
     *
     * @Column(name="unsecurednotesend", type="bigint", nullable=true)
     */
    private $unsecurednotesend;

    /**
     * @var integer
     *
     * @Column(name="othrliabend", type="bigint", nullable=true)
     */
    private $othrliabend;

    /**
     * @var integer
     *
     * @Column(name="totliabend", type="bigint", nullable=true)
     */
    private $totliabend;

    /**
     * @var integer
     *
     * @Column(name="unrstrctnetasstsend", type="bigint", nullable=true)
     */
    private $unrstrctnetasstsend;

    /**
     * @var integer
     *
     * @Column(name="temprstrctnetasstsend", type="bigint", nullable=true)
     */
    private $temprstrctnetasstsend;

    /**
     * @var integer
     *
     * @Column(name="permrstrctnetasstsend", type="bigint", nullable=true)
     */
    private $permrstrctnetasstsend;

    /**
     * @var integer
     *
     * @Column(name="capitalstktrstend", type="bigint", nullable=true)
     */
    private $capitalstktrstend;

    /**
     * @var integer
     *
     * @Column(name="paidinsurplusend", type="bigint", nullable=true)
     */
    private $paidinsurplusend;

    /**
     * @var integer
     *
     * @Column(name="retainedearnend", type="bigint", nullable=true)
     */
    private $retainedearnend;

    /**
     * @var integer
     *
     * @Column(name="totnetassetend", type="bigint", nullable=true)
     */
    private $totnetassetend;

    /**
     * @var integer
     *
     * @Column(name="totnetliabastend", type="bigint", nullable=true)
     */
    private $totnetliabastend;

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
     * @param int $accntingfees
     */
    public function setAccntingfees($accntingfees)
    {
        $this->accntingfees = $accntingfees;
    }

    /**
     * @return int
     */
    public function getAccntingfees()
    {
        return $this->accntingfees;
    }

    /**
     * @param int $accntspayableend
     */
    public function setAccntspayableend($accntspayableend)
    {
        $this->accntspayableend = $accntspayableend;
    }

    /**
     * @return int
     */
    public function getAccntspayableend()
    {
        return $this->accntspayableend;
    }

    /**
     * @param int $accntsrcvblend
     */
    public function setAccntsrcvblend($accntsrcvblend)
    {
        $this->accntsrcvblend = $accntsrcvblend;
    }

    /**
     * @return int
     */
    public function getAccntsrcvblend()
    {
        return $this->accntsrcvblend;
    }

    /**
     * @param string $actonbehalfcd
     */
    public function setActonbehalfcd($actonbehalfcd)
    {
        $this->actonbehalfcd = $actonbehalfcd;
    }

    /**
     * @return string
     */
    public function getActonbehalfcd()
    {
        return $this->actonbehalfcd;
    }

    /**
     * @param int $advrtpromo
     */
    public function setAdvrtpromo($advrtpromo)
    {
        $this->advrtpromo = $advrtpromo;
    }

    /**
     * @return int
     */
    public function getAdvrtpromo()
    {
        return $this->advrtpromo;
    }

    /**
     * @param string $awarexcessbnftcd
     */
    public function setAwarexcessbnftcd($awarexcessbnftcd)
    {
        $this->awarexcessbnftcd = $awarexcessbnftcd;
    }

    /**
     * @return string
     */
    public function getAwarexcessbnftcd()
    {
        return $this->awarexcessbnftcd;
    }

    /**
     * @param int $benifitsmembrs
     */
    public function setBenifitsmembrs($benifitsmembrs)
    {
        $this->benifitsmembrs = $benifitsmembrs;
    }

    /**
     * @return int
     */
    public function getBenifitsmembrs()
    {
        return $this->benifitsmembrs;
    }

    /**
     * @param int $capitalstktrstend
     */
    public function setCapitalstktrstend($capitalstktrstend)
    {
        $this->capitalstktrstend = $capitalstktrstend;
    }

    /**
     * @return int
     */
    public function getCapitalstktrstend()
    {
        return $this->capitalstktrstend;
    }

    /**
     * @param string $ceaseoperationscd
     */
    public function setCeaseoperationscd($ceaseoperationscd)
    {
        $this->ceaseoperationscd = $ceaseoperationscd;
    }

    /**
     * @return string
     */
    public function getCeaseoperationscd()
    {
        return $this->ceaseoperationscd;
    }

    /**
     * @param string $compltschocd
     */
    public function setCompltschocd($compltschocd)
    {
        $this->compltschocd = $compltschocd;
    }

    /**
     * @return string
     */
    public function getCompltschocd()
    {
        return $this->compltschocd;
    }

    /**
     * @param int $compnsatnandothr
     */
    public function setCompnsatnandothr($compnsatnandothr)
    {
        $this->compnsatnandothr = $compnsatnandothr;
    }

    /**
     * @return int
     */
    public function getCompnsatnandothr()
    {
        return $this->compnsatnandothr;
    }

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
     * @param string $conduct5percentcd
     */
    public function setConduct5percentcd($conduct5percentcd)
    {
        $this->conduct5percentcd = $conduct5percentcd;
    }

    /**
     * @return string
     */
    public function getConduct5percentcd()
    {
        return $this->conduct5percentcd;
    }

    /**
     * @param int $converconventmtng
     */
    public function setConverconventmtng($converconventmtng)
    {
        $this->converconventmtng = $converconventmtng;
    }

    /**
     * @return int
     */
    public function getConverconventmtng()
    {
        return $this->converconventmtng;
    }

    /**
     * @param string $crcounselingqstncd
     */
    public function setCrcounselingqstncd($crcounselingqstncd)
    {
        $this->crcounselingqstncd = $crcounselingqstncd;
    }

    /**
     * @return string
     */
    public function getCrcounselingqstncd()
    {
        return $this->crcounselingqstncd;
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
     * @param int $currfrmrcvblend
     */
    public function setCurrfrmrcvblend($currfrmrcvblend)
    {
        $this->currfrmrcvblend = $currfrmrcvblend;
    }

    /**
     * @return int
     */
    public function getCurrfrmrcvblend()
    {
        return $this->currfrmrcvblend;
    }

    /**
     * @param int $deferedrevnuend
     */
    public function setDeferedrevnuend($deferedrevnuend)
    {
        $this->deferedrevnuend = $deferedrevnuend;
    }

    /**
     * @return int
     */
    public function getDeferedrevnuend()
    {
        return $this->deferedrevnuend;
    }

    /**
     * @param int $deprcatndepletn
     */
    public function setDeprcatndepletn($deprcatndepletn)
    {
        $this->deprcatndepletn = $deprcatndepletn;
    }

    /**
     * @return int
     */
    public function getDeprcatndepletn()
    {
        return $this->deprcatndepletn;
    }

    /**
     * @param string $dirbusnreltdcd
     */
    public function setDirbusnreltdcd($dirbusnreltdcd)
    {
        $this->dirbusnreltdcd = $dirbusnreltdcd;
    }

    /**
     * @return string
     */
    public function getDirbusnreltdcd()
    {
        return $this->dirbusnreltdcd;
    }

    /**
     * @param string $distribtodonorcd
     */
    public function setDistribtodonorcd($distribtodonorcd)
    {
        $this->distribtodonorcd = $distribtodonorcd;
    }

    /**
     * @return string
     */
    public function getDistribtodonorcd()
    {
        return $this->distribtodonorcd;
    }

    /**
     * @param string $dnradvisedfundscd
     */
    public function setDnradvisedfundscd($dnradvisedfundscd)
    {
        $this->dnradvisedfundscd = $dnradvisedfundscd;
    }

    /**
     * @return string
     */
    public function getDnradvisedfundscd()
    {
        return $this->dnradvisedfundscd;
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
     * @param string $engageexcessbnftcd
     */
    public function setEngageexcessbnftcd($engageexcessbnftcd)
    {
        $this->engageexcessbnftcd = $engageexcessbnftcd;
    }

    /**
     * @return string
     */
    public function getEngageexcessbnftcd()
    {
        return $this->engageexcessbnftcd;
    }

    /**
     * @param int $escrwaccntliabend
     */
    public function setEscrwaccntliabend($escrwaccntliabend)
    {
        $this->escrwaccntliabend = $escrwaccntliabend;
    }

    /**
     * @return int
     */
    public function getEscrwaccntliabend()
    {
        return $this->escrwaccntliabend;
    }

    /**
     * @param string $excbushldngscd
     */
    public function setExcbushldngscd($excbushldngscd)
    {
        $this->excbushldngscd = $excbushldngscd;
    }

    /**
     * @return string
     */
    public function getExcbushldngscd()
    {
        return $this->excbushldngscd;
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
     * @param string $exprstmntcd
     */
    public function setExprstmntcd($exprstmntcd)
    {
        $this->exprstmntcd = $exprstmntcd;
    }

    /**
     * @return string
     */
    public function getExprstmntcd()
    {
        return $this->exprstmntcd;
    }

    /**
     * @param int $f1096cnt
     */
    public function setF1096cnt($f1096cnt)
    {
        $this->f1096cnt = $f1096cnt;
    }

    /**
     * @return int
     */
    public function getF1096cnt()
    {
        return $this->f1096cnt;
    }

    /**
     * @param int $f8282cnt
     */
    public function setF8282cnt($f8282cnt)
    {
        $this->f8282cnt = $f8282cnt;
    }

    /**
     * @return int
     */
    public function getF8282cnt()
    {
        return $this->f8282cnt;
    }

    /**
     * @param int $feesforsrvcinvstmgmt
     */
    public function setFeesforsrvcinvstmgmt($feesforsrvcinvstmgmt)
    {
        $this->feesforsrvcinvstmgmt = $feesforsrvcinvstmgmt;
    }

    /**
     * @return int
     */
    public function getFeesforsrvcinvstmgmt()
    {
        return $this->feesforsrvcinvstmgmt;
    }

    /**
     * @param int $feesforsrvclobby
     */
    public function setFeesforsrvclobby($feesforsrvclobby)
    {
        $this->feesforsrvclobby = $feesforsrvclobby;
    }

    /**
     * @return int
     */
    public function getFeesforsrvclobby()
    {
        return $this->feesforsrvclobby;
    }

    /**
     * @param int $feesforsrvcmgmt
     */
    public function setFeesforsrvcmgmt($feesforsrvcmgmt)
    {
        $this->feesforsrvcmgmt = $feesforsrvcmgmt;
    }

    /**
     * @return int
     */
    public function getFeesforsrvcmgmt()
    {
        return $this->feesforsrvcmgmt;
    }

    /**
     * @param int $feesforsrvcothr
     */
    public function setFeesforsrvcothr($feesforsrvcothr)
    {
        $this->feesforsrvcothr = $feesforsrvcothr;
    }

    /**
     * @return int
     */
    public function getFeesforsrvcothr()
    {
        return $this->feesforsrvcothr;
    }

    /**
     * @param string $filedf1098ccd
     */
    public function setFiledf1098ccd($filedf1098ccd)
    {
        $this->filedf1098ccd = $filedf1098ccd;
    }

    /**
     * @return string
     */
    public function getFiledf1098ccd()
    {
        return $this->filedf1098ccd;
    }

    /**
     * @param string $filedf720cd
     */
    public function setFiledf720cd($filedf720cd)
    {
        $this->filedf720cd = $filedf720cd;
    }

    /**
     * @return string
     */
    public function getFiledf720cd()
    {
        return $this->filedf720cd;
    }

    /**
     * @param string $filedf8282cd
     */
    public function setFiledf8282cd($filedf8282cd)
    {
        $this->filedf8282cd = $filedf8282cd;
    }

    /**
     * @return string
     */
    public function getFiledf8282cd()
    {
        return $this->filedf8282cd;
    }

    /**
     * @param string $filedf8886tcd
     */
    public function setFiledf8886tcd($filedf8886tcd)
    {
        $this->filedf8886tcd = $filedf8886tcd;
    }

    /**
     * @return string
     */
    public function getFiledf8886tcd()
    {
        return $this->filedf8886tcd;
    }

    /**
     * @param string $filedf8899cd
     */
    public function setFiledf8899cd($filedf8899cd)
    {
        $this->filedf8899cd = $filedf8899cd;
    }

    /**
     * @return string
     */
    public function getFiledf8899cd()
    {
        return $this->filedf8899cd;
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
     * @param string $filedlieuf1041cd
     */
    public function setFiledlieuf1041cd($filedlieuf1041cd)
    {
        $this->filedlieuf1041cd = $filedlieuf1041cd;
    }

    /**
     * @return string
     */
    public function getFiledlieuf1041cd()
    {
        return $this->filedlieuf1041cd;
    }

    /**
     * @param string $filerqrdrtnscd
     */
    public function setFilerqrdrtnscd($filerqrdrtnscd)
    {
        $this->filerqrdrtnscd = $filerqrdrtnscd;
    }

    /**
     * @return string
     */
    public function getFilerqrdrtnscd()
    {
        return $this->filerqrdrtnscd;
    }

    /**
     * @param string $fmlybusnreltdcd
     */
    public function setFmlybusnreltdcd($fmlybusnreltdcd)
    {
        $this->fmlybusnreltdcd = $fmlybusnreltdcd;
    }

    /**
     * @return string
     */
    public function getFmlybusnreltdcd()
    {
        return $this->fmlybusnreltdcd;
    }

    /**
     * @param string $fndsrcvdcd
     */
    public function setFndsrcvdcd($fndsrcvdcd)
    {
        $this->fndsrcvdcd = $fndsrcvdcd;
    }

    /**
     * @return string
     */
    public function getFndsrcvdcd()
    {
        return $this->fndsrcvdcd;
    }

    /**
     * @param string $frgnacctcd
     */
    public function setFrgnacctcd($frgnacctcd)
    {
        $this->frgnacctcd = $frgnacctcd;
    }

    /**
     * @return string
     */
    public function getFrgnacctcd()
    {
        return $this->frgnacctcd;
    }

    /**
     * @param string $frgnaggragrntscd
     */
    public function setFrgnaggragrntscd($frgnaggragrntscd)
    {
        $this->frgnaggragrntscd = $frgnaggragrntscd;
    }

    /**
     * @return string
     */
    public function getFrgnaggragrntscd()
    {
        return $this->frgnaggragrntscd;
    }

    /**
     * @param string $frgngrntscd
     */
    public function setFrgngrntscd($frgngrntscd)
    {
        $this->frgngrntscd = $frgngrntscd;
    }

    /**
     * @return string
     */
    public function getFrgngrntscd()
    {
        return $this->frgngrntscd;
    }

    /**
     * @param string $frgnofficecd
     */
    public function setFrgnofficecd($frgnofficecd)
    {
        $this->frgnofficecd = $frgnofficecd;
    }

    /**
     * @return string
     */
    public function getFrgnofficecd()
    {
        return $this->frgnofficecd;
    }

    /**
     * @param string $frgnrevexpnscd
     */
    public function setFrgnrevexpnscd($frgnrevexpnscd)
    {
        $this->frgnrevexpnscd = $frgnrevexpnscd;
    }

    /**
     * @return string
     */
    public function getFrgnrevexpnscd()
    {
        return $this->frgnrevexpnscd;
    }

    /**
     * @param int $fw2gcnt
     */
    public function setFw2gcnt($fw2gcnt)
    {
        $this->fw2gcnt = $fw2gcnt;
    }

    /**
     * @return int
     */
    public function getFw2gcnt()
    {
        return $this->fw2gcnt;
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
     * @param string $grantoofficercd
     */
    public function setGrantoofficercd($grantoofficercd)
    {
        $this->grantoofficercd = $grantoofficercd;
    }

    /**
     * @return string
     */
    public function getGrantoofficercd()
    {
        return $this->grantoofficercd;
    }

    /**
     * @param int $grnsttoindiv
     */
    public function setGrnsttoindiv($grnsttoindiv)
    {
        $this->grnsttoindiv = $grnsttoindiv;
    }

    /**
     * @return int
     */
    public function getGrnsttoindiv()
    {
        return $this->grnsttoindiv;
    }

    /**
     * @param int $grntspayableend
     */
    public function setGrntspayableend($grntspayableend)
    {
        $this->grntspayableend = $grntspayableend;
    }

    /**
     * @return int
     */
    public function getGrntspayableend()
    {
        return $this->grntspayableend;
    }

    /**
     * @param int $grntstofrgngovt
     */
    public function setGrntstofrgngovt($grntstofrgngovt)
    {
        $this->grntstofrgngovt = $grntstofrgngovt;
    }

    /**
     * @return int
     */
    public function getGrntstofrgngovt()
    {
        return $this->grntstofrgngovt;
    }

    /**
     * @param int $grntstogovt
     */
    public function setGrntstogovt($grntstogovt)
    {
        $this->grntstogovt = $grntstogovt;
    }

    /**
     * @return int
     */
    public function getGrntstogovt()
    {
        return $this->grntstogovt;
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
     * @param string $hldassetsintermpermcd
     */
    public function setHldassetsintermpermcd($hldassetsintermpermcd)
    {
        $this->hldassetsintermpermcd = $hldassetsintermpermcd;
    }

    /**
     * @return string
     */
    public function getHldassetsintermpermcd()
    {
        return $this->hldassetsintermpermcd;
    }

    /**
     * @param string $hospaudfinstmtcd
     */
    public function setHospaudfinstmtcd($hospaudfinstmtcd)
    {
        $this->hospaudfinstmtcd = $hospaudfinstmtcd;
    }

    /**
     * @return string
     */
    public function getHospaudfinstmtcd()
    {
        return $this->hospaudfinstmtcd;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $inclinfinstmtcd
     */
    public function setInclinfinstmtcd($inclinfinstmtcd)
    {
        $this->inclinfinstmtcd = $inclinfinstmtcd;
    }

    /**
     * @return string
     */
    public function getInclinfinstmtcd()
    {
        return $this->inclinfinstmtcd;
    }

    /**
     * @param int $infotech
     */
    public function setInfotech($infotech)
    {
        $this->infotech = $infotech;
    }

    /**
     * @return int
     */
    public function getInfotech()
    {
        return $this->infotech;
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
     * @param int $insurance
     */
    public function setInsurance($insurance)
    {
        $this->insurance = $insurance;
    }

    /**
     * @return int
     */
    public function getInsurance()
    {
        return $this->insurance;
    }

    /**
     * @param int $intangibleassetsend
     */
    public function setIntangibleassetsend($intangibleassetsend)
    {
        $this->intangibleassetsend = $intangibleassetsend;
    }

    /**
     * @return int
     */
    public function getIntangibleassetsend()
    {
        return $this->intangibleassetsend;
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
     * @param string $intincntrlcd
     */
    public function setIntincntrlcd($intincntrlcd)
    {
        $this->intincntrlcd = $intincntrlcd;
    }

    /**
     * @return string
     */
    public function getIntincntrlcd()
    {
        return $this->intincntrlcd;
    }

    /**
     * @param int $invntriesalesend
     */
    public function setInvntriesalesend($invntriesalesend)
    {
        $this->invntriesalesend = $invntriesalesend;
    }

    /**
     * @return int
     */
    public function getInvntriesalesend()
    {
        return $this->invntriesalesend;
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
     * @param int $invstmntsend
     */
    public function setInvstmntsend($invstmntsend)
    {
        $this->invstmntsend = $invstmntsend;
    }

    /**
     * @return int
     */
    public function getInvstmntsend()
    {
        return $this->invstmntsend;
    }

    /**
     * @param int $invstmntsothrend
     */
    public function setInvstmntsothrend($invstmntsothrend)
    {
        $this->invstmntsothrend = $invstmntsothrend;
    }

    /**
     * @return int
     */
    public function getInvstmntsothrend()
    {
        return $this->invstmntsothrend;
    }

    /**
     * @param int $invstmntsprgmend
     */
    public function setInvstmntsprgmend($invstmntsprgmend)
    {
        $this->invstmntsprgmend = $invstmntsprgmend;
    }

    /**
     * @return int
     */
    public function getInvstmntsprgmend()
    {
        return $this->invstmntsprgmend;
    }

    /**
     * @param string $invstproceedscd
     */
    public function setInvstproceedscd($invstproceedscd)
    {
        $this->invstproceedscd = $invstproceedscd;
    }

    /**
     * @return string
     */
    public function getInvstproceedscd()
    {
        return $this->invstproceedscd;
    }

    /**
     * @param string $lbbyingactvtscd
     */
    public function setLbbyingactvtscd($lbbyingactvtscd)
    {
        $this->lbbyingactvtscd = $lbbyingactvtscd;
    }

    /**
     * @return string
     */
    public function getLbbyingactvtscd()
    {
        return $this->lbbyingactvtscd;
    }

    /**
     * @param int $legalfees
     */
    public function setLegalfees($legalfees)
    {
        $this->legalfees = $legalfees;
    }

    /**
     * @return int
     */
    public function getLegalfees()
    {
        return $this->legalfees;
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
     * @param int $lndbldgsequipend
     */
    public function setLndbldgsequipend($lndbldgsequipend)
    {
        $this->lndbldgsequipend = $lndbldgsequipend;
    }

    /**
     * @return int
     */
    public function getLndbldgsequipend()
    {
        return $this->lndbldgsequipend;
    }

    /**
     * @param string $loantofficercd
     */
    public function setLoantofficercd($loantofficercd)
    {
        $this->loantofficercd = $loantofficercd;
    }

    /**
     * @return string
     */
    public function getLoantofficercd()
    {
        return $this->loantofficercd;
    }

    /**
     * @param string $maintescrwaccntcd
     */
    public function setMaintescrwaccntcd($maintescrwaccntcd)
    {
        $this->maintescrwaccntcd = $maintescrwaccntcd;
    }

    /**
     * @return string
     */
    public function getMaintescrwaccntcd()
    {
        return $this->maintescrwaccntcd;
    }

    /**
     * @param string $maintwrkofartcd
     */
    public function setMaintwrkofartcd($maintwrkofartcd)
    {
        $this->maintwrkofartcd = $maintwrkofartcd;
    }

    /**
     * @return string
     */
    public function getMaintwrkofartcd()
    {
        return $this->maintwrkofartcd;
    }

    /**
     * @param int $miscrev11acd
     */
    public function setMiscrev11acd($miscrev11acd)
    {
        $this->miscrev11acd = $miscrev11acd;
    }

    /**
     * @return int
     */
    public function getMiscrev11acd()
    {
        return $this->miscrev11acd;
    }

    /**
     * @param int $miscrev11bcd
     */
    public function setMiscrev11bcd($miscrev11bcd)
    {
        $this->miscrev11bcd = $miscrev11bcd;
    }

    /**
     * @return int
     */
    public function getMiscrev11bcd()
    {
        return $this->miscrev11bcd;
    }

    /**
     * @param int $miscrev11ccd
     */
    public function setMiscrev11ccd($miscrev11ccd)
    {
        $this->miscrev11ccd = $miscrev11ccd;
    }

    /**
     * @return int
     */
    public function getMiscrev11ccd()
    {
        return $this->miscrev11ccd;
    }

    /**
     * @param int $miscrevtot11b
     */
    public function setMiscrevtot11b($miscrevtot11b)
    {
        $this->miscrevtot11b = $miscrevtot11b;
    }

    /**
     * @return int
     */
    public function getMiscrevtot11b()
    {
        return $this->miscrevtot11b;
    }

    /**
     * @param int $miscrevtot11c
     */
    public function setMiscrevtot11c($miscrevtot11c)
    {
        $this->miscrevtot11c = $miscrevtot11c;
    }

    /**
     * @return int
     */
    public function getMiscrevtot11c()
    {
        return $this->miscrevtot11c;
    }

    /**
     * @param int $miscrevtot11d
     */
    public function setMiscrevtot11d($miscrevtot11d)
    {
        $this->miscrevtot11d = $miscrevtot11d;
    }

    /**
     * @return int
     */
    public function getMiscrevtot11d()
    {
        return $this->miscrevtot11d;
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
     * @param int $miscrevtota
     */
    public function setMiscrevtota($miscrevtota)
    {
        $this->miscrevtota = $miscrevtota;
    }

    /**
     * @return int
     */
    public function getMiscrevtota()
    {
        return $this->miscrevtota;
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
     * @param int $nocontractor100kcnt
     */
    public function setNocontractor100kcnt($nocontractor100kcnt)
    {
        $this->nocontractor100kcnt = $nocontractor100kcnt;
    }

    /**
     * @return int
     */
    public function getNocontractor100kcnt()
    {
        return $this->nocontractor100kcnt;
    }

    /**
     * @param int $noemplyeesw3cnt
     */
    public function setNoemplyeesw3cnt($noemplyeesw3cnt)
    {
        $this->noemplyeesw3cnt = $noemplyeesw3cnt;
    }

    /**
     * @return int
     */
    public function getNoemplyeesw3cnt()
    {
        return $this->noemplyeesw3cnt;
    }

    /**
     * @param int $noindiv100kcnt
     */
    public function setNoindiv100kcnt($noindiv100kcnt)
    {
        $this->noindiv100kcnt = $noindiv100kcnt;
    }

    /**
     * @return int
     */
    public function getNoindiv100kcnt()
    {
        return $this->noindiv100kcnt;
    }

    /**
     * @param int $nonintcashend
     */
    public function setNonintcashend($nonintcashend)
    {
        $this->nonintcashend = $nonintcashend;
    }

    /**
     * @return int
     */
    public function getNonintcashend()
    {
        return $this->nonintcashend;
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
     * @param int $notesloansrcvblend
     */
    public function setNotesloansrcvblend($notesloansrcvblend)
    {
        $this->notesloansrcvblend = $notesloansrcvblend;
    }

    /**
     * @return int
     */
    public function getNotesloansrcvblend()
    {
        return $this->notesloansrcvblend;
    }

    /**
     * @param string $notfydnrvalcd
     */
    public function setNotfydnrvalcd($notfydnrvalcd)
    {
        $this->notfydnrvalcd = $notfydnrvalcd;
    }

    /**
     * @return string
     */
    public function getNotfydnrvalcd()
    {
        return $this->notfydnrvalcd;
    }

    /**
     * @param int $occupancy
     */
    public function setOccupancy($occupancy)
    {
        $this->occupancy = $occupancy;
    }

    /**
     * @return int
     */
    public function getOccupancy()
    {
        return $this->occupancy;
    }

    /**
     * @param int $officexpns
     */
    public function setOfficexpns($officexpns)
    {
        $this->officexpns = $officexpns;
    }

    /**
     * @return int
     */
    public function getOfficexpns()
    {
        return $this->officexpns;
    }

    /**
     * @param string $operatehosptlcd
     */
    public function setOperatehosptlcd($operatehosptlcd)
    {
        $this->operatehosptlcd = $operatehosptlcd;
    }

    /**
     * @return string
     */
    public function getOperatehosptlcd()
    {
        return $this->operatehosptlcd;
    }

    /**
     * @param string $operateschools170cd
     */
    public function setOperateschools170cd($operateschools170cd)
    {
        $this->operateschools170cd = $operateschools170cd;
    }

    /**
     * @return string
     */
    public function getOperateschools170cd()
    {
        return $this->operateschools170cd;
    }

    /**
     * @param string $orgtrnsfrcd
     */
    public function setOrgtrnsfrcd($orgtrnsfrcd)
    {
        $this->orgtrnsfrcd = $orgtrnsfrcd;
    }

    /**
     * @return string
     */
    public function getOrgtrnsfrcd()
    {
        return $this->orgtrnsfrcd;
    }

    /**
     * @param int $othrassetsend
     */
    public function setOthrassetsend($othrassetsend)
    {
        $this->othrassetsend = $othrassetsend;
    }

    /**
     * @return int
     */
    public function getOthrassetsend()
    {
        return $this->othrassetsend;
    }

    /**
     * @param int $othremplyeebenef
     */
    public function setOthremplyeebenef($othremplyeebenef)
    {
        $this->othremplyeebenef = $othremplyeebenef;
    }

    /**
     * @return int
     */
    public function getOthremplyeebenef()
    {
        return $this->othremplyeebenef;
    }

    /**
     * @param int $othrexpnsa
     */
    public function setOthrexpnsa($othrexpnsa)
    {
        $this->othrexpnsa = $othrexpnsa;
    }

    /**
     * @return int
     */
    public function getOthrexpnsa()
    {
        return $this->othrexpnsa;
    }

    /**
     * @param int $othrexpnsb
     */
    public function setOthrexpnsb($othrexpnsb)
    {
        $this->othrexpnsb = $othrexpnsb;
    }

    /**
     * @return int
     */
    public function getOthrexpnsb()
    {
        return $this->othrexpnsb;
    }

    /**
     * @param int $othrexpnsc
     */
    public function setOthrexpnsc($othrexpnsc)
    {
        $this->othrexpnsc = $othrexpnsc;
    }

    /**
     * @return int
     */
    public function getOthrexpnsc()
    {
        return $this->othrexpnsc;
    }

    /**
     * @param int $othrexpnsd
     */
    public function setOthrexpnsd($othrexpnsd)
    {
        $this->othrexpnsd = $othrexpnsd;
    }

    /**
     * @return int
     */
    public function getOthrexpnsd()
    {
        return $this->othrexpnsd;
    }

    /**
     * @param int $othrexpnse
     */
    public function setOthrexpnse($othrexpnse)
    {
        $this->othrexpnse = $othrexpnse;
    }

    /**
     * @return int
     */
    public function getOthrexpnse()
    {
        return $this->othrexpnse;
    }

    /**
     * @param int $othrexpnsf
     */
    public function setOthrexpnsf($othrexpnsf)
    {
        $this->othrexpnsf = $othrexpnsf;
    }

    /**
     * @return int
     */
    public function getOthrexpnsf()
    {
        return $this->othrexpnsf;
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
     * @param int $othrliabend
     */
    public function setOthrliabend($othrliabend)
    {
        $this->othrliabend = $othrliabend;
    }

    /**
     * @return int
     */
    public function getOthrliabend()
    {
        return $this->othrliabend;
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
     * @param string $ownsepentcd
     */
    public function setOwnsepentcd($ownsepentcd)
    {
        $this->ownsepentcd = $ownsepentcd;
    }

    /**
     * @return string
     */
    public function getOwnsepentcd()
    {
        return $this->ownsepentcd;
    }

    /**
     * @param int $paidinsurplusend
     */
    public function setPaidinsurplusend($paidinsurplusend)
    {
        $this->paidinsurplusend = $paidinsurplusend;
    }

    /**
     * @return int
     */
    public function getPaidinsurplusend()
    {
        return $this->paidinsurplusend;
    }

    /**
     * @param int $paybletoffcrsend
     */
    public function setPaybletoffcrsend($paybletoffcrsend)
    {
        $this->paybletoffcrsend = $paybletoffcrsend;
    }

    /**
     * @return int
     */
    public function getPaybletoffcrsend()
    {
        return $this->paybletoffcrsend;
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
     * @param int $pensionplancontrb
     */
    public function setPensionplancontrb($pensionplancontrb)
    {
        $this->pensionplancontrb = $pensionplancontrb;
    }

    /**
     * @return int
     */
    public function getPensionplancontrb()
    {
        return $this->pensionplancontrb;
    }

    /**
     * @param int $permrstrctnetasstsend
     */
    public function setPermrstrctnetasstsend($permrstrctnetasstsend)
    {
        $this->permrstrctnetasstsend = $permrstrctnetasstsend;
    }

    /**
     * @return int
     */
    public function getPermrstrctnetasstsend()
    {
        return $this->permrstrctnetasstsend;
    }

    /**
     * @param int $pldgegrntrcvblend
     */
    public function setPldgegrntrcvblend($pldgegrntrcvblend)
    {
        $this->pldgegrntrcvblend = $pldgegrntrcvblend;
    }

    /**
     * @return int
     */
    public function getPldgegrntrcvblend()
    {
        return $this->pldgegrntrcvblend;
    }

    /**
     * @param string $politicalactvtscd
     */
    public function setPoliticalactvtscd($politicalactvtscd)
    {
        $this->politicalactvtscd = $politicalactvtscd;
    }

    /**
     * @return string
     */
    public function getPoliticalactvtscd()
    {
        return $this->politicalactvtscd;
    }

    /**
     * @param string $premiumspaidcd
     */
    public function setPremiumspaidcd($premiumspaidcd)
    {
        $this->premiumspaidcd = $premiumspaidcd;
    }

    /**
     * @return string
     */
    public function getPremiumspaidcd()
    {
        return $this->premiumspaidcd;
    }

    /**
     * @param int $prepaidexpnsend
     */
    public function setPrepaidexpnsend($prepaidexpnsend)
    {
        $this->prepaidexpnsend = $prepaidexpnsend;
    }

    /**
     * @return int
     */
    public function getPrepaidexpnsend()
    {
        return $this->prepaidexpnsend;
    }

    /**
     * @param int $prgmservcode2acd
     */
    public function setPrgmservcode2acd($prgmservcode2acd)
    {
        $this->prgmservcode2acd = $prgmservcode2acd;
    }

    /**
     * @return int
     */
    public function getPrgmservcode2acd()
    {
        return $this->prgmservcode2acd;
    }

    /**
     * @param int $prgmservcode2bcd
     */
    public function setPrgmservcode2bcd($prgmservcode2bcd)
    {
        $this->prgmservcode2bcd = $prgmservcode2bcd;
    }

    /**
     * @return int
     */
    public function getPrgmservcode2bcd()
    {
        return $this->prgmservcode2bcd;
    }

    /**
     * @param int $prgmservcode2ccd
     */
    public function setPrgmservcode2ccd($prgmservcode2ccd)
    {
        $this->prgmservcode2ccd = $prgmservcode2ccd;
    }

    /**
     * @return int
     */
    public function getPrgmservcode2ccd()
    {
        return $this->prgmservcode2ccd;
    }

    /**
     * @param int $prgmservcode2dcd
     */
    public function setPrgmservcode2dcd($prgmservcode2dcd)
    {
        $this->prgmservcode2dcd = $prgmservcode2dcd;
    }

    /**
     * @return int
     */
    public function getPrgmservcode2dcd()
    {
        return $this->prgmservcode2dcd;
    }

    /**
     * @param int $prgmservcode2ecd
     */
    public function setPrgmservcode2ecd($prgmservcode2ecd)
    {
        $this->prgmservcode2ecd = $prgmservcode2ecd;
    }

    /**
     * @return int
     */
    public function getPrgmservcode2ecd()
    {
        return $this->prgmservcode2ecd;
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
     * @param string $providegoodscd
     */
    public function setProvidegoodscd($providegoodscd)
    {
        $this->providegoodscd = $providegoodscd;
    }

    /**
     * @return string
     */
    public function getProvidegoodscd()
    {
        return $this->providegoodscd;
    }

    /**
     * @param string $prptyintrcvdcd
     */
    public function setPrptyintrcvdcd($prptyintrcvdcd)
    {
        $this->prptyintrcvdcd = $prptyintrcvdcd;
    }

    /**
     * @return string
     */
    public function getPrptyintrcvdcd()
    {
        return $this->prptyintrcvdcd;
    }

    /**
     * @param string $prtynotifyorgcd
     */
    public function setPrtynotifyorgcd($prtynotifyorgcd)
    {
        $this->prtynotifyorgcd = $prtynotifyorgcd;
    }

    /**
     * @return string
     */
    public function getPrtynotifyorgcd()
    {
        return $this->prtynotifyorgcd;
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
     * @param int $pymtoaffiliates
     */
    public function setPymtoaffiliates($pymtoaffiliates)
    {
        $this->pymtoaffiliates = $pymtoaffiliates;
    }

    /**
     * @return int
     */
    public function getPymtoaffiliates()
    {
        return $this->pymtoaffiliates;
    }

    /**
     * @param int $qualhlthonhnd
     */
    public function setQualhlthonhnd($qualhlthonhnd)
    {
        $this->qualhlthonhnd = $qualhlthonhnd;
    }

    /**
     * @return int
     */
    public function getQualhlthonhnd()
    {
        return $this->qualhlthonhnd;
    }

    /**
     * @param string $qualhlthplncd
     */
    public function setQualhlthplncd($qualhlthplncd)
    {
        $this->qualhlthplncd = $qualhlthplncd;
    }

    /**
     * @return string
     */
    public function getQualhlthplncd()
    {
        return $this->qualhlthplncd;
    }

    /**
     * @param int $qualhlthreqmntn
     */
    public function setQualhlthreqmntn($qualhlthreqmntn)
    {
        $this->qualhlthreqmntn = $qualhlthreqmntn;
    }

    /**
     * @return int
     */
    public function getQualhlthreqmntn()
    {
        return $this->qualhlthreqmntn;
    }

    /**
     * @param int $rcvbldisqualend
     */
    public function setRcvbldisqualend($rcvbldisqualend)
    {
        $this->rcvbldisqualend = $rcvbldisqualend;
    }

    /**
     * @return int
     */
    public function getRcvbldisqualend()
    {
        return $this->rcvbldisqualend;
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
     * @param string $rcvdpdtngcd
     */
    public function setRcvdpdtngcd($rcvdpdtngcd)
    {
        $this->rcvdpdtngcd = $rcvdpdtngcd;
    }

    /**
     * @return string
     */
    public function getRcvdpdtngcd()
    {
        return $this->rcvdpdtngcd;
    }

    /**
     * @param string $recvartcd
     */
    public function setRecvartcd($recvartcd)
    {
        $this->recvartcd = $recvartcd;
    }

    /**
     * @return string
     */
    public function getRecvartcd()
    {
        return $this->recvartcd;
    }

    /**
     * @param string $recvnoncashcd
     */
    public function setRecvnoncashcd($recvnoncashcd)
    {
        $this->recvnoncashcd = $recvnoncashcd;
    }

    /**
     * @return string
     */
    public function getRecvnoncashcd()
    {
        return $this->recvnoncashcd;
    }

    /**
     * @param string $reltdorgcd
     */
    public function setReltdorgcd($reltdorgcd)
    {
        $this->reltdorgcd = $reltdorgcd;
    }

    /**
     * @return string
     */
    public function getReltdorgcd()
    {
        return $this->reltdorgcd;
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
     * @param int $royaltsexpns
     */
    public function setRoyaltsexpns($royaltsexpns)
    {
        $this->royaltsexpns = $royaltsexpns;
    }

    /**
     * @return int
     */
    public function getRoyaltsexpns()
    {
        return $this->royaltsexpns;
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
     * @param string $rptgrntstogovtcd
     */
    public function setRptgrntstogovtcd($rptgrntstogovtcd)
    {
        $this->rptgrntstogovtcd = $rptgrntstogovtcd;
    }

    /**
     * @return string
     */
    public function getRptgrntstogovtcd()
    {
        return $this->rptgrntstogovtcd;
    }

    /**
     * @param string $rptgrntstoindvcd
     */
    public function setRptgrntstoindvcd($rptgrntstoindvcd)
    {
        $this->rptgrntstoindvcd = $rptgrntstoindvcd;
    }

    /**
     * @return string
     */
    public function getRptgrntstoindvcd()
    {
        return $this->rptgrntstoindvcd;
    }

    /**
     * @param string $rptincfnndrsngcd
     */
    public function setRptincfnndrsngcd($rptincfnndrsngcd)
    {
        $this->rptincfnndrsngcd = $rptincfnndrsngcd;
    }

    /**
     * @return string
     */
    public function getRptincfnndrsngcd()
    {
        return $this->rptincfnndrsngcd;
    }

    /**
     * @param string $rptincgamingcd
     */
    public function setRptincgamingcd($rptincgamingcd)
    {
        $this->rptincgamingcd = $rptincgamingcd;
    }

    /**
     * @return string
     */
    public function getRptincgamingcd()
    {
        return $this->rptincgamingcd;
    }

    /**
     * @param string $rptinvstothsecd
     */
    public function setRptinvstothsecd($rptinvstothsecd)
    {
        $this->rptinvstothsecd = $rptinvstothsecd;
    }

    /**
     * @return string
     */
    public function getRptinvstothsecd()
    {
        return $this->rptinvstothsecd;
    }

    /**
     * @param string $rptinvstprgrelcd
     */
    public function setRptinvstprgrelcd($rptinvstprgrelcd)
    {
        $this->rptinvstprgrelcd = $rptinvstprgrelcd;
    }

    /**
     * @return string
     */
    public function getRptinvstprgrelcd()
    {
        return $this->rptinvstprgrelcd;
    }

    /**
     * @param string $rptlndbldgeqptcd
     */
    public function setRptlndbldgeqptcd($rptlndbldgeqptcd)
    {
        $this->rptlndbldgeqptcd = $rptlndbldgeqptcd;
    }

    /**
     * @return string
     */
    public function getRptlndbldgeqptcd()
    {
        return $this->rptlndbldgeqptcd;
    }

    /**
     * @param string $rptothasstcd
     */
    public function setRptothasstcd($rptothasstcd)
    {
        $this->rptothasstcd = $rptothasstcd;
    }

    /**
     * @return string
     */
    public function getRptothasstcd()
    {
        return $this->rptothasstcd;
    }

    /**
     * @param string $rptothliabcd
     */
    public function setRptothliabcd($rptothliabcd)
    {
        $this->rptothliabcd = $rptothliabcd;
    }

    /**
     * @return string
     */
    public function getRptothliabcd()
    {
        return $this->rptothliabcd;
    }

    /**
     * @param string $rptprofndrsngfeescd
     */
    public function setRptprofndrsngfeescd($rptprofndrsngfeescd)
    {
        $this->rptprofndrsngfeescd = $rptprofndrsngfeescd;
    }

    /**
     * @return string
     */
    public function getRptprofndrsngfeescd()
    {
        return $this->rptprofndrsngfeescd;
    }

    /**
     * @param string $rptyestocompnstncd
     */
    public function setRptyestocompnstncd($rptyestocompnstncd)
    {
        $this->rptyestocompnstncd = $rptyestocompnstncd;
    }

    /**
     * @return string
     */
    public function getRptyestocompnstncd()
    {
        return $this->rptyestocompnstncd;
    }

    /**
     * @param string $s4966distribcd
     */
    public function setS4966distribcd($s4966distribcd)
    {
        $this->s4966distribcd = $s4966distribcd;
    }

    /**
     * @return string
     */
    public function getS4966distribcd()
    {
        return $this->s4966distribcd;
    }

    /**
     * @param string $s501c3or4947a1cd
     */
    public function setS501c3or4947a1cd($s501c3or4947a1cd)
    {
        $this->s501c3or4947a1cd = $s501c3or4947a1cd;
    }

    /**
     * @return string
     */
    public function getS501c3or4947a1cd()
    {
        return $this->s501c3or4947a1cd;
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
     * @param string $schdbind
     */
    public function setSchdbind($schdbind)
    {
        $this->schdbind = $schdbind;
    }

    /**
     * @return string
     */
    public function getSchdbind()
    {
        return $this->schdbind;
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
     * @param string $sellorexchcd
     */
    public function setSellorexchcd($sellorexchcd)
    {
        $this->sellorexchcd = $sellorexchcd;
    }

    /**
     * @return string
     */
    public function getSellorexchcd()
    {
        return $this->sellorexchcd;
    }

    /**
     * @param string $sepcnsldtfinstmtcd
     */
    public function setSepcnsldtfinstmtcd($sepcnsldtfinstmtcd)
    {
        $this->sepcnsldtfinstmtcd = $sepcnsldtfinstmtcd;
    }

    /**
     * @return string
     */
    public function getSepcnsldtfinstmtcd()
    {
        return $this->sepcnsldtfinstmtcd;
    }

    /**
     * @param string $sepindaudfinstmtcd
     */
    public function setSepindaudfinstmtcd($sepindaudfinstmtcd)
    {
        $this->sepindaudfinstmtcd = $sepindaudfinstmtcd;
    }

    /**
     * @return string
     */
    public function getSepindaudfinstmtcd()
    {
        return $this->sepindaudfinstmtcd;
    }

    /**
     * @param string $servasofficercd
     */
    public function setServasofficercd($servasofficercd)
    {
        $this->servasofficercd = $servasofficercd;
    }

    /**
     * @return string
     */
    public function getServasofficercd()
    {
        return $this->servasofficercd;
    }

    /**
     * @param string $solicitcntrbcd
     */
    public function setSolicitcntrbcd($solicitcntrbcd)
    {
        $this->solicitcntrbcd = $solicitcntrbcd;
    }

    /**
     * @return string
     */
    public function getSolicitcntrbcd()
    {
        return $this->solicitcntrbcd;
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
     * @param string $subjto6033cd
     */
    public function setSubjto6033cd($subjto6033cd)
    {
        $this->subjto6033cd = $subjto6033cd;
    }

    /**
     * @return string
     */
    public function getSubjto6033cd()
    {
        return $this->subjto6033cd;
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
     * @param int $svngstempinvend
     */
    public function setSvngstempinvend($svngstempinvend)
    {
        $this->svngstempinvend = $svngstempinvend;
    }

    /**
     * @return int
     */
    public function getSvngstempinvend()
    {
        return $this->svngstempinvend;
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
     * @param int $temprstrctnetasstsend
     */
    public function setTemprstrctnetasstsend($temprstrctnetasstsend)
    {
        $this->temprstrctnetasstsend = $temprstrctnetasstsend;
    }

    /**
     * @return int
     */
    public function getTemprstrctnetasstsend()
    {
        return $this->temprstrctnetasstsend;
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
     * @param int $totcomprelatede
     */
    public function setTotcomprelatede($totcomprelatede)
    {
        $this->totcomprelatede = $totcomprelatede;
    }

    /**
     * @return int
     */
    public function getTotcomprelatede()
    {
        return $this->totcomprelatede;
    }

    /**
     * @param int $totestcompf
     */
    public function setTotestcompf($totestcompf)
    {
        $this->totestcompf = $totestcompf;
    }

    /**
     * @return int
     */
    public function getTotestcompf()
    {
        return $this->totestcompf;
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
     * @param int $totnetliabastend
     */
    public function setTotnetliabastend($totnetliabastend)
    {
        $this->totnetliabastend = $totnetliabastend;
    }

    /**
     * @return int
     */
    public function getTotnetliabastend()
    {
        return $this->totnetliabastend;
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
     * @param int $totreprtabled
     */
    public function setTotreprtabled($totreprtabled)
    {
        $this->totreprtabled = $totreprtabled;
    }

    /**
     * @return int
     */
    public function getTotreprtabled()
    {
        return $this->totreprtabled;
    }

    /**
     * @param int $totrev2acola
     */
    public function setTotrev2acola($totrev2acola)
    {
        $this->totrev2acola = $totrev2acola;
    }

    /**
     * @return int
     */
    public function getTotrev2acola()
    {
        return $this->totrev2acola;
    }

    /**
     * @param int $totrev2bcola
     */
    public function setTotrev2bcola($totrev2bcola)
    {
        $this->totrev2bcola = $totrev2bcola;
    }

    /**
     * @return int
     */
    public function getTotrev2bcola()
    {
        return $this->totrev2bcola;
    }

    /**
     * @param int $totrev2ccola
     */
    public function setTotrev2ccola($totrev2ccola)
    {
        $this->totrev2ccola = $totrev2ccola;
    }

    /**
     * @return int
     */
    public function getTotrev2ccola()
    {
        return $this->totrev2ccola;
    }

    /**
     * @param int $totrev2dcola
     */
    public function setTotrev2dcola($totrev2dcola)
    {
        $this->totrev2dcola = $totrev2dcola;
    }

    /**
     * @return int
     */
    public function getTotrev2dcola()
    {
        return $this->totrev2dcola;
    }

    /**
     * @param int $totrev2ecola
     */
    public function setTotrev2ecola($totrev2ecola)
    {
        $this->totrev2ecola = $totrev2ecola;
    }

    /**
     * @return int
     */
    public function getTotrev2ecola()
    {
        return $this->totrev2ecola;
    }

    /**
     * @param int $totrev2fcola
     */
    public function setTotrev2fcola($totrev2fcola)
    {
        $this->totrev2fcola = $totrev2fcola;
    }

    /**
     * @return int
     */
    public function getTotrev2fcola()
    {
        return $this->totrev2fcola;
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
     * @param int $travel
     */
    public function setTravel($travel)
    {
        $this->travel = $travel;
    }

    /**
     * @return int
     */
    public function getTravel()
    {
        return $this->travel;
    }

    /**
     * @param int $travelofpublicoffcl
     */
    public function setTravelofpublicoffcl($travelofpublicoffcl)
    {
        $this->travelofpublicoffcl = $travelofpublicoffcl;
    }

    /**
     * @return int
     */
    public function getTravelofpublicoffcl()
    {
        return $this->travelofpublicoffcl;
    }

    /**
     * @param string $txexmptbndcd
     */
    public function setTxexmptbndcd($txexmptbndcd)
    {
        $this->txexmptbndcd = $txexmptbndcd;
    }

    /**
     * @return string
     */
    public function getTxexmptbndcd()
    {
        return $this->txexmptbndcd;
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
     * @param int $txexmptint
     */
    public function setTxexmptint($txexmptint)
    {
        $this->txexmptint = $txexmptint;
    }

    /**
     * @return int
     */
    public function getTxexmptint()
    {
        return $this->txexmptint;
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

    /**
     * @param int $unrstrctnetasstsend
     */
    public function setUnrstrctnetasstsend($unrstrctnetasstsend)
    {
        $this->unrstrctnetasstsend = $unrstrctnetasstsend;
    }

    /**
     * @return int
     */
    public function getUnrstrctnetasstsend()
    {
        return $this->unrstrctnetasstsend;
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

    /**
     * @param string $wthldngrulescd
     */
    public function setWthldngrulescd($wthldngrulescd)
    {
        $this->wthldngrulescd = $wthldngrulescd;
    }

    /**
     * @return string
     */
    public function getWthldngrulescd()
    {
        return $this->wthldngrulescd;
    }

}
