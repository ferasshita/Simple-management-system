<?php if ($_SESSION['Treasury'] == '0') {
if($_SESSION['hide_trsh'] == '1' && ($active == "cash" || $active == "chak" || $active == "transfar" || $active == "cards" || $active == "investment")){}else{
?>
                <div class="row mt-30">
  <?php
error_reporting(0);
//   $typem =  $_SESSION['type'];
//   $sid =  $_SESSION['id'];
//   $shop_id = $_SESSION['shop_id'];
//   $boss_id = $_SESSION['boss_id'];
//   if($typem == "boss"){
//       $gfid = $boss_id;
//   }elseif($typem == "admin"){
//       $gfid = $shop_id;
//   }else{
//       $gfid = $shop_id;
//   }
//   $delk = "0";
//   $bgh = "cash";
//   //============total of the money=============================
//   $vpsql = "SELECT * FROM treasury WHERE tyi=:bgh AND shop_id=:sid OR tyi=:bgh AND boss_id=:sid";
//   $view_postsi = $conn->prepare($vpsql);
//   $view_postsi->bindParam(':sid', $gfid, PDO::PARAM_INT);
//   $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
//   $view_postsi->execute();

  foreach ($treasry as $postsfetch) {
    $shop_id = $postsfetch['shop_id'];
    $vpsql = "SELECT Username FROM signup WHERE type='shop' AND id='$shop_id'";
    $FetchedData2=$this->comman_model->get_all_data_by_query($vpsql);
    foreach($FetchedData2 as $postsfetchb)
    {
            $user_name = $postsfetchb['Username'];

    }
      $kind = $postsfetch['kind'];
      $number = $postsfetch['number'];
      echo"<div class='col-12 col-md-6 col-lg-4'>
                <div class='box box-body pull-up'>
                    <div class='media align-items-center p-0'>
                        <div class='text-center'>
                            <a href='#'><img src='".$dircheckPath."imgs/Currency_img/";if($kind == "usd"){echo"usd.svg";}elseif($kind == "LYD")
{echo"lyd_cur.png";}elseif($kind == "EU"){echo"eu.svg";}elseif($kind == "GBP"){echo"pund.svg";}elseif($kind == "YEN"){echo"yen.svg";}else{echo"usd.svg";}echo"' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='float-left rounded-circle' title='$kind'></a>
                        </div>
                        <div>
                            <h3 class='no-margin'>$kind (";if($_SESSION['type']!="boss"){echo lang('cash');}else{echo"$user_name";} echo")</h3>
                        </div>
                    </div>
                    <div class='flexbox align-items-center mt-25'>
                        <div>
                            <p class='no-margin'><span class='text-green'>".number_format("$number",2)."</span></p>
                        </div>

                    </div>
                </div>
            </div>";
  }
//   $vpsql = "SELECT * FROM my_bank WHERE boss_id=:sid";
//   $view_posts = $conn->prepare($vpsql);
//   $view_posts->bindParam(':sid', $boss_id, PDO::PARAM_INT);
//   $view_posts->execute();

  foreach ($mybank as $postsfetch) {
      $idn = $postsfetch['id'];
      $bank_name = $postsfetch['bank_name'];
      //============total of the money=============================
    //   $vpsql = "SELECT * FROM treasury WHERE wh=:sid";
    //   $view_postsi = $conn->prepare($vpsql);
    //   $view_postsi->bindParam(':sid', $idn, PDO::PARAM_INT);
    //   $view_postsi->execute();
      foreach ($bank["bank"][$idn] as $postsfetch) {

          $kind = $postsfetch['kind'];
          $number = $postsfetch['number'];
          echo"
      <div class='col-12 col-md-6 col-lg-4'>
          <div class='box box-body pull-up'>
              <div class='media align-items-center p-0'>
                  <div class='text-center'>
                  <a href='#'><img src='".$dircheckPath."imgs/Currency_img/";if($kind == "usd"){echo"usd.svg";}elseif($kind == "LYD")
{echo"lyd_cur.png";}elseif($kind == "EU"){echo"eu.svg";}elseif($kind == "GBP"){echo"pund.svg";}elseif($kind == "YEN"){echo"yen.svg";}else{echo"usd.svg";}echo"' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='float-left rounded-circle' title='$kind'></a>
                  </div>
                  <div>
                      <h3 class='no-margin'>$kind ($bank_name)</h3>
                  </div>
              </div>
              <div class='flexbox align-items-center mt-25'>
                  <div>
                      <p class='no-margin'><span class='text-green'>".number_format("$number",2)."</span></p>
                  </div>

              </div>
          </div>
      </div>";

      }}
  //========================================================
  ?>


                </div>
<?php }} ?>
