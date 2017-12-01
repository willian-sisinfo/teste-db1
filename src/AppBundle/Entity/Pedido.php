<?php
namespace AppBundle\Entity;

/**
 * Created by PhpStorm.
 * User: willian
 * Date: 29/11/17
 * Time: 09:28
 */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="pedidos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PedidoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Pedido
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /** @ORM\ManyToOne(targetEntity="Pessoa")
     * @Assert\NotNull(message = "Selecione o cliente")
     */
    private $cliente;

    /** @ORM\OneToMany(targetEntity="ItemPedido", mappedBy="pedido")
     */
    private $itens;

    /**
     * @ORM\Column(type="datetime")
     */
    private $emissao;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=false)
     */
    private $total;

    public function __construct()
    {
        $this->itens = new ArrayCollection();
        $this->status = 0;
    }

    /**
     * @ORM\PrePersist()
     */
    public function preUpdate() {
        $this->emissao= new \DateTime();
    }

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
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * @param mixed $cliente
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * @return mixed
     */
    public function getEmissao()
    {
        return $this->emissao;
    }

    /**
     * @param mixed $emissao
     */
    public function setEmissao($emissao)
    {
        $this->emissao = $emissao;
    }

    /**
     * @return mixed
     */
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * @param mixed $itens
     */
    public function setItens($itens)
    {
        $this->itens = $itens;
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

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


}