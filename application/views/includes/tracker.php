
          <?php
              $url = "http://api.exchangeratesapi.io/v1/latest?access_key=1b1f79b7139ed09bc15fa82a7aae60a5";

              $client = curl_init($url);
              curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
              $response = curl_exec($client);

              $result = json_decode($response);
                $gdjs = $result->rates;

                //show 3 number after pointLYD
                $usd= number_format($gdjs->USD, 1, '.', ' ');
                $eur= number_format($gdjs->EUR, 3, '.', ' ');
                $gbp= number_format($gdjs->GBP, 3, '.', ' ');
                $jpy= number_format($gdjs->JPY, 3, '.', ' ');
                $try= number_format($gdjs->TRY, 3, '.', ' ');
                $myr= number_format($gdjs->MYR, 3, '.', ' ');
                $rub= number_format($gdjs->RUB, 3, '.', ' ');
                $LYD= number_format($gdjs->LYD, 3, '.', ' ');
                $TRY= number_format($gdjs->TRY, 3, '.', ' ');
          ?>
          <div class="row">
              <div class="col-12">
                  <div class="box">
                      <div class="box-body">
                          <ul id="webticker-2" class="text-center">
                            <li class="py-5"><img src='<?php echo base_url();?>Asset/imgs/Currency_img/usd.svg' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='rounded-circle' title='USD'> <p class="mb-0">USD</p> <span class="d-block text-green"> <?php echo"$usd" ?> </span></li>
                            <li class="py-5"><img src='<?php echo base_url();?>Asset/imgs/Currency_img/eu.svg' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='rounded-circle' title='EUR'> <p class="mb-0">EUR</p> <span class="d-block text-green"> <?php echo"$eur" ?> </span></li>
                            <li class="py-5"><img src='<?php echo base_url();?>Asset/imgs/Currency_img/pund.svg' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='rounded-circle' title='GBP'> <p class="mb-0">GBP</p> <span class="d-block text-green"> <?php echo"$gbp" ?> </span></li>
                            <li class="py-5"><img src='<?php echo base_url();?>Asset/imgs/Currency_img/yen.svg' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='rounded-circle' title='JPY'> <p class="mb-0">JPY</p> <span class="d-block text-green"> <?php echo"$jpy" ?> </span></li>
                            <li class="py-5"><img src='<?php echo base_url();?>Asset/imgs/Currency_img/usd.svg' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='rounded-circle' title='TRY'> <p class="mb-0">TRY </p> <span class="d-block text-green"> <?php echo"$TRY" ?> </span></li>
                            <li class="py-5"><img src='<?php echo base_url();?>Asset/imgs/Currency_img/pund.svg' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='rounded-circle' title='AUD'> <p class="mb-0">AUD</p> <span class="d-block text-green"> <?php echo"$try" ?> </span></li>
                            <li class="py-5"><img src='<?php echo base_url();?>Asset/imgs/Currency_img/usd.svg' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='rounded-circle' title='MYR'> <p class="mb-0">MYR</p> <span class="d-block text-green"> <?php echo"$myr" ?> </span></li>
                            <li class="py-5"><img src='<?php echo base_url();?>Asset/imgs/Currency_img/pund.svg' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='rounded-circle' title='RUB'> <p class="mb-0">RUB </p> <span class="d-block text-green"> <?php echo"$rub" ?> </span></li>
                            <li class="py-5"><img src='<?php echo base_url();?>Asset/imgs/Currency_img/lyd_cur.png' style='width:40px;font-size: 2.5rem;display: inline-block;transition: 0.1s ease all;margin-top:1px;margin-bottom:1px;' class='rounded-circle' title='LYD'> <p class="mb-0">LYD </p> <span class="d-block text-green"> <?php echo"$LYD" ?> </span></li>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
