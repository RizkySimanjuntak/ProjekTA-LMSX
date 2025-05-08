<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">

        
        <!-- Informasi Course -->
        <div class="card mb-4">
            <div class="card-header">
                <h1>{{ $course->full_name }}</h1>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $course->summary }}</p>
                <p class="card-text">{{ $course->cpmk }}</p>
            </div>
        </div>

        <!-- Informasi Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ $section->title }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $section->description }}</p>
                <p class="card-text">{{ $section->sub_cpmk }}</p>
            </div>
        </div>

        <!-- Sub Topics -->
        @if($section->sub_topic->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    Sub Materi
                </div>
                <div class="card-body">
                    @include('components.mode_konstruksi')
                    <ul class="list-group">
                        @foreach($section->sub_topic as $subTopic)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">

                                    <h6 class="mb-0">{{ $subTopic->title }}</h6>
                                    <div class="lom-dropdown-container" style="display: none;">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" 
                                                    id="lomDropdown{{ $subTopic->id }}" 
                                                    data-bs-toggle="dropdown" 
                                                    aria-expanded="false">
                                                Gaya Belajar
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="lomDropdown{{ $subTopic->id }}">
                                                <li><h6 class="dropdown-header">Dimensi Masukan</h6></li>
                                                <li><a class="dropdown-item" href="#" data-lom-type="visual">Visual</a></li>
                                                <li><a class="dropdown-item" href="#" data-lom-type="verbal">Verbal</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><h6 class="dropdown-header">Dimensi Persepsi</h6></li>
                                                <li><a class="dropdown-item" href="#" data-lom-type="sensitif">Sensitif</a></li>
                                                <li><a class="dropdown-item" href="#" data-lom-type="intuitif">Intuitif</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><h6 class="dropdown-header">Dimensi Pemrosesan</h6></li>
                                                <li><a class="dropdown-item" href="#" data-lom-type="aktif">Aktif</a></li>
                                                <li><a class="dropdown-item" href="#" data-lom-type="reflektif">Reflektif</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">{{ $subTopic->description }}</div>
                                <div class="card-body">{{ $subTopic->content }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Referensi -->
        @if($section->referensi->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    References
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($section->referensi as $referensi)
                            <li class="list-group-item">
                                <p class="mt-2">{{ $referensi->content }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="mt-4">
            <a href="{{ route('courses.topics', $course->id) }}" class="btn btn-secondary">Back to Course</a>
            @can('update', $section)
                <a href="{{ route('sections.edit', [$course->id, $section->id]) }}" class="btn btn-primary">Edit Section</a>
            @endcan
        </div>
    </div>
</div>