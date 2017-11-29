<?php
namespace AppBundle\Entity;
/**
 * Created by PhpStorm.
 * User: willian
 * Date: 29/11/17
 * Time: 09:28
 */

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="produtos")
 * @ORM\Entity
 * @UniqueEntity("nome", message="Esse valor já está em uso por outro registro")
 * @UniqueEntity("codigo", message="Esse valor já está em uso por outro registro")
 */
class Produto
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string", unique=true, nullable=false)
     * @Assert\NotBlank(message = "Informe o código do produto")
     */
    private $codigo;

    /** @ORM\Column(type="string", unique=true, nullable=false)
     * @Assert\NotBlank(message = "Informe o nome do roduto")
     */
    private $nome;


    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=false)
     * @Assert\NotBlank(message = "Informe o preço unitário")
     * @Assert\GreaterThan(value = 0, message = "Preço unitário deve ser maior que zero")
     */
    private $precoUnitario;

    /**
     * @Gedmo\Slug(fields={"nome"}, updatable=true)
     * @ORM\Column(type="string", unique=true)
     */
    private $slug;

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
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}