<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $reports = Report::all();
        return parent::responseSuccess(['reports' => $reports]);
    }
    public function store(Request $request)
    {
        $data = (object) $request->json()->all();
        $data->user_id = auth()->user()->user_id;
        $report = Report::create(get_object_vars($data));

        return response()->json([
            'message' => 'Report created successfully',
            'report' => $report,
        ], 201);
    }

    public function show($id)
    {
        $report = Report::with('reportFiles')->findOrFail($id);

        return response()->json([
            'report' => $report,
        ]);
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->update($request->all());

        return response()->json([
            'message' => 'Report updated successfully',
            'report' => $report,
        ]);
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json([
            'message' => 'Report deleted successfully',
        ]);
    }

    //Upload file
    public function uploadFile(Request $request)
    {
        if (!$request->hasFile('media')) {
            return parent::responseError(500, 'No media uploaded');
        }
        $file = $request->file('media');
        $mimeType = $file->getMimeType();
        if (str_starts_with($mimeType, 'image/')) {
            $mediaType = 'photo';
        } else {
            return parent::responseError(500, 'Invalid media type');
        }
        
        $user = auth()->user();
        $report_id = $request->report_id;
        if (!$report = Report::find($report_id)) {
            return parent::responseError(500, 'Invalid report id');
        }

        try {
            $filename = Str::uuid() . '_' . $user->user_id . '_' . $report_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media/report/' . $mediaType, $filename, 'local');

            $file = new ReportFile();
            $file->report_id = $report_id;
            $file->file_name = $filename;
            $file->file_path = $path;
            $file->save();
        } catch (\Exception $e) {
            Storage::disk('local')->delete($path);
            $this->errorCode = 5007; //Fail to upload media
            $this->errorMessage = sprintf(trans('errors.' . $this->errorCode));
            return parent::responseError($this->errorCode, $this->errorMessage, $e->getMessage());
        }
        return parent::responseSuccess(
            [
                'report' => $report,
                'file' => $file
            ],
            'File uploaded successfully'
        );
    }
}