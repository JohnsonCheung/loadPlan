<?php
function fmt__adj_isRetWhs(&$dta)
{
    foreach ($dta as $i => $dr) {
        if ($dr['isRetWhs'] == '1') {
            $dr['isRetWhs'] = 'Ret';
        } else {
            $dr['isRetWhs'] = '';
        }
        $dta[$i] = $dr;
    }
}

function fmt__adj_isCold(&$dta)
{
    foreach ($dta as $i => $dr) {
        if ($dr['isCold'] == '1') {
            $dr['isCold'] = 'Cold';
        } else {
            $dr['isCold'] = '';
        }
        $dta[$i] = $dr;
    }
}

function fmt__add_ordDelvDteFmt_pastDays(&$dta)
{
    $today = new DateTime();
    foreach ($dta as $i => $dr) {
        $ordNo = $dr['ordNo'];
        $ordDelvDte = $dr['ordDelvDte'];
        $d = new DateTime($ordDelvDte);
        if ($today === $d) return $ordNo;
        $diff = DATE_DIFF($d, $today);
        $ndays = $diff->days;
        if ($ndays === 0) {
            $dr['ordDelvDteFmt'] = '';
        } else {
            $dr['ordDelvDteFmt'] = $ordDelvDte;
            $dr['pastDays'] = $ndays;
        }
        $dta[$i] = $dr;
    }
}

include_once '..\phpFn\db.php';
$con = db_con();
$dte = date("Y-m-d");
$sql = "SELECT ord, ordDelvDte, ordNo, a.cusCd, chiShtNm, engShtNm, isRetWhs, isCold, pickTim, pickAdrCd, ordTy, nCntr
 FROM ord a left join cus b on a.cusCd=b.cusCd
 WHERE ordDelvDte = '$dte' or (ordDelvDte <= '$dte' and isComplete=b'0')
 ORDER BY ordDelvDte desc, ordNo desc";
$dta = runsql_dta($con, $sql);
fmt__add_ordDelvDteFmt_pastDays($dta);
fmt__adj_isCold($dta);
fmt__adj_isRetWhs($dta);
echo json_encode($dta);
?>