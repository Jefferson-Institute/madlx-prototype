<?php

namespace App\Http\Controllers\Api;

use App\Laravue\JsonResponse;
use App\Learner;
use App\LearnerGroup;
use App\StatementTemplate;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JsonMachine\JsonMachine;
use PhpParser\JsonDecoder;

/**
 *
 * @package App\Http\Controllers\Api
 */
class LearnerController extends BaseController
{
    public function index(Request $request)
    {
        $query = Learner::query();

        $query->when($request->group && $request->group !== 'all', function ($q) use ($request) {
            return $q->where('group', $request->group);
        });

        $query->when($request->country && $request->country !== 'all', function ($q) use ($request) {
            return $q->where('country', $request->country);
        });

        $data = $query->get();
        return response()->json(new JsonResponse($data));
    }

    public function getLrsUsers()
    {
        $CUBEJS_API_URL = 'https://demo-cube.madlx.org/cubejs-api/v1/load';
        $CUBEJS_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmxpbmUgSldUIEJ1aWxkZXIiLCJpYXQiOjE2MTMwNDg1MjMsImV4cCI6MTY0NDU4NDUyNCwiYXVkIjoiZGFzaC5qZWZmZXJzb25ob3N0aW5nLm9yZyIsInN1YiI6IiIsIkVtYWlsIjoiYWRtaW5AamVmZmVyc29uaG9zdGluZy5vcmcifQ.mSfNczOIZyfXXCrU61WCwWVB1ojQCy3WjUy_8WAGnz8';
        $response = Http::withToken($CUBEJS_TOKEN)->get($CUBEJS_API_URL, [
            'query' => '{"measures":["Statements.count"],"dimensions":["Statements.actorName", "Statements.actorMbox"],"order":{"Statements.actorName":"asc"}}',
        ]);
        if($response->successful()) {
            $learners = array_map(function($value) {
                return [
                    'username' => $value['Statements.actorName'], 
                    'email' => $value['Statements.actorMbox'],
                ];
            }, $response->json()['data']);
            return response()->json(new JsonResponse($learners));
        }
        return response()->json(new JsonResponse(''));
    }

    public function saveLrsUsers(Request $request)
    {
        try {
            //insert into Learners
            foreach($request->learners as $learnerData) {
                DB::table('learners')->insertOrIgnore([
                    [
                        "username" => $learnerData['username'],
                        "name" => $learnerData['username'],
                        "email" => $learnerData['email'],
                    ],
                ]);
            }

        } catch(Exception $ex) {
            return response()->json(['error' => 'failed'], 500);
        }

        return response()->json(['status' => 'success']);
    }

    public function objAvgScore(Request $request)
    {
        $group = $request->query('group');
        if($group !== 'all') {
            $data = DB::table('learners_grades')
            ->select(DB::raw('oid, objective, avg(learners_grades.grade) AS score'))
            ->join('learners', 'learners.username', '=', 'learners_grades.username')
            ->where('learners.group', $group)
            ->groupBy('objective', 'oid')
            ->get();
            return response()->json(new JsonResponse($data));
        }
        $data = DB::table('learners_grades')
        ->select(DB::raw('oid, objective, avg(grade) AS score'))
        ->groupBy('objective', 'oid')
        ->get();

        return response()->json(new JsonResponse($data));
    }

    public function objLearnersCount(Request $request)
    {
        $group = $request->query('group');
        if($group !== 'all') {
            $data = DB::table('learners_grades')
            ->select(DB::raw('oid, objective, count(learners_grades.username) AS users'))
            ->join('learners', 'learners.username', '=', 'learners_grades.username')
            ->where('learners.group', $group)
            ->groupBy('objective', 'oid')
            ->get();
            return response()->json(new JsonResponse($data));
        }

        $data = DB::table('learners_grades')
        ->select(DB::raw('oid, objective, count(username) AS users'))
        ->groupBy('objective', 'oid')
        ->get();

        return response()->json(new JsonResponse($data));
    }

