<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH.'libraries/spout/src/Spout/Autoloader/autoload.php';
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;

class KPI extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Dao_bitacoras_model');
        $this->load->model('Dao_reportes_model');
    }

    public function Control() {
        $data = array(
            'active_sidebar' => false,
            'title' => 'FO Movil Control KPI',
            'active' => 'ccili',
            'sub_bar' => true,
        );
        $this->load->view('parts/header', $data);
        $this->load->view('Front_Office_Movil/Control');
        $this->load->view('parts/footer');
    }
    public function cargarInfo($fechaInicio, $fechaFinal) {

        $this->load->library('Datatables');
        $FO_table = $this->datatables->init();
        $FO_table  ->select("TICKETID,  TIPO_TKT,  CREATIONDATE,  STATUS,  INTERNALPRIORITY,  CREATEDBY,  OWNERGROUP,  INCEXCLUIR,  INCMEXCLUSION,  PROVEEDORES,  DESCRIPTION,  RUTA_TKT,  REGIONAL,  TIEMPO_VIDA_TKT,  TIEMPO_RESOLUCION_TKT,  TIEMPO_DETECCION, TIEMPO_ESCALA,  TIEMPO_FALLA,  TIEMPO_OT_ALM")
                    ->from('maximo.INCIDENT') 
                    ->where('(DESCRIPTION LIKE "%FAOC:%" OR DESCRIPTION LIKE "%FAOB:%" OR DESCRIPTION LIKE "%FEE:%"  OR DESCRIPTION LIKE "%FI:%" OR DESCRIPTION LIKE "%FAPP:%" OR DESCRIPTION LIKE "%FOIP:%")')
                    ->where("OWNERGROUP NOT LIKE '%FO_SDH%'")
                    ->where('DESCRIPTION NOT LIKE "%DEPU%" ')
                    ->where('DESCRIPTION NOT LIKE "%FHG%" ')
                    ->where('DESCRIPTION NOT LIKE "%FSP%" ')
                    ->where('DESCRIPTION NOT LIKE "%MAIL%" ')
                    ->where('DESCRIPTION NOT LIKE "%MG%" ')
                    ->where('DESCRIPTION NOT LIKE "%NO EXITOSO%"  ')
                    ->where('DESCRIPTION NOT LIKE "%VM%" ')
                    ->where('DESCRIPTION NOT LIKE "%VENTANA MANT%" ')
                    ->where('DESCRIPTION NOT LIKE "%FEE%SIN PE%"')  
                    ->where("STATUS !=", "ELIMINADO")
                    ->where("STATUS !=", "CANCELADO")
                    ->where("CREATIONDATE >=", date("Y-m-d", strtotime($fechaInicio)))
                    ->where("CREATIONDATE <=", date("Y-m-d h:m", strtotime($fechaFinal . ' ' . '23:59')));
                  
        $FO_table 
                    ->column('TICKETID','TICKETID')
                    ->column('TIPO_TKT','TIPO_TKT')
                    ->column('CREATIONDATE','CREATIONDATE')
                    ->column('STATUS','STATUS')
                    ->column('INTERNALPRIORITY','INTERNALPRIORITY')
                    ->column('CREATEDBY','CREATEDBY')
                    ->column('OWNERGROUP','OWNERGROUP')
                    ->column('INCEXCLUIR','INCEXCLUIR')
                    ->column('INCMEXCLUSION','INCMEXCLUSION')
                    ->column('PROVEEDORES','PROVEEDORES')
                    ->column('DESCRIPTION','DESCRIPTION')
                    ->column('RUTA_TKT','RUTA_TKT')
                    ->column('REGIONAL','REGIONAL')
                    ->column('TIEMPO_VIDA_TKT','TIEMPO_VIDA_TKT')
                    ->column('TIEMPO_RESOLUCION_TKT','TIEMPO_RESOLUCION_TKT')
                    ->column('TIEMPO_DETECCION','TIEMPO_DETECCION')
                    ->column('TIEMPO_ESCALA','TIEMPO_ESCALA')
                    ->column('TIEMPO_FALLA','TIEMPO_FALLA')
                    ->column('TIEMPO_OT_ALM','TIEMPO_OT_ALM');
                    $FO_table ->style(array(
                        'class' => 'table table-striped',
                    ))
                    ->set_options('scrollX', 'true');
        $this->datatables->create('FO_table', $FO_table); 
        $this->load->view('Front_Office_Movil/loadTable');
       
        //             $bitacora_BO_table = $this->datatables->init();
        // $bitacora_BO_table->select('*')->from('znoc.BITACORA_BO')->where("DATE_FORMAT(fecha, '%Y-%m-%d') BETWEEN '$fechaInicio' and '$fechaFinal'");

        // $bitacora_BO_table
        //     ->style(array(
        //     'class' => 'table table-striped',
        //     ))
        //     ->column('Fecha', 'fecha')
        //     ->column('Ticket', 'ticket')
        //     ->column('Tarea', 'tarea')
        //     ->column('Estacion', 'estacion');
            
        // $this->datatables->create('bitacora_BO_table', $bitacora_BO_table); 
        // $this->load->view('bitacoras/loadBOData');
    }


    public function getIncidentsFO()
    {
      $query = $this->input->post('query');
      $data = $this->Dao_reportes_model->getIncidentFO($query);
      echo json_encode($data);
    }
    public function exportIncidentsFO()
    {
      $data = $_SESSION['x'];
      // echo '<pre>'; print_r("lol"); echo '</pre>';
      
  
        $writer = WriterEntityFactory::createXLSXWriter();
        $style = (new StyleBuilder())
        ->setShouldWrapText(false)
        ->build();
  
        $writer->openToBrowser('IncidentesFOMovil('.date('Y-m-d').').xlsx');
        $titles = array('TICKETID', 'TIPO_TKT', 'CREATIONDATE', 'STATUS', 'INTERNALPRIORITY', 'CREATEDBY', 'OWNERGROUP', 'INCEXCLUIR', 'INCMEXCLUSION', 'PROVEEDORES', 'DESCRIPTION', 'RUTA_TKT', 'REGIONAL', 'TIEMPO_VIDA_TKT', 'TIEMPO_RESOLUCION_TKT', 'TIEMPO_DETECCION', 'TIEMPO_ESCALA', 'TIEMPO_FALLA', 'TIEMPO_OT_ALM');
  
        $header = WriterEntityFactory::createRowFromArray($titles);
        $writer->addRow($header);
  
        foreach ($data as $val) {
          $cells = array();
          foreach ($val as $val1) {
            array_push($cells,WriterEntityFactory::createCell($val1,$style));
          }
          $rowFromValues = WriterEntityFactory::createRow($cells);
          $writer->addRow($rowFromValues);
        }
        $writer->close();
  
    }

    public function getGraphInfo() {
        // $inicio = ;
        // $final = $this->input->post('final');

        $inicio = str_replace('/', '-', $this->input->post('inicio') );
        $fdesde = date("Y-m-d", strtotime($inicio));

        $final = str_replace('/', '-', $this->input->post('final') );
        $fhasta = date("Y-m-d", strtotime($final));

        $data = $this->Dao_reportes_model->getGraphInfo($fdesde,$fhasta);
    echo json_encode($data);
    }


    public function loadModal($fecha, $prioridad) {

        $this->load->library('Datatables');
        $modal_table = $this->datatables->init();
        $modal_table  ->select("TICKETID,  TIPO_TKT,  CREATIONDATE,  STATUS,  INTERNALPRIORITY,  CREATEDBY,  OWNERGROUP,  INCEXCLUIR,  INCMEXCLUSION,  PROVEEDORES,  DESCRIPTION,  RUTA_TKT,  REGIONAL,  TIEMPO_VIDA_TKT,  TIEMPO_RESOLUCION_TKT,  TIEMPO_DETECCION, TIEMPO_ESCALA,  TIEMPO_FALLA,  TIEMPO_OT_ALM")
                    ->from('maximo.INCIDENT') 
                    ->where('(DESCRIPTION LIKE "%FAOC:%" OR DESCRIPTION LIKE "%FAOB:%" OR DESCRIPTION LIKE "%FEE:%"  OR DESCRIPTION LIKE "%FI:%" OR DESCRIPTION LIKE "%FAPP:%" OR DESCRIPTION LIKE "%FOIP:%")')
                    ->where("OWNERGROUP NOT LIKE '%FO_SDH%'")
                    ->where('DESCRIPTION NOT LIKE "%DEPU%" ')
                    ->where('DESCRIPTION NOT LIKE "%FHG%" ')
                    ->where('DESCRIPTION NOT LIKE "%FSP%" ')
                    ->where('DESCRIPTION NOT LIKE "%MAIL%" ')
                    ->where('DESCRIPTION NOT LIKE "%MG%" ')
                    ->where('DESCRIPTION NOT LIKE "%NO EXITOSO%"  ')
                    ->where('DESCRIPTION NOT LIKE "%VM%" ')
                    ->where('DESCRIPTION NOT LIKE "%VENTANA MANT%" ')
                    ->where('DESCRIPTION NOT LIKE "%FEE%SIN PE%"')  
                    ->where("STATUS !=", "ELIMINADO")
                    ->where("STATUS !=", "CANCELADO")
                    ->where("INTERNALPRIORITY =", $prioridad )
                    ->where("CREATIONDATE >=", $fecha)
                    ->where("CREATIONDATE <=", $fecha . ' ' . '23:59');
                  
        $modal_table 
                    ->column('TICKETID','TICKETID')
                    ->column('TIPO_TKT','TIPO_TKT')
                    ->column('CREATIONDATE','CREATIONDATE')
                    ->column('STATUS','STATUS')
                    ->column('INTERNALPRIORITY','INTERNALPRIORITY')
                    ->column('CREATEDBY','CREATEDBY')
                    ->column('OWNERGROUP','OWNERGROUP')
                    ->column('INCEXCLUIR','INCEXCLUIR')
                    ->column('INCMEXCLUSION','INCMEXCLUSION')
                    ->column('PROVEEDORES','PROVEEDORES')
                    ->column('DESCRIPTION','DESCRIPTION')
                    ->column('RUTA_TKT','RUTA_TKT')
                    ->column('REGIONAL','REGIONAL')
                    ->column('TIEMPO_VIDA_TKT','TIEMPO_VIDA_TKT')
                    ->column('TIEMPO_RESOLUCION_TKT','TIEMPO_RESOLUCION_TKT')
                    ->column('TIEMPO_DETECCION','TIEMPO_DETECCION')
                    ->column('TIEMPO_ESCALA','TIEMPO_ESCALA')
                    ->column('TIEMPO_FALLA','TIEMPO_FALLA')
                    ->column('TIEMPO_OT_ALM','TIEMPO_OT_ALM');
                    $modal_table ->style(array(
                        'class' => 'table table-striped',
                    ))
                    ->set_options('scrollX', 'true');
        $this->datatables->create('modal_table', $modal_table); 
        $this->load->view('Front_Office_Movil/loadModal');

    }

}
/* End of file Bitacoras.php */
?>