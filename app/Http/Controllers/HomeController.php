<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use App\Models\Comment;

class HomeController extends Controller
{
    public function task()
    {
        $id = Auth::user()->id;
        $tasks = Task::where('created_by',$id)->orWhere('assigned_user',$id)->where('delete_task',0)->orderBy('id', 'DESC')->get();
        return view('task', compact('tasks'));
    }

    function create_task(Request $request)
    {
        $this->validate($request,[
            'title'=>'required',
            'description'=>'required'
        ]);

        $task = $request->except('_token');
        $task['created_by'] = Auth::user()->id;

        if($request->has('task_attachment'))
        {
            $image = $request->task_attachment;
            $image_name = 'T'.time().'_'.rand(111111111,999999999).'.'.$image->getClientOriginalExtension();
            $image_path = public_path('document/task');
            $image->move($image_path, $image_name);
            
            $task['task_attachment'] = "document/task/".$image_name;
        }

        Task::create($task);
        return redirect()->back()->with('saved', 'Task Created Successfully');
    }

    public function task_details($id)
    {
        $task = Task::with('comments')->where('id',$id)->where('delete_task',0)->first();
        if(!empty($task)){
            $users = User::all()->except(Auth::id());
        
            if($task->status == 1){
                $text_color = "text-info";
                $task_status = "In progress";
            }elseif($task->status == 2){
                $text_color = "text-success";
                $task_status = "Completed";
            }else{
                $text_color = "text-warning";
                $task_status = "Open";
            }
            return view('task_detail',compact('task','users','text_color','task_status'));
        }else{
            return redirect()->route('task')->with('error', 'Something Went wrong!');
        }
    }

    function user_and_status(Request $request, $id)
    {
        $data = $request->except('_token');
        Task::where('id',$id)->update($data);
        return redirect()->back()->with('saved', 'Task status or user updated successfully');
    }

    function post_comment(Request $request, $id)
    {
        $this->validate($request,[
            'comment_content'=>'required'
        ]);

        $comment = $request->only('comment_content');
        $comment['user_id'] = Auth::id();
        $comment['task_id'] = $id;

        if($request->has('attachment'))
        {
            $image = $request->attachment;
            $image_name = 'C'.time().'_'.rand(111111111,999999999).'.'.$image->getClientOriginalExtension();
            $image_path = public_path('document/comment');
            $image->move($image_path, $image_name);
            
            $comment['attachment'] = "document/comment/".$image_name;
        }
        
        Comment::create($comment);
        return redirect()->back()->with('saved', 'Your comment has been posted');
    }

    function delete_task($id)
    {
        $data['delete_task'] = 1;

        Task::where('id',$id)->update($data);
        return redirect()->route('task')->with('saved', 'Task deleted');
    }

    function update_task(Request $request, $id)
    {
        $task = $request->except('_token');

        if($request->has('task_attachment'))
        {
            $image = $request->task_attachment;
            $image_name = 'T'.time().'_'.rand(111111111,999999999).'.'.$image->getClientOriginalExtension();
            $image_path = public_path('document/task');
            $image->move($image_path, $image_name);
            
            $task['task_attachment'] = "document/task/".$image_name;
        }

        Task::where('id',$id)->update($task);
        return redirect()->back()->with('saved', 'Task Updated');
    }

    function edit_comment($id)
    {
        $comment = Comment::where('id',$id)->first();
        return view('edit_comment',compact('comment'));
    }

    function update_comment(Request $request, $id)
    {
        $comment = $request->only('comment_content');

        if($request->has('attachment'))
        {
            $image = $request->attachment;
            $image_name = 'C'.time().'_'.rand(111111111,999999999).'.'.$image->getClientOriginalExtension();
            $image_path = public_path('document/comment');
            $image->move($image_path, $image_name);
            
            $comment['attachment'] = "document/comment/".$image_name;
        }
        
        Comment::where('id',$id)->update($comment);
        return redirect()->back()->with('saved', 'Your comment has been updated');
    }

    function delete_comment($id)
    {
        $data['delete_comment'] = 1;

        Comment::where('id',$id)->update($data);
        return redirect()->back()->with('saved', 'Comment deleted');
    }
}
