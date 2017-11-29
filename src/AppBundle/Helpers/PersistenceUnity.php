<?php

/*
 * Classe que geralmente uso nos meus projetos internos.
 * Como costumo a trabalhar sempre com Ajax no frontend e uso o symfony como API REST
 * Essa classe é apenas um facilitador para funções de CRUD com retorno JSON
 */

namespace AppBundle\Helpers;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Transients\ActionError;

class PersistenceUnity extends Controller{

    private $entity;
    private $controller;

    public function __construct($entity, $controller) {
        $this->entity = $entity;
        $this->controller = $controller;
    }

    public function persistModel($model) {
        $validator = $this->controller->get('validator');
        $errors = $validator->validate($model);

        if (count($errors) > 0) {
            $action = new \stdClass();
            $action->status = 'ERRO';
            $errorsArray = array();
            foreach($errors as $error) {
                $throwable = new \stdClass();
                $throwable->field = $error->getPropertyPath();
                $throwable->message = $error->getMessage();
                $errorsArray[] = $throwable;
            }
            $action->erros = $errorsArray;
            return new JsonResponse($action);
        } else {
            try {
                $entityManager = $this->controller->getDoctrine()->getManager();
                $entityManager->persist($model);
                $entityManager->flush();

                $action = new \stdClass();
                $action->status = 'OK';
                $action->message = 'Informações salvas com sucesso!';
                return new JsonResponse($action);

            } catch(UniqueConstraintViolationException $e) {
                $action = new ActionError('Já existe um registro com os parâmetros informados. Verifique.');
                return new JsonResponse($action);
            } catch(Exception $err) {
                $action = new ActionError('Ocorreu um erro ao salvar o registro, contate o suporte.');
                return new JsonResponse($action);
            }
        }
    }

    public function mergeModel($model) {
        $validator = $this->controller->get('validator');
        $errors = $validator->validate($model);

        if (count($errors) > 0) {
            $action = new \stdClass();
            $action->status = 'ERRO';
            $errorsArray = array();
            foreach($errors as $error) {
                $throwable = new \stdClass();
                $throwable->field = $error->getPropertyPath();
                $throwable->message = $error->getMessage();
                $errorsArray[] = $throwable;
            }
            $action->erros = $errorsArray;
            return new JsonResponse($action);
        } else {
            try {
                $entityManager = $this->controller->getDoctrine()->getManager();
                $entityManager->merge($model);
                $entityManager->flush();

                $action = new \stdClass();
                $action->status = 'OK';
                $action->message = 'Informações salvas com sucesso!';
                return new JsonResponse($action);

            } catch(UniqueConstraintViolationException $e) {
                $action = new ActionError('Já existe um registro com os parâmetros informados. Verifique.');
                return new JsonResponse($action);
            } catch(Exception $err) {
                $action = new ActionError('Ocorreu um erro ao salvar o registro, contate o suporte.');
                return new JsonResponse($action);
            }
        }
    }
    
    public function persistAndReturn($model) {
        $validator = $this->controller->get('validator');
        $errors = $validator->validate($model);

        if (count($errors) > 0) {
            return null;
        } else {
            try {
                $entityManager = $this->controller->getDoctrine()->getManager();
                $entityManager->persist($model);
                $entityManager->flush();

                return $model;

            } catch(UniqueConstraintViolationException $e) {
                return null;
            } catch(Exception $err) {
                return null;
            }
        }
    }

    public function deleteModel($model) {
        $validator = $this->controller->get('validator');
        $errors = $validator->validate($model);

        if (count($errors) > 0) {
            $action = new \stdClass();
            $action->status = 'ERRO';
            $errorsArray = array();
            foreach($errors as $error) {
                $throwable = new \stdClass();
                $throwable->field = $error->getPropertyPath();
                $throwable->message = $error->getMessage();
                $errorsArray[] = $throwable;
            }
            $action->erros = $errorsArray;
            return new JsonResponse($action);
        } else {
            try {
                $entityManager = $this->controller->getDoctrine()->getManager();
                $entityManager->remove($model);
                $entityManager->flush();

                $action = new \stdClass();
                $action->status = 'OK';
                $action->message = 'Registro removido com sucesso!';
                return new JsonResponse($action);

            } catch(Exception $err) {
                $action = new ActionError('Ocorreu um erro ao remover o registro, contate o suporte.');
                return new JsonResponse($action);
            }
        }
    }

    public function deleteArray($models) {
        $validator = $this->controller->get('validator');
        $entityManager = $this->controller->getDoctrine()->getManager();

        foreach ($models as $model) {
            $errors = $validator->validate($models);
            if (count($errors) > 0) {
                $action = new \stdClass();
                $action->status = 'ERRO';
                $errorsArray = array();
                foreach($errors as $error) {
                    $throwable = new \stdClass();
                    $throwable->field = $error->getPropertyPath();
                    $throwable->message = $error->getMessage();
                    $errorsArray[] = $throwable;
                }
                $action->erros = $errorsArray;
                return new JsonResponse($action);
            } else {
                try {
                    $entityManager->remove($model);
                } catch (Exception $err) {
                    $action = new ActionError('Ocorreu um erro ao remover o registro, contate o suporte.');
                    return new JsonResponse($action);
                }
            }
        }

        try {
            $entityManager->flush();
            $entityManager->getUnitOfWork()->clear();
            $action = new \stdClass();
            $action->status = 'OK';
            $action->message = 'Registro removido com sucesso!';
            return new JsonResponse($action);

        } catch(Exception $err) {
            $action = new ActionError('Ocorreu um erro ao remover o registro, contate o suporte.');
            return new JsonResponse($action);
        }

    }
    
    public function deactiveModel($model) {
        try {
            $entityManager = $this->controller->getDoctrine()->getManager();
            $entityManager->persist($model);
            $entityManager->flush();

            $action = new \stdClass();
            $action->status = 'OK';
            $action->message = 'Registro removido com sucesso!';
            return new JsonResponse($action);
        } catch(Exception $err) {
            $action = new ActionError('Ocorreu um erro ao remover o registro, contate o suporte.');
            return new JsonResponse($action);
        }
    }

    public function findAll() {
        return $this->controller->getDoctrine()
            ->getRepository($this->entity)
            ->findAll();
    }

    public function findAllAsJson() {
        return $this->controller->getDoctrine()
            ->getRepository($this->entity)
            ->createQueryBuilder('q')
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllOrderedBy($fieldsToOrder) {
        return $this->controller->getDoctrine()
            ->getRepository($this->entity)
            ->findBy(array(), $fieldsToOrder);
    }

    public function findBy($fieldsToSearch) {
        return $this->controller->getDoctrine()
            ->getRepository($this->entity)
            ->findOneBy($fieldsToSearch);
    }

    public function listBy($fieldsToSearch) {
        return $this->controller->getDoctrine()
            ->getRepository($this->entity)
            ->findBy($fieldsToSearch);
    }

    public function findModelById($id) {
        return $this->controller->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);
    }
}