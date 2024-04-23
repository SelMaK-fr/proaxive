<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain;

use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Literal;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;

class BaseRepository
{

    protected string $model;

    private string $field = '';

    private string $fieldVal = '';

    private array $params = [];

    public function __construct(private readonly Query $query){}

    /**
     * Return all record in an object or in array
     * false = object
     * true = array
     * false by default
     * @throws Exception
     */
    public function all(bool $type = false): Select
    {
        if($type){
            // For array
            return $this->makeQueryDefault();
        } else {
            // For object
            return $this->makeQueryObject();
        }
    }

    public function allArray(): array
    {
        return $this->makeQueryDefault()->fetchAll();
    }

    /**
     * Return all record for a key in an object
     * @throws Exception
     */
    public function allBy(string $key, int $value, int $limit = 16, bool $type = false): Select
    {
        if($type){
            // For array
            $query = $this->makeQueryDefault()->where("$key = ?", [$value]);
            if($limit) {
                $query->limit($limit);
            }
        } else {
            // For object
            $query = $this->makeQueryObject()->where("$key = ?", [$value]);
            if($limit) {
                $query->limit($limit);
            }
        }
        return $query;
    }

    /**
     * Return one record for a key in an object
     * @throws Exception
     */
    public function find(string $key, mixed $value, bool $type = false)
    {
        if($type){
            // For array
            try {
                return $this->makeQueryDefault()->where("$key = ?", [$value])->fetch();
            } catch (\Exception){
                return new Exception('Erreur de la base de données !');
            }

        } else {
            // For object
            try {
                return $this->makeQueryObject()->where("$key = ?", [$value])->fetch();
            } catch (\Exception){
                return new Exception('Erreur de la base de données !');
            }
        }
    }

    /**
     * @throws Exception
     */
    public function findByString(string $key, string $id, bool $type = false)
    {
        if($type){
            return $this->makeQueryDefault()->where("$key = ?", [$id])->fetch();
        } else {
            return $this->makeQueryObject()->where("$key = ?", [$id])->fetch();
        }
    }

    public function extract($key, $value, ?array $params = []): array
    {
        if($params && array_key_exists('byField', $params)
            && array_key_exists('fieldVal', $params)){
            $record = $this->all()->where($params['byField'].' = ?', [$params['fieldVal']])->orderBy($value);
        } else {
            $record = $this->all()->orderBy($value);
        }
        $return = [];
        foreach ($record as $v) {
            $return[$v->$key] = $v->$value;
        }
        return $return;
    }

    public function lastInsertId(): bool|string
    {
        return $this->query->getPdo()->lastInsertId();
    }

    public function countRowWhere(int $id, string $where): int
    {
        $query = $this->makeQueryDefault()->select('id')
            ->where($where .' = '. $id);
        return count($query);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $query = $this->makeQueryDefault()->count();
        return $query;
    }

    /**
     * @param mixed $limit
     * @return array|bool
     * @throws Exception
     */
    public function allArrayForPaginator(mixed $limit)
    {
        return $this->makeQueryDefault()->limit($limit)->orderBy('created_at DESC')->fetchAll();
    }

    /**
     * @throws Exception
     */
    public function ifExist(string $key, string $value)
    {
        return $this->makeQueryDefault()->where("$key = ?", [$value])->count();
    }

    /**
     * Value [] + Automatic Date (created_at, updated_at) true ou false
     * @param array $values
     * @param bool|null $date
     * @return int|bool|string
     * @throws Exception
     */
    public function add(array $values, ?bool $date = null): int|bool|string
    {
        if($date) {
            $values['created_at'] = new Literal('NOW()');
            $values['updated_at'] = new Literal('NOW()');
        }
        return $this->query->insertInto($this->model)->values($values)->execute();
    }


    /**
     * @param array $data
     * @param int|null $id
     * @return bool|int|\PDOStatement
     * @throws Exception
     */
    public function update(array $data, ?int $id): bool|int|\PDOStatement
    {
        $query = $this->query->update($this->model, $data, $id);
        return $query->execute();
    }

    /**
     * @param int $id
     * @return bool|int|\PDOStatement
     * @throws Exception
     */
    public function delete(int $id): bool|int|\PDOStatement
    {
        $query = $this->query->deleteFrom($this->model, $id);
        return $query->execute();
    }

    /**
     * @throws Exception
     */
    public function deleteWithoutId(): bool|int|\PDOStatement
    {
        $query = $this->query->deleteFrom($this->model);
        return $query->execute();
    }

    /**
     * Pagination for API
     * @param int $page
     * @param int $perPage
     * @param array $params
     * @param int $total
     * @return array
     */
    public function getResultsWithPagination(
        int $page,
        int $perPage,
        array $params,
        int $total
    ): array {
        return [
          'pagination' => [
              'totalRows' => $total,
              'totalPages' => ceil($total / $perPage),
              'currentPage' => $page,
              'perPage' => $perPage
          ],
          'data' => $this->getResultByPage($page, $perPage, $params),
        ];
    }

    protected function getResultByPage(
        int $page,
        int $perPage,
        ?array $params
    ): array|bool
    {
        $offset = ($page - 1) * $perPage;
        //$query .= " LIMIT {$perPage} OFFSET {$offset}";
        return $this->query->from($this->model)->limit($perPage)->offset($offset)->fetchAll();
    }

    /**
     * Return an array
     * @throws Exception
     */
    protected function makeQueryDefault(): Select
    {
        return $this->query->from($this->model);
    }

    /**
     * Return an object
     * @throws Exception
     */
    protected function makeQueryObject(): Select
    {
        return $this->query->from($this->model)->asObject();
    }
}