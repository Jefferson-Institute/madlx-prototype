<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 *
 * @package App\Http\Controllers\Api
 */
class PythonscriptController extends BaseController
{
    public function run(Request $request)
    {
        if(!Storage::disk('public')->put('data.csv', $request->input($request->input('data')))) {
            return false;
        }
        $output = shell_exec('python3 correlation.py');
        return $output;
        // echo $output;
        // Storage::put('data.csv', $output);
        // Storage::setVisibility('data.csv', 'public');
        // if(!Storage::disk('public')->put('data3.csv', $output)) {
        //    return false;
        //}
    }

}
