<?php

namespace AppBundle\Entity;

/**
 * Created by PhpStorm.
 * User: willian
 * Date: 29/11/17
 * Time: 09:28
 */

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="itens_pedido")
 * @ORM\Entity
 */
class ItemPedido
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\ManyToOne(targetEntity="Produto") */
    private $produto;

    /** @ORM\ManyToOne(targetEntity="Pedido", inversedBy="itens")
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $pedido;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message = "Informe a quantidade")
     * @Assert\GreaterThan(value = 0, message = "Quantidade deve ser maior que zero")
     */
    private $quantidade;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=false)
     * @Assert\GreaterThan(value = 0)
     */
    private $precoUnitario;

    /**
     * @ORM\Column(type="float")
     */
    private $percentualDesconto;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=false)
     * @Assert\GreaterThan(value = 0, message = "Total deve ser maior que zero")
     */
    private $total;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * @param mixed $produto
     */
    public function setProduto($produto)
    {
        $this->produto = $produto;
    }

    /**
     * @return mixed
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * @param mixed $pedido
     */
    public function setPedido($pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * @return mixed
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param mixed $quantidade
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return mixed
     */
    public function getPrecoUnitario()
    {
        return $this->precoUnitario;
    }

    /**
     * @param mixed $precoUnitario
     */
    public function setPrecoUnitario($precoUnitario)
    {
        $this->precoUnitario = $precoUnitario;
    }

    /**
     * @return mixed
     */
    public function getPercentualDesconto()
    {
        return $this->percentualDesconto;
    }

    /**
     * @param mixed $percentualDesconto
     */
    public function setPercentualDesconto($percentualDesconto)
    {
        $this->percentualDesconto = $percentualDesconto;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

}