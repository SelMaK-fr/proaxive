<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Menu;

class Menu
{
    protected $items = [];

    public function addItem($label, $url = '', $subItems = [])
    {
        $item = ['label' => $label, 'url' => $url, 'subItems' => []];

        // Ajouter les sous-menus s'ils existent
        foreach ($subItems as $subLabel => $subUrl) {
            $item['subItems'][] = ['label' => $subLabel, 'url' => $subUrl];
        }

        $this->items[] = $item;
    }

    public function getItems()
    {
        return $this->items;
    }

}