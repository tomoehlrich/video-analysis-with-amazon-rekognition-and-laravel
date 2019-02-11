<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Aws\Rekognition\RekognitionClient;

use App\Video;

class RekognitionController extends Controller
{


    /**
     * Show the list of uploaded videos
     *
     * @return View
     */
    public function index() {

        $videos = Video::orderBy('created_at', 'desc')->get();

        return view('index', [
            'videos' => $videos
        ]);
    }


    /**
     * Shows the analysis results for a video
     *
     * @param  Request  $request
     * @return View
     */
    public function results($id) {

        $video = Video::find($id);

        return view('results', [
            'video' => $video
        ]);
    }


    /**
     * Show the upload form
     *
     * @return View
     */
    public function upload() {
        return view('upload');
    }


    /**
     * Upload a video to S3 and store info in local DB
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        $request->validate([
            'file' => 'required|file|max:5120|mimes:mp4',
        ]);

        $originalFilename = $request->file->getClientOriginalName();
        $fileExtension = $request->file->getClientOriginalExtension();
        $uniqueFilename = str_random(32) . '.' . $fileExtension;

        $path = $request->file->storeAs('', $uniqueFilename, 's3');

        $client = new RekognitionClient([
            'region' => env('AWS_DEFAULT_REGION', 'eu-west-1'),
            'version' => 'latest'
        ]);

        $result = $client->startLabelDetection([
            'ClientRequestToken' => str_random(),
            'JobTag' => 'rekognition-test',
            'MinConfidence' => 50,
            'NotificationChannel' => [
                'RoleArn' => env('AWS_IAM_ROLE_ARN'),
                'SNSTopicArn' => env('AWS_SNS_TOPIC_ARN'),
            ],
            'Video' => [
                'S3Object' => [
                    'Bucket' => env('AWS_BUCKET'),
                    'Name' => $uniqueFilename
                ],
            ],
        ]);


        $video = new Video;
        $video->name = $uniqueFilename;
        $video->original_name = $originalFilename;
        $video->aws_job_id = $result->get('JobId');
        $video->save();

        // dd($result);

        return back()
            ->with('success','Video has been successfully uploaded');
    }
}
