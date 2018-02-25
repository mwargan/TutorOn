<?php
/**
 * Created by PhpStorm.
 * subject: darryl
 * Date: 10/6/2017
 * Time: 6:15 AM
 */

namespace App\Http\Controllers\Admin;

use App\Components\Core\Result;
use App\Components\subject\Contracts\userSubjectRepository;
use Auth;
use Illuminate\Http\Request;

class userSubjectController extends AdminController
{
    /**
     * @var subjectRepository
     */
    private $subjectRepository;

    /**
     * subjectController constructor.
     * @param subjectRepository $subjectRepository
     */
    public function __construct(userSubjectRepository $userSubjectRepository)
    {
        $this->userSubjectRepository = $userSubjectRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = $this->userSubjectRepository->index(request()->all());

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
            'description' => 'required',
            'price' => 'required',
            'subject_id' => 'required'
        ]);

        if($validate->fails())
        {
            return $this->sendResponse(
                $validate->errors()->first(),
                null,
                400
            );
        }

        $results = $this->userSubjectRepository->create($request->all());

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
        $results = $this->subjectRepository->get($id);

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

        $results = $this->subjectRepository->update($id,$request->all());

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

        $results = $this->subjectRepository->delete($id);

        return $this->sendResponse(
            $results->getMessage(),
            $results->getData(),
            $results->getStatusCode()
        );
    }
}