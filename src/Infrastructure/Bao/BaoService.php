<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Bao;

use stdClass;

final class BaoService
{
    /**
     * @param $file_bao
     * @return false|string
     */
    public function view($file_bao){
        return $this->open_bao_file($file_bao);
    }

    /**
     * @param $file_bao
     * @param $patern
     * @return array|mixed|string|string[]
     */
    public function parse_unique($file_bao, $patern){

        $pax = $this->open_bao_file($file_bao);
        preg_match('`\['.$patern.'](.+)\[/'.$patern.']`', $pax, $value);
        $replace_value = str_replace(' : ', '', $value);
        return $replace_value[1];
    }

    /**
     * @param string $file_bao
     * @param bool|null $toArray
     * @return stdClass|array
     */
    public function parser_array(string $file_bao, ?bool $toArray = false){
        $pax = $this->open_bao_file($file_bao);
        preg_match('`\[INSTALL_DATE](.+)\[/INSTALL_DATE]`', $pax, $r_install_date);
        preg_match('`\[PC_NAME](.+)\[/PC_NAME]`', $pax, $r_pc_name);
        preg_match('`\[BIOS](.+)\[/BIOS]`', $pax, $r_bios);
        preg_match('`\[MODEL](.+)\[/MODEL]`', $pax, $r_manufacturer);
        preg_match('`\[RAM](.+)\[/RAM]`', $pax, $r_ram);
        preg_match('`\[CPU](.+)\[/CPU]`', $pax, $r_cpu);
        preg_match('`\[SOCKET](.+)\[/SOCKET]`', $pax, $r_socket);
        preg_match('`\[OS](.+)\[/OS]`', $pax, $r_os);
        preg_match('`\[MB](.+)\[/MB]`', $pax, $r_mb);
        preg_match('`\[GC](.+)\[/GC]`', $pax, $r_cg);
        preg_match('`\[SN](.+)\[/SN]`', $pax, $r_sn);
        preg_match('/\[HDD0\](.*?)\[\/HDD0\]/ism', $pax, $r_hdd0);
        preg_match('/\[HDD1\](.*?)\[\/HDD1\]/ism', $pax, $r_hdd1);
        preg_match('/\[HDD2\](.*?)\[\/HDD2\]/ism', $pax, $r_hdd2);
        preg_match('/\[HDD3\](.*?)\[\/HDD3\]/ism', $pax, $r_hdd3);
        if(empty($r_hdd1)){
            $r_hdd1 = '##';
            $r_hdd2 = '##';
            $r_hdd3 = '##';
        } elseif(empty($r_hdd2)){
            $r_hdd2 = '##';
            $r_hdd3 = '##';
        } elseif(empty($r_hdd3)){
            $r_hdd3 = '##';
        }
        $array = [
            "c_install_date" => str_replace(' : ', '', $r_install_date[1]),
            "name" => str_replace(' : ', '', $r_pc_name[1]),
            "c_bios" => str_replace(' : ', '', $r_bios[1]),
            "e_model" => str_replace(' : ', '', $r_manufacturer[1]),
            "c_memory" => str_replace(' : ', '', $r_ram[1]),
            "c_processor" => str_replace(' : ', '', $r_cpu[1]),
            "os_name" => str_replace(' : ', '', $r_os[1]),
            "c_socket" => str_replace(' : ', '', $r_socket[1]),
            "c_motherboard" => str_replace(' : ', '', $r_mb[1]),
            "c_gpu" => str_replace(' : ', '', $r_cg[1]),
            "e_serial" => str_replace(' : ', '', $r_sn[1]),
            "c_hdd0" => preg_replace('~[[:cntrl:]]~', '', $r_hdd0[1]),
            "c_hdd1" => preg_replace('~[[:cntrl:]]~', '', $r_hdd1[1]),
            "c_hdd2" => preg_replace('~[[:cntrl:]]~', '', $r_hdd2[1]),
            "c_hdd3" => preg_replace('~[[:cntrl:]]~', '', $r_hdd3[1])

        ];
        if($toArray === false){
            $object = new stdClass();
            foreach ($array as $key => $value)
            {
                $object->$key = $value;
            }
        } else {
            $object = [];
            foreach ($array as $key => $value)
            {
                $object[$key] = $value;
            }
        }

        return $object;

    }

    /**
     * @param int $id
     * @param string $filename
     * @param string $error
     * @return false|string
     */
    public function checkPath(int $id, string $filename, string $error){
        $path = dirname(__DIR__).'/' . 'documents/bao/equipments/' . $id. '/' . $filename;
        if(file_exists($path)){
            return $path;
        } else {
            return false;
        }
    }

    /**
     * @param string $file_bao
     * @return false|string
     */
    private function open_bao_file(string $file_bao)
    {
        $open_file = $file_bao;
        $file = fopen($open_file, 'r');
        $contents = fread($file, filesize($open_file));
        fclose($file);
        return $contents;
    }

    /**
     * @param $url
     * @return void
     */
    protected function redirect($url){
        header("Location: $url");
        exit();
    }
}