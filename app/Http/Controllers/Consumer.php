<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Consumer extends Controller
{
    public function user_Login(Request $request){
        //Validate request
        $field = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string'
        ]);
        //Check if entry exists
        $user = User::where('name', $field['name'])->first();

        //If entry doesn't exist, return false
        if(!$user || !Hash::check($field['password'], $user->password)){
            //Return false
            return response([
                'message' => 'Gegevens kloppen niet!'
            ],401);
        }

        //Create token
        $token = $user->createToken('web')->plainTextToken;

        //Return data
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function user_register(Request $request){
        //Validate login fields
        $field = $request->validate([
            'name' => 'required|string|unique:users,name',
            'password' => 'required|string'
        ]);

        //Create user table
        $user = User::create([
            'name' => $field['name'], 'role' => 2,
            'password' => bcrypt($field['password'])
        ]);

        //Create token
        $token = $user->createToken('web')->plainTextToken;

        //Return data
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function user_create_list(Request $request){
        $field = $request->validate([
            'table_name' => 'required|string|unique:todos,table_name|unique:todos,user_id',
        ]);

        Todo::create([
            'table_name' => $field['table_name'],
            'user_id' => auth()->user()->id,
            'table_creation' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function user_grab_list(){
        return Todo::where([['user_id', '=', auth()->user()->id]])->leftJoin('tasks', 'tasks.table_id', '=', 'todos.id')->leftJoin('users', 'users.id', '=', 'todos.user_id')->get(['todos.table_name','tasks.table_id', 'tasks.task_name', 'todos.table_creation', 'users.name as username', 'tasks.id as id','todos.id as todo_id', 'users.id as username_id']);
    }
    public function user_grab_tasks(Request $request){
        return Todo::where([['user_id', '=', auth()->user()->id], ['table_id', '=', $request->id]])->join('tasks', 'tasks.table_id', '=', 'todos.id')->join('options', 'options.task_id', '=', 'tasks.id')->get(['tasks.tasK_name', 'options.*']);
    }

    public function user_add_task(Request $request){


//        $tasks = Task::leftJoin('todos', 'todos.id', '=', 'tasks.table_id')->where([['table_id', '=', $request->id], ['user_id', '=', auth()->user()->id]])->select('tasks.id', 'todos.id as todo_id')->get();;
//        $array = json_decode(json_encode($tasks), true);
//        $last_task = 1;
//        if($tasks){
//            $array = json_decode(json_encode($tasks), true);
//            $last_task = (end($array));
//        }
;
        $new = new Task;
        $new->table_id = $request->id;
        $new->task_name = strval($request->list);
        $new->items = null;
        $new->save();

    }

    public function user_add_item(Request $request){
        return Option::where([['task_id', '=', $request->id]])->get();
//        $items = $items->reject(null);
//        $object = (object)[
//            'id' => 5,
//            'name' => $request->name,
//            'status' => true,
//            'tasks' => [],
//            'label' => []
//        ];
    }
}
