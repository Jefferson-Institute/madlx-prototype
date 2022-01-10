<?php

namespace App\Http\Controllers\Api;

use App\Dashboard;
use App\Laravue\JsonResponse;
use App\Visualization;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Http;

/**
 *
 * @package App\Http\Controllers\Api
 */
class VisualizationController extends BaseController
{
    public function index(Request $request)
    {
        $authority = $request->authority;

        if(Dashboard::where('id', $request->dashboard_id)->exists()) {
            $visualizations = Visualization::where('dashboard_id', $request->dashboard_id)
            ->when($authority, function ($query, $authority) {
                return $query->where('authority', $authority);
            })
            ->get();
            return response()->json(new JsonResponse($visualizations));
        }

        $visualizations = Visualization::where('dashboard_id', null)
        ->when($authority, function ($query, $authority) {
            return $query->where('authority', $authority);
        })
        ->get();

        return response()->json(new JsonResponse($visualizations));
    }

    public function getAuthorities()
    {
        $CUBEJS_API_URL = 'cube:4000/cubejs-api/v1/load';
        $CUBEJS_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmxpbmUgSldUIEJ1aWxkZXIiLCJpYXQiOjE2MTMwNDg1MjMsImV4cCI6MTY0NDU4NDUyNCwiYXVkIjoiZGFzaC5qZWZmZXJzb25ob3N0aW5nLm9yZyIsInN1YiI6IiIsIkVtYWlsIjoiYWRtaW5AamVmZmVyc29uaG9zdGluZy5vcmcifQ.mSfNczOIZyfXXCrU61WCwWVB1ojQCy3WjUy_8WAGnz8';
        $response = Http::withToken($CUBEJS_TOKEN)->get($CUBEJS_API_URL, [
            'query' => '{"measures":["Statements.count"],"dimensions":["Statements.authorityName"]}',
        ]);
        if($response->successful()) {
            $authorities = array_map(function($value) {
                return $value['Statements.authorityName'];
            }, $response->json()['data']);
            return response()->json(new JsonResponse($authorities));
        }
        return response()->json(new JsonResponse([], 'data_sources_error'), Response::HTTP_NO_CONTENT);
    }

    public function store(Request $request)
    {
        $params = $request->all();

        // Create new Dashboard if there is none
        if(!Dashboard::where('id', $params['dashboard_id'])->exists()) {
            $dashboard = new Dashboard();
            $dashboard->id = $params['dashboard_id'];
            $dashboard->save();

            // Duplicate all Default Visualisations
            $visualisations = Visualization::where('dashboard_id', null)->get();
            foreach ($visualisations as $v) {
                $newVisualization = $v->replicate();
                $newVisualization->dashboard_id = $params['dashboard_id'];
                $newVisualization->save();
            }
        }

        $visualization = new Visualization();
        $visualization->dashboard_id = $params['dashboard_id'];
        $visualization->viz_state = json_encode($params['query']);
        $visualization->pivot = $params['pivot'] == [] ? '{}' : json_encode($params['pivot']);
        $visualization->title = $params['title'];
        $visualization->type = $params['type'];
        $visualization->authority = $params['authority'];
        $visualization->background = $params['background'];
        $visualization->icon = $params['icon'];
        $visualization->legend = $params['legend'];
        $visualization->curve = $params['curve'];
        $visualization->xtitle = $params['xtitle'];
        $visualization->ytitle = $params['ytitle'];
        $visualization->save();

        return response()->json(new JsonResponse($visualization));
    }

    public function show(Visualization $visualization)
    {
        return response()->json(new JsonResponse($visualization));
    }

