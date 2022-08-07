<?php

namespace Modules\Core\Helpers;

/**
 * Xử lý ngày tháng
 *
 * created at 05/08/2022
 * @author khuongtq
 */
class DateHelper
{
    /**
     * Kiểm tra dữ liệu có phải một trong các format đã định trước
     *
     * @param String $data Chuỗi ngày tháng
     * @return String|Boolean $format Định dạng ngày tháng
     */
    public static function checkDateFormat($date)
    {
        $arrFormat = [
            'm/d/Y', 'd/m/Y', 'm/Y', 'Y',
            'd-m-Y', 'm-Y',
            'd.m.Y', 'm.Y',
            'Y/m/d', 'Y/m',
            'Y-m-d', 'Y-m',
            'Y.m.d', 'Y.m',
        ];
        foreach ($arrFormat as $format) {
            $dt = \DateTime::createFromFormat($format, $date);
            if ($dt && $dt->format($format) === $date) {
                return $format;
            }
        }

        return false;
    }

    /**
     * Convert chuỗi date từ format input và trả về với format output
     *
     * @param string $date Chuỗi ngày tháng truyền vào
     * @param string $inputFormat Định dạng ngày tháng truyền vào
     * @param string $outputFormat Định dạng ngày tháng muốn lấy
     * @return string $dateReturn Chuỗi ngày tháng đã convert nếu đúng định dạng và tham số,
     * nếu sai trả về chính ngày tháng truyền vào
     */
    public static function _date($date, $ouputFormat = 'd/m/Y', $inputFormat = 'Y-m-d')
    {
        $dt = \DateTime::createFromFormat($inputFormat, $date);
        if ($dt && $dt->format($inputFormat) === $date) {
            $dateReturn = $dt->format($ouputFormat);
        } else {
            $dateReturn = $date;
        }

        return $dateReturn;
    }
}
