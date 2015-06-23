<?php
$con = new mysqli("localhost", "root", "ritachan", "loadPlan");
if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_error;
} else {
    $a = 1;
    $sql = "SELECT regCd, inpCd, chiNm, engNm, isDea FROM region where regCd='$a'";
    $res = $con->query($sql);
    $array = [];
    for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) {
        $res->data_seek($row_no);
        $row = $res->fetch_array(MYSQLI_NUM);
        array_push($array, $row);;
    }
    echo json_encode($array);
}
/*
$regCd = $row['regCd'];
$inpCd = $row['inpCd'];
$engNm = $row['engNm'];
$chiNm = $row['chiNm'];
list($regCd, $inpCd, $engNm, $chiNm) = $row;
echo " regCd = $regCd | inpCd = $inpCd | engNm = $engNm | chiNm = $chiNm\n";
echo json_encode($row) . "\n";
*/
?>
