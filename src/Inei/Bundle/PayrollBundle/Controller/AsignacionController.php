<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AsignacionController
 *
 * @author holivares
 */
class AsignacionController extends Controller {

    /**
     * @Route("/new", name="_asignacion_tomo")
     * @Template("")
     */
    public function asignacionAction(Request $request) {
        $_form = $this->createForm('asignacion');
        $asignacion = $request->request->get('asignacion');
        $service = $this->get('asignacion_service');
        if ($asignacion) {
            if (array_key_exists('tomos', $asignacion) & 
                    array_key_exists('asignado', $asignacion)) {
                $usuario = $asignacion['asignado'];
                $tomos = $asignacion['tomos'];
                $result = $service->asignarTomos($usuario, $tomos);
                if ($result) {
                    $this->get('session')->getFlashBag()->add(
                            'asignacion', 'Registro grabado con éxito'
                    );
                } else {
                    $this->get('session')->getFlashBag()->add(
                            'asignacion', 'Ocurrio un error al grabar el registro'
                    );
                }
            }
        }
        return array(
            'form' => $_form->createView()
        );
    }

    /**
     * @Route("/tomo/ajax/", name="_asignacion_tomo_ajax")
     * @Template("")
     */
    public function tomoAction(Request $request) {
        $pk = $request->request->get('tomo');
        $usuario = $request->request->get('usuario');
        $service = $this->get('tomos_service');
        $tomo = $service->findNoDigitado($pk);
        if($tomo){
            $this->get('asignacion_service')
                    ->asignarTomos($usuario, $pk);
        }
        $response = new Response(json_encode($tomo));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/tomo-quitar/ajax/", name="_asignacion_tomo_quitar_ajax")
     * @Template("")
     */
    public function tomoQuitarAction(Request $request) {
        $pk = $request->request->get('tomo');
        $usuario = $request->request->get('usuario');
        $result = $this->get('asignacion_service')
                    ->desasignarTomos($usuario, $pk);        
        $response = new Response(json_encode(array(
            'success' => $result
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/tomos/ajax/", name="_asignacion_tomos_ajax")
     * @Template("")
     */
    public function tomosAction(Request $request) {
        $pk = $request->request->get('usuario');
        $service = $this->get('tomos_service');
        $tomos = $service->findTomosAsignados($pk);
        $response = new Response(json_encode($tomos));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}