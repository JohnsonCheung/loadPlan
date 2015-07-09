<?php
include_once '../phpFn/dte.php';
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

function fmt__add_futureDays(&$dta)
{
    foreach ($dta as $i => $dr) {
        $a = new DateTime($dr['ordDelvDte']);
        $b = new DateTime(today());
        $c = date_diff($a, $b)->days;
        $dr['futureDays'] = $c;
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

function fmt__add_ordFmt(&$dta)
{
    $today = new DateTime();
    foreach ($dta as $i => $dr) {
        $ordNo = $dr['ordNo'];
        $ordDelvDte = $dr['ordDelvDte'];
        $dr['ordNoFmt'] = $dr['ordDelvDte'] . ' #' . $ordNo;
        $dta[$i] = $dr;
    }
}

include_once '..\phpFn\db.php';
$con = db_con();
$dte = date("Y-m-d");
$sql = "SELECT ord, ordDelvDte, ordNo, a.cusCd, chiShtNm, engShtNm, isRetWhs, isCold, pickTim, pickAdrCd, ordTy, nCntr, loadUOM
 FROM ord a inner join cus b on a.cusCd=b.cusCd
 WHERE ordDelvDte > '$dte' and isComplete=b'0'";
$dta = runsql_dta($con, $sql);
fmt__add_ordFmt($dta);
fmt__add_futureDays($dta);
fmt__adj_isCold($dta);
fmt__adj_isRetWhs($dta);
echo json_encode($dta);
?>