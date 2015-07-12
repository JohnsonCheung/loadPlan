<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-10
 * Time: 11:56
 */
include_once '/../phpFn/db.php';

class Chk_ordadr
{
    private $con;

    function __construct()
    {
        $this->con = db_con();
    }

    function chk_all_fld()
    {
        $this->ordContentLvc();

    }

    function chk_fld_ordContentLvc()
    {
        // output: clear and write the target table:  loadplanChk->ordadr_ordContentLvs
        // target table - ordadr_ordContentLvs = ordAdr ordContent | ordContentLvc ErNo ErMsg, where ErNo =Enum(NoEr Er1 Er2) with dft=NoEr
        // for each item in ordadr->ordContentLvs write one record with target table
        // Er1: item not found in table ordContent
        // Er2: item found in table ordContent, but ord-id mis-match
        $M1 = "[ordContent] not found in table-[ordContent]";
        $M2 = '[ordContent] found in table-[ordContent] having [ord1] which is diff from [ordadr]->[ord]';
        $con = $this->con;
        runsql_exec($con, "use loadplanChk;");
        runsql_exec($con, "DELETE FROM ordadr_ordContentLvs; ");
        $dta = runsql_dta($con, "SELECT ordAdr, ordContentLvc, ord FROM loadplan.ordadr WHERE NOT ordContentLvc IS NULL;", MYSQLI_NUM);
        $ordContent_ord_dic = runsql_dic($con, "SELECT ordContent, ord FROM loadplan.ordContent;");
        foreach ($dta as $dr) {
            list($ordAdr, $ordContentLvc, $ord) = $dr;
            $ay = preg_split("/\,/", $ordContentLvc);
            foreach ($ay as $itm) {
                $ord1 = @ $ordContent_ord_dic[$itm];
                if (is_null($ord1)) {
                    runsql_exec($con, "insert into ordadr_ordContentLvs (ordAdr, ordContent, ord, ordContentLvc, ErNo, ErMsg)
 values($ordAdr, $itm, $ord, '$ordContentLvc', 'Er1', '$M1')");
                } elseif ($ord !== $ord1) {
                    runsql_exec($con, "insert into ordadr_ordContentLvs (ordAdr, ordContent, ord, ordContentLvc, ErNo, ErMsg, ord1)
 values($ordAdr, $itm, $ord, '$ordContentLvc', 'Er2', '$M2', $ord1)");
                } else {
                    runsql_exec($con, "insert into ordadr_ordContentLvs (ordAdr, ordContent, ord, ordContentLvc)
 values($ordAdr, $itm, $ord, '$ordContentLvc')");  //<-- NoEr

                }
            }
        }
    }
}

$a = new Chk_ordadr();
$a->chk_fld_ordContentLvc();