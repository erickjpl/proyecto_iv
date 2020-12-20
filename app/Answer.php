<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * [insertExam description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function insertExam($request)
    {
        try {
            $query = DB::table('exam_answers')->insertGetId(
                [
                    'user_id' => $request["user_id"],
                    'course_id' => $request["course_id"],
                    'exam_id' => $request["exam_id"],
                    'created_at' => date("Y-m-d H:i:s"),
                ]
            );
            return $query;
        } catch (\Illuminate\Database\QueryException $ex) {
            Log::error('COD: ' . $ex->errorInfo[0] . ' ERROR: ' . $ex->errorInfo[2] . ' LINE: ' . $ex->getLine() . ' FILE: ' . $ex->getFile());
            return $this->returnOper(false, $ex->errorInfo[0]);
        }
    }

    /**
     * [insertAnswer description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function insertAnswer($request)
    {
        try {
            $query = DB::table('answers_rel')->insert(
                [
                    'question' => $request["question"],
                    'answer' => $request["answer"],
                    'exam_answer_id' => $request["exam_answer_id"],
                    'question_id' => $request["question_id"],
                ]
            );
            return true;
        } catch (\Illuminate\Database\QueryException $ex) {
            Log::error('COD: ' . $ex->errorInfo[0] . ' ERROR: ' . $ex->errorInfo[2] . ' LINE: ' . $ex->getLine() . ' FILE: ' . $ex->getFile());
            return $this->returnOper(false, $ex->errorInfo[0]);
        }
    }

    /**
     * [returnOper function que retorna la operacion insert,update,delete 
     * de la clase]
     * @param  [type] $oper      [true=operacion exitosa, false=error]
     * @param  [type] $cod_error [cod del error]
     * @return [json]         
     */
    function returnOper($bool, $cod_error = null)
    {
        $oper = array();
        $oper["oper"] = $bool;
        if (!empty($cod_error)) {
            $oper["error"] = 'COD: ' . $cod_error;
        }
        return $oper;
    }


    /**
     * [validExam description]
     * @param  [type] $course  [description]
     * @param  [type] $exam_id [description]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    function validExam($course, $exam_id, $user_id = null)
    {
        $query = DB::table('exam_answers');
        if (!empty($user_id)) {
            $query->where('exam_answers.user_id', $user_id);
        }
        $query->where('exam_answers.exam_id', $exam_id);
        $query->where('exam_answers.course_id', $course);
        $query->select('exam_answers.id');
        $data = $query->get();
        if (count($data) > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * [dataAnswers description]
     * @param  [type] $exam_id [description]
     * @param  [type] $user_id [description]
     * @param  [type] $course  [description]
     * @return [type]          [description]
     */
    function dataAnswer($exam_id, $user_id, $course)
    {
        $query = DB::table('exam_answers');
        $query->where('exam_answers.exam_id', $exam_id);
        $query->where('exam_answers.user_id', $user_id);
        $query->where('exam_answers.course_id', $course);
        $query->select('exam_answers.id', 'exam_answers.coment', 'exam_answers.qualification');
        $data = $query->get();
        return $data;
    }

    /**
     * [getAnswerRel description]
     * @param  [type] $answer_id [description]
     * @return [type]            [description]
     */
    function getAnswersRel($answer_id)
    {
        $query = DB::table('answers_rel');
        $query->where('answers_rel.exam_answer_id', $answer_id);
        $query->select('answers_rel.question', 'answers_rel.answer');
        $data = $query->get();
        return $data;
    }

    /**
     * [updateAnswer description]
     * @param  [type] $data [description]
     * @param  [type] $id   [description]
     * @return [type]       [description]
     */
    function updateAnswer($data, $id)
    {
        try {
            $update = DB::table('exam_answers');
            $update->where('id', $id);
            foreach ($data as $key => $value) {
                $update->update([$key => $value]);
            }
            return $this->returnOper(true);
        } catch (\Illuminate\Database\QueryException $ex) {
            Log::error('COD: ' . $ex->errorInfo[0] . ' ERROR: ' . $ex->errorInfo[2] . ' LINE: ' . $ex->getLine() . ' FILE: ' . $ex->getFile());
            return $this->returnOper(false, $ex->errorInfo[0]);
        }
    }

    /**
     * [listStudentCertif description]
     * @param  [type] $course [description]
     * @return [type]         [description]
     */
    function listStudentCertif($course)
    {
        $query = DB::table('exam_answers');

        $query->select('users.id', 'users.name', 'users.lastname', 'users.email', DB::raw("(SELECT COALESCE(COUNT(qualification), 0) from exam_answers WHERE user_id = users.id AND course_id = courses.id AND qualification = 'A') / COUNT(*) * 100 AS qualification"));
        $query->join('users', 'users.id', '=', 'exam_answers.user_id');
        $query->join('courses', 'courses.id', '=', 'exam_answers.course_id');
        $query->leftJoin('certificates', 'certificates.course_id', 'courses.id');
        $query->where('exam_answers.course_id', $course);
        $query->whereNull('certificates.course_id');
        $query->whereNull('certificates.user_id');
        $abc = $query->toSql();
        $data = $query->get()->groupBy('users.id');
        return $data;
    }
}

