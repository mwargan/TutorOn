<?php
/**
 * Created by PhpStorm.
 * class: darryl
 * Date: 10/6/2017
 * Time: 6:15 AM
 */

namespace App\Http\Controllers\Admin;

use App\Components\Core\Result;
use App\Components\UserClass\Contracts\SubjectUsersRepository;
use Auth;
use Illuminate\Http\Request;

class subjectUsersController extends AdminController
{
    /**
     * @var classRepository
     */
    private $classRepository;

    /**
     * classController constructor.
     * @param classRepository $classRepository
     */
    public function __construct(subjectUsersRepository $subjectUsersRepository)
    {
        $this->subjectUsersRepository = $subjectUsersRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = $this->subjectUsersRepository->index(request()->all());

        return $this->sendResponse(
            $results->getMessage(),
            $results->getData()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = validator($request->all(),[
            'class_id' => 'required'
        ]);

        if($validate->fails())
        {
            return $this->sendResponse(
                $validate->errors()->first(),
                null,
                400
            );
        }

        $results = $this->subjectUsersRepository->create($request->all());

        return $this->sendResponse(
            $results->getMessage(),
            $results->getData()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $results = $this->classRepository->get($id);

        return $this->sendResponse(
            $results->getMessage(),
            $results->getData()
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = validator($request->all(),[
            't-data' => 'required'
        ]);

        if($validate->fails())
        {
            return $this->sendResponse(
                $validate->errors()->first(),
                null,
                400
            );
        }

        $results = $this->classRepository->update($id,$request->all());

        return $this->sendResponse(
            $results->getMessage(),
            $results->getData()
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // do not delete self
        if($id==Auth::user()->id)
        {
            return $this->sendResponse(
                Result::MESSAGE_FORBIDDEN,
                null,
                403
            );
        }

        $results = $this->classRepository->delete($id);

        return $this->sendResponse(
            $results->getMessage(),
            $results->getData(),
            $results->getStatusCode()
        );
    }
}