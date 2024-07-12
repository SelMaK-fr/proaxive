<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Workshop;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Selmak\Proaxive2\Http\Type\Type;

final class WorkshopDeleteType extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder('workshop_delete', $data))
            ->add('company_id', ChoiceType::class, [
                'label' => 'Magasin',
                'placeholder' => false,
                'choices' => self::getCompany($args['id']),
                'required' => true
            ]);
        return $builder->getForm();
    }

    private function getCompany(int $id): array
    {
        $companies = [];
        $req = $this->query->from('company')
            ->select(null)
            ->select('company.id, company.name, COUNT(u.id) as countUsers')
            ->leftJoin('users as u ON u.company_id = company.id')
            ->where('company.id != ?', [$id])
            ->groupBy('company.id, company.name')
            ->fetchAll();
        foreach ($req as $c) {
            if($c['countUsers'] > 0) {
                $companies[$c['id']] = $c['name'] . ' (' . $c['countUsers'] . ' user(s))';
            }
        }
        return $companies;
    }
}