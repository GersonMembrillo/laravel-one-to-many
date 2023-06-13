<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Type;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        $types = Type::all();
        $projects = Project::paginate(4);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     *
     */
    public function create()
    {
        $types = Type::all();
        return view('admin.projects.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $slug = Str::slug($request->title, '-');
        $data['slug'] = $slug;
        // $data['type_id'] = Auth::id();
        if ($request->hasFile('image')) {
            $image_path = Storage::put('uploads', $request->image);
            $data['image'] = asset('storage/' . $image_path);
        }


        //se scrivo singoli dati sulla create.blade.php:
        // $project = new Project();
        // $project->title = $data['title'];
        // $project->image = $data['image'];
        // $project->description = $data['description'];
        // $$project->save();

        // se metti $fillable nel model (si usa se ci sono molti dati):
        // $project->fill($data)
        // $$project->save();

        //metodo più pulito
        $project = Project::create($data);

        return redirect()->route('admin.projects.show', $project->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     */
    public function show(Project $project)
    {

        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     *
     */
    public function edit(Project $project)
    {

        // if (!Auth::user()->is_admin && $project->type_id !== Auth::id()) {
        //     abort(403);
        // }
        $types = Type::all();
        return view('admin.projects.edit', compact('project', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $slug = Str::slug($request->title, '-');
        $data['slug'] = $slug;
        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::delete($project->image);
            }
            $image_path = Storage::put('uploads', $request->image);
            $data['image'] = asset('storage/' . $image_path);
        }
        $project->update($data);
        return redirect()->route('admin.projects.show', $project->slug)->with('message', "Project is successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     */
    public function destroy(Project $project)
    {
        if ($project->image) {
            $toRemove = "http://127.0.0.1:8000/storage/";
            $imagetoremove = str_replace($toRemove, '', $project->image);
            //dd($imagetoremove);
            Storage::delete($imagetoremove);
        }
        $project->delete();
        return redirect()->route('admin.projects.index')->with('message', "$project->title successfully deleted!");
    }
}
