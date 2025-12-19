<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.helloasso-sandbox.com/oauth2/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'grant_type=refresh_token&refresh_token=CfDJ8AtsRDLgvMdDogQ7LO0eHgXuZyV2WhbxjkGkQwGQKFl6ztX71Bw7ElIBv2Cwf7oaynZiyEUWyLxfHH9eHtvGPS-9pDytRNjEB-Wb27X_XZKHfpyLhU8moZkAWuuD4d4xW8hWO0-q6SNOSisPUx_vWT8ZXY0Kbo29zXsJZPVw9ViTIoLnboIekPrc-N0Q3lPD_F5Lav8LzTtFjtqEa1-Cr96cRYrjjZ44M733pyx321IM2PlJimjYz1Hqcgdb94crWeu2nc5tGswzGx0ml6g5MRZiErdgUV3P6vSJQ9Ilv-IG8nO0CLB2NZUmKD3qdAEY4jgmolBeKfAIlezueuyTy9U',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'accept: application/json',
    'Cookie: __cf_bm=1Hs1AFzrhlggUfI6kfZjsLLrZ6d9ni9tgoJFsc6jZmE-1759941124-1.0.1.1-yIrJiZQOjjmoAtKhAFKZqIJZKhA9BXf1Y18pht9_H8pqxSiGO6Br3ULegij2KqZavUU9Kdbe_Oyy4RwBPzgtSbDFeg2F5GBMXvRwafibdDU'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
