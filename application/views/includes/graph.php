
<!--graph js code-->
<script>
    $(function () {
        "use strict";


        var t = c3.generate({
            bindto: "#line-chart",
            size: { height: 350 },
            point: { r: 4 },
            color: { pattern: ["#ff3f3f", "#17b3a3"] },
            data: {
                columns: [
                    <?php
                    // $sid =  $_SESSION['id'];
                    // $shopo =  $_SESSION['shop_id'];
                    // $typo =  $_SESSION['type'];
                    // if($typo == "admin"){
                    //     $fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE shop_id='$shopo'";
                    //     $fetchUsers = $conn->prepare($fetchUsers_sql);
                    //     $fetchUsers->execute();
                    // }else{
                    //     $fetchUsers_sql = "SELECT DISTINCT id FROM signup WHERE boss_id='$sid' OR id='$sid'";
                    //     $fetchUsers = $conn->prepare($fetchUsers_sql);
                    //     $fetchUsers->execute();
                    // }
                    //while ($rows = $fetchUsers->fetch(PDO::FETCH_ASSOC)) {
                        
                    $gfid = $Archive_id;//$rows['id']; //}
                    // $vpsql = "SELECT DISTINCT kind FROM treasury WHERE shop_id=:p_user_id AND kind !='LYD' AND tyi='cash'";
                    // $view_posts = $conn->prepare($vpsql);
                    // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    // $view_posts->execute();
                    //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                    foreach($graph as  $postsfetch ) {
                        
                      $numberya = $postsfetch["kind"]; //$postsfetch['kind'];
                      echo"['$numberya'";
                    //   $vpsql = "SELECT * FROM transactions WHERE user_id=:p_user_id AND given_name=:usd";
                    //   $view_postsi = $conn->prepare($vpsql);
                    //   $view_postsi->bindParam(':p_user_id', $sid, PDO::PARAM_INT);
                    //   $view_postsi->bindParam(':usd', $numberya, PDO::PARAM_INT);
                    //   $view_postsi->execute();
                      //while ($postsfetch = $view_postsi->fetch(PDO::FETCH_ASSOC)) {
                    foreach($postsfetch[$numberya] as $postsfetch) {
                        $postsfetch["kind"];
                        $post_id = $postsfetch['post_id'];
                        $user_id = $postsfetch['user_id'];
                        $exchange = $postsfetch['exchange'];
                        $received_name = $postsfetch['received_name'];
                        $received = $postsfetch['received'];
                        $given_name = $postsfetch['given_name'];
                        $kind = $postsfetch['kin'];
                        $amountsd = $postsfetch['given'];
                        $time = $postsfetch['time'];
                        $timex = $postsfetch['timex'];
                        $type = $postsfetch['type'];
                            $media = $postsfetch['media'];
                            if($received_name == $given_name){
                                $amstv = $received*$exchange;
                                $ambtv = $amountsd*$exchange;
                                $cdaexf = $amstv-$ambtv;
}else{
                                $cdaex = $amountsd*$media;
                                $cdaegx = number_format("$cdaex",1, ".", "");
                                $hytu = $received-$cdaegx+0;
                                $cdaexf = number_format("$hytu",2, ".", "");
                            }


                        echo ", ".str_replace(',', '', $cdaexf);

                      }
                    }echo"]";
                     ?>

                ],
            },
            grid: { y: { show: !0, stroke: "#faa700" } }
        });
        // setTimeout(function() {
        //     t.load({
        //         columns: [
        //             ['data1', 230, 190, 300, 500, 300, 400]
        //         ]
        //     })
        // }, 1e3), setTimeout(function() {
        //     t.load({
        //         columns: [
        //             ['data3', 130, 150, 200, 300, 200, 100]
        //         ]
        //     })
        // }, 1500), setTimeout(function() { t.unload({ ids: "data1" }) }, 2e3)


    });
</script>

