<?php
/**
  * Línea del histórico de precios de un artículo para un cliente.
  * 
  * @author Ramón Sanmatías <ramon.sanmatias@gmail.com>
*/
class linea_historico
{
    /**
      * Referencia del artículo.
      * @var type 
    */
    public $referencia;
    /**
      * Url de la página del artículo.
      * @var type 
    */
    public $url;
    /**
      * Descripción del artículo.
      * @var type 
    */
    public $descripcion;
    /**
      * Precio general del artículo.
      * @var type 
    */
    public $pvp_lista;
    /**
      * Cantidad comprada del artículo en la última factura del cliente.
      * @var type 
    */
    public $cantidad;
    /**
      * Precio del artículo en la última factura del cliente.
      * @var type 
    */
    public $pvp;


    public function __construct($l=FALSE)
    {      
      if($l)
        {
            $this->referencia = $l['referencia'];
            $this->url = $l['url'];
            $this->descripcion = $l['descripcion'];
            $this->pvp_lista = floatval($l['pvp_lista']);     
            $this->cantidad = floatval($l['cantidad']);
            $this->pvp = floatval($l['pvp']);
      
        }
        else
        {
            $this->referencia = NULL;
            $this->url = '';
            $this->descripcion = '';
            $this->pvp_lista = 0;   
            $this->cantidad = 0;
            $this->pvp = 0;
        }
   }
}