<?php

/**
 * creado por dvb 
 * el 6 de septiembre del 2023
 * Webservice para consumir la API de Latin AD
 * https://gestor.latinad.com/
 */

class WS_Latin
{

    private $url = 'https://api.publinet.io/';
    private $token = '';
    private $email = 'gerencia@nubograma.com';
    private $pass = 'DfORtFulINCH';
    private $idcompania = 535;

    public function __construct()
    {
        $this->Login();
    }

    /**
     * proceso para ejecutar el curl
     */
    public function Execute($endpoint,$params,$method){

        $curl = curl_init();
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $this->url.$endpoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->token
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if($method == "POST"){
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        // EXECUTE:
        $response = curl_exec($curl);

        #print_r($response);
        #print_r($response);

        curl_close($curl);

        return $response = json_decode($response, true);
    }

    /**
     * proceso para el login y obtener el token
     */
    public function Login(){
        $params = [
            'login'=>$this->email,
            'password'=>$this->pass,
            'remember'=>1,
        ];
        $respuesta = $this->Execute("login",$params,"POST");

        if(isset($respuesta['token'])){
            // lo guardamos 
            $this->token = $respuesta['token'];
        }
    }

    /**
     * proceso para obtener todos los anuncios de la campañia
     */
    public function getAds(){

        echo $this->token;
        $respuesta = $this->Execute("companies/".$this->idcompania."/contents",[],"GET");

        return $respuesta;
    }

    /**
     * proceso para crear un anuncio
     * https://api.publinet.io/contents
     */
    public function  crearAnuncio(){

    }

    /**
     * proceso para ver el detalle de un anuncio
     * https://api.publinet.io/contents/ID
     */

    /**
     * proceso para crear un cliente
     * https://api.publinet.io/clients
     * {"name":"Test","billing_information_country":"México","billing_information_address":"","billing_information_name":"","billing_information_tax_id":"","agency_profit":15,"cpm_agency_markup":30,"brands":[]}
     */

    /**
     * proceso para vincular anuncio a pantalla
     * https://api.publinet.io/contents/231097/displays
     */


}