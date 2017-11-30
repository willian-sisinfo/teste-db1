<?php

namespace AppBundle\Controller;

use AppBundle\Helpers\PersistenceUnity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Pessoa;
use AppBundle\Form\PessoaType;

class PessoaController extends Controller
{
    /**
     * @Route("/cliente/index", name="cliente_index")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $pu = new PersistenceUnity('AppBundle:Pessoa', $this);
        $clientes = $pu->findAll();
        return $this->render('cliente/index.html.twig', array(
            'clientes' => $clientes,
        ));
    }

    /**
     * @Route("/cliente/novo", name="novo_cliente")
     * @Method({"GET"})
     */
    public function novoClienteAction(Request $request)
    {
        $cliente = new Pessoa();
        $form = $this->createCreateForm($cliente);

        return $this->render('cliente/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/cliente/inserir", name="inserir_cliente")
     * @Method({"POST", "GET"})
     */
    public function inserirClienteAction(Request $request) {
        if($request->isMethod('GET')) {
            return new RedirectResponse($this->generateUrl('novo_cliente'));
        }

        $cliente = new Pessoa();
        $form = $this->createCreateForm($cliente);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Cliente inserido com sucesso!');

            return new RedirectResponse($this->generateUrl('cliente_index'));
        }
        return $this->render('cliente/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/cliente/editar/{slug}", name="editar_cliente")
     * @Method({"GET"})
     */
    public function editarClienteAction($slug) {
        if(isset($slug)) {
            $pu = new PersistenceUnity('AppBundle:Pessoa', $this);
            $cliente = $pu->findBy(array('slug' => $slug));
            if($cliente != null) {
                $form = $this->createEditForm($cliente);
                return $this->render('cliente/form.html.twig', array('cliente' => $cliente, 'form' => $form->createView()));
            }
            throw $this->createNotFoundException('Pessoa não encontrado');
        }

        return new RedirectResponse($this->generateUrl('cliente_index'));
    }

    /**
     * @Route("/cliente/atualizar/{id}", name="atualizar_cliente")
     * @Method({"POST", "GET"})
     */
    public function atualizarClienteAction($id, Request $request) {
        if($request->isMethod('GET')) {
            return new RedirectResponse($this->generateUrl('cliente_index'));
        }

        if(isset($id)) {
            $em = $this->getDoctrine()->getManager();
            $cliente = $em->getRepository('AppBundle:Pessoa')->find($id);
            if($cliente != null) {
                $form= $this->createEditForm($cliente);
                $form->handleRequest($request);
                if($form->isValid()) {
                    $em->flush();
                    $request->getSession()
                        ->getFlashBag()
                        ->add('success', 'Pessoa atualizado com sucesso!');
                    return new RedirectResponse($this->generateUrl('cliente_index'));
                }
            }
            throw $this->createNotFoundException('Pessoa não encontrado');
        }
        return new RedirectResponse($this->generateUrl('cliente_index'));

    }

    /**
     * @Route("/cliente/excluir", name="excluir_cliente")
     * @Method({"POST", "GET"})
     */
    public function excluirClienteAction(Request $request) {
        if($request->isMethod('GET')) {
            return new RedirectResponse($this->generateUrl('cliente_index'));
        }
        $id = $request->get('objectId');
        if(isset($id)) {
            $pu = new PersistenceUnity('AppBundle:Pessoa', $this);
            $cliente = $pu->findModelById($id);
            if($cliente != null) {
                return $pu->deleteModel($cliente);
            }
            throw $this->createNotFoundException('Pessoa não encontrado');
        }
        return new RedirectResponse($this->generateUrl('cliente_index'));

    }

    private function createCreateForm(Pessoa $entity) {
        return $this->createForm(new PessoaType(), $entity, array(
            'action' => $this->generateUrl('inserir_cliente'),
            'method' => 'POST'
        ));
    }

    private function createEditForm(Pessoa $entity) {
        return $this->createForm(new PessoaType(),
            $entity,
            array('action' => $this->generateUrl('atualizar_cliente', array('id' => $entity->getId()))
            , 'method' => 'POST'));
    }

}
