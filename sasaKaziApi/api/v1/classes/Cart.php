<?php


class CartDbHandler
{


    /**
     * @var PDO
     */
    /**
     * DbHandler constructor.
     */


    /**
     * @return mixed
     */

    /**
     * @var PDO
     */
    private $conn;

    /**
     * DbHandler constructor.
     */
    function __construct()
    {
       // require_once dirname(__FILE__) . '../configs/DbConnect.php';
        require_once dirname(__FILE__) . '/../configs/DbConnect.php';
       // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
    public function pay($amount,$phone,$orderId,$firebasetoken){
        $stk_request_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $outh_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';


    $safaricom_pass_key = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
    $safaricom_party_b = "174379";
    $safaricom_bussiness_short_code = "174379";
    $safaricom_Auth_key = "w50fosd811ukoVwaz6pcYpGZQoL3kTxy";
    $safaricom_Secret = "eaObM2hGlYgNDxCz";
    $outh = $safaricom_Auth_key . ':' . $safaricom_Secret;
    $curl_outh = curl_init($outh_url);
    curl_setopt($curl_outh, CURLOPT_RETURNTRANSFER, 1);

    $credentials = base64_encode($outh);
    curl_setopt($curl_outh, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
    curl_setopt($curl_outh, CURLOPT_HEADER, false);
    curl_setopt($curl_outh, CURLOPT_SSL_VERIFYPEER, false);

    $curl_outh_response = curl_exec($curl_outh);

    $json = json_decode($curl_outh_response, true);


    $time = date("YmdHis", time());

    $password = $safaricom_bussiness_short_code . $safaricom_pass_key . $time;


    $curl_stk = curl_init();
    curl_setopt($curl_stk, CURLOPT_URL, $stk_request_url);
    curl_setopt($curl_stk, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $json['access_token']));
    $curl_post_data = array(

        'BusinessShortCode' => '174379',
        'Password' => base64_encode($password),
        'Timestamp' => $time,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => '1',
        'PartyA' => $phone,
        'PartyB' => '174379',
        'PhoneNumber' => $phone,
        'CallBackURL' => 'http://sasa.denkiri.com/sasaKaziApi/api/v1/payment/callback.php?orderId='. urlencode($orderId).'&ftoken='.urlencode($firebasetoken),
        'AccountReference' => 'CompanyXLTD',
        'TransactionDesc' => 'Payment of X'
    );
    $data_string = json_encode($curl_post_data);
    curl_setopt($curl_stk, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_stk, CURLOPT_POST, true);
    curl_setopt($curl_stk, CURLOPT_HEADER, false);
    curl_setopt($curl_stk, CURLOPT_POSTFIELDS, $data_string);

    $curl_stk_response = curl_exec($curl_stk);
    $testjason = json_decode($curl_stk_response);

    if($testjason->ResponseCode == 0){
        return "Request made successfuly";
    }else{
        return "Something went wrong, please try again";
    }


   // return $curl_stk_response;
      //  }

    }
    public function insertIntoOrders($invoice,$name,$date,$amount,$phone){
       $date = date('F d, Y');
       $dmonth = date('F');
       $dyear = date('Y');    
       $paymentStatus=0;
        $stmt = $this->conn->prepare("INSERT INTO mpesa_payments
        (invoice_number,name,date,amount,month,year,paymentStatus,PhoneNumber)
        VALUES(?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sissssis",$invoice,$name,$date,$amount,$dmonth,$dyear,$paymentStatus,$phone);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $this->getOrder($invoice);
    }
        public function pendingTransactions($name){
       

        $stmt = $this->conn->prepare("SELECT * FROM mpesa_payments WHERE paymentStatus!=1 AND name=? ORDER BY id DESC");
        $stmt->bind_param("i",$name);
         $stmt->execute();
         $result = $stmt->get_result();
         $num_rows = $result->num_rows;
         
         $stmt->close();
 
       
         
         if($num_rows>0){
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            return $results;
         }else{
             return NULL;
 
         }
         
       
         
    }
      public function completeTransactions($name){
      
        $stmt = $this->conn->prepare("SELECT * FROM mpesa_payments WHERE paymentStatus=1 AND name=? ORDER BY id DESC");
         $stmt->bind_param("i",$name);
         $stmt->execute();
         $result = $stmt->get_result();
         $num_rows = $result->num_rows;
         
         $stmt->close();
 
       
         
         if($num_rows>0){
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            return $results;
         }else{
             return NULL;
 
         }
         
       
         
    }
      public function transactions($name){
      
        $stmt = $this->conn->prepare("SELECT * FROM mpesa_payments WHERE name=? ORDER BY id DESC");
         $stmt->bind_param("i",$name);
         $stmt->execute();
         $result = $stmt->get_result();
         $num_rows = $result->num_rows;
         
         $stmt->close();
 
       
         
         if($num_rows>0){
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            return $results;
         }else{
             return NULL;
 
         }
         
       
         
    }

    public function updateOrderNotPaid($orderId,$details){
        $stmt = $this->conn->prepare("UPDATE  mpesa_payments SET paymentStatus= 2,payment_details= ?  WHERE invoice_number = ?  ");

        $stmt->bind_param("ss",$details,$orderId);

        $stmt->execute();

        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();

       
        if($num_rows>0){
            return false;
        }else{
            return true;

        }
    }

    public function updateOrderPaid($orderId,$details,$mpesacost,$mpesareciept,$mpesadate,$mpesanumber){
        $stmt = $this->conn->prepare("UPDATE mpesa_payments SET paymentStatus = 1, payment_details= ?,mpesa_amount= ?,MpesaReceiptNumber= ?,TransactionDate= ?,PhoneNumber= ?  WHERE invoice_number = ?  ");

        $stmt->bind_param("ssssss",$details,$mpesacost,$mpesareciept,$mpesadate,$mpesanumber,$orderId);

        $stmt->execute();

        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();

       
        if($num_rows>0){
            return false;
        }else{
            return true;

        }
    }

   
    public function  getOrder($invoice){
        $stmt = $this->conn->prepare("SELECT * FROM mpesa_payments WHERE invoice_number = ?  ");
        $stmt->bind_param("s", $invoice);
       
        $stmt->execute();
        
        
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        
        $stmt->close();

       
        
        if($num_rows>0){
            $data=$result->fetch_assoc();
           $data["invoice_number"]= json_decode($data["invoice_number"], true);
           $data["cashier"]= json_decode($data["cashier"], true);
           $data["date"]= json_decode($data["date"], true);
           $data["type"]= json_decode($data["type"], true);
           $data["amount"]= json_decode($data["amount"], true);
           $data["name"]= json_decode($data["name"], true);
           $data["month"]= json_decode($data["month"], true);
           $data["year"]= json_decode($data["year"], true);
           $data["paymentStatus"]= $this->getPaymentStatus($data["paymentStatus"]);
             return $data;
        }else{
            return null;

        }
    }
     
  
   
    private function getPaymentStatus($paymentStatus){
        if($paymentStatus==1){
            return "Full Payment Recieved by SasaKazi";
        }
        if($paymentStatus==0){
            return " Payment Pending";
        }
        if($paymentStatus==2){
            return " Payment Failed";
        }
        
        return "Null";
    }

    public function objectToArray($d) {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }
        if (is_array($d)) {
            return array_map(__FUNCTION__, $d);
        } else {
            return $d;
        }
    }


 

  








  




    
 




 





  
}











?>
