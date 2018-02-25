<?php
/**
 * Created by PhpStorm.
 * User: darryl
 * Date: 10/9/2017
 * Time: 10:02 PM
 */

namespace App\Components\UserClass\Repositories;

use App\Notifications\NewStudent;
use Illuminate\Http\Request;

use App\Components\Core\Result;
use App\Components\Core\Utilities\Helpers;
use App\Components\UserClass\Contracts\SubjectUsersRepository;
use App\Components\UserClass\Models\UserClasses;
use Grimzy\LaravelMysqlSpatial\Types\Point;

use Auth;

class MySQLSubjectUsersRepository implements SubjectUsersRepository
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
        $groupIds = explode(',',Helpers::hasValue($params['userclasses_group_id'],''));
        $orderBy = Helpers::hasValue($params['order_by'],'id');
        $orderSort = Helpers::hasValue($params['order_sort'],'desc');
        $name = Helpers::hasValue($params['name'],null);
        $me = Helpers::hasValue($params['me'],null);
        $paginate = Helpers::hasValue($params['paginate'],'yes');
        $perPage = Helpers::hasValue($params['per_page'],10);

        $q = UserClasses::orderBy($orderBy,$orderSort)->with('class.subject', 'class.user.profilePicture');

        if($name) $q = $q->where('t-data', '=', "{$name}");
        if($me) $q = $q->where('user_id', '=', Auth::user()->id);

        if(count($groupIds) > 0 && !empty($groupIds[0])) $q = $q->whereIn('userclasses_group_id',$groupIds);

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
        $Userclasses = UserClasses::create([
            'class_id' => preg_replace('/[^A-Za-z0-9\-]/', ' ', $data['class_id']),
            'user_id' => Auth::user()->id
        ]);

        if(!$Userclasses) return new Result(false,Result::MESSAGE_FAILED_CREATE,null,400);
            $user = UserClasses::find($Userclasses['id'])->class->user;
            $user->notify(new NewStudent());

            // add the group
        foreach ($data['days'] as $result)
        {
            if($result) $Userclasses->days()->create([
                'weekday_id' => $result,
                'meeting_time' => $data['meeting-time'],
                'lesson_duration' => $data['hours-per-lesson'],
                'updated_by' => Auth::user()->id
            ]);
        }
        if($data['location']) {
            $locality = 'App\Locality'::firstOrCreate(['name' => $data['location']['locality']]);
           if(isset($data['location']['postal_code'])) {
            $postal = 'App\PostalCode'::firstOrCreate(['postal_code' => $data['location']['postal_code']], ['locality_id' => $locality->id]);
           }
            $Userclasses->location()->create([
                'location' => new Point($data['location']['lat'], $data['location']['lng']),
                'locality_id' => (isset($locality) ? $locality->id : null),
                'postal_code_id' => (isset($postal) ? $postal->id : null),
                'updated_by' => Auth::user()->id
            ]);
        }
        return new Result(true,Result::MESSAGE_SUCCESS_CREATE,$Userclasses,201);
    }

    /**
     * get resource by id
     *
     * @param $id
     * @return Result
     */
    public function get($id)
    {
        $Userclasses = Userclasses::find($id);

        if(!$Userclasses) return new Result(false,Result::MESSAGE_NOT_FOUND,null,404);

        return new Result(true,Result::MESSAGE_SUCCESS,$Userclasses,200);
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
        $Userclasses = Userclasses::find($id);

        if(!$Userclasses) return new Result(false,Result::MESSAGE_NOT_FOUND,null,404);

        $Userclasses['t-data'] = $data['t-data'];

        if(!$Userclasses->save()) return new Result(false,Result::MESSAGE_FAILED_UPDATE,null,400);

        return new Result(true,Result::MESSAGE_SUCCESS_UPDATE,$Userclasses,201);
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
            $Userclasses = Userclasses::find($id);

            if(!$Userclasses) return new Result(false,"Failed to delete resource with id: {$id}. Error: ".Result::MESSAGE_NOT_FOUND,null,404);

            // delete userclasses record
            $Userclasses->delete();

            // delete actual userclasses
            $this->deleteUserclasses($Userclasses->path);
        }

        return new Result(true,Result::MESSAGE_SUCCESS,$Userclasses,200);
    }

    /**
     * @param \Illuminate\Http\UploadedUserclasses $userclasses
     * @return Result
     */
    public function upload($userclasses)
    {
        $userclassesOriginalName = $userclasses->getClientOriginalName();
        $userclassesPath = $userclasses->store('local');

        if(!$userclassesPath) return new Result(false,"Failed to upload.",null,400);

        // other data
        $ext   = pathinfo(config('userclassessystems.disks.local.root').'/'.$userclassesPath, PATHINFO_EXTENSION);
        $size  = Storage::disk('local')->getSize($userclassesPath);
        $type  = Storage::disk('local')->getMimetype($userclassesPath);

        $response = [
            'original_name' => $userclassesOriginalName,
            'path' => $userclassesPath,
            'ext' => $ext,
            'size' => $size,
            'type' => $type,
        ];

        return new Result(true,'Upload success.',$response,200);
    }

}