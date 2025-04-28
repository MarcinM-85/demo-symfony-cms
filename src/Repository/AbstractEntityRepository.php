<?php
// src/Repository/AbstractEntityRepository.php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class AbstractEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * Elastyczne pobieranie danych z dowolnymi warunkami i JOINami
     *
     * @param array $params [
     *   'select' => 'e', // lub np. 'partial e.{id, name}', domyślnie: alias
     *   'join' => [['type' => 'left', 'target' => 'e.author', 'alias' => 'a']],
     *   'where' => [['field' => 'e.cat', 'operator' => '=', 'value' => 1]],
     *   'order' => ['e.id' => 'DESC'],
     *   'limit' => 10,
     *   'offset' => 0,
     *   'paginate' => true, // domyślnie: true
     * ]
     * @param string $alias
     * @return array ['items' => [...], 'total' => int]
     */
    public function getList(array $params = [], string $alias = 'e'): array
    {
        $select = $params['sql']['select'] ?? $alias;
        $joins = [];
        $whereClauses = [];
        $orderBy = [];
        $parameters = [];

        // JOIN
        foreach ($params['sql']['join'] ?? [] as $join) {
            $type = strtoupper($join['type'] ?? 'LEFT');
            $target = $join['target'] ?? '';
            $joinAlias = $join['alias'] ?? '';
            $with = $join['with'] ?? '';

            if ($target && $joinAlias) {
                $joinLine = $type.' JOIN '.$target.' '.$joinAlias;
                if ($with) {
                    $joinLine .= ' WITH '.$with;
                }
                $joins[] = $joinLine;
            }
        }

        // WHERE
        foreach ($params['sql']['where'] ?? [] as $i => $cond) {
            $field = $cond['field'] ?? '';
            $operator = strtoupper($cond['operator'] ?? '=');
            $paramName = 'param_' . $i;

            if (in_array($operator, ['IS NULL', 'IS NOT NULL'])) {
                $whereClauses[] = "$field $operator";
            } elseif ($operator === 'IN') {
                $whereClauses[] = "$field IN (:$paramName)";
                $parameters[$paramName] = $cond['value'];
            } else {
                $whereClauses[] = "$field $operator :$paramName";
                $parameters[$paramName] = $cond['value'];
            }
        }

        // ORDER
        foreach ($params['sql']['order'] ?? [] as $field => $dir) {
            $orderBy[] = "$field " . strtoupper($dir);
        }

        // Składanie DQL
        $dql = "SELECT $select FROM " . $this->getEntityName() . " $alias";
        if ($joins) {
            $dql .= ' ' . implode(' ', $joins);
        }
        if ($whereClauses) {
            $dql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }
        if ($orderBy) {
            $dql .= ' ORDER BY ' . implode(', ', $orderBy);
        }

        // Tworzenie zapytania
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters($parameters);

        // Paginator
        $paginate = $params['paginate'] ?? true;

        if ($paginate) {
            $limit = isset($params['page_limit']) ? (int)$params['page_limit'] : 20;
            $page = (int)($params['page'] ?? 1);
            $offset = ($page - 1) * $limit;

            $query->setFirstResult($offset)->setMaxResults($limit);

            $paginator = new Paginator($query);
            return [
                'items' => iterator_to_array($paginator, false),
                'total' => count($paginator),
            ];
        }

        return [
            'items' => $query->getResult(),
            'total' => null,
        ];
    }
}