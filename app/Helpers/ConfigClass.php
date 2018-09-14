<?php
/**
 *
 */
namespace App\Helpers;
class ConfigClass
{

  function checkReferal($idMember)
  {
    $pointReferal = 500;
    $getDataMember = sqlArray(sqlQuery("select * from member where id = '$idMember'"));
    if(sqlRowCount(sqlQuery("select * from referal_log where id_member = '$idMember'")) == 0){
      if($getDataMember['saldo'] >= 5000){
        $dataReferalLog = array(
          "id_member" => $idMember,
          "id_referal" => $getDataMember['id_referal'],
          "tanggal" => date("Y-m-d"),
          "point" => $pointReferal,
        );
        sqlQuery(sqlInsert("referal_log",$dataReferalLog));
        sqlQuery("UPDATE member set saldo = saldo + $pointReferal where id = '".$getDataMember['id_referal']."'");
      }
    }

    return $getDataMember['id_referal'];
  }
}

?>
