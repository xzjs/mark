<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $host;

    public function __construct()
    {
        $this->host = config('services.qiniu.host');
    }

    /**
     * 拼接图片路径
     * @param $img
     * @return string
     */
    protected function appendHost($img)
    {
        return $this->host . $img;
    }


}
