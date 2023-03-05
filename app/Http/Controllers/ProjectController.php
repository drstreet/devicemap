<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ParseCsv\Csv;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects()->paginate(10);

        return view('projects.index')->with('projects', $projects);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
                               'name' => 'required|string|max:255',
                               'first_device_id' => 'required|numeric',
                               'csv_file' => 'required|file|mimes:csv',
                               'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                               'x1' => 'required|numeric',
                               'y1' => 'required|numeric',
                               'x2' => 'required|numeric',
                               'y2' => 'required|numeric',
                           ]);

        $csv_file = $request->file('csv_file');
        $image = $request->file('image');

        $csv_file_name = time().'.'.$csv_file->extension();
        $image_name = time().'.'.$image->extension();

        $csv_file->move(storage_path('app/public/csv_files'), $csv_file_name);
        $image->move(storage_path('app/public/images'), $image_name);

        auth()->user()->projects()->create([
                                               'name' => $request->name,
                                               'device_id' => $request->first_device_id,
                                               'csv_file' => $csv_file_name,
                                               'background_image' => $image_name,
                                               'x1' => $request->x1,
                                               'y1' => $request->y1,
                                               'x2' => $request->x2,
                                               'y2' => $request->y2,
                                           ]);

        return redirect()->route('projects.index')->with('status', 'project-created');
    }
    public function show($id)
    {
        $project = auth()->user()->projects()->find($id);
        if (!$project) {
            return redirect()->route('projects.index')->with('status', 'project-not-found');
        }

        $csv = new Csv;
        $csv->delimiter = "\t";
        $csv->parse(storage_path('app/public/csv_files'.'/'.$project->csv_file));
        $rows = $csv->data;

        $data = array_map(function($row) use ($project) {
            $exploded = explode(",", implode(" ", $row));

            if ($exploded[3] == $project->device_id) {
                return [
                    'timestamp' => $exploded[0],
                    'id' => $exploded[3],
                    'x' => $exploded[4],
                    'y' => $exploded[5],
                    'x1' => $project->x1,
                    'y1' => $project->y1,
                    'x2' => $project->x2,
                    'y2' => $project->y2,
                ];
            }
        }, $rows);

        //$pixelDistance = sqrt(pow($project->x2 - $project->x1, 2) + pow($project->y2 - $project->y1, 2));
        $scale = hypot($project->x2 - $project->x1, $project->y2 - $project->y1);

        $data = array_filter($data); // remove null values

        return view('projects.show')->with([
                                               'project' => $project,
                                               'data' => $data,
                                               //'pixelDistance' => $pixelDistance,
                                               'scale' => $scale,
                                           ]);
    }

    public function destroy($id)
    {
        $project = auth()->user()->projects()->find($id);
        if (! $project) {
            return redirect()->route('projects.index')->with('status', 'project-not-found');
        }

        $project->delete();

        return redirect()->route('projects.index')->with('status', 'project-deleted');
    }
}
