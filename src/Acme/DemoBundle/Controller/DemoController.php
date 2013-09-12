<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Acme\DemoBundle\Form\ContactType;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\DemoBundle\Entity\Product;
use Acme\DemoBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class DemoController extends Controller
{
    /**
     * @Route("/", name="_demo")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/hello/{name}", name="_demo_hello")
     * @Template()
     */
    public function helloAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/contact", name="_demo_contact")
     * @Template()
     */
    public function contactAction()
    {
        $form = $this->get('form.factory')->create(new ContactType());

        $request = $this->get('request');
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $mailer = $this->get('mailer');
                // .. setup a message and send it
                // http://symfony.com/doc/current/cookbook/email.html

                $this->get('session')->getFlashBag()->set('notice', 'Message sent!');

                return new RedirectResponse($this->generateUrl('_demo'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/product", name="_create_product")
     * @Template()
     */
    public function createAction()
    {
        $product = new Product();
        $product->setName('A Foo Bar');
        $product->setPrice('19.99');
        $product->setDescription('Lorem ipsum dolor');

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response('Created product id '.$product->getId());
    }
    
    /**
     * @Route("/product/{id}", name="_find_product")
     * @Template()
     */
    public function showAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository('AcmeDemoBundle:Product')
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Product id '.$product->getId().' and his category is: '.$product->getCategory()->getName());
    }
    
    /**
     * @Route("/category", name="_create_category")
     * @Template()
     */
    public function createCategoryProductAction()
    {
        $category = new Category();
        $category->setName('Main Products');

        $product = new Product();
        $product->setName('Foo');
        $product->setPrice(19.99);
        $product->setDescription("descripcion");
        // relate this product to the category
        $product->setCategory($category);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($product);
        $em->flush();

        return new Response(
            'Created product id: '.$product->getId().' and category id: '.$category->getId()
        );
    }
    
    /**
     * @Route("/products/{category}", name="_list_products")
     * @Template()
     */
    public function showProductsAction($category)
    {
        $category = $this->getDoctrine()
            ->getRepository('AcmeDemoBundle:Category')
            ->find($category);

        $products = $category->getProducts();
        $response = 'List of products<br>';
        foreach ($products as $key => $value) {
         $response .= $value->getName().'<br>';
        }
        return new Response(
            $response
        );
    }
}
