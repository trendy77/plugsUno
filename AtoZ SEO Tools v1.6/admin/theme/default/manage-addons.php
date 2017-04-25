<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

/*
 * @author Balaji
 * @name: A to Z SEO Tools
 * @copyright © 2015 ProThemes.Biz
 *
 */
?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $p_title; ?>  
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="/admin/?route=manage-addons"><i class="fa fa-cogs"></i> Admin</a></li>
            <li class="active"><?php echo $p_title; ?> </li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Install Addons</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                 <?php
                if(isset($msg)){
                    echo $msg;
                }?>
                                    <table class="table table-hover">
                                        <tbody><tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                        </tr>
                                        <?php
                                        $loopC = 1;
                                        foreach($minMsg as $msg){
                                          echo '
                                          <tr>
                                            <td>'.$loopC.'</td>
                                            <td>'.$msg[0].'</td>
                                            <td>'.$msg[1].'</td>
                                          ';  
                                          $loopC++;
                                        } 
                                        ?>
                                         </tbody></table>
                                        
                <hr />
                <form action="#" method="POST" enctype="multipart/form-data">

                <br />
                                        <div class="form-group">											
											<label for="addonID">Select a addon package:</label>
											<div class="controls">			   
                                         <input type="file" name="addonUpload" id="addonUpload" class="btn btn-default" />
                                         <input type="hidden" name="addonID" id="addonID" value="1" /> <br />
                                         <?php if($minError){ ?>
                                         <input type="submit" value="Upload" name="submit" class="btn btn-primary" disabled="" />
                                         <?php } else { ?>
                                         <input type="submit" value="Upload" name="submit" class="btn btn-primary" />
                                         <?php } ?>
		                                  </div> <!-- /controls -->	
					
                    					</div> <!-- /control-group -->
                </form>
                
                <div class="row">
                <div class="col-md-6">
                <br />
                <div class="callout callout-danger">
                    <h4>Note!</h4>
                    <p>1) Don't upload unkown addons and make sure it is downloaded from authorized site.</p>
                    <p>2) Read instruction before processing automatic installation.</p>
                  </div>
                </div>
                </div>

                </div><!-- /.box-body -->
      
              </div><!-- /.box -->
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
