<?php

namespace Inei\Bundle\ConsistenciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * 
     * @param type $name
     * @Route("/consistencia/", name="_consistencia_index")
     * @Template("")
     */
    public function indexAction()
    {
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
    public function consistenciaAction()
    {
        $service = $this->get('consistencia_service');
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
     * @Route("/consistencia/proceso/manual/", name="_consistencia_manual_procesar")
     * @Template("")
     */
    public function consistenciaManualAction(Request $request)
    {
        $service = $this->get('consistencia_service');
        $criteria = array();
        $tipo = 1;
        $form = array(
            'soundex' => '',
            'nombres' => '',
            'codigo' => ''
        );
        if($request->query->has('form')){
            $criteria = $request->query->get('form');            
            foreach ($criteria as $key => $value) {
                if (!$value) {
                    unset($criteria[$key]);
                }
            }
            $form = $request->query->get('form');
        }        
        if($request->query->has('tipo_busqueda')){
            $tipo = $request->query->get('tipo_busqueda');
        }
        switch ($tipo){
            case 1:
                $query = $service->findPersonalNoEncontrado($criteria);break;
            case 2:
                $query = $service->findPersonalEncontrado($criteria);break;
            case 3:
                $query = $service->findPersonal($criteria);break;
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
    public function iniciaConsistenciaAction(){
        $service = $this->get('consistencia_service');
        
        $result = $service->getPersonalDigitado();
        $data = array(
            'success' => $result !== -1,
            'data' => $result,
            'error' => $result === -1?
                'Ocurrio un error al consistenciar los empleados':''
        );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/consistencia/proceso/ajax/", name="_consistencia_proceso_ajax")     
     */
    public function procesaConsistenciaAction(){
        $service = $this->get('consistencia_service');
        
        $result = $service->processPersonalTodo();
        $data = array(
            'success' => $result !== -1,
            'data' => $result,
            'error' => $result === -1?
                'Ocurrio un error al procesar los empleados':''
        );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }
    
    
    /**
     * @Route("/consistencia/personal/ajax/", name="_maestro_personal_ajax")     
     */
    public function getPersonalSigaAction(Request $request){
        $service = $this->get('consistencia_service');
        $nombres = array();
        if($request->query->has('personal')){
            $nombres = $request->query->get('personal');
        }
        $result = $service->findPersonalSiga($nombres);
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
    public function personalAsociaAction(Request $request){
        $service = $this->get('consistencia_service');
        $nombres = array();
        $persona = NULL;
        if($request->query->has('personal')){
            $nombres = $request->query->get('personal');
        }
        if($request->query->has('persona')){
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
    public function registraPersonalAction(Request $request){
        $service = $this->get('consistencia_service');
        $result = false;
        if($request->query->has('form')){
            $form = $request->query->get('form');
            $result = $service->registraPersona($form);
        }
        $data = array(
            'success' => $result,
            'data' => $result,
            'error' => $result?'':'Ocurrio un error al registrar la persona'
        );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/consistencia/personal/print/", name="_personal_print")
     */
    public function imprimir(Request $request){
        $service = $this->get('consistencia_service');
        $criteria = array();
        if($request->query->has('form')){
            $criteria = $request->query->get('form');            
            foreach ($criteria as $key => $value) {
                if (!$value) {
                    unset($criteria[$key]);
                }
            }
        }
        $tipo = 1;
        if($request->query->has('tipo_busqueda')){
            $tipo = $request->query->get('tipo_busqueda');
        }
        switch ($tipo){
            case 1:
                $query = $service->findPersonalNoEncontrado($criteria);break;
            case 2:
                $query = $service->findPersonalEncontrado($criteria);break;
            case 3:
                $query = $service->findPersonal($criteria);break;
            default :
                $query = $service->findPersonalNoEncontrado($criteria);
        }
        $data = $query->getArrayResult();//print_r($data);exit;
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s.csv"', 'personal'));
        $response->prepare($request);
        $response->sendHeaders();
        echo $service->printCSV($data);
        exit;
    }
}
