<?php

namespace AppBundle\Controller;

use AppBundle\Transients\ActionError;
use AppBundle\Helpers\PersistenceUnity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Produto;
use AppBundle\Form\ProdutoType;

class ProdutoController extends Controller
{
    /**
     * @Route("/produto/index", name="produto_index")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $pu = new PersistenceUnity('AppBundle:Produto', $this);
        $produtos = $pu->findAll();
        return $this->render('produto/index.html.twig', array(
            'produtos' => $produtos,
        ));
    }

    /**
     * @Route("/produto/novo", name="novo_produto")
     * @Method({"GET"})
     */
    public function novoProdutoAction(Request $request)
    {
        $produto = new Produto();
        $form = $this->createCreateForm($produto);

        return $this->render('produto/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/produto/inserir", name="inserir_produto")
     * @Method({"POST", "GET"})
     */
    public function inserirProdutoAction(Request $request) {
        if($request->isMethod('GET')) {
            return new RedirectResponse($this->generateUrl('novo_produto'));
        }

        $produto = new Produto();
        $form = $this->createCreateForm($produto);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produto);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Produto inserido com sucesso!');

            return new RedirectResponse($this->generateUrl('produto_index'));
        }
        return $this->render('produto/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/produto/editar/{slug}", name="editar_produto")
     * @Method({"GET"})
     */
    public function editarProdutoAction($slug) {
        if(isset($slug)) {
            $pu = new PersistenceUnity('AppBundle:Produto', $this);
            $produto = $pu->findBy(array('slug' => $slug));
            if($produto != null) {
                $form = $this->createEditForm($produto);
                return $this->render('produto/form.html.twig', array('produto' => $produto, 'form' => $form->createView()));
            }
            throw $this->createNotFoundException('Produto não encontrado');
        }

        return new RedirectResponse($this->generateUrl('produto_index'));
    }

    /**
     * @Route("/produto/atualizar/{id}", name="atualizar_produto")
     * @Method({"POST", "GET"})
     */
    public function atualizarProdutoAction($id, Request $request) {
        if($request->isMethod('GET')) {
            return new RedirectResponse($this->generateUrl('produto_index'));
        }

        if(isset($id)) {
            $em = $this->getDoctrine()->getManager();
            $produto = $em->getRepository('AppBundle:Produto')->find($id);
            if($produto != null) {
                $form= $this->createEditForm($produto);
                $form->handleRequest($request);
                if($form->isValid()) {
                    $em->flush();
                    $request->getSession()
                        ->getFlashBag()
                        ->add('success', 'Produto atualizado com sucesso!');
                    return new RedirectResponse($this->generateUrl('produto_index'));
                }
            }
            throw $this->createNotFoundException('Produto não encontrado');
        }
        return new RedirectResponse($this->generateUrl('produto_index'));

    }

    /**
     * @Route("/produto/excluir", name="excluir_produto")
     * @Method({"POST", "GET"})
     */
    public function excluirProdutoAction(Request $request) {
        if($request->isMethod('GET')) {
            return new RedirectResponse($this->generateUrl('produto_index'));
        }
        $id = $request->get('objectId');
        if(isset($id)) {
            $pu = new PersistenceUnity('AppBundle:Produto', $this);
            $produto = $pu->findModelById($id);

            if($produto != null) {
                $puItem = new PersistenceUnity('AppBundle:ItemPedido', $this);
                $item = $puItem->findBy(array('produto' => $produto));
                if($item != null) {
                    return new JsonResponse(new ActionError('Não é possível excluir esse produto pois ele pertence a um pedido'));
                }

                return $pu->deleteModel($produto);
            }
            throw $this->createNotFoundException('Produto não encontrado');
        }
        return new RedirectResponse($this->generateUrl('produto_index'));

    }

    private function createCreateForm(Produto $entity) {
        return $this->createForm(new ProdutoType(), $entity, array(
            'action' => $this->generateUrl('inserir_produto'),
            'method' => 'POST'
        ));
    }

    private function createEditForm(Produto $entity) {
        return $this->createForm(new ProdutoType(),
            $entity,
            array('action' => $this->generateUrl('atualizar_produto', array('id' => $entity->getId()))
            , 'method' => 'POST'));
    }

}
