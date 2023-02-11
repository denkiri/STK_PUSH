<?php
class DbHandler
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
    
    public function login($email,$password){
       
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE user.email = ?  AND user.password = ? ");
        $stmt->bind_param("ss", $email,$password);
       
        $stmt->execute();
        
        
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        
        $stmt->close();

       
        
        if($num_rows>0){
            return $result->fetch_assoc();
        }else{
            return NULL;

        }
        
      
        
    }


    public function register($email,$phone,$name,$password)
    {

        $stmt = $this->conn->prepare("INSERT INTO user (user.email,user.mobile,user.name,user.password)
           
        VALUES(?,?,?,?)");

        $stmt->bind_param("ssss",$email,$phone,$name,$password);

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
    public function updateToken($id,$token){
        $stmt = $this->conn->prepare("UPDATE user SET token = ? WHERE user.id = ?");
        $stmt->bind_param("si",$token, $id);
        $result =$stmt->execute();
        //$result = $stmt->get_result();
        
      //  $num_rows = $stmt->num_rows;
        $stmt->close();

       // echo json_encode($result);
      
        return $result;
    }
    function authenticateToken($token) {
        $stmt = $this->conn->prepare("SELECT id FROM user WHERE user.token = ? ");
        $stmt->bind_param("s", $token);
       
        $stmt->execute();
        
        
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        
        $stmt->close();

       
        
        if($num_rows>0){
            return $result->fetch_assoc()["id"];
        }else{
            return NULL;

        }
    }
     
    public function isUserExists($email,$mobile)
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE user.email = ?  OR user.mobile = ?");
        $stmt->bind_param("ss", $email,$mobile);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

      public function isEmailExists($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE  user.email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
}
?>
