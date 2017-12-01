<?php

namespace AppBundle\Controller;

use AppBundle\Helpers\PersistenceUnity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Pedido;

use AppBundle\Form\PedidoType;

class PedidoController extends Controller
{

    /**
     * @Route("/pedido/index", name="pedido_index")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $pu = new PersistenceUnity('AppBundle:Pedido', $this);
        $pedidos = $pu->findAll();
        return $this->render('pedido/index.html.twig', array(
            'pedidos' => $pedidos,
        ));
    }

    /**
     * @Route("/pedido/novo", name="novo_pedido")
     * @Method({"GET"})
     */
    public function novoPedidoAction(Request $request)
    {
        $pedido = new Pedido();
        $form = $this->createCreateForm($pedido);

        return $this->render('pedido/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/pedido/criar", name="criar_pedido")
     * @Method({"POST", "GET"})
     */
    public function criarPedidoAction(Request $request) {

        $pedido = new Pedido();
        $form = $this->createCreateForm($pedido);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $result = $em->getRepository('AppBundle:Pedido')
                ->getLastOrderNumber();
            if($result != null) {
                $pedido->setNumero($result + 1);
            } else {
                $pedido->setNumero(1);
            }
            $pedido->setTotal(0);
            $em->persist($pedido);
            $em->flush();

            return new RedirectResponse($this->generateUrl('gerenciar_pedido', array('pedido' => $pedido->getNumero())));
        }
        return $this->render('pedido/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/pedido/excluir", name="excluir_pedido")
     * @Method({"POST", "GET"})
     */
    public function excluirPedidoAction(Request $request) {
        if($request->isMethod('GET')) {
            return new RedirectResponse($this->generateUrl('pedido_index'));
        }
        $id = $request->get('objectId');
        if(isset($id)) {
            $pu = new PersistenceUnity('AppBundle:Pedido', $this);
            $pedido = $pu->findModelById($id);
            if($pedido != null) {
                return $pu->deleteModel($pedido);
            }
            throw $this->createNotFoundException('Pedido não encontrado');
        }
        return new RedirectResponse($this->generateUrl('pedido_index'));

    }


    /**
     * @Route("/pedido/visualizar/{pedido}", name="visualizar_pedido")
     * @Method({"POST", "GET"})
     */
    public function visualizarPedidoAction($pedido, Request $request) {
        if(isset($pedido)) {
            $pu = new PersistenceUnity('AppBundle:Pedido', $this);
            $pedidoObject = $pu->findBy(array('numero' => $pedido));
            return $this->render('pedido/visualizar-pedido.html.twig', array(
                'pedido' => $pedidoObject
            ));
        }
    }


    /**
     * @Route("/pedido/fechar/{pedido}", name="fechar_pedido")
     * @Method({"POST", "GET"})
     */
    public function fecharPedidoAction($pedido, Request $request) {

        if(isset($pedido)) {
            $em = $this->getDoctrine()->getManager();
            $pedidoObject =  $em->getRepository('AppBundle:Pedido')->find($pedido);
            if($pedidoObject != null) {
                $pedidoObject->setStatus(1);
                $em->flush();

                $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Pedido fechado com sucesso!');

                return new RedirectResponse($this->generateUrl('pedido_index'));

            }
            throw $this->createNotFoundException('Pedido não encontrado');
        }
        return new RedirectResponse($this->generateUrl('pedido_index'));

    }

    private function createCreateForm(Pedido $entity) {
        return $this->createForm(new PedidoType(), $entity, array(
            'action' => $this->generateUrl('criar_pedido'),
            'method' => 'POST'
        ));
    }

    private function createEditForm(Pedido $entity) {
        return $this->createForm(new PedidoType(),
            $entity,
            array('action' => $this->generateUrl('atualizar_pedido', array('id' => $entity->getId()))
            , 'method' => 'POST'));
    }

}
