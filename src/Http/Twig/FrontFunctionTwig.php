<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Twig;

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
            new TwigFunction('getDataWayStepsStatus', [$this, 'getDataWayStepsStatus'], ['is_safe' => ['html']]),
        ];
    }

    protected function spanBlock($data, $title, $tag): string
    {
        $surround = '<span class="d-block">'.$title;
        if($tag){
            $surround .= "<$tag>" . $data . "</$tag>";
        }
        $surround .= '</span>';
        return $surround;
    }

    /**
     * @param $data
     * @param $link
     * @return string
     */
    public function getDataWithLink($data, $link): string
    {
        if($data == null){
            return '<a href="'.$link.'" class="btn-sm d-block btn-light-four">Ajouter</a>';
        } else {
            return $data;
        }
    }

    /**
     * @param string $title
     * @param string|array|null $data
     * @return string
     */
    public function getData(string $title, string|array|null $data, ?string $tag = 'span'): string
    {
        $v = [];
        if($data != null){
            if(is_array($data)){
                foreach ($data as $value){
                    if($value){
                        $v[] = $value;
                    }
                }
                if($v){
                    $html = '<div><span class="fw-600 d-inline-block mr-1">'.$title.' :</span> <'.$tag.'>'.implode(' - ', $v).'</'.$tag.'></div>';
                } else {
                    $html = '';
                }
            } elseif(is_string($data)) {
                $html = '<div><span class="fw-600 d-inline-block mr-1">'.$title.' :</span><'.$tag.'>'.$data.'</'.$tag.'></div>';
            } else {
                $html = 'Data error';
            }
            return $html;
        }
        return '';
    }

    /**
     * @param $data
     * @return string
     */
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

    /**
     * @param string $name
     * @param string $color
     * @return string
     */
    public function getDataStatus(string $name, string $color): string
    {
        return '<span class="label-mid" style="background-color:#'.$color.';color:#ffffff;">'.$name.'</span>';
    }

    /**
     * @param $var
     * @return string
     */
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

    /**
     * @param $data
     * @param string|null $text
     * @return string
     */
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

    /**
     * @param $data
     * @return string
     */
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

    /**
     * @param $data
     * @return string
     */
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

    public function getDataWayStepsStatus($data): string
    {
        $v = '';
        if($data == 1){
            $v = '<span class="label-mid btn-light-info text-uppercase"><i class="ri-home-2-line"></i> Matériel récupéré</span>';
        } elseif($data == 2){
            $v = '<span class="label-mid btn-light-info text-uppercase"><i class="ri-tools-line"></i> En atelier</span>';
        } elseif($data == 3){
            $v = '<span class="label-mid btn-light-info text-uppercase"><i class="ri-list-check-2"></i> Tests finaux</span>';
        } elseif ($data == 4){
            $v = '<span class="label-mid btn-light-warning text-uppercase"><i class="ri-door-open-line"></i> En cours de sortie</span>';
        } elseif ($data == 5){
            $v = '<span class="label-mid badge-light-green text-uppercase"><i class="ri-check-line"></i> Terminé</span>';
        }
        return $v;
    }
}