    public function LearnersCountryCount(Request $request)
    {
        $group = $request->query('group');
        if($group !== 'all') {
            $data = DB::table('learners')
            ->select(DB::raw('country, count(username) AS users'))
            ->where('learners.group', $group)
            ->groupBy('country')
            ->get();
            return response()->json(new JsonResponse($data));
        }

        $data = DB::table('learners')
        ->select(DB::raw('country, count(username) AS users'))
        ->groupBy('country')
        ->get();

        return response()->json(new JsonResponse($data));
    }

    public function GroupsWithLearners()
    {
        $data = LearnerGroup::with('learners')->get();
        return response()->json(new JsonResponse($data));
    }
    // public function getLearnersFromLrs(Request $request) {
    //     $CUBEJS_API_URL = 'https://demo-cube.madlx.org/cubejs-api/v1/load';
    //     $CUBEJS_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmxpbmUgSldUIEJ1aWxkZXIiLCJpYXQiOjE2MTMwNDg1MjMsImV4cCI6MTY0NDU4NDUyNCwiYXVkIjoiZGFzaC5qZWZmZXJzb25ob3N0aW5nLm9yZyIsInN1YiI6IiIsIkVtYWlsIjoiYWRtaW5AamVmZmVyc29uaG9zdGluZy5vcmcifQ.mSfNczOIZyfXXCrU61WCwWVB1ojQCy3WjUy_8WAGnz8';
    //     $response = Http::withToken($CUBEJS_TOKEN)->get($CUBEJS_API_URL, [
    //         'query' => '{"measures":["Statements.Users"],"dimensions":["Statements.actorName"]}',
    //     ]);
    //     if($response->successful()) {
    //         $users = array_map(function($value) {
    //             return $value['Statements.actorName'];
    //         }, $response->json()['data']);
    //         return response()->json(new JsonResponse($users));
    //     }
    //     return response()->json(new JsonResponse([], 'data_sources_error'), Response::HTTP_NO_CONTENT);
    // }
    public function importToLrs(Request $request)
    {
        try {
            $API_URL = 'https://demo-lrs.madlx.org/trax/api/38a17443-52df-4fcf-a349-138d9af73f37/xapi/std/statements';

            //save file
            $file = $request->file('file')->store('imports');
            $path = Storage::path($file);
            
            //read file
            $statements = JsonMachine::fromFile($path);
            $successfull_statements = 0;
            $chunk = [];
            $iterator = 0;

            foreach ($statements as $st) {
                $iterator++;
                $chunk[] = $st;
                if($iterator >= 100) {
                    $response = Http::withHeaders([
                        'X-Experience-API-Version' => '1.0.0',
                    ])->withBasicAuth(
                        'hR14chsgz0eVFYaW12qUSA', 'djEwpqoEIYEklkZr5QAnpQ'
                    )->withBody(
                        json_encode($chunk), 'application/json'
                    )->post(
                        $API_URL
                    );
    
                    if($response->successful()) {
                        $successfull_statements += count($chunk);
                        DB::insert('insert into imports (authority, statements) values (?, ?)', [$chunk[0]['authority']['account']['name'], json_encode($response->body())]);
                    }

                    $iterator = 0;
                    $chunk = [];
                }
            }

            if($iterator > 0) {
                $response = Http::withHeaders([
                    'X-Experience-API-Version' => '1.0.0',
                ])->withBasicAuth(
                    'hR14chsgz0eVFYaW12qUSA', 'djEwpqoEIYEklkZr5QAnpQ'
                )->withBody(
                    json_encode($chunk), 'application/json'
                )->post(
                    $API_URL
                );

                if($response->successful()) {
                    $successfull_statements += count($chunk);
                    DB::insert('insert into imports (authority, statements) values (?, ?)', [$chunk[0]['authority']['account']['name'], json_encode($response->body())]);
                }
            }

            if($successfull_statements > 0) {
                return response()->json(['status' => 'success', 'response' => 'Succesfully sent ' .$successfull_statements. ' statements to LRS']);
            }

            return response()->json(['status' => 'failed', 'response' => $response->body()], 400);
        } catch(Exception $ex) {
            return response()->json(['error' => 'failed', 'response' => 'Failed Server Exception thrown'], 400);
        } finally {
            if($file) {
                Storage::delete($file);
            }
        }
    }

