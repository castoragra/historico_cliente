<?php
require_model('articulo.php');
require_model('factura_cliente.php');
require_model('linea_historico.php');
 
class historico_cliente extends fs_controller
{
   public $historico;

   public function __construct()
   {
      parent::__construct(__CLASS__, 'Histrórico de Cliente', 'ventas', FALSE, FALSE);
   }
   
   protected function private_core()
   {
        $this->share_extension();

        $articulo = new articulo();
        $facturas = new factura_cliente();
        $cod_cliente = NULL;


        if (isset($_REQUEST['cod'])) 
        {
            $cod_cliente = $_REQUEST['cod'];
        }

        if ($cod_cliente)
        { 
            $facturas_cliente = $facturas->all_from_cliente($cod_cliente);
            // ordenamos las facturas por fecha para obtener los últimos precios.
            uasort($facturas_cliente, "order_by_date");
        
            $this->historico = array(); 
            
            foreach ($facturas_cliente as $factura) {
                $lineas = $factura->get_lineas();
                foreach ($lineas as $linea) {
                    if($linea->referencia && !$this->historico[$linea->referencia]){
                        $pvp_lista = $articulo->get($linea->referencia)->pvp;
                        $linea_historico = new linea_historico (array(
                            'referencia' => $linea->referencia,
                            'url' => $linea->articulo_url(),
                            'descripcion' => $linea->descripcion,
                            'cantidad' => $linea->cantidad,
                            'pvp' => $linea->pvpunitario,
                            'pvp_lista' => $pvp_lista
                        ));
                        $this->historico[$linea->referencia] = $linea_historico;
                    }
                }
            }
            // Ordenamos artículos por su descripción.
            uasort($this->historico, array($this, "order_by_desc"));
        }
   }

   protected function order_by_desc($a, $b)
   {    
       return strnatcasecmp($a->descripcion, $b->descripcion);
   }
   
   protected function oder_by_date($a, $b)
   {
       $date_a = transform_date($a->fecha, $a->hora);
       $date_b = transform_date($b->fecha, $b->hora); 
        if ($date_a  == $date_b) {
            return 0;
        }
        return ($date_a  < $date_b) ? -1 : 1;
   }

   protected function transform_date ($date, $time='00:00:00')
   {
       list($day, $month, $year) = explode("-", $date); // Fechas en español DD-MM-AAAA
       list($hour, $minute, $second) = explode(":", $time);
       return mktime($hour, $minute, $second, $month, $day, $year);
   }

   private function share_extension()
   {
      $extensiones = array(
          array(
              'name' => 'tab_historico',
              'page_from' => __CLASS__,
              'page_to' => 'nueva_venta',
              'type' => 'tab',
              'text' => '<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span class="hidden-xs">&nbsp;Histórico</span>',
              'params' => ''
          ) 
      );

      foreach($extensiones as $ext)
      {
         $fsext = new fs_extension($ext);
         if( !$fsext->save() )
         {
            $this->new_error_msg('Error al guardar la extensión ' . $ext['name']);
         }
      }
   }
}