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
class Daftar extends Controller
{


    var $err = "";
    var $cek = "";
    var $content = "";
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
    public function point()
    {

      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";
      $getDataTukarPoint = sqlQuery("select * from trade_point ");
      while ($dataTukarPoint =  sqlArray($getDataTukarPoint)) {
        $arrayTukarPoint[] = array(
          "id" => $dataTukarPoint['id'],
          "title" => $dataTukarPoint['title'],
          "price" => $dataTukarPoint['price'],
          "description" => $dataTukarPoint['description'],
          "stock" => $dataTukarPoint['stock'],
          "gambar" => $dataTukarPoint['gambar'],
        );
      }


      $this->content = $arrayTukarPoint;
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
    public function payment()
    {

      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";
      $getDataMember = sqlArray(sqlQuery("select * from member where email='$email'"));
      if(sqlRowCount(sqlQuery("select * from payment where id_member = '".$getDataMember['id']."' limit $from,$to")) == 0){
        $this->err = "ERROR DATA ABIS";
      }
      $getDataPayment = sqlQuery("select * from payment where id_member = '".$getDataMember['id']."' limit $from,$to");
      while ($dataPayment =  sqlArray($getDataPayment)) {
        $getDataTukarPoint = sqlArray(sqlQuery("select * from trade_point where id ='".$dataPayment['id_trade_point']."'"));
        $arrayTukarPoint[] = array(
          "id" => $dataPayment['id'],
          "id_trade_point" => $dataPayment['id_trade_point'],
          "tukar_point_title" => $getDataTukarPoint['title'],
          "tanggal" => $dataPayment['tanggal'],
          "jam" => $dataPayment['jam'],
          "status" => $dataPayment['status'],
        );
      }


      $this->content = $arrayTukarPoint;
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
