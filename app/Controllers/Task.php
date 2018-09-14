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
class Task extends Controller
{


    var $err = "";
    var $cek = "";
    var $content = array();
    public function index()
    {

      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "select * from member where email = '$email' and password = '".$password."'";

      if(sqlRowCount(sqlQuery("select * from member where email = '$email' and password = '".$password."'")) != 0){
        $getDataUser = sqlArray(sqlQuery("select * from member where email='$email'"));

      }else{
        $this->err = "Login Gagal";
      }
      $this->content = array(
        "id" => $getDataUser['id'],
        "email" => $getDataUser['email'],
        "password" => $getDataUser['password'],
        "nama" => $getDataUser['nama'],
        "nomor_telepon" => $getDataUser['nomor_telepon'],
        "saldo" => $getDataUser['saldo'],
        "status" => $getDataUser['status'],
      );
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
    public function absen()
    {

      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}
      $this->err = "";
      $getSettingAbsen = sqlArray(sqlQuery("select * from ad_setting where name='ABSEN'"));
      if($getSettingAbsen['status'] != "ACTIVE"){
        $this->err = $getSettingAbsen['error_message'];
        $this->content[] = array(
          "point" => 0
        );
      }else{
        $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));
        if(sqlRowCount(sqlQuery("select * from absen where id_member = '".$getDataMember['id']."' and tanggal = '".date("Y-m-d")."'")) != 0){
          $this->err = "Absen Hari ini sudah di lakukan !";
          $this->content[] = array(
            "point" => 0
          );

        }else{
          $dataInsertAbsen = array(
            "id_member" => $getDataMember["id"],
            "tanggal" => date("Y-m-d"),
            "point" => $getSettingAbsen['point'],
          );
          $queryInsertAbsen = sqlInsert("absen",$dataInsertAbsen);
          sqlQuery($queryInsertAbsen);
          $getDataAbsen = sqlArray(sqlQuery("select * from absen where id_member = '".$getDataMember["id"]."' and tanggal = '".date("Y-m-d")."'"));
          sqlQuery("UPDATE member set saldo = saldo + ".$getDataAbsen['point']." where id = '".$getDataMember['id']."'");
          $configClass = new ConfigClass;
          $configClass->checkReferal($getDataMember['id']);
          $this->content[] = array(
            "point" => $getDataAbsen['point'],
            "ad_unit" => $getSettingAbsen['ad_unit'],
          );

        }
      }
      $this->cek = "";



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
    public function game()
    {

      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));
      // $getDataAds = sqlArray(sqlQuery("select * from ad_setting where name = '$adsName'"));
      $this->cek = "";



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
}
