<?php
declare(strict_types=1);
namespace App;

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

    /**
     * Return all record for a key in an object
     * @throws Exception
     */
    public function allBy(string $key, int $value, bool $type = false): Select
    {
        if($type){
            // For array
            return $this->makeQueryDefault()->where("$key = ?", [$value]);
        } else {
            // For object
            return $this->makeQueryObject()->where("$key = ?", [$value]);
        }
    }

    /**
     * Return one record for a key in an object
     * @throws Exception
     */
    public function find(string $key, int $value, bool $type = false)
    {
        if($type){
            // For array
            return $this->makeQueryDefault()->where("$key = ?", [$value])->fetch();
        } else {
            // For object
            return $this->makeQueryObject()->where("$key = ?", [$value])->fetch();
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
     * @param int $id
     * @return bool|int|\PDOStatement
     * @throws Exception
     */
    public function update(array $data, int $id): bool|int|\PDOStatement
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