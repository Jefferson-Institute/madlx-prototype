<?php

namespace App\Http\Controllers\Api;

use App\Laravue\JsonResponse;
use App\LearnerGroup;
use Illuminate\Http\Request;

/**
 *
 * @package App\Http\Controllers\Api
 */
class LearnerGroupsController extends BaseController
{
    public function index()
    {
        $data = LearnerGroup::all();
        return response()->json(new JsonResponse($data));
    }

    public function store(Request $request)
    {
        $params = $request->all();
        $model = new LearnerGroup();
        $model->name = $params['name'];
        $model->save();
        return response()->json(new JsonResponse($model));
    }

    public function destroy($name)
    {
        try {
            $model = LearnerGroup::where('name', $name)->first();
            $model->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
