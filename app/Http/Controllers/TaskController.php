<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //
    public function getAll(Request $r) {

        // $tasks = Task::all();
        // $data['tasks'] = $tasks;

        $limit = $r->query('limit');
        $tarefas = DB::table('tasks')
            ->select('tasks.*',
                'categories.title as categoryTitle',
                'categories.color as categoryColor'
            )
            ->where('tasks.user_id', '=', $r->id)
            ->leftJoin('categories', 'categories.id', 'tasks.category_id')
            ->paginate($limit);
        $array['result'] = $tarefas->items();
        $array['current_page'] = $tarefas->currentPage();
        $array['total'] = $tarefas->total();

        return $array;
    }



    public function getOne(Request $r) {
        $tasks = Task::find($r->id);
        if(!$tasks) {
            return $array['error'] =  'Tarefa não encontrada !';
        }
        $tasks['user'] = $tasks->user;
        $tasks['category'] = $tasks->category;
        return $tasks;
    }

    public function create(Request $r) {
        $validator = Validator::make($r->only(['title','due_date','description','category_id']), [
            'title' => 'required',
            'due_date' => 'required',
            'description' => 'required',
            'category_id' => 'required',
        ]);

        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $task = $r->only(['title','description','due_date','user_id','category_id']);
        $user = Task::create($task);
        return $array['result'] = $user;
    }

    public function edit(Request $r) {

        $request_data = $r->only(['title','due_date','category_id','description']);

        $task = Task::find($r->id);
        if(!$task) {
            return $array['error'] = 'Erro de task nao existente';
        }
        $task->update($request_data);
        $task->save();
        return;
    }

    public function delete(Request $r) {
        $task = Task::find($r->id);

        if(!$task) {
            return 'Erro, Tarefa não encontrada!';
        }

        $task->delete();

        return 'Tarefa deletada com sucesso!';


    }

    public function check(Request $r) {
        $task = Task::find($r->id);

        if(!$task) {
            return 'Erro, Tarefa não encontrada!';
        }
        $isdone = $task->is_done;
        $task->is_done = !$isdone;
        $task->save();
        return !$isdone;
    }


}



// $limit = $r->query('limit');
// $tarefas = DB::table('tasks')
//     ->select('tasks.*',
//         'categories.title as categoryTitle',
//         'categories.color as categoryColor'
//     )
//     ->where('id', '=', '1')
//     ->leftJoin('categories', 'categories.id', 'tasks.category_id')
//     ->paginate($limit);
// $array['result'] = $tarefas->items();
// $array['current_page'] = $tarefas->currentPage();
// $array['total'] = $tarefas->total();
