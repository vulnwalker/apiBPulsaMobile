<?php
/**
 * Created by O2System Framework File Generator.
 * DateTime: 24/06/2018 13:06
 */

// ------------------------------------------------------------------------

namespace App\Controllers;

// ------------------------------------------------------------------------

use O2System\Framework\Http\Controllers\Restful as Controller;
use App\Helpers\ConfigClass as ConfigClass;


/**
 * Class Login
 *
 * @package \App\Controllers
 */
class Ads extends Controller
{


    var $err = "";
    var $cek = "";
    var $content = array();
    public function index()
    {

      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));
      $getDataAds = sqlArray(sqlQuery("select * from ad_setting where name = '$adsName'"));
      if($getDataAds['status'] !='ACTIVE'){
        $this->err = $getDataAds['error_message'];
      }else{
        $getMaxIdLasRequest = sqlArray(sqlQuery("select max(id) from log_ad_show where id_member = '".$getDataMember['id']."' and id_ad ='".$getDataAds['id']."'"));
        $getDataLastRequest = sqlArray(sqlQuery("select * from log_ad_show where id = '".$getMaxIdLasRequest['max(id)']."'"));
        $selisiWaktu = $this->convertTimeToInteger(date("Y-m-d").";".date("H:i")) - $this->convertTimeToInteger($getDataLastRequest['tanggal'].";".$getDataLastRequest['jam']);
        if($selisiWaktu >  $getDataAds['delay'] ){
          $dataInsertLogAdRequest = array(
            "id_ad" => $getDataAds['id'],
            "id_member" => $getDataMember['id'],
            "tanggal" => date("Y-m-d"),
            "jam" => date("H:i"),
          );
          $queryInsertLogAdRequest = sqlInsert("log_request_ad",$dataInsertLogAdRequest);
          sqlQuery($queryInsertLogAdRequest);
          $this->content[] = array(
            "ads_unit" => $getDataAds['ad_unit']
          );
        }else{
          $this->err = "Tunggu ".$getDataAds['delay']." Menit !";
        }
      }

      $this->sendPayload(
          [
              'request' => [
                  'method' => $_SERVER[ 'REQUEST_METHOD' ],
                  'time'   => $_SERVER[ 'REQUEST_TIME' ],
                  'uri'    => $_SERVER[ 'REQUEST_URI' ],
                  'agent'  => $_SERVER[ 'HTTP_USER_AGENT' ],
              ],
              'cek'  => $this->cek,
              'content'  => $this->content,
              'err' => $this->err
          ]
      );
    }
    function reward(){
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));
      $getDataAds = sqlArray(sqlQuery("select * from ad_setting where name = '$adsName'"));
      $dataInsertLogAdRequest = array(
        "id_ad" => $getDataAds['id'],
        "id_member" => $getDataMember['id'],
        "tanggal" => date("Y-m-d"),
        "jam" => date("H:i"),
        "point" => $getDataAds['point'],
      );
      sqlQuery("UPDATE member set saldo = saldo + ".$getDataAds['point']." where id = '".$getDataMember['id']."'");
      $configClass = new ConfigClass;
      $configClass->checkReferal($getDataMember['id']);
      $queryInsertLogAdRequest = sqlInsert("log_ad_show",$dataInsertLogAdRequest);
      sqlQuery($queryInsertLogAdRequest);
      $this->content[] = array(
        "point" => "".$getDataAds['point'].""
      );
      $this->cek = "select * from member where email = '$email'";
      $this->sendPayload(
          [
              'request' => [
                  'method' => $_SERVER[ 'REQUEST_METHOD' ],
                  'time'   => $_SERVER[ 'REQUEST_TIME' ],
                  'uri'    => $_SERVER[ 'REQUEST_URI' ],
                  'agent'  => $_SERVER[ 'HTTP_USER_AGENT' ],
              ],
              'cek'  => $this->cek,
              'content'  => $this->content,
              'err' => $this->err
          ]
      );
    }

    function convertTimeToInteger($concatTanggalJam){
      $explodeTanggalJamConcat = explode(";",$concatTanggalJam);
      $integerTanggal = $this->dateToInteger($explodeTanggalJamConcat[0]);
      $integerJam = $this->timeToInteger($explodeTanggalJamConcat[1]);
      $integerValue = $integerTanggal + $integerJam;
      return $integerValue;
    }
    function dateToInteger($date){
      $explodeTanggal = explode("-",$date);
      $hari = $explodeTanggal[2] * (24 * 60 ) ;
      $bulan = $explodeTanggal[1] * (30 * (24 * 60) ) ;
      $tahun = $explodeTanggal[0] * (365 * (30 * (24 * 60) ) ) ;
      $integerValue = $tahun + $bulan + $hari;
      return $integerValue;
    }
    function timeToInteger($time){
      $explodeJam = explode(":",$time);
      $jam = $explodeJam[0] * 60;
      $menit = $explodeJam[1] ;
      $integerValue = $jam + $menit;
      return $integerValue;
    }

    function generateDate($tanggal){
          $tanggal = explode('-',$tanggal);
          $tanggal = $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
          return $tanggal;
    }
}
