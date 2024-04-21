<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Security;

class SerialNumberFormatterService
{
    private \stdClass $params;

    public function __construct(\stdClass $settings)
    {
        $this->params = $settings;
    }

    public function getPlaceholders(string $format)
    {
        $regex = "/{{([A-Z_]{1,})(?::)?([a-zA-Z0-9_]{1,6}|.{1})?}}/";
        preg_match_all($regex, $format, $placeholders);
        return $placeholders;
    }

    /**
     * @throws \Exception
     */
    public function generateSerialNumber(): string
    {
        $number = '';
        if(!$this->params->generate_number_alpha){
            $a = 6;
        } else {
            $a = (int)$this->params->generate_number_alpha;
        }
        $alpha = substr(bin2hex(random_bytes($a)), 0, $a);
        if(!$this->params->date_for_number){
            $d = 'Y';
        } else {
            $d = (string) $this->params->date_for_number;
        }
        $number .= date($d) . '-' . $alpha;

        return $number;
    }

}