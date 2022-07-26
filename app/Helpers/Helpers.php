<?php 

//===CURL POST Method===//
if(!function_exists('getPOSTRequest'))
{
    function getPOSTRequest($url, $data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => 
                array(
                    "cache-control: no-cache",
                    "content-type: application/json"            
                ),
        ));
        $response = curl_exec($curl); 
        if (curl_errno($curl)) 
        {
            return curl_error($curl);
        }
        else
        {
            return $response;  
        }
        curl_close($curl);       
    }
}