<?php
    //Auto Load Classes
    require_once("../autoloader.php");

    //Allowed API Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: POST");
    header("Allow: POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

    $headers = apache_request_headers();
    $response = array();
    $controller = new ApiAccess;
    $airtimeController = new Airtime;
    date_default_timezone_set('Africa/Lagos');
            


    // -------------------------------------------------------------------
    //  Check Request Method
    // -------------------------------------------------------------------

    $requestMethod = $_SERVER["REQUEST_METHOD"]; 
    if ($requestMethod !== 'POST') {
        header('HTTP/1.0 400 Bad Request');
        $response["status"] = "fail";
        $response["msg"] = "Only POST method is allowed";
        echo json_encode($response); exit(); 
    } 
    
    // -------------------------------------------------------------------
    //  Check For Api Authorization
    // -------------------------------------------------------------------
    
    if((isset($headers['Authorization']) || isset($headers['authorization'])) || (isset($headers['Token']) || isset($headers['token']))){
        if((isset($headers['Authorization']) || isset($headers['authorization']))){
            $token = trim(str_replace("Token", "", (isset($headers['Authorization'])) ? $headers['Authorization'] : $headers['authorization']));
        }
        if((isset($headers['Token']) || isset($headers['token']))){
            $token = trim(str_replace("Token", "", (isset($headers['Token'])) ? $headers['Token'] : $headers['token']));
        }
        $result=$controller->validateAccessToken($token);
        if($result["status"] == "fail"){
            // tell the user no products found
            header('HTTP/1.0 401 Unauthorized');
            $response["status"] = "fail";
            $response["msg"] = "Authorization token not found $token";
            echo json_encode($response); exit(); 
        }
        else{
            $usertype = $result["usertype"];
            $userbalance = (float) $result["balance"]; 
            $userid = $result["userid"];
            $refearedby = $result["refearedby"];
            $referal = $result["phone"];
            $referalname = $result["name"];
         }
    }
    else{
        header('HTTP/1.0 401 Unauthorized');
        // tell the user no products found
        $response["status"] = "fail";
        $response["msg"] = "Your authorization token is required.";
        echo json_encode($response); exit(); 
    }

    // -------------------------------------------------------------------
    //  Get The Request Details
    // -------------------------------------------------------------------

    $input = @file_get_contents("php://input");

    //decode the json file
    $body = json_decode($input);
    
    // Support Other API Format
    $body2 = array();   
    if(isset($body->Ported_number)){$body2["ported_number"]=$body->Ported_number;}
    if(isset($body->mobile_number)){$body2["phone"]=$body->mobile_number;}
    if(!isset($body->ref)){$body2["ref"]=time();}
    if(!isset($body->airtime_type)){$body2["airtime_type"]="VTU";}
    $body = (object) array_merge( (array)$body, $body2 );

    $network= (isset($body->network)) ? $body->network : "";
    $phone= (isset($body->phone)) ? $body->phone : "";
    $amount= (isset($body->amount)) ? $body->amount : "";
    $ported_number= (isset($body->ported_number)) ? $body->ported_number : "false";
    $airtime_type= (isset($body->airtime_type)) ? $body->airtime_type : "";
    $ref= (isset($body->ref)) ? $body->ref : "";

    // -------------------------------------------------------------------
    //  Check Inputs Parameters
    // -------------------------------------------------------------------

    $requiredField = "";
    
    if($airtime_type == ""){$requiredField ="Airtime Type Is Required"; }
    if($amount == ""){$requiredField ="Amount Is Required"; }
    if($phone == ""){$requiredField ="Phone Is Required"; }
    if($network == ""){$requiredField ="Network Id Required"; }
    if($ref == ""){$requiredField ="Ref Is Required"; }
    if($airtime_type <> ""){
        if($airtime_type <> "VTU" && $airtime_type <> "Share And Sell"){
            $requiredField ="Airtime Type can only be VTU or Share And Sell";
        }
    }

    if($requiredField <> ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    //  Verify Network Id
    // -------------------------------------------------------------------
    
    $result = $controller->verifyNetworkId($network);
    if($result["status"]=="fail"){
        header('HTTP/1.0 400 Invalid Network Id');
        $response['status']="fail";
        $response['msg'] = "The Network id is invalid";
        echo json_encode($response);
        exit();
    }
    else{
        $networkDetails=$result; 
    }


    // -------------------------------------------------------------------
    //  Check If Network Is Available
    // -------------------------------------------------------------------

    if($airtime_type == "Share And Sell"){
        if($networkDetails["networkStatus"] <> "On" || $networkDetails["sharesellStatus"] <> "On"){
            header('HTTP/1.0 400 Network Not Available');
            $response['status']="fail";
            $response['msg'] = "Sorry, {$networkDetails["network"]} is not available at the moment";
            if($networkDetails["sharesellStatus"] == "Off"){
                $response['msg'] = "Sorry, {$networkDetails["network"]} Share And Sell service is not available at the moment";
            }
            echo json_encode($response);
            exit();
        }
    }
    

    if($airtime_type == "VTU"){
        if($networkDetails["networkStatus"] <> "On" || $networkDetails["vtuStatus"] <> "On"){
            header('HTTP/1.0 400 Network Not Available');
            $response['status']="fail";
            $response['msg'] = "Sorry, {$networkDetails["network"]} is not available at the moment";
            if($networkDetails["vtuStatus"] == "Off"){
                $response['msg'] = "Sorry, {$networkDetails["network"]} VTU service is not available at the moment";
            }
            echo json_encode($response);
            exit();
        }
    }
    
    
    

    // -------------------------------------------------------------------
    //  Verify Phone Number
    // -------------------------------------------------------------------
    if($ported_number == "false"){
        $result = $controller->verifyPhoneNumber($phone,$networkDetails["network"]);
        if($result["status"]=="fail"){
            header('HTTP/1.0 400 Invalid Phone Number');
            $response['status']="fail";
            $response['msg'] = $result["msg"];
            echo json_encode($response);
            exit();
        }
    }

    // -------------------------------------------------------------------
    //  Calculate Airtime Discount
    // -------------------------------------------------------------------
    
    $result = $controller->calculateAirtimeDiscount($network,$airtime_type,$amount,$usertype);
    $amountopay = (float) $result["discount"];
    $buyamount =  (float) $result["buyamount"]; 
    $profit = $amountopay - $buyamount;
    
    // -------------------------------------------------------------------
    //  Check Id User Balance Can Perform The Transaction
    // -------------------------------------------------------------------
    if($amountopay > $userbalance || $amountopay < 0){
            header('HTTP/1.0 400 Insufficient Balance');
            $response['status']="fail";
            $response['msg'] = "Insufficient balance fund your wallet and try again";
            echo json_encode($response);
            exit();
    }

    // -------------------------------------------------------------------
    //  Check For Minimum And Maximum Amount Of Airtime Purchase
    // -------------------------------------------------------------------
    $airtimelimit = $controller->getSiteSettings();
    $airtimemin = (int) $airtimelimit->airtimemin;
    $airtimemax = (int) $airtimelimit->airtimemax;

    if($amount < $airtimemin){
        header("HTTP/1.0 400 Minimum airtime purchase is $airtimemin");
        $response['status']="fail";
        $response['msg'] = "Minimum airtime you can purchase is $airtimemin";
        echo json_encode($response);
        exit();
    }

    if($amount > $airtimemax){
        header("HTTP/1.0 400 Maximum airtime purchase is $airtimemax");
        $response['status']="fail";
        $response['msg'] = "Maximum airtime you can purchase is $airtimemax";
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    //  Check For Api Authorization
    // -------------------------------------------------------------------
    
    $result = $controller->checkIfTransactionExist($ref);
    if($result["status"]=="fail"){
        header('HTTP/1.0 400 Transaction Ref Already Exist');
        $response['status']="fail";
        $response['msg'] = "Transaction Ref Already Exist";
        echo json_encode($response);
        exit();
    }

 
    // -------------------------------------------------------------------
    // Purchase Airtime
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------

    $servicename = "Airtime";
    $servicedesc = "{$networkDetails["network"]} Airtime purchase of N{$amount} for phone number {$phone}";
    
     
    $result = $controller->checkTransactionDuplicate($servicename,$servicedesc);
    if($result["status"]=="fail"){
        header('HTTP/1.0 400 Possible Transaction Duplicate, Please Verify Transaction & Try Again After 60 Seconds');
        $response['status']="fail";
        $response['msg'] = "Possible Transaction Duplicate, Please Verify Transaction & Try Again After 60 Seconds";
        echo json_encode($response);
        exit();
    }

    // Debit User Before Performing The Transaction
    $oldbalance = (float) $userbalance;
    $deibt = $oldbalance - $amountopay;
    $checkDebit=$controller->debitUserBeforeTransaction($userid,$deibt);

    if($checkDebit <> "success"){
        header('HTTP/1.0 400 Could Not Complete Transaction');
        $response['status']="fail";
        $response['msg'] = "Could Not Complete Transaction";
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    //  Record Transaction As Processing With Status 5
    // -------------------------------------------------------------------
    $transRecord=$controller->recordTransaction($userid,$servicename,$servicedesc,$amountopay,$userbalance,$body->ref,"5");
        
    // -------------------------------------------------------------------
    //  Send Request To Purchase Airtime
    // -------------------------------------------------------------------

    $result = $airtimeController->purchaseMyAirtime($body,$networkDetails);
     
    // -------------------------------------------------------------------
    // Debit User Wallet & Record Transaction
    // -------------------------------------------------------------------
    if($result["status"]=="success"){
        if($refearedby <> ""){ $controller->creditReferalBonus($referal,$referalname,$refearedby,$servicename); }
        $controller->updateTransactionStatus($userid,$body->ref,$amountopay,"0");
        $controller->saveProfit($body->ref,$profit);
        $response['status']="success";
        $response['Status']="successful";
        header('HTTP/1.0 200 Transaction Successful');
        echo json_encode($response);
        exit();
    } 
    else{
        header('HTTP/1.0 400 Transaction Failed');
        $response['status']="fail";
        $response['Status']="failed";
        $response['msg'] = $result["msg"];
        $controller->updateTransactionStatus($userid,$body->ref,$amountopay,"1");
        echo json_encode($response);
        exit();
    }

?>