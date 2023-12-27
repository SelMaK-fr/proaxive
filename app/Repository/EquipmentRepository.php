<?php
declare(strict_types=1);
namespace App\Repository;

use App\BaseRepository;

class EquipmentRepository extends BaseRepository
{

    protected string $model = 'equipments';

    public function search(string $key): \Envms\FluentPDO\Queries\Select
    {
        return $this->makeQueryDefault()
            ->where('name LIKE ? OR e_serial LIKE ? OR customer_name LIKE ?', ["%".$key."%", "%".$key."%", "%".$key."%"])->orderBy('created_at DESC');

    }

    public function findWithTypeBool(int $id): mixed
    {
        return $this->makeQueryObject()
            ->select('equipments.*, is_peripheral, te.name te_name')
            ->leftJoin('types_equipments as te ON te.id = equipments.types_equipments_id')
            ->where('equipments.id = ?', [$id])
            ->fetch()
            ;
    }

    public function findWithBrand(int $id)
    {
        return $this->makeQueryObject()
            ->select(null)
            ->select('equipments.*, brands.logo_file e_logo')
            ->leftJoin('brands as b ON b.id = equipments.brands_id')
            ->where('equipments.id = ?', [$id])
            ->fetch()
            ;
    }
}