<?php
class ApiDni
{
    public static function buscarPorDni($dni)
    {
        $token = 'apis-token-15494.AzUyCGX6RWbcWUFSj0QirF0A4xkMGZw1'; // Reemplaza con tu token de apis.net.pe
        $url = "https://api.apis.net.pe/v1/dni?numero=$dni";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token"
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return ['success' => false, 'error' => 'No se pudo conectar a la API'];
        }

        $data = json_decode($response, true);

        if (isset($data['numeroDocumento'])) {
            return [
                'data' => [
                    'nombres' => $data['nombres'] ?? '',
                    'apellido_paterno' => $data['apellidoPaterno'] ?? '',
                    'apellido_materno' => $data['apellidoMaterno'] ?? '',
                    'fecha_nacimiento' => $data['fechaNacimiento'] ?? ''
                    // 'sexo' => $sexo // Eliminado, la API no lo retorna realmente
                ]
            ];
        } else {
            return ['success' => false, 'error' => 'No se encontró el DNI'];
        }
    }
}
