<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Document;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\FileType;
use Palmtree\Form\Type\RadioType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class DocumentCreateType extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'document',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('name', TextType::class, [
                'label' => "Nom du document",
                'placeholder' => "Entrez un nom pour ce document"
            ])
            ->add('description', TextType::class, [
                'label' => "Description",
                'required' => false,
                'placeholder' => "Petite description"
            ])
            ->add('is_online', CheckboxType::class, [
                'required' => false,
                'label' => 'Mode public'
            ])
            ->add('customer_id', 'choice', [
                'placeholder' => false,
                'label' => 'Clients',
                'choices' => self::getCustomer()
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
            ])
        ;

        return $builder->getForm();
    }

    private function getCustomer(): array
    {
        $customers = [];
        $req = $this->query->from('customers')->select(null)->select('customers.id, customers.fullname')->fetchAll();
        foreach ($req as $c) {
            $e = $this->query->from('equipments')->where('customers_id = ?', $c['id'])->fetchAll();
            foreach ($e as $t) {
                if($t){
                    $customers[$c['id']] = $c['fullname'];
                }
            }
        }
        return $customers;
    }
}