<script>
    $(function() {
        "use strict";
        // ------------------------------
        // Basic pie chart
        // ------------------------------
        // based on prepared DOM, initialize echarts instance
        var basicpieChart = echarts.init(document.getElementById('basic-pie'));
        var option = {
            // Add title
            title: {
                text: 'A site user access source',
                subtext: 'Purely Fictitious',
                x: 'center'
            },

            // Add tooltip
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },

            // Add legend
            legend: {
                orient: 'vertical',
                x: 'left',
                data: ['LYD', 'EU', 'USD', 'GBP']
            },

            // Add custom colors
            color: ['#689f38', '#38649f', '#389f99', '#ee1044'],

            // Display toolbox
            toolbox: {
                show: true,
                orient: 'vertical',
                feature: {
                    mark: {
                        show: true,
                        title: {
                            mark: 'Markline switch',
                            markUndo: 'Undo markline',
                            markClear: 'Clear markline'
                        }
                    },
                    dataView: {
                        show: true,
                        readOnly: false,
                        title: 'View data',
                        lang: ['View chart data', 'Close', 'Update']
                    },
                    magicType: {
                        show: true,
                        title: {
                            pie: 'Switch to pies',
                            funnel: 'Switch to funnel',
                        },
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                y: '20%',
                                width: '50%',
                                height: '70%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    restore: {
                        show: true,
                        title: 'Restore'
                    },
                    saveAsImage: {
                        show: true,
                        title: 'Same as image',
                        lang: ['Save']
                    }
                }
            },

            // Enable drag recalculate
            calculable: true,

            // Add series
            series: [{
                name: 'Marketing',
                type: 'pie',
                radius: '70%',
                center: ['50%', '57.5%'],
                data: [
                  <?php
                //   $sid =  $_SESSION['id'];
                //   $shop_id =  $_SESSION['shop_id'];
                //   $typo =  $_SESSION['type'];
                //   $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='LYD'";
                //   $view_posts = $conn->prepare($vpsql);
                //   $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                //   $view_posts->execute();
                  //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                  foreach($CashLYD as $postsfetch) {
                    
                        $numberca = $postsfetch['number'];
                        echo"{";

                        echo "value: $numberca,";

                         echo" name: 'LYD'}";
                    }
                   ?>
                   <?php
                //    $sid =  $_SESSION['id'];
                //    $shop_id =  $_SESSION['shop_id'];
                //    $typo =  $_SESSION['type'];
                //    $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='EU'";
                //    $view_posts = $conn->prepare($vpsql);
                //    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                //    $view_posts->execute();
                   //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                    foreach ($CashLEU as $postsfetch) {
                    
                     $numberca = $postsfetch['number'];
                     echo",{";

                     echo "value: $numberca,";

                   echo" name: 'EU'}";}
                    ?>
                    <?php
                    // $sid =  $_SESSION['id'];
                    // $shop_id =  $_SESSION['shop_id'];
                    // $typo =  $_SESSION['type'];
                    // $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='USD'";
                    // $view_posts = $conn->prepare($vpsql);
                    // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    // $view_posts->execute();
                    //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                    foreach($CashUSD as $postsfetch) {
                        
                      $numberca = $postsfetch['number'];
                      echo",{";

                      echo "value: $numberca,";

                    echo" name: 'USD'}";}
                     ?>
                     <?php
                    //  $sid =  $_SESSION['id'];
                    //  $shop_id =  $_SESSION['shop_id'];
                    //  $typo =  $_SESSION['type'];
                    //  $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='GBP'";
                    //  $view_posts = $conn->prepare($vpsql);
                    //  $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    //  $view_posts->execute();
                     //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                    foreach($CashGB as $postsfetch) {                        
                       $numberca = $postsfetch['number'];
                       echo"{";

                       echo "value: $numberca,";

                     echo" name: 'GBP'}";}
                      ?>

                ]
            }]
        };

        basicpieChart.setOption(option);
        // ------------------------------
        // Basic pie chart
        // ------------------------------
        // based on prepared DOM, initialize echarts instance
        var basicdoughnutChart = echarts.init(document.getElementById('basic-doughnut'));
        var option = {
            // Add title
            title: {
                text: 'A site user access source',
                subtext: 'Purely Fictitious',
                x: 'center'
            },

            // Add legend
            legend: {
                orient: 'vertical',
                x: 'left',
                data: ['LYD', 'EU', 'USD', 'GBP']
            },

            // Add custom colors
            color: ['#689f38', '#38649f', '#389f99', '#ee1044'],

            // Display toolbox
            toolbox: {
                show: true,
                orient: 'vertical',
                feature: {
                    mark: {
                        show: true,
                        title: {
                            mark: 'Markline switch',
                            markUndo: 'Undo markline',
                            markClear: 'Clear markline'
                        }
                    },
                    dataView: {
                        show: true,
                        readOnly: false,
                        title: 'View data',
                        lang: ['View chart data', 'Close', 'Update']
                    },
                    magicType: {
                        show: true,
                        title: {
                            pie: 'Switch to pies',
                            funnel: 'Switch to funnel',
                        },
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                y: '20%',
                                width: '50%',
                                height: '70%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    restore: {
                        show: true,
                        title: 'Restore'
                    },
                    saveAsImage: {
                        show: true,
                        title: 'Same as image',
                        lang: ['Save']
                    }
                }
            },

            // Enable drag recalculate
            calculable: true,

            // Add series
            series: [
                {
                    name: 'Marketing',
                    type: 'pie',
                    radius: ['50%', '70%'],
                    center: ['50%', '57.5%'],
                    itemStyle: {
                        normal: {
                            label: {
                                show: true
                            },
                            labelLine: {
                                show: true
                            }
                        },
                        emphasis: {
                            label: {
                                show: true,
                                formatter: '{b}' + '\n\n' + '{c} ({d}%)',
                                position: 'center',
                                textStyle: {
                                    fontSize: '17',
                                    fontWeight: '500'
                                }
                            }
                        }
                    },

                    data: [
                      <?php
                    //   $sid =  $_SESSION['id'];
                    //   $shop_id =  $_SESSION['shop_id'];
                    //   $typo =  $_SESSION['type'];
                    //   $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='LYD'";
                    //   $view_posts = $conn->prepare($vpsql);
                    //   $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    //   $view_posts->execute();
                    //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                    foreach ($CashLYD as $postsfetch) {
                        
                        $numberca = $postsfetch['number'];
                        echo"{";

                        echo "value: $numberca,";

                      echo" name: 'LYD'}";}
                       ?>
                       <?php
                    //    $sid =  $_SESSION['id'];
                    //    $shop_id =  $_SESSION['shop_id'];
                    //    $typo =  $_SESSION['type'];
                    //    $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='EU'";
                    //    $view_posts = $conn->prepare($vpsql);
                    //    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    //    $view_posts->execute();
                    //     while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashLEU as $postsfetch) {
                        
                         $numberca = $postsfetch['number'];
                         echo"{";

                         echo "value: $numberca,";

                       echo" name: 'EU'}";}
                        ?>
                        <?php
                        // $sid =  $_SESSION['id'];
                        // $shop_id =  $_SESSION['shop_id'];
                        // $typo =  $_SESSION['type'];
                        // $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='USD'";
                        // $view_posts = $conn->prepare($vpsql);
                        // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                        // $view_posts->execute();
                        //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashUSD as $postsfetch) 
                        {
                            $numberca = $postsfetch['number'];
                            echo"{";

                            echo "value: $numberca,";

                            echo" name: 'USD'}";
                        }
                         ?>
                         <?php
                        //  $sid =  $_SESSION['id'];
                        //  $shop_id =  $_SESSION['shop_id'];
                        //  $typo =  $_SESSION['type'];
                        //  $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='GBP'";
                        //  $view_posts = $conn->prepare($vpsql);
                        //  $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                        //  $view_posts->execute();

                        // while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashGB as $postsfetch) {
                           $numberca = $postsfetch['number'];
                           echo"{";

                           echo "value: $numberca,";

                         echo" name: 'GBP'}";}
                          ?>
                    ]
                }
            ]
        };

        basicdoughnutChart.setOption(option);
        // ------------------------------
        // customized chart
        // ------------------------------
        // based on prepared DOM, initialize echarts instance
        var customizedChart = echarts.init(document.getElementById('customized-chart'));
        var option = {

            backgroundColor: '#fff',

            title: {
                text: 'Customized Pie',
                left: 'center',
                top: 20,
                textStyle: {
                    color: '#ccc'
                }
            },

            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },

            visualMap: {
                show: false,
                min: 80,
                max: 600,
                inRange: {
                    colorLightness: [0, 1]
                }
            },
            series : [
                {
                    name:'Marketing',
                    type:'pie',
                    radius : '55%',
                    center: ['50%', '50%'],
                    data:[
                      <?php
                    //   $sid =  $_SESSION['id'];
                    //   $shop_id =  $_SESSION['shop_id'];
                    //   $typo =  $_SESSION['type'];
                    //   $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='LYD'";
                    //   $view_posts = $conn->prepare($vpsql);
                    //   $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    //   $view_posts->execute();
                    //   while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                     foreach($CashLYD as $postsfetch){
                        $numberca = $postsfetch['number'];
                        echo"{";

                        echo "value: $numberca,";

                      echo" name: 'LYD'}";}
                       ?>
                       <?php
                    //    $sid =  $_SESSION['id'];
                    //    $shop_id =  $_SESSION['shop_id'];
                    //    $typo =  $_SESSION['type'];
                    //    $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='EU'";
                    //    $view_posts = $conn->prepare($vpsql);
                    //    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    //    $view_posts->execute();
                    //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                    foreach($CashLEU as $postsfetch){
                         $numberca = $postsfetch['number'];
                         echo"{";

                         echo "value: $numberca,";

                       echo" name: 'EU'}";}
                        ?>
                        <?php
                        // $sid =  $_SESSION['id'];
                        // $shop_id =  $_SESSION['shop_id'];
                        // $typo =  $_SESSION['type'];
                        // $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='USD'";
                        // $view_posts = $conn->prepare($vpsql);
                        // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                        // $view_posts->execute();
                        //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashUSD as $postsfetch){
                          $numberca = $postsfetch['number'];
                          echo"{";

                          echo "value: $numberca,";

                        echo" name: 'USD'}";}
                         ?>
                         <?php
                        //  $sid =  $_SESSION['id'];
                        //  $shop_id =  $_SESSION['shop_id'];
                        //  $typo =  $_SESSION['type'];
                        //  $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='GBP'";
                        //  $view_posts = $conn->prepare($vpsql);
                        //  $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                        //  $view_posts->execute();
                        
                        // while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashGB as $postsfetch){
                           $numberca = $postsfetch['number'];
                           echo"{";

                           echo "value: $numberca,";

                         echo" name: 'GBP'}";}
                          ?>
                    ].sort(function (a, b) { return a.value - b.value; }),
                    roseType: 'radius',
                    label: {
                        normal: {
                            textStyle: {
                                color: 'rgba(0, 0, 0, 0.3)'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            lineStyle: {
                                color: 'rgba(0, 0, 0, 0.3)'
                            },
                            smooth: 0.2,
                            length: 10,
                            length2: 20
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: '#38649f',
                            shadowBlur: 200,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    },

                    animationType: 'scale',
                    animationEasing: 'elasticOut',
                    animationDelay: function (idx) {
                        return Math.random() * 200;
                    }
                }
            ]
        };


        customizedChart.setOption(option);
        // ------------------------------
        // Nested chart
        // ------------------------------
        // based on prepared DOM, initialize echarts instance
        var nestedChart = echarts.init(document.getElementById('nested-pie'));
        var option = {

            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },

            // Add legend
            legend: {
                orient: 'vertical',
                x: 'left',
                data: ['LYD', 'EU', 'USD', 'GBP']
            },

            // Add custom colors
            color: ['#689f38', '#38649f', '#389f99', '#ee1044'],

            // Display toolbox
            toolbox: {
                show: true,
                orient: 'vertical',
                feature: {
                    mark: {
                        show: true,
                        title: {
                            mark: 'Markline switch',
                            markUndo: 'Undo markline',
                            markClear: 'Clear markline'
                        }
                    },
                    dataView: {
                        show: true,
                        readOnly: false,
                        title: 'View data',
                        lang: ['View chart data', 'Close', 'Update']
                    },
                    magicType: {
                        show: true,
                        title: {
                            pie: 'Switch to pies',
                            funnel: 'Switch to funnel',
                        },
                        type: ['pie', 'funnel']
                    },
                    restore: {
                        show: true,
                        title: 'Restore'
                    },
                    saveAsImage: {
                        show: true,
                        title: 'Same as image',
                        lang: ['Save']
                    }
                }
            },

            // Enable drag recalculate
            calculable: false,

            // Add series
            series: [

                // Inner
                {
                    name: 'Countries',
                    type: 'pie',
                    selectedMode: 'single',
                    radius: [0, '40%'],

                    // for funnel
                    x: '15%',
                    y: '7.5%',
                    width: '40%',
                    height: '85%',
                    funnelAlign: 'right',
                    max: 1548,

                    itemStyle: {
                        normal: {
                            label: {
                                position: 'inner'
                            },
                            labelLine: {
                                show: false
                            }
                        },
                        emphasis: {
                            label: {
                                show: true
                            }
                        }
                    },

                    data: [
                      <?php
                    //   $sid =  $_SESSION['id'];
                    //   $shop_id =  $_SESSION['shop_id'];
                    //   $typo =  $_SESSION['type'];
                    //   $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='LYD'";
                    //   $view_posts = $conn->prepare($vpsql);
                    //   $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    //   $view_posts->execute();
                    foreach($CashLYD as $postsfetch){
                      //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        $numberca = $postsfetch['number'];
                        echo"{";

                        echo "value: $numberca,";

                      echo" name: 'LYD'}";}
                       ?>
                       <?php
                    //    $sid =  $_SESSION['id'];
                    //    $shop_id =  $_SESSION['shop_id'];
                    //    $typo =  $_SESSION['type'];
                    //    $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='EU'";
                    //    $view_posts = $conn->prepare($vpsql);
                    //    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    //    $view_posts->execute();
                    //    while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                    foreach($CashLEU as $postsfetch){
                         $numberca = $postsfetch['number'];
                         echo"{";

                         echo "value: $numberca,";

                       echo" name: 'EU'}";}
                        ?>
                        <?php
                        // $sid =  $_SESSION['id'];
                        // $shop_id =  $_SESSION['shop_id'];
                        // $typo =  $_SESSION['type'];
                        // $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='USD'";
                        // $view_posts = $conn->prepare($vpsql);
                        // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                        // $view_posts->execute();
                        // while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashUSD as $postsfetch){
                          $numberca = $postsfetch['number'];
                          echo"{";

                          echo "value: $numberca,";

                        echo" name: 'USD'}";}
                         ?>
                         <?php
                        //  $sid =  $_SESSION['id'];
                        //  $shop_id =  $_SESSION['shop_id'];
                        //  $typo =  $_SESSION['type'];
                        //  $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='GBP'";
                        //  $view_posts = $conn->prepare($vpsql);
                        //  $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                        //  $view_posts->execute();
                        //  while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashGB as $postsfetch){
                           $numberca = $postsfetch['number'];
                           echo"{";

                           echo "value: $numberca,";

                         echo" name: 'GBP'}";}
                          ?>
                    ]
                },

                // Outer
                {
                    name: 'Countries',
                    type: 'pie',
                    radius: ['60%', '85%'],

                    // for funnel
                    x: '55%',
                    y: '7.5%',
                    width: '35%',
                    height: '85%',
                    funnelAlign: 'left',
                    max: 1048,

                    data: [
                      <?php
                    //   $sid =  $_SESSION['id'];
                    //   $shop_id =  $_SESSION['shop_id'];
                    //   $typo =  $_SESSION['type'];
                    //   $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='LYD'";
                    //   $view_posts = $conn->prepare($vpsql);
                    //   $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    //   $view_posts->execute();
                    foreach($CashLYD as $postsfetch){
                      //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        $numberca = $postsfetch['number'];
                        echo"{";

                        echo "value: $numberca,";

                      echo" name: 'LYD'}";}
                       ?>
                       <?php
                    //    $sid =  $_SESSION['id'];
                    //    $shop_id =  $_SESSION['shop_id'];
                    //    $typo =  $_SESSION['type'];
                    //    $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='EU'";
                    //    $view_posts = $conn->prepare($vpsql);
                    //    $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                    //    $view_posts->execute();
                    //    while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashLEU as $postsfetch){
                         $numberca = $postsfetch['number'];
                         echo"{";

                         echo "value: $numberca,";

                       echo" name: 'EU'}";}
                        ?>
                        <?php
                        // $sid =  $_SESSION['id'];
                        // $shop_id =  $_SESSION['shop_id'];
                        // $typo =  $_SESSION['type'];
                        // $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='USD'";
                        // $view_posts = $conn->prepare($vpsql);
                        // $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                        // $view_posts->execute();
                        // while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashUSD as $postsfetch){
                          $numberca = $postsfetch['number'];
                          echo"{";

                          echo "value: $numberca,";

                        echo" name: 'USD'}";}
                         ?>
                         <?php
                        //  $sid =  $_SESSION['id'];
                        //  $shop_id =  $_SESSION['shop_id'];
                        //  $typo =  $_SESSION['type'];
                        //  $vpsql = "SELECT kind, number FROM treasury WHERE shop_id=:p_user_id AND tyi='cash' AND kind='GBP'";
                        //  $view_posts = $conn->prepare($vpsql);
                        //  $view_posts->bindParam(':p_user_id', $shop_id, PDO::PARAM_INT);
                        //  $view_posts->execute();
                         //while ($postsfetch = $view_posts->fetch(PDO::FETCH_ASSOC)) {
                        foreach($CashGB as $postsfetch){
                           $numberca = $postsfetch['number'];
                           echo"{";

                           echo "value: $numberca,";

                         echo" name: 'GBP'}";}
                          ?>
                    ]
                }
            ]
        };


        nestedChart.setOption(option);
        // ------------------------------
        // pole chart
        // ------------------------------
        // based on prepared DOM, initialize echarts instance
        var poleChart = echarts.init(document.getElementById('pole-chart'));
        // Data style
        var dataStyle = {
            normal: {
                label: {show: false},
                labelLine: {show: false}
            }
        };

        // Placeholder style
        var placeHolderStyle = {
            normal: {
                color: 'rgba(0,0,0,0)',
                label: {show: false},
                labelLine: {show: false}
            },
            emphasis: {
                color: 'rgba(0,0,0,0)'
            }
        };
        var option = {
            title: {
                text: 'Stacked histogram',
                subtext: 'Weekly Data',
                x: 'center',
                y: 'center',
                itemGap: 10,
                textStyle: {
                    color: 'rgba(30,144,255,0.8)',
                    fontSize: 19,
                    fontWeight: '500'
                }
            },

            // Add tooltip
            tooltip: {
                show: true,
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },

            // Add legend
            legend: {
                orient: 'vertical',
                x: document.getElementById('pole-chart').offsetWidth / 2,
                y: 30,
                x: '55%',
                itemGap: 15,
                data: ['Monday','Tuesday','Wednesday']
            },

            // Add custom colors
            color: ['#689f38', '#38649f', '#ff8f00'],

            // Add series
            series: [
                {
                    name: '1',
                    type: 'pie',
                    clockWise: false,
                    radius: ['75%', '90%'],
                    itemStyle: dataStyle,
                    data: [
                        {
                            value: 60,
                            name: 'Monday'
                        },
                        {
                            value: 40,
                            name: 'invisible',
                            itemStyle: placeHolderStyle
                        }
                    ]
                },

                {
                    name: '2',
                    type:'pie',
                    clockWise: false,
                    radius: ['60%', '75%'],
                    itemStyle: dataStyle,
                    data: [
                        {
                            value: 30,
                            name: 'Tuesday'
                        },
                        {
                            value: 70,
                            name: 'invisible',
                            itemStyle: placeHolderStyle
                        }
                    ]
                },

                {
                    name: '3',
                    type: 'pie',
                    clockWise: false,
                    radius: ['45%', '60%'],
                    itemStyle: dataStyle,
                    data: [
                        {
                            value: 10,
                            name: 'Wednesday'
                        },
                        {
                            value: 90,
                            name: 'invisible',
                            itemStyle: placeHolderStyle
                        }
                    ]
                }
            ]
        };
        poleChart.setOption(option);
        // ------------------------------
        // nightingale chart
        // ------------------------------
        // based on prepared DOM, initialize echarts instance
        var nightingaleChart = echarts.init(document.getElementById('nightingale-chart'));
        var option = {
            title: {
                text: 'Ningdinger Rose Map',
                subtext: 'Purely fictitious',
                x: 'center'
            },

            // Add tooltip
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: +{c}$ ({d}%)"
            },

            // Add legend
            legend: {
                x: 'left',
                y: 'top',
                orient: 'vertical',
                data:['rose1','rose2','rose3','rose4','rose5','rose6','rose7','rose8']
            },

            color: ['#689f38', '#e4eaec', '#38649f', '#389f99', '#ee1044', '#ff8f00', '#673ab7', '#4974e0'],

            // Display toolbox
            toolbox: {
                show: true,
                orient: 'vertical',
                feature: {
                    mark: {
                        show: true,
                        title: {
                            mark: 'Markline switch',
                            markUndo: 'Undo markline',
                            markClear: 'Clear markline'
                        }
                    },
                    dataView: {
                        show: true,
                        readOnly: false,
                        title: 'View data',
                        lang: ['View chart data', 'Close', 'Update']
                    },
                    magicType: {
                        show: true,
                        title: {
                            pie: 'Switch to pies',
                            funnel: 'Switch to funnel',
                        },
                        type: ['pie', 'funnel']
                    },
                    restore: {
                        show: true,
                        title: 'Restore'
                    },
                    saveAsImage: {
                        show: true,
                        title: 'Same as image',
                        lang: ['Save']
                    }
                }
            },

            // Enable drag recalculate
            calculable: true,

            // Add series
            series: [
                {
                    name: 'Area Mode',
                    type: 'pie',
                    radius: ['15%', '73%'],
                    center: ['50%', '57%'],
                    roseType: 'area',

                    // Funnel
                    width: '40%',
                    height: '78%',
                    x: '30%',
                    y: '17.5%',
                    max: 450,
                    sort: 'ascending',

                    data: [
                        {value: 440, name: 'rose1'},
                        {value: 260, name: 'rose2'},
                        {value: 350, name: 'rose3'},
                        {value: 250, name: 'rose4'},
                        {value: 210, name: 'rose5'},
                        {value: 350, name: 'rose6'},
                        {value: 300, name: 'rose7'},
                        {value: 450, name: 'rose8'}
                    ]
                }
            ]
        };
        nightingaleChart.setOption(option);


        // ------------------------------
        // bar-polar-stack-radial
        // ------------------------------

        var dom = document.getElementById("bar-polar-stack-radial");
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        app.title = 'Stacked bar chart in polar coordinate system';

        option = {
            color: ['#689f38', '#38649f', '#389f99'],
            angleAxis: {
                type: 'category',
                data: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                z: 10
            },
            radiusAxis: {
            },
            polar: {
            },
            series: [{
                type: 'bar',
                data: [1, 2, 3, 4, 3, 5, 1],
                coordinateSystem: 'polar',
                name: 'A',
                stack: 'a'
            }, {
                type: 'bar',
                data: [2, 4, 6, 1, 3, 2, 1],
                coordinateSystem: 'polar',
                name: 'B',
                stack: 'a'
            }, {
                type: 'bar',
                data: [1, 2, 3, 4, 1, 2, 5],
                coordinateSystem: 'polar',
                name: 'C',
                stack: 'a'
            }],
            legend: {
                show: true,
                data: ['A', 'B', 'C']
            }
        };
        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }
        //------------------------------------------------------
        // Resize chart on menu width change and window resize
        //------------------------------------------------------
        $(function () {

            // Resize chart on menu width change and window resize
            $(window).on('resize', resize);
            $(".sidebartoggler").on('click', resize);

            // Resize function
            function resize() {
                setTimeout(function() {

                    // Resize chart
                    basicpieChart.resize();
                    basicdoughnutChart.resize();
                    customizedChart.resize();
                    nestedChart.resize();
                    poleChart.resize();
                }, 200);
            }
        });
    });
</script>
