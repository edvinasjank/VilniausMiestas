<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index(Request $request) {
        $limit = $request->get('limit', 10);
        $sort = $request->get('sort', 'asc');
        $filterArray = $request->request->all();
        $people = City::getRecords($filterArray, $limit, $sort);
        if(isset($_GET['download'])){
            return AdminController::download($people);
        }
        return view('index', [
            'persons' => $people,
            'address' => isset($filterArray['adress']) ? $filterArray['adress'] : ''
        ]);

    }

    public function deletePeople(Request $request)
    {
        $id = $request->request->get('people');
        if (!$id) {
            return redirect()->back()->with('error_message', 'Pasirinkite norimus įrašus');
        }
        City::deleteRecords($id);

        return redirect()->back()->with('success_message', 'Įrašai sėkmingai ištrint');
    }

    public function autocomplete(Request $request)
    {
        $term = $request->get('address');
        $result = array();
        $queries = City::select()
            ->where('street', 'LIKE', '%'.$term.'%')
            ->groupBy('street')->take(5)->get();
        foreach($queries as $query) {
            $result[] = ['street' => $query->street];
        }

        return response()->json($result);

    }

    public function edit(Request $request){
        $arr = $request->request->all();
        if($arr['street'] != null){
            City::where('id', $arr['id'])
                ->update(['street' => $arr['street'],
                        'kids' => $arr['kids']]);
        }
        
    }
    
    public function import(Request $request){
        $file = $request->files->get('file');
     
        if (!$file) {
            return redirect()->back()->with('error_message', 'Pasirinkite failą failas');
        }
        City::import($file);

        return redirect()->back()->with('success_message', 'Įrašai sėkmingai importuoti');
        
    }
    public static function download($list)
    {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('GIMIMO_METAI', 'GIMIMO_VALSTYBE', 'LYTIS', 'SEIMOS_PADETIS', 'KIEK_TURI_VAIKU', 'SENIUNIJA',  'GATVE');

        $callback = function () use ($list, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($list as $person) {
                fputcsv($file, array($person->birth_year, $person->birth_country, $person->gender,
                    $person->family_situation, $person->kids, $person->location, $person->street));
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
}