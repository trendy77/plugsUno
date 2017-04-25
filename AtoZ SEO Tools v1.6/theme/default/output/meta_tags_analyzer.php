<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

/*
 * @author Balaji
 * @name A to Z SEO Tools - PHP Script
 * @copyright © 2015 ProThemes.Biz
 *
 */
?>

  <div class="container main-container">
	<div class="row">
      	
          	<div class="col-md-8 main-index">
            
            <div class="xd_top_box">
             <?php echo $ads_720x90; ?>
            </div>
            
              	<h2 id="title"><?php echo $data['tool_name']; ?></h2>

               <?php if ($pointOut != 'output') { ?>
               <br />
               <p><?php echo $lang['28']; ?>
               </p>
               <form method="POST" action="<?php echo $toolOutputURL;?>" onsubmit="return fixURL();"> 
               <input type="text" name="url" id="url" value="" class="form-control"/>
               <br />
               <?php
               if ($toolCap)
               {
               echo $captchaCode;  
               }
               ?>
               <div class="text-center">
               <input class="btn btn-info" type="submit" value="<?php echo $lang['8']; ?>" name="submit"/>
               </div>
               </form>     
                          
               <?php 
               } else { 
               //Output Block
               if(isset($error)) {
                
                echo '<br/><br/><div class="alert alert-error">
                <strong>Alert!</strong> '.$error.'
                </div><br/><br/>
                <div class="text-center"><a class="btn btn-info" href="'.$toolURL.'">'.$lang['12'].'</a>
                </div><br/>';
                
               } else {
               ?>
                        <br />

                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td><strong><?php echo $lang['29']; ?></strong></td>
                                            <td><?php echo $myUrl; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo $lang['30']; ?></strong></td>
                                            <td><?php echo $site_title; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo $lang['31']; ?></strong></td>
                                            <td><?php echo $site_description; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo $lang['32']; ?></strong></td>
                                            <td><?php echo $site_keywords; ?></td>
                                        </tr>
                                    </tbody></table>
    
    <div class="text-center">
    <br /> &nbsp; <br />
    <a class="btn btn-info" href="<?php echo $toolURL; ?>"><?php echo $lang['27']; ?></a>
    <br />
    </div>

<?php } } ?>

<br />

<div class="xd_top_box">
<?php echo $ads_720x90; ?>
</div>

<h2 id="sec1" class="about_tool"><?php echo $lang['11'].' '.$data['tool_name']; ?></h2>
<p>
<?php echo $data['about_tool']; ?>
</p> <br />
</div>              
            
<?php
// Sidebar
require_once(THEME_DIR."sidebar.php");
?>     		
        </div>
    </div> <br />