<h1>{{ $announcement->title }}</h1>
<p>Hello,</p>
<p>{{ $announcement->body }}</p>
<p>Course: {{ $announcement->courseClass->course_title }} ({{ $announcement->courseClass->course_code }})</p>
