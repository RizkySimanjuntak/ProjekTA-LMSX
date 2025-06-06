<?php

namespace App\Http\Controllers;

use App\Models\MDLAssign;
use App\Models\MDLCourse;
use App\Models\MDLFiles;
use App\Models\MDLFolder;
use App\Models\MDLForum;
use App\Models\MDLIntuitive;
use App\Models\MDLPage;
use App\Models\MDLQuiz;
use App\Models\MDLQuizAttempts;
use App\Models\MDLQuizGrades;
use App\Models\MDLReflective;
use App\Models\MDLSection;
use App\Models\MDLSensing;
use App\Models\MDLSequential;
use App\Models\MDLUserLearningStyles;
use App\Models\MDLVisual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReflectiveIntuitiveVisualSequentialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($course_id, $topik, $section_id)
    {
        $user = Auth::user();

        $course = MDLCourse::find($course_id);
        if (!$course) {
            abort(404, "Course dengan ID $course_id tidak ditemukan.");
        }

        // Ambil data section
        $section = MDLSection::find($section_id);
        if (!$section) {
            abort(404, "Section dengan ID $section_id tidak ditemukan.");
        }

        $topikKey = 'topik' . $topik;

        $sensingMaterials = MDLSensing::where('course_id', $course->id)
            ->where('section_id', $section_id)
            ->get();

        // Gaya Belajar Reflective
        $reflectiveFiles = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'reflective')
            ->where('topik', $topikKey)
            ->whereIn('type', ['reflective-pdf', 'reflective-link'])
            ->get();

        $imageFiles = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'reflective')
            ->where('topik', $topikKey)
            ->where('type', 'reflective-image')
            ->get();

        $videoFiles = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'reflective')
            ->where('topik', $topikKey)
            ->where('type', 'reflective-video')
            ->get();

        $assignments = MDLAssign::where('course_id', $course->id)
            ->where('learning_style', 'reflective')
            ->where('topik', $topikKey)
            ->get();

        $forum = MDLForum::where('course_id', $course->id)
            ->where('learning_style', 'reflective')
            ->where('topik', $topikKey)
            ->with(['posts.user'])->get();

        $quiz = MDLQuiz::where('course_id', $course->id)
            ->where('learning_style', 'reflective')
            ->where('topik', $topikKey)
            ->first();

        $pageFilesReflective = MDLPage::where('course_id', $course->id)
            ->where('learning_style', 'reflective')
            ->where('topik', $topikKey)
            ->where('type', 'like', 'reflective-%')
            ->get();

        // Gaya Belajar Intuitive
        $intuitiveMaterial = MDLIntuitive::where('course_id', $course->id)
            ->where('section_id', $section_id)
            ->get();

        $intuitiveFiles = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'intuitive')
            ->where('topik', $topikKey)
            ->whereIn('type', ['intuitive-pdf', 'intuitive-link'])
            ->get();

        $intuitiveImage = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'intuitive')
            ->where('topik', $topikKey)
            ->where('type', 'intuitive-image')
            ->get();

        $intuitiveVideo = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'intuitive')
            ->where('topik', $topikKey)
            ->where('type', 'intuitive-video')
            ->get();

        $intuitiveAssignments = MDLAssign::where('course_id', $course->id)
            ->where('learning_style', 'intuitive')
            ->where('topik', $topikKey)
            ->get();

        $intuitiveForum = MDLForum::where('course_id', $course->id)
            ->where('learning_style', 'intuitive')
            ->where('topik', $topikKey)
            ->with(['posts.user'])->get();

        $intuitiveQuiz = MDLQuiz::where('course_id', $course->id)
            ->where('learning_style', 'intuitive')
            ->where('topik', $topikKey)
            ->get();

        $pageFilesIntuitive = MDLPage::where('course_id', $course->id)
            ->where('learning_style', 'intuitive')
            ->where('topik', $topikKey)
            ->where('type', 'like', 'intuitive-%')
            ->get();

        // Gaya Belajar Visual
        $visualMaterial = MDLVisual::where('course_id', $course_id)->first();

        $visualPdfs = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'visual')
            ->where('topik', $topikKey)
            ->whereIn('type', ['visual-pdf', 'visual-link'])
            ->get();

        $visualImage = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'visual')
            ->where('topik', $topikKey)
            ->where('type', 'visual-image')
            ->get();

        $youtubeVideos = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'visual')
            ->where('topik', $topikKey)
            ->where('type', 'visual-video')
            ->get();

        $visualAssignments = MDLAssign::where('course_id', $course->id)
            ->where('learning_style', 'visual')
            ->where('topik', $topikKey)
            ->get();

        $visualForum = MDLForum::where('course_id', $course->id)
            ->where('learning_style', 'visual')
            ->where('topik', $topikKey)
            ->with(['posts.user'])->get();

        $visualQuiz = MDLQuiz::where('course_id', $course->id)
            ->where('learning_style', 'visual')
            ->where('topik', $topikKey)
            ->get();

        $pageFilesVisual = MDLPage::where('course_id', $course->id)
            ->where('learning_style', 'visual')
            ->where('topik', $topikKey)
            ->where('type', 'like', 'visual-%')
            ->get();

        // Gaya Belajar Sequential
        $sequentialMaterial = MDLSequential::where('course_id', $course_id)->first();

        $sequentialPdfs = MDLFolder::where('course_id', $course_id)
            ->where('learning_style', 'sequential')
            ->where('topik', $topikKey)
            ->get(); // jika ada topik tertentu bisa difilter juga

        $sequentialImage = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'sequential')
            ->where('topik', $topikKey)
            ->where('type', 'sequential-image')
            ->get();

        $sequentialVideo = MDLFiles::where('course_id', $course->id)
            ->where('learning_style', 'sequential')
            ->where('topik', $topikKey)
            ->where('type', 'sequential-video')
            ->get();

        $sequentialAssignments = MDLAssign::where('course_id', $course->id)
            ->where('learning_style', 'sequential')
            ->where('topik', $topikKey)
            ->get();

        $sequentialForum = MDLForum::where('course_id', $course->id)
            ->where('learning_style', 'sequential')
            ->where('topik', $topikKey)
            ->with(['posts.user'])->get();

        $sequentialQuiz = MDLQuiz::where('course_id', $course_id)
            ->where('learning_style', 'sequential')
            ->where('topik', $topikKey)
            ->first();

        $pageFilesSequential = MDLPage::where('course_id', $course->id)
            ->where('learning_style', 'sequential')
            ->where('topik', $topikKey)
            ->where('type', 'like', 'sequential-%')
            ->get();

        $quiz_attempt = null;
        $quiz_grade = null;

        if ($sequentialQuiz) {
            $quiz_attempt = MDLQuizAttempts::where('quiz_id', $quiz->id)
                ->where('user_id', Auth::id())
                ->latest()
                ->first();

            $quiz_grade = MDLQuizGrades::where('quiz_id', $quiz->id)
                ->where('user_id', Auth::id())
                ->latest()
                ->first();
        }

        // Ambil data gaya belajar berdasarkan final_score dan category
        $learningStyles = MDLUserLearningStyles::where('user_id', $user->id)->get();

        $dimensionLabels = [
            'ACT/REF' => ['Active', 'Reflective'],
            'SNS/INT' => ['Sensing', 'Intuitive'],
            'VIS/VRB' => ['Visual', 'Verbal'],
            'SEQ/GLO' => ['Sequential', 'Global'],
        ];

        $scores = [];

        foreach ($learningStyles as $style) {
            $dimension = $style->dimension;

            // Ambil nama gaya dari final_score, contoh: "5Active" -> "Active"
            preg_match('/[A-Za-z]+$/', $style->final_score, $matches);
            $label = $matches[0] ?? $dimensionLabels[$dimension][0];

            $scores[$dimension] = [
                'label' => $label,
                'category' => $style->category,
            ];
        }

        $descriptions = [
            'ACT/REF' => [
                'Reflective' => [
                    'Balanced' => 'Anda seimbang dalam belajar mandiri dan diskusi',
                    'Moderate' => 'Anda cukup suka belajar melalui refleksi dan berpikir',
                    'Strong' => 'Anda sangat suka belajar mandiri melalui refleksi sebelum bertindak',
                ],
            ],
            'SNS/INT' => [
                'Intuitive' => [
                    'Balanced' => 'Anda seimbang antara memahami fakta konkret dan teori abstrak',
                    'Moderate' => 'Anda cukup nyaman dengan konsep abstrak dan ide-ide',
                    'Strong' => 'Anda sangat suka belajar melalui konsep abstrak dan teori',
                ],
            ],
            'VIS/VRB' => [
                'Visual' => [
                    'Balanced' => 'Anda seimbang antara memahami informasi visual dan verbal',
                    'Moderate' => 'Anda cukup nyaman memahami melalui gambar atau ilustrasi',
                    'Strong' => 'Anda sangat nyaman belajar dengan melihat gambar, diagram, atau video',
                ],
            ],
            'SEQ/GLO' => [
                'Sequential' => [
                    'Balanced' => 'Anda seimbang dalam memahami langkah-langkah terperinci dan gambaran besar',
                    'Moderate' => 'Anda cukup nyaman dengan langkah-langkah terperinci sebelum memahami gambaran besar',
                    'Strong' => 'Anda sangat suka memahami langkah-langkah terperinci secara berurutan sebelum melihat gambaran besar',
                ],
            ],
        ];

        return view('layouts.v_template', [
            'menu' => 'menu.v_menu_admin',
            'title' => $course->full_name,
            'content' => "learning_styles_combined.topik$topik.reflective_intuitive_visual_sequential.index",
            'course' => $course,
            'section' => $section,
            'sensing_materials' => $sensingMaterials,
            'scores'     => $scores,
            'descriptions' => $descriptions,

            // Reflective
            'reflective_files' => $reflectiveFiles,
            'reflective_images' => $imageFiles,
            'reflective_videos' => $videoFiles,
            'assignments' => $assignments,
            'forums' => $forum,
            'quiz' => $quiz,
            'page_files_reflective' => $pageFilesReflective,

            // Intuitive
            'intuitive_materials' => $intuitiveMaterial,
            'intuitive_files' => $intuitiveFiles,
            'intuitive_image' => $intuitiveImage,
            'intuitive_video' => $intuitiveVideo,
            'intuitive_assignments' => $intuitiveAssignments,
            'intuitive_forums' => $intuitiveForum,
            'quiz_intuitives' => $intuitiveQuiz,
            'page_files_intuitive' => $pageFilesIntuitive,

            // Visual
            'visual_material' => $visualMaterial,
            'youtube_videos' => $youtubeVideos,
            'visual_image' => $visualImage,
            'visual_pdfs' => $visualPdfs,
            'visual_assignment' => $visualAssignments,
            'visual_forums' => $visualForum,
            'quiz_visuals' => $visualQuiz,
            'page_files_visual' => $pageFilesVisual,

            // Sequential
            'sequential_material' => $sequentialMaterial,
            'sequential_pdfs' => $sequentialPdfs,
            'sequential_video' => $sequentialVideo,
            'sequential_image' => $sequentialImage,
            'sequential_assignment' => $sequentialAssignments,
            'sequential_forums' => $sequentialForum,
            'quiz_sequential' => $sequentialQuiz,
            'quiz_attempt' => $quiz_attempt,
            'quiz_grade' => $quiz_grade,
            'page_files_sequential' => $pageFilesSequential,
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
