<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        //Create user
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
    public function change_card_title_card(Request $request){
//        Change table name
        $request['table_name'] = preg_replace('/\D/', '', $request['table_name']);
        echo $request['table_name'];

        Task::where('id',$request['table_name'])->update(['task_name'=>$request['new_title']]);

    }

    public function user_remove_tab(Request $request){
        $request['id'] = preg_replace('/\D/', '', $request['id']);
        Task::destroy($request['id']);
        DB::table('options')->where('task_id',$request['id'])->delete();
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
        $task = Task::where('table_id', $request->id);
        return $task->with('options')->get();
    }
    public function user_grab_single_item(Request $request){
        return Option::where([['id', '=', $request->id]])->get();
    }

    public function user_add_task(Request $request){
        $new = new Task;
        $new->table_id = $request->id;
        $new->task_name = strval($request->list);
        $new->items = null;
        $new->save();

    }

    public function user_add_item(Request $request){
        $new = new Option;
        $new->name = $request->list;
        $new->status = 'Actief';
        $new->tasks = null;
        $new->label = null;
        $new->task_id = $request->id;
        $new->save();


    }
    public function user_update_options(Request $request){
        $data = $request->data[0];
        return Option::where('id', '=', $data['id'])
            ->update([
                'name' => $data['name'],
                'status' => $data['status'],
                'tasks' => $data['tasks'],
                'label' => $data['label'],
                'time' => $data['time'],
                'description' => $data['description']
            ]);
    }
    public function user_remove_options(Request $request){
        Option::where('id', $request->id)->delete();
    }
    public function user_remove_list(Request $request){
        Todo::where('id', $request->id)->delete();
    }
}
