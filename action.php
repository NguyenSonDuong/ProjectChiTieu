<?php
// đây là biến toán cục
global $connect;

function connect_db($databaseName){
    // gọi tới biến toàn cục
    global $connect;

    if(!$connect){
        // kết nối
        $connect = mysqli_connect("localhost","root","",$databaseName) or die("Lỗi kết nối");
        // kiểu mã hóa luồng dữ liệu gửi đi
        mysqli_set_charset($connect,"utf8");
    }

}

function disconnect_db(){
    global $connect;
    // ngắt kết nói với database
    if($connect){
        mysqli_close($connect);
    }
}

function insertUser($id, $name){
    global $connect;
    if(!$connect){
        connect_db("user_infor");
    }
    $query = "INSERT INTO user_spend VALUES (?,?,?)";
    $stmt = mysqli_prepare($connect,$query);
    mysqli_stmt_bind_param($stmt,"sss",$var1,$var2, $date);
    $var1 = $id;
    $var2 = $name;
    $date = new DateTime("now");
    if(mysqli_stmt_execute($stmt)){
        return array(
            "text"=>"Đã thêm tài khoản thành công"
        );
    }else{
        exit(sendText("Lỗi thêm người dùng"));

    }
}

function sendText($mess){
    $data = array(
        "messages"=> array(
            array(
                "text"=>$mess
            )
        )
    );
    return json_encode($data);
}
function sendArrayText($mess){
    $data = array(
        "messages"=> $mess
    );
    return json_encode($data);
}



function insertSpend($id,$khoanchitieu,$sotien,$ghichu){
    global $connect;
    if(!$connect){
        connect_db("user_infor");
    }
    $query = "INSERT INTO spend VALUES (?,?,?,?,?)";
    $stmt = mysqli_prepare($connect,$query);
    mysqli_stmt_bind_param($stmt,"sssss",$id,$khoanchitieu, $sotien,$ghichu,$date);
    $date = new DateTime("now");
    if(mysqli_stmt_execute($stmt)){
        return array(
            "text"=>"Đã thêm chi tiêu thành công"
        );
    }else{
        exit(sendText("Lỗi thêm chi tiêu"));
    }

}

function loginCheck($id){
    global $connect;
    if(!$connect){
        connect_db("user_infor");
    }
    $query = "SELECT user_spend.id FROM user_spend WHERE id = ? ";
    $stmt = mysqli_prepare($connect,$query);
    mysqli_stmt_bind_param($stmt,"ss",$id);
    $re = mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id);
    if($re){
        $reponsive = array();
        while (mysqli_stmt_fetch($stmt)){
            $reponsive[] = array(
                'ID'=>$id
            );
        }
        if(count($reponsive)!=1){
            return false;
        }else
            return true;
    }else{
        return false;
    }

}

function getSpendDay($id){
    global $connect;
    if(!$connect){
        connect_db("user_infor");
    }
    $query = "SELECT spend.*,user_spend.username FROM user_spend,spend WHERE user_spend.id = ? AND  user_spend.id = spend.id AND DATE_FORMAT(user_spend.time_spend, '%Y-%c-%d') = ? ";
    $stmt = mysqli_prepare($connect,$query);
    mysqli_stmt_bind_param($stmt,"s",$id);
    $re = mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id,$khoanchitieu,$sotienchitieu,$ghichu,$date,$username);
    if($re){
        $reponsive = array();
        while (mysqli_stmt_fetch($stmt)){
            $reponsive[] = array(
                'ID'=>$id
            );
        }
        if(count($reponsive)!=1){
            return false;
        }else
            return true;
    }else{
        return false;
    }

}

?>

