<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /*
     * 服务器post数据请求
     * @param string $url  
     * @param array $param    
     */
    public function http_post($url, $param, $post_file = false){
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE)
        {
                curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); // CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file)
        {
                $strPOST = $param;
        }
        else
        {
            $strPOST = json_encode($param);
//                $aPOST = array();
//                foreach ($param as $key => $val)
//                {
//                        $aPOST[] = $key . "=" . urlencode($val);
//                }
//                $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_TIMEOUT,5);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200)
        {
                return $sContent;
        }
        else
        {
                return false;
        }
    }
    
    
    //服务器get数据请求
    public function http_get($url){
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE)
        {
                curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); // CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_TIMEOUT,5);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200)
        {
                return $sContent;
        }
        else
        {
                return false;
        }
    }
    
    
    
    /* xml转数组
     * @return json
     */

    public function FromXml($xml) {
        if (!$xml) {
            echo "xml数据异常！";
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }
}
