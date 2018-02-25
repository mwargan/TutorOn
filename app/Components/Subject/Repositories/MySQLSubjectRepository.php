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
use App\Components\Subject\Contracts\SubjectRepository;
use App\Components\Subject\Models\Subject;
use App\Components\Subject\Utilities\SubjectHelper;
use Storage;
use Image;

class MySQLSubjectRepository implements SubjectRepository
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
        $orderBy = Helpers::hasValue($params['order_by'],'tag-id');
        $orderSort = Helpers::hasValue($params['order_sort'],'desc');
        $name = Helpers::hasValue($params['name'],null);
        $paginate = Helpers::hasValue($params['paginate'],'yes');
        $perPage = Helpers::hasValue($params['per_page'],10);

        $q = Subject::orderBy($orderBy,$orderSort)->with('classes.user.profilePicture');

        if($name) $q = $q->where('t-data','=',"{$name}");
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
        $Subject = Subject::create([
            't-data' => preg_replace('/[^A-Za-z0-9\-]/', ' ', $data['t-data'])
        ]);

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

    /**
     * @param \Illuminate\Http\UploadedSubject $subject
     * @return Result
     */
    public function upload($subject)
    {
        $subjectOriginalName = $subject->getClientOriginalName();
        $subjectPath = $subject->store('local');

        if(!$subjectPath) return new Result(false,"Failed to upload.",null,400);

        // other data
        $ext   = pathinfo(config('subjectsystems.disks.local.root').'/'.$subjectPath, PATHINFO_EXTENSION);
        $size  = Storage::disk('local')->getSize($subjectPath);
        $type  = Storage::disk('local')->getMimetype($subjectPath);

        $response = [
            'original_name' => $subjectOriginalName,
            'path' => $subjectPath,
            'ext' => $ext,
            'size' => $size,
            'type' => $type,
        ];

        return new Result(true,'Upload success.',$response,200);
    }

}