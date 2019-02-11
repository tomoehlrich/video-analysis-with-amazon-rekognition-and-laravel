<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Aws\Rekognition\RekognitionClient;

use App\Video;

class GetRekognitionResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rekognition:get-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the Rekognition video analysis results';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $videos = Video::where('analyzed', '<>', 1)
            ->orderBy('created_at', 'ASC')
            ->get();

        if($videos->isNotEmpty()) {

            $client = new RekognitionClient([
                'region' => env('AWS_DEFAULT_REGION', 'eu-west-1'),
                'version' => 'latest'
            ]);

            foreach ($videos as $srcVideo) {

                $result = $client->getLabelDetection([
                    'JobId' => $srcVideo->aws_job_id
                ]);

                $this->info('Checking video ' . $srcVideo->aws_job_id . ' ' . $srcVideo->original_name);

                if($result->get('JobStatus') == 'SUCCEEDED') {
                    $this->info('Video analysis results retrieved for ' . $srcVideo->aws_job_id . ' ' . $srcVideo->original_name);

                    $video = Video::find($srcVideo->id);
                    $video->results = $result->get('Labels');
                    $video->analyzed = 1;
                    $video->save();
                }
            }
        }

    }
}
