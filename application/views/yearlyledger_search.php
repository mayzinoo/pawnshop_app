
<h3 class="center">ေခ်းေငြ လခ်ဳပ္စာရင္း</h3>

<div class="row mt">
          <div class="col-md-12">
            <div class="white-panel pn">
          <div class="custom-check goleft mt">
          <table class="table ledgerlist">
            <thead>

                  <tr>
                    <th></th>
                    <?php
                      foreach($collateraldata->result() as $collist):
                    ?>
                      <th><?php echo $collist->vr_type; ?></th>
                    <?php endforeach; ?>
                  </tr>
                  <tr>
                    <th></th>

                    <?php
                      foreach($collateraldata->result() as $cdate):
                    ?>
                        <th><?php echo $cdate->ydate; ?> - <?php echo $cdate->mdate; ?>  </th>
                    <?php endforeach; ?>

                  </tr>

            </thead>
            <tbody id="content">
                  <tr>
                    <td style="background:#CD232C;">ေခ်းေငြ</td>

                      <!-- <?php
                        for ($d=1;$d<=$collateraldata->num_rows();$d++) {
                          echo "<td></td>";
                        }

                     ?> -->
                     <?php
                       foreach($ctotalamt->result() as $totalamt):
                     ?>
                         <td><?php echo number_format($totalamt->ctotal); ?></td>
                     <?php endforeach; ?>

                  </tr>

                  <tr>

       <td>
                   <?php

                    foreach($redeemdata->result() as $rdate):

                      echo "<tr><td>";
                      echo $rdate->ydate; ?> - <?php echo $rdate->mdate;
                   echo" </td>";
                      foreach($collateraldata->result() as $collist):
                    $vdate= $rdate->ydate."-".$rdate->mdate;
                    $spdata=$this->Main_model->get_byspecificdata($vdate,$collist->vr_type);
                    if($spdata->loantotal==""){
                      echo "<td>";
                      echo "";
                      echo "</td>";
                    }
                    else{
                      echo "<td>";
                      echo number_format($spdata->loantotal);
                      echo "</td>";
                    }

                  endforeach;

                   echo "</tr>";

                endforeach;

               /* for($i=1;$i<=$collateraldata->num_rows()-$redeemdata->num_rows();$i++)
                          {
                          echo  '<td align="center">-</td>';

                          }*/

                ?>
</td>
              </tr>
              <tr>
                  <td>ေရြးေငြေပါင္း</td>
                              <?php
                              echo" </td>";
                                 foreach($collateraldata->result() as $collist):
                                     $vdate= $rdate->ydate."-".$rdate->mdate;
                                     $spdata=$this->Main_model->get_byspecifirdata($collist->vr_type);
                                     echo "<td>";
                                     echo number_format($spdata->loantotal);
                                     echo "</td>";

                                endforeach;

                              echo "</tr>";

                           ?>
             <tr>
               <td>က်န္ေငြ</td>
                       <?php
                       echo" </td>";
                          foreach($collateraldata->result() as $collist):
                              $vdate= $rdate->ydate."-".$rdate->mdate;
                              $spdata=$this->Main_model->get_byspecifiremaindata($collist->vr_type);
                              $result=number_format($spdata->remaintotal);
                              if($result=='0'){
                                echo "<td>";
                                echo "";
                                echo "</td>";
                              }
                            else{
                              echo "<td>";
                              echo $result;
                              echo "</td>";
                            }


                         endforeach;

                       echo "</tr>";

                    ?>

            </tbody>

          </table>
        </div><!-- /table-responsive -->
      </div><!--/ White-panel -->
  </div><! --/col-md-12 -->
</div><! -- row
