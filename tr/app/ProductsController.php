<?php session_start();?>



<?php if(isset($_POST['action'])){
    switch($_POST['action']){
        case 'create':
            $name = $_POST['name'];
            $slug = $_POST['slug'];
            $description= $_POST['description'];
            $features = $_POST['features'];
            $brand_id = $_POST['brand_id'];
            $p = new ProductsController();

            $img = $p->moveImage($_FILES['imagen']);

        
            $p->create($name, $slug, $description, $features, $brand_id, $img);   
            break;
        
    }
}
class ProductsController{
    public function getAll(){
    $curl = curl_init();
    $token = $_SESSION['token'];
    // echo $token; 
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://crud.jonathansoto.mx/api/products',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$token
        // 'Authorization: Bearer 1|PcPVKlixuabgKcsW26pfyg7PI7SWNXS8YoItBC2M'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
    }

    public static function gerBrands(){
        
$curl = curl_init();

$token = $_SESSION['token'];
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://crud.jonathansoto.mx/api/brands',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token
  ),
));$response = curl_exec($curl); 
curl_close($curl);
$response = json_decode($response);

if ( isset($response->code) && $response->code > 0) {
    
    return $response->data;
}else{

    return array();
}
    }

    public function create($name, $slug, $description, $features, $brand_id, $img){

        
        $token = $_SESSION['token'];         
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://crud.jonathansoto.mx/api/products',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('name' => $name,'slug' => $slug,'description' =>$description,'features' => $features,'brand_id' => $brand_id,'cover'=> NEW CURLFile($img)),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token
        ),
    ));
        $response = curl_exec($curl);
        curl_close($curl);

        unlink($img);

       // echo $response;
       header('Location: ../products/index.php?success=true');
        //echo "<script>alert(".$response.")</script>";
        
    }


    
        


    public function moveImage($file){
        $fileName = $file['name'];
        $UPLOAD_PATH  =  $_SERVER['DOCUMENT_ROOT'] . '/tr/public/img/upload/'.$fileName;
        $fileTempName = $file['tmp_name'];
        move_uploaded_file($fileTempName, $UPLOAD_PATH);
       // unlink($UPLOAD_PATH);
        return $UPLOAD_PATH;


        
    }


    public static function getBySlug($slug){
                
        $curl = curl_init();
        $token = $_SESSION['token'];    

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://crud.jonathansoto.mx/api/products/slug/'.$slug,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token
        ),
        ));

 
        $response = curl_exec($curl);
        $response = json_decode($response);


        if ( isset($response->code) && $response->code > 0) {
    
            return $response->data;
        }else{
        
            return array();
        }
        // echo $response;

    }
}
?>