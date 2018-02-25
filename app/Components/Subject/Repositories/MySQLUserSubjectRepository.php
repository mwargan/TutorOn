<?php
/**
 * Created by PhpStorm.
 * User: darryl
 * Date: 10/9/2017
 * Time: 10:02 PM
 */

namespace App\Components\Subject\Repositories;


use App\Components\Core\Result;
use App\Components\Core\Utilities\Helpers;
use App\Components\Subject\Contracts\UserSubjectRepository;
use App\Components\Subject\Models\UserClass;
use App\Components\Subject\Utilities\SubjectHelper;
use Auth;


class MySQLUserSubjectRepository implements UserSubjectRepository
{

    /**
     * list resource
     *
     * @param array $params
     * @return Result
     */
    public function index($params)
    {
        // we need to transform group ids to array: 1,2,3,4 => [1,2,3,4]
        $groupIds = explode(',',Helpers::hasValue($params['subject_group_id'],''));
        $orderBy = Helpers::hasValue($params['order_by'],'subject_id');
        $orderSort = Helpers::hasValue($params['order_sort'],'desc');
        $name = Helpers::hasValue($params['name'],null);
        $me = Helpers::hasValue($params['me'],null);
        $paginate = Helpers::hasValue($params['paginate'],'yes');
        $perPage = Helpers::hasValue($params['per_page'],10);

        $q = UserClass::orderBy($orderBy,$orderSort)->with('subject', 'students');

        if($name) $q = $q->where('t-data','like',"%{$name}%");
        if($me) $q = $q->where('user_id', '=', Auth::user()->id);

        if(count($groupIds) > 0 && !empty($groupIds[0])) $q = $q->whereIn('subject_group_id',$groupIds);

        if($paginate==='yes')
        {
            return new Result(true,Result::MESSAGE_SUCCESS_LIST,$q->paginate($perPage));
        }

        return new Result(true,Result::MESSAGE_SUCCESS_LIST,$q->get());
    }

    /**
     * create resource
     *
     * @param array $data
     * @return Result
     */
    public function create($data)
    {
        $Subject = UserClass::create([
            'description' => $data['description'],
            'user_id' => Auth::user()->id,
            'subject_id' => preg_replace('/[^0-9]/', ' ', $data['subject_id']),
            'price' => preg_replace('/[^0-9]/', '', $data['price'])
        ]);
        foreach ($data['languages'] as $result)
        {
            if($result) {
                $language = 'App\Language'::firstOrCreate(['name' => $result['name']], ['native_name' => $result['nativeName'], 'iso639_1' => $result['iso639_1']]);
                $Subject->languages()->create([
                    'language_id' => $language->id,
                ]);
                'App\UserLanguage'::firstOrCreate(['language_id' => $language->id], ['user_id' => Auth::user()->id]);
            }
        }
        if(!$Subject) return new Result(false,Result::MESSAGE_FAILED_CREATE,null,400);

        return new Result(true,Result::MESSAGE_SUCCESS_CREATE,$Subject,201);
    }

    /**
     * get resource by id
     *
     * @param $id
     * @return Result
     */
    public function get($id)
    {
        $Subject = Subject::find($id);

        if(!$Subject) return new Result(false,Result::MESSAGE_NOT_FOUND,null,404);

        return new Result(true,Result::MESSAGE_SUCCESS,$Subject,200);
    }

    /**
     * update resource
     *
     * @param int $id
     * @param array $data
     * @return Result
     */
    public function update($id, $data)
    {
        $Subject = Subject::find($id);

        if(!$Subject) return new Result(false,Result::MESSAGE_NOT_FOUND,null,404);

        $Subject['t-data'] = $data['t-data'];

        if(!$Subject->save()) return new Result(false,Result::MESSAGE_FAILED_UPDATE,null,400);

        return new Result(true,Result::MESSAGE_SUCCESS_UPDATE,$Subject,201);
    }

    /**
     * delete resource by id
     *
     * @param int $id
     * @return Result
     */
    public function delete($id)
    {
        $ids = explode(',',$id);

        foreach ($ids as $id)
        {
            $Subject = Subject::find($id);

            if(!$Subject) return new Result(false,"Failed to delete resource with id: {$id}. Error: ".Result::MESSAGE_NOT_FOUND,null,404);

            // delete subject record
            $Subject->delete();

            // delete actual subject
            $this->deleteSubject($Subject->path);
        }

        return new Result(true,Result::MESSAGE_SUCCESS,$Subject,200);
    }

}