<?php

namespace common\models\Helper;

class Helper
{
    /**
     * @param string $path
     * @param array|null $row
     * @param array $rows
     */
    public static function createFileCsv(string $path, array $row = null, array $rows) {
        $file = fopen($path, 'w');
        if($row!==null) {
            foreach($row as $k=>$f) {
                $row[$k] = self::toCharset($f);
            }
            fputcsv($file, $row, ";");
        }
        foreach ($rows as $k=>$r) {
            foreach($r as $kk=>$rr) {
                $r[$kk] = self::toCharset($rr);
            }
            fputcsv($file, $r, ";");   /* записываем строку в csv-файл */
        }
        fclose($file);
        if(!file_exists($path)) {
            throw new \DomainException('File not found');
        }
    }

    /**
     * @param $ii
     * @param string $from
     * @param string $to
     * @return bool|false|string
     */
    public static function toCharset($ii,$from='utf-8',$to='windows-1251'){
        return iconv($from,$to,$ii);
    }
}