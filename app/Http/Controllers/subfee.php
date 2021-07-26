<?php

namespace App\Http\Controllers;

use App\Models\StudentModel;
use App\Models\SubFeeModel;
use Illuminate\Http\Request;

class subfee extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allstudents = StudentModel::join('classbk', 'student.idClass', '=', 'classbk.id')
            ->join('scholarship', 'scholarship.id', '=', 'student.idStudentShip')
            ->join('course', 'course.id', '=', 'classbk.idCourse')
            ->select('student.*', 'classbk.name as classname', 'scholarship.name as scholarship', 'course.name as course', 'course.id as idcorse')
            ->where('student.disable', '!=', '1')->get();
        return view('subfee.index',[
            'list' => $allstudents
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        SubFeeModel::insert([
            'idStudent' => $request->id,
            'note' => $request->note,
            'fee' => $request->fee,
            'accountant' => $request->session()->get('email'),
            'payer' => $request->nameStudent,
            'date' => date('Y-m-d'),
            'class_bk' => $request->classStudent,
            'countPay' => $request->count,
            'disable' => 0
        ]);
        return redirect(route('subfee.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = StudentModel::join('classbk', 'classbk.id','=','student.idClass')
        ->join('course','classbk.idCourse','=','course.id')
        ->join('major','classbk.idMajor','=','major.id')
        ->Select('student.*','classbk.name as nameclass','course.name as course','major.name as major')
        ->where('student.id',$id)
        ->first();
        
        $maxpayment = SubFeeModel::select('subfee.*')
        ->where('subfee.idStudent',$id)->max('id');
        $payment = SubFeeModel::select('subfee.*')
        ->where('id',$maxpayment)->first();
        return view('subfee.subfee',[
            'payment' => $payment,
            'info' => $info
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}