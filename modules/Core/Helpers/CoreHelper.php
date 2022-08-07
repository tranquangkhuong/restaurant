<?php

namespace Modules\Core\Helpers;

/**
 * Helper cơ bản
 *
 * created at 05/08/2022
 * @author khuongtq
 */
class CoreHelper
{
    /**
     * Tạo thư mục
     *
     * @param String $path Đường dẫn thư mục gốc (cấp 1)
     * @param String $folderYear Thư mục cấp 2 (năm)
     * @param String $folderMonth Thư mục cấp 3 (tháng)
     * @param String $folderDat Thư mục cấp 4 (ngày)
     * @return String $path Đường dẫn đầy đủ các thư mục mới tạo
     */
    public static function createFolder($path, $folderYear = '', $folderMonth = '', $folderDay = '')
    {
        $path = str_replace("/", "\\", $path);

        if (!file_exists($path)) mkdir($path, 0777);

        // Tạo năm
        if ($folderYear != '') {
            $path = $path . $folderYear;
            if (!file_exists($path)) mkdir($path, 0777);
        }

        // Tạo tháng trong năm
        if ($folderMonth != '') {
            $path = $path . chr(92) . $folderMonth;
            if (!file_exists($path)) mkdir($path, 0777);
        }

        //Tao ngày trong tháng
        if ($folderDay != '') {
            $path = $path . chr(92) . $folderMonth . chr(92) . $folderDay;
            if (!file_exists($path)) mkdir($path, 0777);
        }

        return str_replace("/", "\\", $path).chr(92);
    }

    /**
     * Bỏ ký tự đặc biệt và dấu cách
     *
     * @param String $string Chuỗi cần convert
     * @return String $string Chuỗi đã convert
     */
    public static function convertBadChar($string)
    {
        $string = htmlspecialchars($string);
        $string = str_replace(' ', '_', $string);

        return $string;
    }

    /**
     * Bỏ dấu ký tự VN
     *
     * @param String $string Chuỗi cần chuyển đổi
     * @return String $string Chuỗi đã chuyển đổi
     */
    public static function convertVNtoEN($string)
    {
        $vnChars = array("á", "à", "ả", "ã", "ạ", "ă", "ắ", "ằ", "ẳ", "ẵ", "ặ", "â", "ấ", "ầ", "ẩ", "ẫ", "ậ", "é", "è", "ẻ", "ẽ", "ẹ", "ê", "ế", "ề", "ể", "ễ", "ệ", "í", "ì", "ì", "ỉ", "ĩ", "ị", "ó", "ò", "ỏ", "õ", "ọ", "ô", "ố", "ồ", "ổ", "ỗ", "ộ", "ơ", "ớ", "ờ", "ở", "ỡ", "ợ", "ú", "ù", "ủ", "ũ", "ụ", "ư", "ứ", "ừ", "ử", "ữ", "ự", "ý", "ỳ", "ỷ", "ỹ", "ỵ", "đ", "Á", "﻿À", "Ả", "Ã", "Ạ", "Ă", "Ắ", "Ằ", "Ẳ", "Ẵ", "Ặ", "Â", "Ấ", "Ầ", "Ẩ", "Ẫ", "Ậ", "É", "È", "Ẻ", "Ẽ", "Ẹ", "Ê", "Ế", "Ề", "Ể", "Ễ", "Ệ", "Í", "Ì", "Ỉ", "Ĩ", "Ị", "Ó", "Ò", "Ỏ", "Õ", "Ọ", "Ô", "Ố", "Ồ", "Ổ", "Ỗ", "Ộ", "Ơ", "Ớ", "Ờ", "Ở", "Ỡ", "Ợ", "Ú", "Ù", "Ủ", "Ũ", "Ụ", "Ư", "Ứ", "Ừ", "Ử", "Ữ", "Ự", "Ý", "Ỳ", "Ỷ", "Ỹ", "Ỵ", "Đ");
        $enChars = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "i", "i", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "y", "y", "y", "y", "y", "d", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "I", "I", "I", "I", "I", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "Y", "Y", "Y", "Y", "Y", "D");
        for ($i = 0; $i < sizeof($vnChars); $i++) {
            $string = str_replace($vnChars[$i], $enChars[$i], $string);
        }

        return $string;
    }

    /**
     * Lấy value từ thẻ xml
     *
     * @param String $stringXml Chuỗi xml
     * @param String $parentTag Thẻ cha (cấp 1)
     * @param String $tagXml Thẻ con (cấp 2)
     * @return String $return Giá trị của thẻ xml
     */
    public static function getXmlTagValue($stringXml, $parentTag, $tagXml = '')
    {
        $objXml = simplexml_load_string($stringXml);
        $return = "";
        if ($tagXml !== '') {
            if (isset($objXml->$parentTag)) {
                $arrData = (array)$objXml->$parentTag;
                if (array_key_exists($tagXml, $arrData) && (string)$arrData[$tagXml] !== "") {
                    $return = (string)$arrData[$tagXml];
                }
            }
        } else {
            $return = (string)$objXml->$parentTag;
        }
        return $return;
    }

    /**
     * Chuyển sang số la mã
     *
     * @param Integer $integer Số nguyên
     * @return String $return Số la mã
     */
    public static function romanicNumber(int $integer)
    {
        $romanic = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $return = '';
        while ($integer > 0) {
            foreach ($romanic as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }

}
