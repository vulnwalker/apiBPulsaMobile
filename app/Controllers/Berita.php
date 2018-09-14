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
class Berita extends Controller
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
    public function detail()
    {

      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";
      $getDataBerita = sqlQuery("select * from news where id = '$idBerita' ");
      while ($dataBerita =  sqlArray($getDataBerita)) {
        $arrayBerita[] = array(
          "id" => $dataBerita['id'],
          "judulBerita" => $dataBerita['title'],
          "contentBerita" => $dataBerita['content'],

        );
      }


      $this->content = $arrayBerita;
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
    public function news()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}
      $this->cek = "";
      if(sqlRowCount(sqlQuery("select * from news  limit $from,$to")) == 0){
        $this->err = "ERROR DATA ABIS";
      }
      $getDataNews = sqlQuery("select * from news limit $from,$to");
      while ($dataNews =  sqlArray($getDataNews)) {
        $src = "http://admin.bpulsa.rm-rf.studio/images/news/default.jpg";
        $string = html_entity_decode(base64_decode($dataNews['content']));
        if( strpos( $string, 'img' ) !== false ) {
           $doc = new \DOMDocument();
           libxml_use_internal_errors(true);
           $doc->loadHTML( $string );
           $xpath = new \DOMXPath($doc);
           $imgs = $xpath->query("//img");
           $img = $imgs->item(0);
           $src = $img->getAttribute("src");
        }
        $isi = strip_tags(base64_decode($dataNews['content']));
         if (strlen($isi) > 250) {

             $stringCut = substr($isi, 0, 200);

             $isi = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
         }
        $arrayNews[] = array(
          "id" => $dataNews['id'],
          "title" => $dataNews['title'],
          "content" => str_replace("\n","",$isi),
          "tanggal" => $dataNews['tanggal'],
          "gambar" => $src,
        );
      }


      $this->content = $arrayNews;
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