    public function import(Request $request)
    {
        try {

            $uploadedFile = $request->file('file');

            $data = [];
            $file_handle = fopen($uploadedFile->path(), 'r');
            fgetcsv($file_handle);
            while (!feof($file_handle)) {
                $row = fgetcsv($file_handle, 0, ',');
                if(is_array($row) && count($row) > 0) {
                    $data[] = $row;
                }
            }
            fclose($file_handle);

            //insert into Learners
            foreach($data as $learnerData) {
                DB::table('learners')->insertOrIgnore([
                    [
                        "username" => $learnerData[4],
                        "name" => $learnerData[4],
                        "email" => $learnerData[5],
                        "group" => $learnerData[1],
                        "country" => $learnerData[8],
                    ],
                ]);
                DB::table('learners_groups')->insertOrIgnore([
                    [
                        "name" => $learnerData[1],
                    ],
                ]);
                DB::table('learners_countries')->insertOrIgnore([
                    [
                        "name" => $learnerData[8],
                    ],
                ]);
                DB::table('learners_grades')->insertOrIgnore([
                    [
                        "date" => DateTime::createFromFormat('Y-m-d H:i e', $learnerData[0]),
                        "username" => $learnerData[4],
                        "oid" => $learnerData[6],
                        "objective" => $learnerData[2],
                        "grade" => $learnerData[3],
                    ],
                ]);
            }

        } catch(Exception $ex) {
            return response()->json(['error' => 'failed'], 500);
        }

        return response()->json(['status' => 'success']);
    }

    public function store(Request $request)
    {
        $params = $request->all();
        $model = Learner::create($params);
        return response()->json(new JsonResponse($model));
    }

    public function update($username, Request $request)
    {
        $params = $request->all();
        $model = Learner::where('username', $username)->first();
        $model->fill($params);
        $model->save();
        return response()->json(new JsonResponse($model));
    }

    public function destroy($username)
    {
        try {
            $model = Learner::where('username', $username)->first();
            $model->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    public function createTemplate(Request $request) {
        try {
            $params = $request->all();
            $model = new StatementTemplate();
            $model->fill($params);
            $model->save();
            return response()->json(new JsonResponse($model));
        } catch(Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }

    public function getTemplates() {
        $templates = StatementTemplate::all();
        return response()->json(new JsonResponse($templates));
    }

    public function getImports() {
        $imports = DB::select('select authority from imports GROUP BY authority');
        return response()->json(new JsonResponse($imports));
    }

    public function deleteImports(Request $request) {
        $authority = $request->authority;
        $imports = DB::select('select * from imports WHERE authority = ?', [$authority]);
        dd(json_decode($imports[0]->statements));

        $API_URL = 'https://demo-lrs.madlx.org/trax/api/38a17443-52df-4fcf-a349-138d9af73f37/xapi/std/statements';

        foreach ($imports as $import) {
            // foreach (json_decode($import->statements) as $st) {
            //     $iterator++;
            //     $chunk[] = $st;
            //     if($iterator >= 100) {
            //         $response = Http::withHeaders([
            //             'X-Experience-API-Version' => '1.0.0',
            //         ])->withBasicAuth(
            //             'hR14chsgz0eVFYaW12qUSA', 'djEwpqoEIYEklkZr5QAnpQ'
            //         )->withBody(
            //             json_encode($chunk), 'application/json'
            //         )->post(
            //             $API_URL
            //         );

            //         if($response->successful()) {
            //             $successfull_statements += count($chunk);
            //             DB::insert('insert into imports (authority, statements) values (?, ?)', [$chunk[0]['authority']['account']['name'], json_encode($response->body())]);
            //         }

            //         $iterator = 0;
            //         $chunk = [];
            //     }
            // }
        }
    }
}
