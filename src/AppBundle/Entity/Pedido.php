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
 * @ORM\Table(name="pedidos")
 * @ORM\Entity
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
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $numero;

    /** @ORM\ManyToOne(targetEntity="Pessoa")
     * @Assert\NotNull(message = "Selecione o cliente")
     */
    private $cliente;

    /**
     * @ORM\Column(type="datetime")
     */
    private $emissao;

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



}