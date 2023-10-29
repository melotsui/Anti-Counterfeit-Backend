<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\District;
use App\Models\SubDistrict;

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

        return parent::responseSuccess([
            'message' => 'Report created successfully',
            'report' => $report,
        ], 201);
    }

    public function show($id)
    {
        if (
            $report = Report::with('reportFiles')->select('reports.*', 'c.category_name', 'd.district_name', 'sd.sub_district_name')
                ->leftJoin('categories as c', 'reports.category_id', '=', 'c.category_id')
                ->leftJoin('districts as d', 'reports.district_id', '=', 'd.district_id')
                ->leftJoin('sub_districts as sd', 'reports.sub_district_id', '=', 'sd.sub_district_id')
                ->where('report_id', $id)->first()
        ) {
            return parent::responseSuccess([
                'report' => $report,
            ]);
        } else {
            return parent::responseError(500, 'Report not found');
        }
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
            $path = $file->storeAs('media/report/' . $mediaType, $filename, 'public');
            //php artisan storage:link
            //http://127.0.0.1:8000/storage/media/report/photo/a9c13927-a243-464d-b148-6c202b171ee2_17_1.jpg
            $file = new ReportFile();
            $file->report_id = $report_id;
            $file->file_name = $filename;
            $file->file_path = $path;
            $file->save();
        } catch (\Exception $e) {
            Storage::disk('public')->delete($path);
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

    //Search
    public function search(Request $request)
    {
        $reports = Report::select('reports.*', 'c.category_name', 'd.district_name', 'sd.sub_district_name')
            ->leftJoin('categories as c', 'reports.category_id', '=', 'c.category_id')
            ->leftJoin('districts as d', 'reports.district_id', '=', 'd.district_id')
            ->leftJoin('sub_districts as sd', 'reports.sub_district_id', '=', 'sd.sub_district_id')
            ->where('report_id', '>', 0);
        if ($request->product != null)
            $reports = $reports->where('product', 'like', '%' . $request->product . '%');
        if ($request->shop != null)
            $reports = $reports->where('shop', 'like', '%' . $request->shop . '%');
        if (
            $request->category_id != null && $request->category_id != 0
            && $request->category_id != '' && $request->category_id != '0'
        )
            $reports = $reports->where('reports.category_id', $request->category_id);
        if (
            $request->district_id != null && $request->district_id != 0
            && $request->district_id != '' && $request->district_id != '0'
        )
            $reports = $reports->where('reports.district_id', $request->district_id);
        if (
            $request->sub_district_id != null && $request->sub_district_id != 0
            && $request->sub_district_id != '' && $request->sub_district_id != '0'
        )
            $reports = $reports->where('reports.sub_district_id', $request->sub_district_id);
        $reports = $reports->get();

        return parent::responseSuccess(
            [
                'reports' => $reports,
            ],
            'File uploaded successfully'
        );
    }

    //History
    public function history(Request $request)
    {
        $user = auth()->user();
        $reports = Report::where('user_id', $user->user_id)->get();

        return parent::responseSuccess(
            [
                'reports' => $reports,
            ],
            'File uploaded successfully'
        );
    }
}