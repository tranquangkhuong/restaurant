<?php

namespace Modules\Core\Helpers;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Thực hiện lưu file log
 *
 * created at 05/08/2022
 * @author khuongtq
 *
 * Đường dẫn: Y/m/d/ [Tên file]
 * @Tên file: Mã đơn vị
 * Cấu trúc: [%datetime%] [%channel%] [%message%] [%context%] trong đó:
 * @datetime: là thời gian lưu log
 * @channel: Mã hồ sơ (mã định danh)
 * @message: trạng thái request; response
 * @context: dữ liệu trả về
 */
class LoggerHelper
{
    protected $folder;
    private $channel = 'info';
    private $fileName = 'log';

    public function __construct() {
        $path = storage_path('logs').chr(92);
        $this->folder = CoreHelper::createFolder($path, date('Y'), date('m'), date('d'));
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    private function setFormat()
    {
        $dateFormat = "H:i:s d/m/Y";
        // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        $output = "[%datetime%] [%channel%] [%message%] [%context%] \n";

        return new LineFormatter($output, $dateFormat);
    }

    public function log($message, $data = [])
    {
        $path = $this->folderPath . "/" . $this->fileName . ".log";
        $stream = new StreamHandler($path, Logger::DEBUG);
        $stream->setFormatter($this->setFormat());
        $securityLogger = new Logger($this->channel);
        $securityLogger->pushHandler($stream);
        $securityLogger->info($message, ['data'=>$data]);
    }
}
