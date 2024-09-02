
<div class="d-flex justify-content-between">
<a class="btn btn-success btn-block mr-2" href="api-setting">General Setting</a> 
<a class="btn btn-primary btn-block ml-2 mt-0" href="monnify-setting">Monnify Setting</a>
<a class="btn btn-info btn-block ml-4 mt-0" href="paystack-setting">Paystack Setting</a>
</div>
<hr/>
<div class="d-flex justify-content-between">
    <a class="btn btn-dark btn-sm btn-block" href="api-setting">General</a>
    <a class="btn btn-dark btn-sm btn-block ml-2 mt-0" href="airtime-api-setting">Airtime</a> 
    <a class="btn btn-dark btn-sm btn-block ml-2 mt-0" href="data-api-setting">Data</a>
    <a class="btn btn-dark btn-sm btn-block ml-2 mt-0" href="wallet-api-setting">Wallet</a>
</div>
<hr/>
<div class="row">
<div class="col-12">
    
    <div class="box">
        <div class="box-header with-border">
            <div class="d-flex justify-content-between mt-2">
                <a class="mr-2" href="data-api-setting?network=MTN"><img src="../../assets/images/mtn.png" class="img-fluid" style="width:80px;" /></a> 
                <a class="mr-2" href="data-api-setting?network=AIRTEL"><img src="../../assets/images/airtel.png" class="img-fluid" style="width:80px;" /></a> 
                <a class="mr-2" href="data-api-setting?network=GLO"><img src="../../assets/images/glo.png" class="img-fluid" style="width:80px;" /></a> 
                <a class="mr-2" href="data-api-setting?network=9MOBILE"><img src="../../assets/images/9mobile.png" class="img-fluid" style="width:80px;" /></a> 
            </div> 
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <form  method="post" class="form-submit">
                    
                <div class="form-group">
                    <label for="success" class="control-label"><?php echo $_GET["network"]; ?> Api Key (SME DATA)</label>
                    <div class="">
                    <input type="text" name="<?php echo strtolower($_GET["network"]); ?>SmeApi" value="<?php echo $controller->getConfigValue($data[0],strtolower($_GET["network"])."SmeApi"); ?>" placeholder="API Key" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="success" class="control-label"><?php echo $_GET["network"]; ?> Api Provider (SME DATA)</label>
                    <div class="">
                    <select name="<?php echo strtolower($_GET["network"]); ?>SmeProvider" class="form-control" required>
                        <option value="">Select Api Provider</option>
                        <?php $SmeProvider=$controller->getConfigValue($data[0],strtolower($_GET["network"])."SmeProvider"); ?>
                        
                        <?php foreach($data[1] AS $apiLinks): if($apiLinks->type == "Data"): ?>
                        <?php if($SmeProvider == $apiLinks->value): ?>
                            <option value="<?php echo $apiLinks->value; ?>" selected><?php echo $apiLinks->name; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $apiLinks->value; ?>"><?php echo $apiLinks->name; ?></option>
                        <?php endif; endif; endforeach; ?>

                    </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="success" class="control-label"><?php echo $_GET["network"]; ?> Api Key (Gifting Data)</label>
                    <div class="">
                    <input type="text" name="<?php echo strtolower($_GET["network"]); ?>GiftingApi" value="<?php echo $controller->getConfigValue($data[0],strtolower($_GET["network"])."GiftingApi"); ?>" placeholder="API Key" class="form-control" required>
                    </div>
                </div>
                

                <div class="form-group">
                    <label for="success" class="control-label"><?php echo $_GET["network"]; ?> Api Provider (Gifting Data)</label>
                    <div class="">
                    <select name="<?php echo strtolower($_GET["network"]); ?>GiftingProvider" class="form-control" required>
                        <option value="">Select Api Provider</option>
                        <?php $GiftingProvider=$controller->getConfigValue($data[0],strtolower($_GET["network"])."GiftingProvider"); ?>
                        
                        <?php foreach($data[1] AS $apiLinks): if($apiLinks->type == "Data"): ?>
                        <?php if($GiftingProvider == $apiLinks->value): ?>
                            <option value="<?php echo $apiLinks->value; ?>" selected><?php echo $apiLinks->name; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $apiLinks->value; ?>"><?php echo $apiLinks->name; ?></option>
                        <?php endif; endif; endforeach; ?>

                    </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="success" class="control-label"><?php echo $_GET["network"]; ?> Api Key (Corporate Data)</label>
                    <div class="">
                    <input type="text" name="<?php echo strtolower($_GET["network"]); ?>CorporateApi" value="<?php echo $controller->getConfigValue($data[0],strtolower($_GET["network"])."CorporateApi"); ?>" placeholder="API Key" class="form-control" required>
                    </div>
                </div>
                

                <div class="form-group">
                    <label for="success" class="control-label"><?php echo $_GET["network"]; ?> Api Provider (Corporate Data)</label>
                    <div class="">
                    <select name="<?php echo strtolower($_GET["network"]); ?>CorporateProvider" class="form-control" required>
                        <option value="">Select Api Provider</option>
                        <?php $CorporateProvider=$controller->getConfigValue($data[0],strtolower($_GET["network"])."CorporateProvider"); ?>
                        
                        <?php foreach($data[1] AS $apiLinks): if($apiLinks->type == "Data"): ?>
                        <?php if($CorporateProvider == $apiLinks->value): ?>
                            <option value="<?php echo $apiLinks->value; ?>" selected><?php echo $apiLinks->name; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $apiLinks->value; ?>"><?php echo $apiLinks->name; ?></option>
                        <?php endif; endif; endforeach; ?>

                    </select>
                    </div>
                </div>

                
                <div class="form-group">
                    <div class="">
                       <button type="submit" name="update-api-config" class="btn btn-info btn-submit"><i class="fa fa-save" aria-hidden="true"></i> Update Details</button>
                    </div>
                </div>
        </form>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
</div>
</div>



