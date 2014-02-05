<?php

namespace Inei\Bundle\ConsistenciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \PHPExcel_IOFactory;

class DefaultController extends Controller {

    /**
     * 
     * @param type $name
     * @Route("/consistencia/", name="_consistencia_index")
     * @Template("")
     */
    public function indexAction() {
        $service = $this->get('consistencia_service');

//        $query = $service->findPersonal();
//        $paginator = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//                $query, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
//        );
        $sincroniza = $service->debeSincronizar();
        return array(
            #'pagination' => $pagination,
            'sincroniza' => $sincroniza
        );
    }

    /**
     * 
     * @param type $name
     * @Route("/consistencia/proceso/", name="_consistencia_procesar")
     * @Template("")
     */
    public function consistenciaAction() {
//        $service = $this->get('consistencia_service');
//        $query = $service->findPersonal();
//        $paginator = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//                $query, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
//        );        
        return array(
                #'pagination' => $pagination            
        );
    }

    /**
     * 
     * @param type $name
     * @Route("/consistencia/proceso/periodo/", name="_consistencia_periodo")
     * @Template("")
     */
    public function consistenciaPeriodoAction() {
        $file = null;
        $target_path = __DIR__ . '/../../../../../web/upload/consistencia/';
        if (isset($_FILES['periodo'])) {
            if (!is_dir($target_path)) {
                //echo $this->getUploadRootDir();
                mkdir($target_path);
                chmod($target_path, 0755);
            }
            $fileName = basename($_FILES['periodo']['name']);
            $target_path .= $fileName;
            if (move_uploaded_file($_FILES['periodo']['tmp_name'], $target_path)) {
//                chmod($target_path .= $fileName, 0644);
            }
            $file = $fileName;
        } else {
            $gestor = @opendir($target_path);
            if ($gestor) {
                while (false !== ($entrada = readdir($gestor))) {
                    if ($entrada !== '.' & $entrada !== null)
                        $file = $entrada;
                    //break;
                }
                closedir($gestor);
            }
        }
        return array(
            'file' => $file
        );
    }

    /**
     * 
     * @param type $name
     * @Route("/consistencia/proceso/periodo/ajax/", name="_consistencia_periodo_ajax")
     * @Template("")
     */
    public function consistenciaPeriodoAjaxAction() {
        $data = array(
            'error' => null,
            'success' => true,
            'data' => 'Ok'
        );
        try {
            $file = null;
            $target_path = __DIR__ . '/../../../../../web/upload/consistencia/';
            $gestor = @opendir($target_path);
            if ($gestor) {
                while (false !== ($entrada = readdir($gestor))) {
                    if ($entrada !== '.' & $entrada !== null & $entrada !== '..')
                        $file = $target_path. $entrada;
                }
                closedir($gestor);
            }
            if ($file) {
                $objPHPExcel = PHPExcel_IOFactory::load($file);
                $sheet = $objPHPExcel->getSheet(0);
                $service = $this->get('consistencia_service');
                $periodo = null;
                $fila = 1;
                $sql = '';
                while (true) {
                    $periodo = $sheet->getCellByColumnAndRow(0, $fila)->getValue();
                    $nperiodo = $sheet->getCellByColumnAndRow(1, $fila)->getValue();
                    
                    if ($periodo === '' | $periodo === null) {
                        break;
                    }
                    if ($nperiodo !== '' | $nperiodo !== null) {
                        if(is_numeric($nperiodo)){
                            $nperiodo = strlen($nperiodo) === 1 ?
                                str_pad($nperiodo, 2, '0', STR_PAD_LEFT):$nperiodo;
                             $sql .= $service->actualizaPeriodoSQL($nperiodo, $periodo);
                        }
                    }
                    $fila++;
                }
                $service->actualizaPeriodo($sql);
            }
        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
            $data['success'] = false;
        }
        $response = new Response(json_encode($data));
        $response->headers->set('content-type', 'application/json');
        return $response;
    }

