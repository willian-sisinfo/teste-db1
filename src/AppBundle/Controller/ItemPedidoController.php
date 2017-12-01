<?php

namespace AppBundle\Controller;

use AppBundle\Form\ItemPedidoType;
use AppBundle\Helpers\PersistenceUnity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ItemPedido;

class ItemPedidoController extends Controller
{
    /**
     * @Route("/pedido/gerenciar/{pedido}", name="gerenciar_pedido")
     * @Method({"GET"})
     */
    public function indexAction($pedido, Request $request)
    {
        if(isset($pedido)) {
            $pu = new PersistenceUnity('AppBundle:Pedido', $this);
            $pedidoObject = $pu->findBy(array('numero' => $pedido));

            if($pedidoObject->getStatus() != 0) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('warn', 'Pedido ' .$pedido. ' já está fechado, impossível adicionar itens!');

                return new RedirectResponse($this->generateUrl('pedido_index'));
            }

            return $this->render('pedido/carrinho.html.twig', array(
                'pedido' => $pedidoObject
            ));
        }

        $request->getSession()
            ->getFlashBag()
            ->add('error', 'Erro ao gerenciar pedido!');
        return new RedirectResponse($this->generateUrl('pedido_index'));

    }

    /**
     * @Route("/pedido/gerenciar/adicionar/{pedido}", name="form_add_item_pedido")
     * @Method({"GET"})
     */
    public function novoClienteAction($pedido, Request $request)
    {
        if(isset($pedido)) {
            $puPedido = new PersistenceUnity('AppBundle:Pedido', $this);
            $pedidoObject = $puPedido->findBy(array('numero' => $pedido));

            if($pedidoObject->getStatus() != 0) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Pedido ' .$pedido. ' já está fechado, impossível adicionar itens!');

                return new RedirectResponse($this->generateUrl('pedido_index'));
            }

            $item = new ItemPedido();
            $form = $this->createCreateForm($item);

            return $this->render('pedido/add-item.html.twig', array(
                'form' => $form->createView(),
                'numero' => $pedido
            ));
        }
        return new RedirectResponse($this->generateUrl('pedido_index'));
    }

    /**
     * @Route("/pedido/gerenciar/inserir-item", name="inserir_item")
     * @Method({"POST", "GET"})
     */
    public function inserirItemPedidoAction(Request $request) {
        if($request->isMethod('GET')) {
            return new RedirectResponse($this->generateUrl('gerenciar_pedido', array('pedido' => $request->get('pedido'))));
        }
        $item = new ItemPedido();
        $form = $this->createCreateForm($item);
        $form->handleRequest($request);


        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $puPedido = new PersistenceUnity('AppBundle:Pedido', $this);
            $pedido = $puPedido->findBy(array('numero' => $request->get('pedido')));

            $item->setPedido($pedido);
            $item->setPrecoUnitario($item->getProduto()->getPrecoUnitario());

            $subtotal = $item->getPrecoUnitario() * $item->getQuantidade();
            if($item->getPercentualDesconto() > 0) {
                $subtotal = $subtotal - (($subtotal*$item->getPercentualDesconto())/100);
            }

            $item->setTotal($subtotal);
            $pedido->getItens()->add($item);
            $pedido->setTotal($pedido->getTotal() + $item->getTotal());

            $em->persist($item);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Item inserido com sucesso!');

            return new RedirectResponse($this->generateUrl('gerenciar_pedido', array('pedido' => $pedido->getNumero())));
        }
        return $this->render('pedido/add-item.html.twig', array(
            'form' => $form->createView(),
            'numero' => $request->get('pedido')
        ));
    }

    /**
     * @Route("/pedido/excluir-item", name="excluir_item_pedido")
     * @Method({"POST", "GET"})
     */
    public function excluirItemPedidoAction(Request $request) {
        if($request->isMethod('GET')) {
            return new RedirectResponse($this->generateUrl('pedido_index'));
        }
        $id = $request->get('objectId');
        if(isset($id)) {
            $pu = new PersistenceUnity('AppBundle:ItemPedido', $this);
            $item = $pu->findModelById($id);
            if($item != null) {
                $pedido = $item->getPedido();
                $pedido->setTotal($pedido->getTotal() - $item->getTotal());

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $pu->deleteModel($item);
            }
            throw $this->createNotFoundException('Item não encontrado');
        }
        return new RedirectResponse($this->generateUrl('cliente_index'));

    }

    private function createCreateForm(ItemPedido $entity) {
        return $this->createForm(new ItemPedidoType(), $entity, array(
            'action' => $this->generateUrl('inserir_item'),
            'method' => 'POST'
        ));
    }

}
