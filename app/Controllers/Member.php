<?php
/**
 * Created by O2System Framework File Generator.
 * DateTime: 24/06/2018 13:06
 */

// ------------------------------------------------------------------------

namespace App\Controllers;

// ------------------------------------------------------------------------

use O2System\Framework\Http\Controllers\Restful as Controller;


/**
 * Class Login
 *
 * @package \App\Controllers
 */
class Member extends Controller
{


    var $err = "";
    var $cek = "";
    var $content = "";
    public function index()
    {
    }
    public function add()
    {

      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $this->err = "Email tidak valid !";
      }
      $referalId = 0;
      if(empty($password))$this->err = "Isi password";
      if(empty($nama))$this->err= "Isi nama";
      if(empty($nomorTelepon))$this->err = "Isi nomor telepon";
      if(!empty($referalEmail)){
        if(sqlRowCount(sqlQuery("select * from member where email = '$referalEmail'")) !=0){
          $getDataReferal = sqlArray(sqlQuery("select * from member where email='$referalEmail'"));
          $referalId = $getDataReferal['id'];
        }else{
          $this->err = "Email referal tidak valid";
        }
      }
      if(empty($this->err)){
        $dataMember = array(
          "email" => $email,
          "password" => $password,
          "nama" => $nama,
          "nomor_telepon" => $nomorTelepon,
          "saldo" => "0",
          "status" => "ACTIVE",
          "id_referal" => $referalId,
        );
        $queryInsertMember = sqlInsert("member",$dataMember);
        sqlQuery($queryInsertMember);
        $this->cek = $queryInsertMember;
        $getDataUser = sqlArray(sqlQuery("select * from member where email = '$email'"));
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

    public function update()
    {

      foreach ($_REQUEST as $key => $value) {
          $$key = $value;
      }
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $this->err = "Email tidak valid !";
      }
      if(empty($password))$this->err = "Isi password";
      if(empty($nama))$this->err= "Isi nama";
      if(empty($nomorTelepon))$this->err = "Isi nomor telepon";
      if(empty($this->err)){
        $getDataMember = sqlArray(sqlQuery("select * from member where email = '$oldEmail'"));
        if(sqlRowCount(sqlQuery("select * from member where email = '$email' and id != '".$getDataMember['id']."'")) !=0) {
          $this->err = "Email tidak tersedia !";
        }
      }

      if(empty($this->err)){
        $dataMember = array(
          "email" => $email,
          "password" => $password,
          "nama" => $nama,
          "nomor_telepon" => $nomorTelepon,
        );
        $queryInsertMember = sqlUpdate("member",$dataMember,"id = '".$getDataMember['id']."'");
        sqlQuery($queryInsertMember);
        $this->cek = $queryInsertMember;
        $getDataUser = sqlArray(sqlQuery("select * from member where email = '$email'"));
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
    public function sync()
    {
      foreach ($_REQUEST as $key => $value) {
          $$key = $value;
      }
      $arrayDataMember = array();
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));
      $arrayDataMember[] = array(
        "id" => $getDataMember['id'],
        "email" => $getDataMember['email'],
        "password" => $getDataMember['password'],
        "nama" => $getDataMember['nama'],
        "nomor_telepon" => $getDataMember['nomor_telepon'],
        "saldo" => $getDataMember['saldo'],
        "status" => $getDataMember['status'],
      );
      $this->content = $arrayDataMember;
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
    public function log()
    {
      foreach ($_REQUEST as $key => $value) {
          $$key = $value;
      }
      $arrayDataMember = array();
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));
      $referalEmail = "-";
      if(sqlRowCount(sqlQuery("select * from member where id = '".$getDataMember['id_referal']."'")) != 0){
        $getDataReferal = sqlArray(sqlQuery("select * from member where id = '".$getDataMember['id_referal']."'"));
        $referalEmail = $getDataReferal['email'];
      }
      $jumlahPenukaran = sqlRowCount(sqlQuery("select * from payment where id_member = '".$getDataMember['id']."'"));
      $jumlahAbsen = sqlRowCount(sqlQuery("select * from absen where id_member = '".$getDataMember['id']."'"));
      $arrayDataMember[] = array(
        "penukaran" => "$jumlahPenukaran",
        "absen" => "$jumlahAbsen",
        "referalEmail" => $referalEmail,

      );
      $this->content = $arrayDataMember;
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