    /**
     * 
     * @param type $name
     * @Route("/consistencia/proceso/manual/", name="_consistencia_manual_procesar")
     * @Template("")
     */
    public function consistenciaManualAction(Request $request) {
        $service = $this->get('consistencia_service');
        $criteria = array();
        $tipo = 1;
        $form = array(
            'soundex' => '',
            'nombres' => '',
            'codigo' => ''
        );
        if ($request->query->has('form')) {
            $criteria = $request->query->get('form');
            foreach ($criteria as $key => $value) {
                if (!$value) {
                    unset($criteria[$key]);
                }
            }
            $form = $request->query->get('form');
        }
        if ($request->query->has('tipo_busqueda')) {
            $tipo = $request->query->get('tipo_busqueda');
        }
        switch ($tipo) {
            case 1:
                $query = $service->findPersonalNoEncontrado($criteria);
                break;
            case 2:
                $query = $service->findPersonalEncontrado($criteria);
                break;
            case 3:
                $query = $service->findPersonal($criteria);
                break;
            default :
                $query = $service->findPersonalNoEncontrado($criteria);
        }
        $form['tipo_busqueda'] = $tipo;
        //$familias = $service->findFamiliasNombres();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 25/* limit per page */
        );
        return array(
            'pagination' => $pagination,
            'form' => $form
                #'familias' => $familias
        );
    }

    /**
     * @Route("/consistencia/ajax/", name="_consistencia_personal_ajax")     
     */
    public function iniciaConsistenciaAction() {
        $service = $this->get('consistencia_service');

        $result = $service->getPersonalDigitado();
        $data = array(
            'success' => $result !== -1,
            'data' => $result,
            'error' => $result === -1 ?
                    'Ocurrio un error al consistenciar los empleados' : ''
        );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }

    /**
     * @Route("/consistencia/proceso/ajax/", name="_consistencia_proceso_ajax")     
     */
    public function procesaConsistenciaAction() {
        $service = $this->get('consistencia_service');

        $result = $service->processPersonalTodo();
        $data = array(
            'success' => $result !== -1,
            'data' => $result,
            'error' => $result === -1 ?
                    'Ocurrio un error al procesar los empleados' : ''
        );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }

    /**
     * @Route("/consistencia/personal/ajax/", name="_maestro_personal_ajax")     
     */
    public function getPersonalSigaAction(Request $request) {
        $service = $this->get('consistencia_service');
        $nombres = '';
        $tipo = $request->query->get('tipo');
        if ($request->query->has('personal')) {
            $nombres = $request->query->get('personal');
        }
        if($tipo === '1'){
            $result = $service->findPersonalSiga($nombres);
        }else{
            $cn = $this->get('doctrine')->getManager('siga')->getConnection();
            $st = $cn->prepare("SELECT DNI as value, DNI || ' - ' || DES_NOMBRE as label 
                FROM PER_RENIEC WHERE DES_NOMBRE LIKE :nombre AND rownum<=5");
            $st->bindValue(':nombre', $nombres.'%');
            $st->execute();
            $result = $st->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
        $data = array(
            'success' => true,
            'data' => $result,
            'error' => ''
        );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }

    /**
     * @Route("/consistencia/personal-asocia/ajax/", name="_personal_asocia_ajax")
     */
    public function personalAsociaAction(Request $request) {
        $service = $this->get('consistencia_service');
        $nombres = array();
        $persona = NULL;
        if ($request->query->has('personal')) {
            $nombres = $request->query->get('personal');
        }
        if ($request->query->has('persona')) {
            $persona = $request->query->get('persona');
        }
        $result = $service->asociarPersonal($nombres, $persona);
        $data = array(
            'success' => $result,
            'data' => $result,
            'error' => ''
        );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }

    /**
     * @Route("/consistencia/personal-registra/ajax/", name="_personal_registra_ajax")
     */
    public function registraPersonalAction(Request $request) {
        $service = $this->get('consistencia_service');
        $result = false;
        if ($request->query->has('form')) {
            $form = $request->query->get('form');
            $result = $service->registraPersona($form);
        }
        $data = array(
            'success' => $result,
            'data' => $result,
            'error' => $result ? '' : 'Ocurrio un error al registrar la persona'
        );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }

    /**
     * @Route("/consistencia/personal/print/", name="_personal_print")
     */
    public function imprimir(Request $request) {
        $service = $this->get('consistencia_service');
        $criteria = array();
        if ($request->query->has('form')) {
            $criteria = $request->query->get('form');
            foreach ($criteria as $key => $value) {
                if (!$value) {
                    unset($criteria[$key]);
                }
            }
        }
        $tipo = 1;
        if ($request->query->has('tipo_busqueda')) {
            $tipo = $request->query->get('tipo_busqueda');
        }
        switch ($tipo) {
            case 1:
                $query = $service->findPersonalNoEncontrado($criteria);
                break;
            case 2:
                $query = $service->findPersonalEncontrado($criteria);
                break;
            case 3:
                $query = $service->findPersonal($criteria);
                break;
            default :
                $query = $service->findPersonalNoEncontrado($criteria);
        }
        $data = $query->getArrayResult(); //print_r($data);exit;
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s.csv"', 'personal'));
        $response->prepare($request);
        $response->sendHeaders();
        echo $service->printCSV($data);
        exit;
    }

}
