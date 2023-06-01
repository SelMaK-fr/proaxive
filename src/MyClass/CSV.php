<?php
namespace src\MyClass;
use PDO;
use src\Database\MysqlDatabase;

class CSV{

    /**
     * Génère le fichier CSV
     * @param $statement
     * @param $filename
     */
    public function export($statement, $filename){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
        $fp = fopen('php://output', 'w');
        foreach($statement as $v){
            fputcsv($fp, $v);
        }
        fclose($fp);
    }

    /**
     * Génère le fichier CSV
     * @param $statement
     * @param $filename
     */
    public function export_thead($statement, $filename){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
        $i = 0;
        foreach($statement as $v){
            $rules = '/(^-)|(-$)|(")/';
            $val = preg_replace($rules, '%', $v);
           if($i == 0){
               echo '"' .implode('";"', array_keys($val)).'"'."\n";
           }
            echo '"' .implode('";"', $val).'"'."\n";
           $i++;
        }
    }
}