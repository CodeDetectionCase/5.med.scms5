<?php
function tooss($path){
    global $C_osson,$C_oss_id,$C_oss_key,$C_bucket,$C_region,$C_regon,$conn,$C_dirx;
    if($C_osson==1){
        $path=str_replace("../","",$path);
        $O_md5=getrx("select * from ".TABLE."oss where O_name='".$path."'","O_md5");
        if($O_md5!=md5(file_get_contents($C_dirx.$path))){
            if($O_md5==""){
                mysqli_query($conn,"insert into ".TABLE."oss(O_name,O_md5) values('".$path."','".md5(file_get_contents($C_dirx.$path))."')");
            }else{
                mysqli_query($conn,"update ".TABLE."oss set O_md5=".md5(file_get_contents($C_dirx.$path))." where O_name='".$path."'");
            }

            $kname = strtolower(substr($path,strrpos($path,'.')+1));
            switch($kname){
                case "bmp":
                $mime="image/bmp";
                break;

                case "png":
                $mime="image/png";
                break;

                case "jpg":
                $mime="image/jpg";
                break;

                case "js":
                $mime="application/x-javascript";
                break;

                case "css":
                $mime="text/css";
                break;

                case "jpeg":
                $mime="image/jpeg";
                break;

                case "gif":
                $mime="image/gif";
                break;

                case "mp4":
                $mime="video/mp4";
                break;

                case "mp3":
                $mime="audio/mpeg";
                break;

                case "wma":
                $mime="audio/x-ms-wma";
                break;

                case "wav":
                $mime="audio/x-wav";
                break;

                default:
                $mime="image/jpg";
                break;
            }
            $url = "http://" . $C_bucket . "." . $C_region;
            $policy = "{\"expiration\": \"2120-01-01T12:00:00.000Z\",\"conditions\":[{\"bucket\": \"" . $C_bucket . "\" },[\"content-length-range\", 0, 104857600]]}";
            $policy = base64_encode($policy);
            $signature = base64_encode(hash_hmac("sha1", $policy, $C_oss_key, true));

            if(class_exists('\CURLFile')) {
                $data = array (
                    'OSSAccessKeyId' => $C_oss_id,
                    'Content-Type'=>$mime,
                    'policy' => $policy,
                    'signature' => $signature,
                    'key' => $path,
                    'file'=> new \CURLFile($C_dirx.$path,$mime,$path),
                    'type'=> $mime,
                    'submit' => 'Upload to OSS'
                );
            }else{
                $data = array (
                    'OSSAccessKeyId' => $C_oss_id,
                    'Content-Type'=>$mime,
                    'policy' => $policy,
                    'signature' => $signature,
                    'key' => $path,
                    'file'=>'@'.$C_dirx.$path.";type=".$mime,
                    'submit' => 'Upload to OSS'
                );
            }

            $ch = curl_init ();

            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_HEADER, 0 );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
            $return = curl_exec ( $ch );
            if($return === false){
             var_dump(curl_error($ch));
            }

            $info = curl_getinfo($ch);
            curl_close ($ch);

            if($info["size_download"]==0){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }else{
        return false;
    }
}
?>