<?php

namespace Modules\Core\Helpers;

/**
 * Xử lý file
 *
 * created at 05/08/2022
 * @author khuongtq
 */
class FileHelper
{
    private $dirTemp;
    private $dirSave;
    private $delimiter;

    public function __construct()
    {
        if (!file_exists(public_path('temp_upload'))) mkdir(public_path('temp_upload'));
        if (!file_exists(public_path('file_upload'))) mkdir(public_path('file_upload'));
        $this->dirTemp = public_path('temp_upload') . chr(92);
        $this->dirSave = public_path('file_upload') . chr(92);
    }

    /**
     * Set thư mục con trong thư mục file_upload
     *
     * @param String $folder Tên thư mục con
     */
    public function setFileUpload($folder)
    {
        $uploadFolder = $this->dirSave;
        $this->dirSave = $uploadFolder . $folder . chr(92);
        if (!file_exists($this->dirSave)) mkdir($this->dirSave);
    }

    /**
     * Đẩy nhiều file từ client lên thư mục temp
     *
     * @param
     * @return array $return Thông tin trả về thành công của file
     */
    public function uploadFileTemp()
    {
        $i=0;
        $return = [];
        $path = $this->dirTemp;
        foreach($_FILES as $file){
            $fileName = $file['name'];
            $fileName = CoreHelper::convertVNtoEN($fileName);
            $fileName = CoreHelper::convertBadChar($fileName);
            $random = mt_rand(1, 1000000);
            $fullFileName = date("Y") . '_' . date("m") . '_' . date("d") . '_' . date("H") . date("i") . date("u") . $random . "!~!" . $fileName;
            move_uploaded_file($file['tmp_name'], $path . $fullFileName);
            $return[$i]['file_name'] = $fullFileName;
            $f = $this->getUrlFileByName($fullFileName, 'temp_upload');
            $return[$i]['url'] = $f['url'];
            $return[$i]['real_file_name'] = $f['real_file_name'];
            $i++;
        }

        return $return;
    }

    /**
     * Chuyển file đính kèm vào thư mục / thư mục con / năm / tháng / ngày
     *
     * @param string $filename tên file. vd: 2022_07_29_1007000000994897!~!Quyet_dinh_01.pdf
     * @param string $unit: Mã đơn vị
     * @return ProcessService $this
     */
    public function moveFileFromTempToSave($filename)
    {
        $fullFileTemp = $this->dirTemp . $filename;
        if (file_exists($fullFileTemp)) {
            $newFile = CoreHelper::createFolder($this->dirSave, date('Y'), date('m'), date('d')) . $filename;
            copy($fullFileTemp, $newFile);
            unlink(realpath($fullFileTemp));

            return $newFile;
        }

        return '';
    }

    /**
     * Xóa file trong file_upload
     *
     * @param String $filename Tên file (có tiền tố ngày tháng)
     */
    public function removeFileUpload($filename)
    {
        $arrFile = explode("_", $filename);
        $pathFile = $this->dirSave . $arrFile[0] . chr(92) . $arrFile[1] . chr(92) . $arrFile[2] . chr(92) . $filename;
        if (file_exists($pathFile)) {
            unlink(realpath($pathFile));
        }
    }

    /**
     * Lấy url và tên file trong $folder
     *
     * @param String $filename Tên file (có tiền tố ngày tháng)
     * @param String $folder Thư mục gốc
     * @return Array $array Url và tên gốc của file
     */
    public function getUrlFileByName($filename, $folder = 'file_upload')
    {
        $url = $realFileName = '';
        $folderUpload = $folder;
        // if($unit == "" && isset(auth('sanctum')->user()->owner_code) && auth('sanctum')->user()->owner_code !== ""){
        //     $unit = auth('sanctum')->user()->owner_code;
        // }
        // if ($unit != '') $attachFolder = $folder . '/' . $unit;
        $arrFile = explode("_", $filename);
        if (isset($arrFile[0]) && isset($arrFile[1]) && isset($arrFile[2]) && isset($arrFile[3])) {
            $arrFilename = explode("!~!", $arrFile[3]);
            if (isset($arrFilename[1])) {
                if ($folder == 'file_upload') {
                    $checkPath = $arrFile[0] . chr(92) . $arrFile[1] . chr(92) . $arrFile[2] . chr(92) . $filename;
                } else {
                    $checkPath = $filename;
                }
                // if (file_exists(public_path($folderUpload . chr(92) . $checkPath))) {
                    $url = url('public/' . $folderUpload . chr(47) . $checkPath);
                    $arrRealFileName = explode("!~!", $filename);
                    $realFileName = $arrRealFileName[1];
                // }
                // else{
                //     if (file_exists(public_path($folder . chr(92) . $checkPath))) {
                //         $url = url('public/' . $folder . chr(92) . $checkPath);
                //         $arrRealFileName = explode("!~!", $filename);
                //         $realFileName = $arrRealFileName[1];
                //     }
                // }
            }
        }

        return [
            'url' => $url,
            'real_file_name' => $realFileName
        ];
    }
}
