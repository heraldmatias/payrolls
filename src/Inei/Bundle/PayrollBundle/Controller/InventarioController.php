<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inei\Bundle\PayrollBundle\Entity\Tomos;
use Inei\Bundle\PayrollBundle\Entity\Folios;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;

/**
 * Description of InventarioController
 *
 * @author holivares
 */
class InventarioController extends Controller {

    /**
     * @Route("/", name="_inventario_list")
     * @Template("")
     */
    public function tomosAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('tomo', 'query')) {
            throw $this->createNotFoundException();
        }
        $_periodos = $this->getDoctrine()->getManager()->createQuery(
                        'SELECT distinct t.periodoTomo FROM IneiPayrollBundle:Tomos t'
                )->getResult();
        $periodos = array();
        foreach ($_periodos as $value) {
            $periodos[$value['periodoTomo']] = $value['periodoTomo'];
        }
        $form = $this->createForm('search_tomos', null, array(
            'method' => 'GET',
            'periodos' => $periodos
        ));
        $form->handleRequest($request);
        $criteria = $form->getData() ? $form->getData() : array();
        foreach ($criteria as $key => $value) {
            if (!$value) {
                unset($criteria[$key]);
            }
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Tomos');
        $query = $em->findBy($criteria);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
        );


        return array(
            'pagination' => $pagination,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/tomos/add", name="_inventario_tomo_add")
     * @Template("")
     */
    public function addTomosAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('tomo', 'add')) {
            throw $this->createNotFoundException();
        }
        $object = new Tomos();
        $form = $this->createForm('tomos', $object);
        $form->handleRequest($request);
        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            try {
                $em = $this->getDoctrine()->getManager();
                $object->setCreador($this->get('security.context')->getToken()->getUser());
                $object->setFecCreac(new \DateTime());
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'tomo', 'Registro grabado satisfactoriamente'
                );
            } catch (DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'tomo', 'Ocurrio un error al grabar el registro'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_inventario_tomo_add' : '_inventario_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{pk}", name="_inventario_tomo_edit")
     * @Template("")
     */
    public function editTomosAction(Request $request, $pk) {
        if (!$this->get('usuario_service')->hasPermission('tomo', 'edit')) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Tomos');
        $object = $em->find(is_numeric($pk)?$pk:-1);
        if(!$object){
            throw $this->createNotFoundException();
        }
        $form = $this->createForm('tomos', $object);
        $form->handleRequest($request);
        /* VERFICAR SI EL FORMULARIO ES VALIDO */
        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $object->setModificador($this->get('security.context')->getToken()->getUser());
                $object->setFecMod(new \DateTime());
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'tomo', 'Registro modificado satisfactoriamente'
                );
            } catch (DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'tomo', 'Ocurrio un error al modificar el registro'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_inventario_tomo_add' : '_inventario_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/folios/", name="_inventario_folio_list")
     * @Template("")
     */
    public function foliosAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('folio', 'query')) {
            throw $this->createNotFoundException();
        }
        $_periodos = $this->getDoctrine()->getManager()->createQuery(
                        'SELECT distinct f.periodoFolio FROM IneiPayrollBundle:Folios f'
                )->getResult();
        $periodos = array();
        foreach ($_periodos as $value) {
            $periodos[$value['periodoFolio']] = $value['periodoFolio'];
        }
        $form = $this->createForm('search_folios', null, array(
            'method' => 'GET',
            'periodos' => $periodos
        ));
        $form->handleRequest($request);
        $criteria = $form->getData() ? $form->getData() : array();
        foreach ($criteria as $key => $value) {
            if (!$value) {
                unset($criteria[$key]);
            }
        }
        $fem = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Folios');
