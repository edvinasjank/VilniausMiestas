<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;

class City extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'city';

    public $timestamps = false;

    protected $list;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'birth_year', 'birth_country', 'gender', 'family_situation', 'kids', 'location', 'street'
    ];

    public static function deleteRecords($recordsId)
    {
        foreach($recordsId as $id){
            City::where('id', $id)->delete();
        }
    }

    public static function getRecords($filterArray, $limit, $sort)
    {
        $query = City::select()
            ->orderBy('street', $sort);
        if(isset($filterArray["adress"]) && $filterArray["adress"] != null){
            $query = $query->where('street', $filterArray["adress"]);
        }
        if(isset($filterArray["genderOption"]) && $filterArray["genderOption"] != null){
            $query = $query->where('gender', $filterArray["genderOption"]);
        }
        if(isset($filterArray["hasKidsOption"]) && $filterArray["hasKidsOption"] != null){
            if($filterArray["hasKidsOption"] == "hasKids"){
                $query = $query->where('kids', '>', '0');
            }
            else{
                $query = $query->where('kids', '=', '0');
            }
        }
        if(isset($_GET['download'])) return $query->get();
       

        return $query->paginate($limit);
    }

    public static function import($filePath)
    {
        $row = 0;
        if (($handle = fopen($filePath, "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $row++;
                if($row == 1) continue;
                $city = new City();
                if($city->where([
                    ['birth_year', $data[0]],
                    ['birth_country', $data[1]],
                    ['gender', $data[2]],
                    ['family_situation', $data[3]],
                    ['kids', $data[4]],
                    ['location', $data[5]],
                    ['street', $data[6]]
                ])->exists()){
                    $city->fill([
                        'birth_year' => $data[0],
                        'birth_country' => $data[1],
                        'gender' => $data[2],
                        'family_situation' => $data[3],
                        'kids' => $data[4],
                        'location' => $data[5],
                        'street' => $data[6]
                    ]);
                    $city->save();
                }
            }
            fclose($handle);
        }
    }

    

}
