<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FrontFunctionTwig extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getDataWithLink', [$this, 'getDataWithLink'], ['is_safe' => ['html']]),
            new TwigFunction('getData', [$this, 'getData'], ['is_safe' => ['html']]),
            new TwigFunction('getRoles', [$this, 'getRoles'], ['is_safe' => ['html']]),
            new TwigFunction('getDataState', [$this, 'getDataState'], ['is_safe' => ['html']]),
            new TwigFunction('getDataStatus', [$this, 'getDataStatus'], ['is_safe' => ['html']]),
            new TwigFunction('getDataPriority', [$this, 'getDataPriority'], ['is_safe' => ['html']]),
            new TwigFunction('getDataWaySteps', [$this, 'getDataWaySteps']),
            new TwigFunction('getDataWayStepsNext', [$this, 'getDataWayStepsNext']),
        ];
    }

    public function getDataWithLink($data, $link): string
    {
        if($data == null){
            return '<a href="'.$link.'" class="btn-sm d-block btn-light-four">Ajouter</a>';
        } else {
            return $data;
        }
    }

    public function getData(string $title, $data): string
    {
        if($data){
            return '<span class="d-block">'.$title.' : '.$data.'</span>';
        }
        return '';
    }

    public function getDataState($data): string
    {
        if($data == 'DRAFT'){
            return '<span class="label-mid badge-light-yellow">BROUILLON</span>';
        } elseif($data == 'VALIDATED'){
            return '<span class="label-mid btn-light-info">VALIDÉE</span>';
        } elseif ($data == 'COMPLETED'){
            return '<span class="label-mid badge-light-green"><i class="ri-check-line"></i> COMPLÈTE</span>';
        }
        return '<span class="label-mid badge-light-pink">NC</span>';
    }

    public function getDataStatus(string $name, string $color): string
    {
        return '<span class="label-mid" style="background-color:#'.$color.';color:#ffffff;">'.$name.'</span>';
    }

    public function getRoles($var): string
    {
        if($var == 'SUPER_ADMIN'){
            return '<span class="label-mid badge-light-pink text-uppercase">Administrateur</span>';
        } elseif($var == 'USER_TECH') {
            return '<span class="label-mid badge-light-green text-uppercase">Technicien</span>';
        } elseif ($var == 'USER_MANAGER'){
            return '<span class="label-mid badge-light-yellow text-uppercase">Manager</span>';
        }
        return $var;
    }

    public function getDataPriority($data, ?string $text = null): string
    {
        $html = '';
        if($data == 'LOW') {
            $html = '<span class="label btn-light-info">'.$text.' Basse</span>';
        } elseif ($data == 'AVERAGE') {
            $html = '<span class="label btn-light-primary">'.$text.' Moyenne</span>';
        } elseif ($data == 'URGENT') {
            $html = '<span class="label btn-light-yellow">'.$text.' Urgent</span>';
        } elseif ($data == 'HIGH') {
            $html = '<span class="label btn-light-pink">'.$text.' Élevée</span>';
        } elseif ($data == 'ABSOLUTE') {
            $html = '<span class="label btn-light-pink">'.$text.' Absolue</span>';
        }
        return $html;
    }

    public function getDataWaySteps($data): string
    {
        $v = '';
        if($data == 1){
            $v = 'Matériel récupéré';
        } elseif($data == 2){
            $v = 'En atelier';
        } elseif($data == 3){
            $v = 'Tests finaux';
        } elseif ($data == 4){
            $v = 'En cours de sortie';
        } elseif ($data == 5){
            $v = 'Terminé';
        }
        return $v;
    }

    public function getDataWayStepsNext($data): string
    {
        $v = '';
        if($data == 1){
            $v = 'En atelier';
        } elseif($data == 2){
            $v = 'Tests finaux';
        } elseif($data == 3){
            $v = 'En cours de sortie';
        } elseif ($data == 4){
            $v = 'Terminé';
        }
        return $v;
    }
}