//        $query = $fem->findBy($criteria, array(
//            'tomo' => 'asc',
//            'codiFolio' => 'asc'
//        ));
        $query = $fem->findCustomBy($criteria);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        return array(
            'pagination' => $pagination,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/folios/add/", name="_inventario_folio_add")
     * @Template("")
     */
    public function addFoliosAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('folio', 'add')) {
            throw $this->createNotFoundException();
        }
        $object = new Folios();
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Tomos');
        $tomo = $request->get('tomo') ? $request->get('tomo') : 0;
        $_tomo = $em->find($tomo);
        $object->setTomo($_tomo);
        $folioupdate = $request->request->get('folioupdate');
        $form = $this->createForm('folios', $object, array('em' => $this->getDoctrine()->getManager()));
        $form->handleRequest($request);
        /******SI EXISTE EL FOLIO Y ES DIFERENTE ENTONCES HAY QUE REEMPLAZAR******/
        if($request->getMethod()==='POST'){
            $service = $this->get('folios_service');
            $service->orderFolios($object->getFolio(), $folioupdate, $object->getTomo()->getCodiTomo());
        }
        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            try {
                $em = $this->getDoctrine()->getManager();
                $conceptos = $object->getConceptos()->toArray();
                $object->getConceptos()->clear();
                $_pks = array_unique(array_map(
                                create_function('$concept', 'return $concept->getcodiConcTco();'), $conceptos
                ));
                $object->setCreador($this->get('security.context')->getToken()->getUser());
                $object->setFecCreac(new \DateTime());
                $em->persist($object);
                $em->flush();
                foreach ($_pks as $pk) {
                    foreach ($conceptos as $concepto) {
                        if ($concepto->getCodiConcTco() === $pk) {
                            $concepto->setCodiFolio($object);
                            $em->persist($concepto);
                            break;
                        }
                    }
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'folio', 'Registro grabado satisfactoriamente'
                );
            } catch (DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'folio', 'Ocurrio un error al grabar el registro'
                );
            }
            $tomo = $object->getTomo()->getCodiTomo();
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_inventario_folio_add' : '_inventario_folio_list';
            $parameters = $form->get('saveAndAdd')->isClicked() ? array('tomo' => $tomo) : array();
            return $this->redirect($this->generateUrl($nextAction, $parameters));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/folios/edit/{pk}/", name="_inventario_folio_edit")
     * @Template("")
     */
    public function editFoliosAction(Request $request, $pk) {
        if (!$this->get('usuario_service')->hasPermission('folio', 'edit')) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Folios');
        $object = $em->findOneCustomBy($pk);
        $folioupdate = $request->request->get('folioupdate');
        $form = $this->createForm('folios', $object, array('em' => $this->getDoctrine()->getManager()));
        $form->handleRequest($request);
        $service = $this->get('folios_service');
        /******SI EXISTE EL FOLIO Y ES DIFERENTE ENTONCES HAY QUE REEMPLAZAR******/
//        if($request->getMethod()==='POST'){
//        }
        /* VERFICAR SI EL FORMULARIO ES VALIDO */
        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $object->setModificador($this->get('security.context')->getToken()->getUser());
                $object->setFecMod(new \DateTime());
                $em->persist($object);
                $em->flush();
                if ($object->getRmconceptos()) {
                    foreach ($object->getRmconceptos() as $conc) {
                        //$em->remove($conc);
                        $service->deleteConcepto($conc);
                    }
                    $em->flush();
                }
                $service->orderFolios($object->getFolio(), $folioupdate,
                        $object->getTomo()->getCodiTomo());
                //$service->updateMatrix($pk);
                $this->get('session')->getFlashBag()->add(
                        'folio', 'Registro modificado satisfactoriamente'
                );
            } catch (DBALException $e) {
                echo $e->getMessage();exit;
                $this->get('session')->getFlashBag()->add(
                        'folio', 'Ocurrio un error al modificar el registro'
                );
            }
            $tomo = $object->getTomo()->getCodiTomo();
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_inventario_folio_add' : '_inventario_folio_list';
            $parameters = $form->get('saveAndAdd')->isClicked() ? array() : array('tomo' => $tomo);
            return $this->redirect($this->generateUrl($nextAction, $parameters));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/folios/ajax/", name="_inventario_folio_ajax")
     * @Template("")
     */
    public function foliosAjaxAction(Request $request) {
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Tomos');
        $tomo = $request->query->get('tomo');
        $folios = array();
        if (is_numeric($tomo)) {
            $_tomo = $em->find($tomo);
            if (null === $_tomo) {
                //NADA
            } else {
                foreach (range(1, $_tomo->getFoliosTomo()) as $value) {
                    $folios[$value] = $value;
                }
            }
        }
        $response = new Response(json_encode($folios));
        $response->headers->set('content-type', 'application/json');
        return $response;
    }

    /**
     * @Route("/delete/{pk}", name="_inventario_tomo_delete")
     * @Template("")
     */
    public function deleteAction(Request $request, $pk) {
        if (!$this->get('usuario_service')->hasPermission('tomo', 'del')) {
            throw $this->createNotFoundException();
        }
        try {
            $object = $this->getDoctrine()->getRepository('IneiPayrollBundle:Tomos')->find($pk);
            $conn = $this->get('database_connection');
            $st = $conn->prepare('DELETE FROM asignacion where co_tomo=' . $pk);
            $st->execute();
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                    'tomo', 'Registro eliminado satisfactoriamente'
            );
        } catch (DBALException $e) {
            $this->get('session')->getFlashBag()->add(
                    'tomo', 'Ocurrio un error al eliminar el registro'
            );
        }
        $nextAction = '_inventario_list';
        return $this->redirect($this->generateUrl($nextAction));
    }
    
    /**
     * @Route("/folio/delete/{pk}", name="_inventario_folio_delete")
     * @Template("")
     */
    public function deleteFolioAction(Request $request, $pk) {
        if (!$this->get('usuario_service')->hasPermission('folio', 'del')) {
            throw $this->createNotFoundException();
        }
        
//            $object = $this->getDoctrine()->getRepository(
//                    'IneiPayrollBundle:Folios')->find($pk);
        $result = $this->get('folios_service')->deleteFolio($pk);
        if($result){
            $this->get('session')->getFlashBag()->add(
                    'folio', 'Registro eliminado satisfactoriamente'
            );
        }else{
            $this->get('session')->getFlashBag()->add(
                    'folio', 'Ocurrio un error al eliminar el registro'
            );
        }
        $nextAction = '_inventario_folio_list';
        return $this->redirect($this->generateUrl($nextAction));
    }
    
     /**
     * @Route("/tomos/valida/{tomo}", name="_inventario_tomo_validate")
     * @Template("")
     */
    public function validateAction(Request $request) {
        $service_xls = $this->get('excel_service');
        $service = $this->get('tomos_service');
        $data = $service->validateTomo();
        $cols = array(
            '', ''
        );
        $title = 'Reporte de Conceptos Repetidos';
        $excel = $service_xls->printReporte($data, $cols, $title, 4);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="conceptos.xlsx"');
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    
    /**
     * @Route("/tomos/descarga/{pk}", name="_inventario_tomo_download")
     * @Template("")
     */
    public function descargaTomoAction(Request $request, $pk) {
        $service = $this->get('tomos_service');
        $title = 'Tomo '. $pk;
        $excel = $service->getReporte($pk, $title, 7);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="Tomo '.$pk.'.xlsx"');
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    
    /**
     * @Route("/folios/periodos/", name="_inventario_folio_periodo")
     * @Template("")
     */
    public function periodosFoliosAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('periodo_folio', 'query')) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm('periodo_folios', null, array('em' => $this->getDoctrine()->getManager()));
        $form->handleRequest($request);
        return array(
            'form' => $form->createView()
        );
    }
    
    /**
     * @Route("/folios/tomo/ajax/", name="_inventario_tomo_folio_ajax")
     * @Template("")
     */
    public function folioAjaxAction(Request $request) {
        $tomo = $request->request->get('tomo');
        $folio = $request->request->get('folio');
        $data = $this->getDoctrine()->getRepository('IneiPayrollBundle:Folios')
                ->findByNum($tomo, $folio);
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/folios/tomo/save/ajax/", name="_inventario_tomo_folio_save_ajax")
     * @Template("")
     */
    public function folioSaveAjaxAction(Request $request) {
        $form = $request->request->get('periodo_folios');
        $service = $this->get('folios_service');
        $success = $service->updatePeriodoFolio($form);
        $data = array(
            'success' => $success,
            'error' => !$success,
            'data' => ''
        );
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

}