<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MDLSection;
use App\Models\MDLCourse;
use App\Helpers\LearningStyleHelper;

class TopikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToStyleContent($section_id)
    {
        $section = MDLSection::findOrFail($section_id);

        $course = MDLCourse::find($section->course_id);
        if (!$course) {
            abort(404, "Course dengan ID {$section->course_id} tidak ditemukan.");
        }

        // Ambil kombinasi gaya belajar user
        $styleCombination = LearningStyleHelper::getUserLearningStyleCombination(); // contoh hasil: reflective_intuitive_verbal_global

        if (!$styleCombination) {
            return redirect()->back()->with('error', 'Gaya belajar belum lengkap.');
        }

        // Ubah gaya belajar ke slug format route, misal: acsensvisseq, refintverglob, dll
        $styleSlug = LearningStyleHelper::getStyleSlug($styleCombination); // misalnya return "acsensvisseq"

        // Redirect ke route yang kamu definisikan
        return redirect()->route($styleSlug, [
            'course_id' => $course->id,
            'topik' => $section->id,
            'section_id' => $section->id
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

    public function showtopikmahasiwa($course_id, $section_id)
    {
        $course = MDLCourse::where('id', $course_id)->firstOrFail();
        // $section = MDLSection::with(['sub_topic', 'referensi'])
        //     ->where('id', $section_id)
        //     ->where('course_id', $course_id)
        //     ->firstOrFail();
            $section = MDLSection::with(['sub_topic.labels', 'sub_topic.pages','sub_topic.quizs','referensi'])
    ->where('id', $section_id)
    ->where('course_id', $course_id)
    ->firstOrFail();
        $sections = MDLSection::where('course_id', $course_id)
            ->where('visible', 1)
            ->orderBy('sort_order', 'asc') // Urutkan berdasarkan sort_order
            ->orderBy('id', 'asc') // Tiebreaker jika sort_order sama
            ->get();
        $indeks = $sections->search(function ($item) use ($section) {
                return $item->id == $section->id;
            }) + 1;

        $data = [
            'menu' => 'menu.v_menu_admin',
            'content' => 'course.sectionShowMahasiswa',
            'indeks' =>$indeks,
            'title' => $section->title,
            'course' => $course,
            'section' => $section,
        ];

        return view('layouts.v_template', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