    public function update(Visualization $visualization, Request $request)
    {
        $params = $request->all();

        // Create new Dashboard if there is none
        if(!Dashboard::where('id', $params['dashboard_id'])->exists()) {
            $dashboard = new Dashboard();
            $dashboard->id = $params['dashboard_id'];
            $dashboard->save();

            // Duplicate all Default Visualisations
            $visualisations = Visualization::where('dashboard_id', null)->get();
            foreach ($visualisations as $v) {
                $newVisualization = $v->replicate();
                $newVisualization->dashboard_id = $params['dashboard_id'];
                $newVisualization->save();

                if($v->id === $visualization->id) {
                    $visualization = $newVisualization;
                }
            }
        }

        if (isset($params['dashboard_id']))
            $visualization->dashboard_id = $params['dashboard_id'];
        if (isset($params['xtitle']))
            $visualization->xtitle = $params['xtitle'];
        if (isset($params['ytitle']))
            $visualization->ytitle = $params['ytitle'];
        if (isset($params['icon']))
            $visualization->icon = $params['icon'];
        if (isset($params['background']))
            $visualization->background = $params['background'];
        if (isset($params['legend']))
            $visualization->legend = $params['legend'];
        if (isset($params['curve']))
            $visualization->curve = $params['curve'];
        if (isset($params['authority']))
            $visualization->authority = $params['authority'];
        if (isset($params['query']))
            $visualization->viz_state = json_encode($params['query']);
        if (isset($params['pivot']))
            $visualization->pivot = $params['pivot'] == [] ? '{}' : json_encode($params['pivot']);
        if (isset($params['title']))
            $visualization->title = $params['title'];
        if (isset($params['type']))
            $visualization->type = $params['type'];
        if (isset($params['h']))
            $visualization->h = $params['h'];
        if (isset($params['w']))
            $visualization->w = $params['w'];
        if (isset($params['x']))
            $visualization->x = $params['x'];
        if (isset($params['y']))
            $visualization->y = $params['y'];

        $visualization->save();
        return response()->json(new JsonResponse($visualization));
    }

    public function updateAll(Request $request)
    {
        $vs = $request->visualisations;
        $authority = $request->authority;

        // Create new Dashboard if there is none
        if(!Dashboard::where('id', $request->dashboard_id)->exists()) {
            $dashboard = new Dashboard();
            $dashboard->id = $request->dashboard_id;
            $dashboard->save();

            // Duplicate all Default Visualisations
            $defaultVisualisations = Visualization::where('dashboard_id', null)->get();

            // Map new visualisation changes for authority
            $newVisMap = [];
            foreach ($vs as $v) {
                $newVisMap[$v['i']] = $v;
            }

            foreach ($defaultVisualisations as $v) {
                $newVisualization = $v->replicate();
                $newVisualization->dashboard_id = $request->dashboard_id;
                // check if it is in this authority
                if(array_key_exists($v->id, $newVisMap)) {
                    $newVisualization->h = $newVisMap[$v->id]['h'];
                    $newVisualization->w = $newVisMap[$v->id]['w'];
                    $newVisualization->x = $newVisMap[$v->id]['x'];
                    $newVisualization->y = $newVisMap[$v->id]['y'];
                }
                $newVisualization->save();
            }

            $visualizations = Visualization::where('dashboard_id', $request->dashboard_id)
            ->when($authority, function ($query, $authority) {
                return $query->where('authority', $authority);
            })
            ->get();
            return response()->json(new JsonResponse($visualizations));
        }

        foreach ($vs as $v) {
            $visualization = Visualization::find($v['i']);
            if (isset($v['h']))
                $visualization->h = $v['h'];
            if (isset($v['w']))
                $visualization->w = $v['w'];
            if (isset($v['x']))
                $visualization->x = $v['x'];
            if (isset($v['y']))
                $visualization->y = $v['y'];

            $visualization->save();
        }


        return response()->json(new JsonResponse(['Success' => 'positions updated']));
    }

    public function destroy(Visualization $visualization, Request $request)
    {
        try {
            // Create new Dashboard if there is none
            if(!Dashboard::where('id', $request->dashboard_id)->exists()) {
                $dashboard = new Dashboard();
                $dashboard->id = $request->dashboard_id;
                $dashboard->save();

                // Duplicate all Default Visualisations
                $visualisations = Visualization::where('dashboard_id', null)->get();
                foreach ($visualisations as $v) {
                    $newVisualization = $v->replicate();
                    $newVisualization->dashboard_id = $request->dashboard_id;
                    $newVisualization->save();

                    if($v->id === $visualization->id) {
                        $visualization = $newVisualization;
                        $visualization->delete();
                    }
                }

                $visualizations = Visualization::where('dashboard_id', $request->dashboard_id)
                ->when($request->authority, function ($query, $authority) {
                    return $query->where('authority', $authority);
                })
                ->get();
                
                return response()->json(new JsonResponse($visualizations));
            }
            $visualization->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}
