@php
$sectionComponentsPath = 'pages/index/components/section-3/';
@endphp

<section class="section">
    <div class="container">
        <h2 class="title">КУРСИ</h2>
        <div class="courses">
            @include($sectionComponentsPath . 'course-1')
            @include($sectionComponentsPath . 'course-2')
            @include($sectionComponentsPath . 'course-3')
            @include($sectionComponentsPath . 'course-4')
        </div>
    </div>
</section>
