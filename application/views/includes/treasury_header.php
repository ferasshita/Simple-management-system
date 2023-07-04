<div class="right-title">
    <div id="fresh" class="d-flex mt-10 justify-content-end">
      <?php if ($_SESSION['Treasury'] == '0') {
if($_SESSION['hide_trsh'] == '0'){ ?>
        <?php
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
        //============total of the money=============================

        // $vpsql = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi=:bgh AND shop_id=:sid OR tyi=:bgh AND boss_id=:sid)";
        // $view_postsi = $conn->prepare($vpsql);
        // $view_postsi->bindParam(':sid', $gfid, PDO::PARAM_INT);
        // $view_postsi->bindParam(':bgh', $bgh, PDO::PARAM_STR);
        // $view_postsi->execute();
        $delk = "0";
        $bgh = "cash";
        $shop_id = $_SESSION['shop_id'];
        $vpsqlc = "SELECT * FROM treasury WHERE (kind='LYD' OR kind='USD') AND (tyi= '$bgh' AND shop_id=$shop_id)";
        $FetchedDatac=$this->comman_model->get_all_data_by_query($vpsqlc);
        foreach ($FetchedDatac as  $postsfetch)
        {
            $kind = $postsfetch['kind'];
            $number = $postsfetch['number'];
            echo"<div class='d-lg-flex mr-20 ml-10 d-none'>
                      <div class='chart-text mr-10'>
                          <h6 class='mb-0'><small>$kind</small></h6>
                          <h4 class='mt-0 text-primary'>".number_format("$number",2)."</h4>
                      </div>
                      <div class='spark-chart'>
                          <div id='thismonth'><canvas width='60' height='35' style='display: inline-block; width: 60px; height: 35px; vertical-align: top;'></canvas></div>
                      </div>
                  </div>";
        }

        //========================================================
        ?>

<?php }} ?>

    </div>
</div>
