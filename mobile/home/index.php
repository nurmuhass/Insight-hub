<?php require_once("includes/route.php"); ?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
<link rel="apple-touch-icon" sizes="180x180" href="../../assets/img/favicon.png">
<link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
<title><?php echo $title; ?></title>
<?php include_once("includes/cssFiles.php"); ?>
</head>
    
<body class="theme-light">

<?php if($title <> "Print Data Pin"): ?>
<div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>
<?php endif; ?>
    
<div id="page">
    
    <!-- Page Nav Title Header -->
    <?php if($title <> "Print Data Pin"): ?>
        <div class="header header-fixed header-logo-center">
            <a href="./" class="header-title">Easy Load</a>
            <?php if($title == "Homepage"){?>
                <a href="#" class="font-17 header-icon header-icon-1"><i class="fas fa-home"></i></a>
            <?php } else{ ?>
                <a href="#" data-back-button class="font-17 header-icon header-icon-1"><i class="fas fa-chevron-left"></i></a>
            <?php } ?>
            <a href="notifications" class="font-17 header-icon header-icon-4"><i class="fas fa-envelope"></i><span class="badge bg-red-dark">1</span></a>
            <a href="#" data-toggle-theme class="font-17 header-icon header-icon-3 show-on-theme-dark"><i class="fas fa-sun"></i></a>
            <a href="#" data-toggle-theme class="font-17 header-icon header-icon-3 show-on-theme-light"><i class="fas fa-moon"></i></a>
        </div>

        <!-- Page Footer -->
        <?php include_once("includes/footer.php"); ?>
    <?php endif; ?>
    
    
		
    <!-- Page content start here-->
        <?php include($page); ?>
    <!-- Page content ends here-->

    <!-- Notification Message -->
        <?php echo $msg; ?>
    <!-- Notification Message -->

    <!-- Models -->
    
    <button id="continue-transaction-prompt-btn" data-menu="continue-transaction-prompt" class="d-none"></button>

    <!-- Verify transaction Prompt Model -->
    <div id="continue-transaction-prompt" 
         class="menu menu-box-modal rounded-m" 
         data-menu-height="350" 
         data-menu-width="300">
        <h1 class="text-center mt-4"><i class="fa fa-3x fa-info-circle scale-box color-blue-dark shadow-xl rounded-circle"></i></h1>
        <h3 class="text-center mt-3 font-700">Are you sure?</h3>
        <p class="boxed-text-xl" id="continue-transaction-prompt-msg"></p>
        <div class="row mb-0 me-3 ms-3">
            <div class="col-6">
                <a href="#" class="btn close-menu btn-full btn-m color-red-dark border-red-dark font-600 rounded-s">No</a>
            </div>
            <div class="col-6">
                <?php if($pinstatus == 0): ?>
                <a href="#" data-menu="pin-modal" class="btn btn-full btn-m color-green-dark border-green-dark font-600 rounded-s">Yes</a>
                <?php else: ?>
                    <a href="#" onclick="$('#thetranspin').val(5); $('#transpinbtn').click();" class="btn btn-full btn-m color-green-dark border-green-dark font-600 rounded-s">Yes</a>
                <?php endif ; ?>
            </div>
        </div>
    </div> 
    
    <!-- Confirm Trasaction Pin Model -->
    <div id="pin-modal" 
         class="menu menu-box-modal rounded-m bg-theme" 
         data-menu-width="300"
         data-menu-height="350">
        <div class="menu-title">
            <p class="color-highlight">Confirm Transaction </p>
            <h1 class="font-800">Continue?</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        
        <div class="content">
            <div class="divider mt-n2"></div>
            
            <div class="row mb-0">
                <div class="col-12">
                    <div class="input-style input-style-always-active has-borders mb-4">
                        <label for="form1" class="color-highlight">Transaction Pin</label>
                        <input type="number" id="thetranspin" maxlength="4" class="form-control" placeholder="1234" required>
                    </div>
                </div>
            </div>
            <button action-btn="" id="transpinbtn" style="width:100%" class="close-menu btn btn-full gradient-blue font-13 btn-m font-600 mt-3 rounded-s">Continue</button>
        </div>
    </div>

    <!-- Agent Account Upgrade Model -->
    <div id="agent-upgrade-modal" 
         class="menu menu-box-modal rounded-m bg-theme" 
         data-menu-width="300"
         data-menu-height="450">
        <div class="menu-title">
            <p class="color-highlight">Confirm Transaction </p>
            <h1 class="font-800">Upgrade</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        
        <div class="content">
            <div class="divider mt-n2"></div>
            <div id="agent-upgrade-msg" class="text-danger mb-3">
            You are about to upgrade to an Agent Account. 
            You can view our pricing page for details about the discounts available for Agents. 
            <br/> You would be charged a total of N<?php echo (is_object($data3)) ? $data3->agentupgrade : "0"; ?> for this service. 
            <?php if($pinstatus == 0) {echo "To continue, enter your transaction pin below."; } ?>
            </div>
            <form action="./" method="POST" >
            <div class="row mb-0">
                <?php if($pinstatus == 0) : ?>
                <div class="col-12">
                    <div class="input-style input-style-always-active has-borders mb-4">
                        <input type="password" name="kpin" maxlength="4" class="form-control" placeholder="1234" required>
                        <label for="form1" class="color-highlight">Transaction Pin</label>
                    </div>
                </div>
                <?php else: ?>
                    <input type="hidden" name="kpin" value="0000" />
                <?php endif; ?>
            </div>
            <button type="submit" name="upgrade-to-agent" id="agent-upgrade-btn" style="width:100%" class="btn btn-full gradient-blue font-13 btn-m font-600 mt-3 rounded-s">Continue</button>
            </form>
        </div>
    </div>

    <!-- Vendor Account Upgrade Model -->
    <div id="vendor-upgrade-modal" 
         class="menu menu-box-modal rounded-m bg-theme" 
         data-menu-width="300"
         data-menu-height="450">
        <div class="menu-title">
            <p class="color-highlight">Confirm Transaction </p>
            <h1 class="font-800">Enter Pin</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        
        <div class="content">
            <div class="divider mt-n2"></div>
            <div id="vendor-upgrade-msg" class="text-danger mb-3">
            You are about to upgrade to a Vendor Account. 
            You can view our pricing page for details about the discounts available for Vendors. 
            <br/> You would be charged a total of N<?php echo (is_object($data3)) ? $data3->vendorupgrade : "0"; ?> for this service. 
            To continue, enter your transaction pin below.
            </div>
            <form action="./" method="POST" >
            <div class="row mb-0">
                <div class="col-12">
                    <div class="input-style input-style-always-active has-borders mb-4">
                        <input type="password" name="kpin" maxlength="4" class="form-control" placeholder="1234" required>
                        <label for="form1" class="color-highlight">Transaction Pin</label>
                    </div>
                </div>
            </div>
            <button type="submit" name="upgrade-to-vendor" id="vendor-upgrade-btn" style="width:100%" class="btn btn-full gradient-blue font-13 btn-m font-600 mt-3 rounded-s">Continue</button>
            </form>
        </div>
    </div>



    
    
    <!-- Main Menu--> 
    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-width="280" data-menu-active="nav-pages">
        <?php include("../menu/menu-main.php"); ?>
    </div>
    
    <!-- Share Menu-->
    <div id="menu-share" class="menu menu-box-bottom rounded-m" data-menu-load="../menu/menu-share.php" data-menu-height="370"></div>  
    
    <!-- Colors Menu-->
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="../menu/menu-colors.php" data-menu-height="480"></div> 
     
    
</div>


<?php include_once("includes/jsFiles.php"); ?>
<?php include_once("includes/topupmatescript.php"); ?>

</body>
</html>