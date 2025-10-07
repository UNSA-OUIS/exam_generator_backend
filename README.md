# test

php artisan migrate:refresh --seed
select id from confinements;
php artisan questions:import /home/edwin/test-questions/questions.json /home/edwin/test-questions/resolutions /home/edwin/test-questions/images xxxxxxxxxxxxxxxx
select id from exams;
php artisan tinker
$controller = app()->make(App\Http\Controllers\MasterController::class);
app()->call([$controller,'generate'],['examId' => '0199a621-582c-7028-87a2-ed17eef85e6a', 'area' => 'SOCIALES']);
