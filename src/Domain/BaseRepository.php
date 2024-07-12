<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain;

use DateTime;
use DateTimeInterface;
use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Literal;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use Pagerfanta\Pagerfanta;
use Selmak\Proaxive2\Infrastructure\Paginator\PagerfantaQuery;

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
    public function all(bool $type = false, ?int $limit = null): Select
    {
        if($type){
            // For array
            if($limit != null){
                $make = $this->makeQueryDefault()->limit($limit);
            } else {
                $make = $this->makeQueryDefault();
            }
            return $make;
        } else {
            // For object
            if($limit != null){
                $make = $this->makeQueryObject()->limit($limit);
            } else {
                $make = $this->makeQueryObject();
            }
            return $make;
        }
    }

    public function allArray(bool $type = false): array
    {
        if($type){
            // For array
            return $this->makeQueryDefault()->fetchAll();
        } else {
            // For object
            return $this->makeQueryObject()->fetchAll();
        }
    }

    /**
     * Return all record for a key in an object
     * @throws Exception
     */
    public function allBy(string $key, int|string $value, int $limit = 16, bool $type = false): Select
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

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $type
     * @return Select
     * @throws Exception
     */
    public function findBy(string $key, mixed $value, bool $type = false)
    {
        if($type) {
            $statement = $this->makeQueryDefault()->where("$key = ?", [$value]);
        } else {
            $statement = $this->makeQueryObject()->where("$key = ?", [$value]);
        }
        return $statement;
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
    public function count(): int
    {
        return $this->makeQueryDefault()->count();
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
     * @param int $perPage
     * @return Pagerfanta
     * @throws Exception
     */
    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        $req = new PagerfantaQuery(
            $this->makeQueryDefault()
        );
        return (new Pagerfanta($req))
            ->setMaxPerPage((int)$perPage)
            ->setCurrentPage((int)$currentPage)
            ;
    }

    /**
     * @throws Exception
     */
    public function ifExist(string $key, string $value)
    {
        return $this->makeQueryDefault()->where("$key = ?", [$value])->count();
    }

    /**
     * Value [] or object + Automatic Date (created_at, updated_at) true ou false
     * @param array $values
     * @param bool|null $date
     * @return int|bool|string
     * @throws Exception
     */
    public function add(mixed $values, ?bool $date = null): int|bool|string
    {
        if(!is_array($values)){
            if($date) {
                $values->setCreatedAt(new Literal('NOW()'));
                $values->setUpdatedAt(new Literal('NOW()'));
            }
            $values = $values->__toArray();
        } else {
            if($date) {
                $values['created_at'] = new Literal('NOW()');
                $values['updated_at'] = new Literal('NOW()');
            }
        }
        return $this->query->insertInto($this->model)->values($values)->execute();
    }

    public function createOject(mixed $values): bool|int|string
    {

        $values->setCreatedAt(new Literal('NOW()'));
        $values->setUpdatedAt(new Literal('NOW()'));

        return $this->query->insertInto($this->model)->values(get_object_vars($values))->execute();
    }


    /**
     * @param array $data
     * @param int $id
     * @param bool $date
     * @return bool|int|\PDOStatement
     * @throws Exception
     */
    public function update(mixed $data, int $id, bool $date = true): bool|int|\PDOStatement
    {
        if(!is_array($data)){
            if($date) {
                $data->setUpdatedAt(new Literal('NOW()'));
            }
            $data = $data->__toArray();
        } else {
            if($date){
                $data['updated_at'] = new Literal('NOW()');
            }
        }
        $query = $this->query->update($this->model, $data, $id);
        return $query->execute();
    }

    /**
     * Permet de mettre à jour en spécifiant un where customisé
     * @param array $data
     * @param string $whereKey
     * @param string|int $whereValue
     * @param bool $date
     * @return bool|int|\PDOStatement
     * @throws Exception
     */
    public function updateBy(array $data, string $whereKey, string|int $whereValue, bool $date = true): bool|int|\PDOStatement
    {
        // update date auto
        if($date){
            $data['updated_at'] = new Literal('NOW()');
        }
        $query = $this->query->update($this->model)->set($data)->where($whereKey, $whereValue);
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

    /**
     * @return Query
     */
    protected function getQuery(): Query
    {
        return $this->query;
    }

    private function objectToArray(object $object): array
    {
        $result = [];
        foreach ($object as $key => $value)
        {
            $result[$key] = (is_array($value) || is_object($value)) ? objectToArray($value) : $value;
        }
        return $result;
    }
}