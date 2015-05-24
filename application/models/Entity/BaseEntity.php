<?php

namespace Entity;

class BaseEntity {

    public function __construct() {}

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return BaseEntity
     */
    static public function findOneBy(array $criteria, array $orderBy = null) {
        return \Base_Controller::$em->getRepository(get_called_class())->findOneBy($criteria, $orderBy);
    }

    /**
     * @param      $id
     * @param int  $lockMode
     * @param null $lockVersion
     *
     * @return BaseEntity
     */
    static public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null) {
        return \Base_Controller::$em->getRepository(get_called_class())->find($id, $lockMode, $lockVersion);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return BaseEntity[]
     */
    static public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return \Base_Controller::$em->getRepository(get_called_class())->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @return BaseEntity[]
     */
    static public function findAll() {
        return \Base_Controller::$em->getRepository(get_called_class())->findAll();
    }

    /**
     * @return integer
     */
    static public function count() {
        $class = get_called_class();
        $query = \Base_Controller::$em->createQuery('SELECT COUNT(cls.id) FROM '.$class.' cls');
        $count = $query->getSingleScalarResult();
        return $count;
    }

    public function get_class_without_namespace() {
        $class = explode('\\', get_class($this));
        return end($class);
    }
}