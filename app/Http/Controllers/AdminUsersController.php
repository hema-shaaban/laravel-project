<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use App\Photo;
use App\Role;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all() ;
        return view('admin.users.index' , compact('users')) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::lists('name' , 'id')->all() ;
        return view('admin.users.create' , compact('roles')) ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $user = $request->all() ;
        //User::create($user);

        $file = $request->file('photo_id') ;


        if ($file)
        {
            $name = time() . $file->getClientOriginalName() ;
            $file->move('images' , $name) ;
            $photo = Photo::create(['file'=>$name]) ;
            $user['photo_id'] = $photo->id ;

        }
        $user['password'] = bcrypt($request->password) ;
        User::create($user);

        return redirect('admin/users') ;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id) ;
        $roles = Role::lists('name' , 'id')->all() ;
        return view('admin.users.edit' , compact('user' ,'roles')) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserEditRequest $request, $id)
    {
        $user = User::find($id) ;
        if(trim($request->password == ''))
        {
            $input = $request->except('password') ;
        }
        else
        {
            $input = $request->all() ;
            $user['password'] = bcrypt($request->password) ;
        }




        $file = $request->file('photo_id') ;


        if ($file)
        {
            $name = time() . $file->getClientOriginalName() ;
            $file->move('images' , $name) ;
            $photo = Photo::create(['file'=>$name]) ;
            $input['photo_id'] = $photo->id ;

        }

        $user->update($input);

        return redirect('admin/users') ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete() ;
        return redirect('admin/users') ;
    }
}
