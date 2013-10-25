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
        $_form->handleRequest($request);
        if ($_form->isValid()) {
            /**             * *GUARDAR** */
            $this->get('session')->getFlashBag()->add(
                    'planilla', 'Ocurrio un error al grabar la planilla'
            );
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
        $service = $this->get('tomos_service');
        $tomo = $service->findNoDigitado($pk);
        $response = new Response(json_encode($tomo));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
}