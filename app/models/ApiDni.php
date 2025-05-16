<?php
class ApiDni
{
  private static $token = 'josemhs_dg2790ahxp1z';

  public static function buscarPorDni($dni)
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://go.net.pe:3000/api/v2/dni/' . $dni,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . self::$token
      ),
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
      return ['error' => $error];
    }

    return json_decode($response, true);
  }
}
