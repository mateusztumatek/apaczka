<?php
namespace App\Traits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
trait Filter
{
    public function scopeFilter($q){
        if(request()->get('id')){
            $q->where('id', request()->get('id'));
        }
       /* $inputs = request()->all();
        foreach ($inputs as $key => $input){
            if($key != 'orderBy' && $key != 'orderType'){
                if(Schema::hasColumn(parent::getTable(), $key)){
                    if($input != null && $input != ''){
                        if($input[0] == '%' && $input[strlen($input) - 1] == '%'){
                            $in = substr($input, 1, 1);
                            $in = substr($in, strlen($in), -1);
                            $q->where($key, 'LIKE', '%'.$input.'%');
                        }else{
                            if($input[0] == '!' && $input[strlen($input) -1] == '!'){
                                $in = substr($input, 1, strlen($input) -2);
                                $q->where($key, '!=', $in);
                            }else{
                                $q->where($key, $input);
                            }
                        }
                    }
                }
            }
        }
        if(array_key_exists('orderBy', $inputs)){
            (array_key_exists('orderType', $inputs))? $type = $inputs['orderType'] : $type = 'desc';
            if(Schema::hasColumn(parent::getTable(), $inputs['orderBy'])){
                $q->orderBy($inputs['orderBy'], $type);
            }
        }
        if(array_key_exists('range', $inputs) && is_array($inputs['range'])){
            $date_first = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($inputs['range'][0]), 'UTC')->setTimeZone('Europe/Zurich');
            $date_second = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($inputs['range'][1]), 'UTC')->setTimeZone('Europe/Zurich')->setHours(23)->setMinutes(59);
            $q->where('created_at', '>=', $date_first)->where('created_at', '<=', $date_second);
        }*/
        return $q;
    }
}
