<?php

namespace App\Http\Controllers;

use App\Models\MDLFolder;
use App\Models\MDLSaveFiles;
use App\Models\CourseSubtopik;
use App\Models\DimensionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MDLFolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($course_id)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

        // public function create(Request $request)
        // {
        //     $subTopicId = $request->query('sub_topic_id');

        //     // Ambil sub-topik spesifik berdasarkan sub_topic_id
        //     $subTopic = CourseSubtopik::findOrFail($subTopicId);
        //     $learningStyles = DimensionOption::all();
        //     $subTopics = CourseSubtopik::all();

        //     return view('folders.create', compact('learningStyles', 'subTopics','subTopic'));
        // }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'files.*' => 'nullable|file|max:10240', // Max 10MB per file
            ]);

            // Create a new folder
            $folder = MDLFolder::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'folder_path' => '', // Will be updated after saving files
                'learning_style_id' => $request->input('learning_style_id'), // Assuming this comes from the form
                'sub_topic_id' => $request->input('sub_topic_id'), // Assuming this comes from the form
            ]);

            // Handle multiple file uploads
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $folderPath = 'folders/' . $folder->id; // Store files in a folder named after the folder ID

                foreach ($files as $file) {
                    // Store the file in the storage
                    $filePath = $file->store($folderPath, 'public');

                    // Create a new record in the savefiles table
                    MDLSaveFiles::create([
                        'name' => $file->getClientOriginalName(),
                        'description' => $validated['description'], // Reusing folder description
                        'folder_id' => $folder->id,
                        'file_path' => $filePath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Update folder_path in mdl_folder table
                $folder->update(['folder_path' => $folderPath]);
            }

            return redirect()->route('folder.index')->with('success', 'Folder and files saved successfully!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MDLFolder  $mDLFolder
     * @return \Illuminate\Http\Response
     */
    public function show(MDLFolder $mDLFolder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MDLFolder  $mDLFolder
     * @return \Illuminate\Http\Response
     */
    public function edit(MDLFolder $mDLFolder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MDLFolder  $mDLFolder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MDLFolder $mDLFolder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MDLFolder  $mDLFolder
     * @return \Illuminate\Http\Response
     */
    public function destroy(MDLFolder $mDLFolder)
    {
        //
    }
}
