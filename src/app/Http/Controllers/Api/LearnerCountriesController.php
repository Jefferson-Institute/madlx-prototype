<?php

namespace App\Http\Controllers\Api;

use App\Laravue\JsonResponse;
use App\LearnerCountry;
use Illuminate\Http\Request;

/**
 *
 * @package App\Http\Controllers\Api
 */
class LearnerCountriesController extends BaseController
{
    public function index()
    {
        $data = LearnerCountry::all();
        return response()->json(new JsonResponse($data));
    }

    public function store(Request $request)
    {
        $params = $request->all();
        $model = new LearnerCountry();
        $model->name = $params['name'];
        $model->save();
        return response()->json(new JsonResponse($model));
    }

    public function destroy($name)
    {
        try {
            $model = LearnerCountry::where('name', $name)->first();
            $model->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
