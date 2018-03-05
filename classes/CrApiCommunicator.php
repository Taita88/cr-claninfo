<?php

  class CrApiCommunicator{
  
    public function getClanMembers(){
    
      $request = curl_init();
    
      //Set the URL that you want to GET by using the CURLOPT_URL option.
      curl_setopt($request, CURLOPT_URL, 'http://api.cr-api.com/clan/2LR0QP');
       
      //Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
      curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
       
      //Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
      curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
      
      curl_setopt($request, CURLOPT_HTTPHEADER, array(
        'auth: db52c43dd4bf476590d9de780ba9b02afe8c204562b04113a4d0f38660363043',    
        ));
       
      //Execute the request.
      $data = curl_exec($request);
       
      //Close the cURL handle.
      curl_close($request);
      
      $parsed_data = json_decode($data);
      
      return $parsed_data->members;
    
    }  
  
  }    
  
  

?>