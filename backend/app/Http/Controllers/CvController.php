<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cv;
use App\Models\CvTemplate;
use Spatie\Browsershot\Browsershot;

class CvController extends Controller
{
    /*public function templates()
    {
        $templates = CvTemplate::where('is_active', true)->get()
            ->get()
            ->makeHidden('name');

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }*/

    public function templates()
{
    $templates = CvTemplate::where('is_active', true)->get();

    $data = $templates->map(function ($template) {
        return [
            'id' => $template->id,
            'slug' => $template->slug,
            'blade_view' => $template->blade_view,
            'preview_image' => $template->preview_image,
            'description' => $template->description,
            'is_active' => $template->is_active,
            'created_at' => $template->created_at,
            'updated_at' => $template->updated_at,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:cv_templates,id',
            'data' => 'required|array',
        ]);

        $template = CvTemplate::findOrFail($validated['template_id']);
        $html = $this->renderCv(
            $template,
            $validated['data'],
        );
        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    //Génerer et download
    public function download(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:cv_templates,id',
            'name' => 'required|string|max:50',
            'data' => 'required|array'
        ]);

        $template = CvTemplate::findOrFail($validated['template_id']);
        $html = $this->renderCv(
            $template,
            $validated['data']
        );

        if(auth()->check()){
            Cv::create([
                'user_id' => auth()->id(),
                'template_id' => $template->id,
                'name' => $validated['name'],
                'data' => $validated['data'],
                'last_downloaded_at' => now()
            ]);
        }

        $pdf = $this->generatePdf($html);
        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="CV_' . str_replace(' ','_', $validated['name']) . '.pdf"');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:cv_templates,id',
            'name' => 'required|string|max:50',
            'data' => 'required|array'
        ]);

        if(auth()->check()){
            $cv = Cv::create([
                'user_id' => auth()->id(),
                'template_id' => $template->id,
                'name' => $validated['name'],
                'data' => $validated['data'],
                'last_downloaded_at' => now()
            ]);
        }    
        
        return response()->json([
            'success' => true,
            'message' => 'Cv crée et sauvegarder avec succès',
            'data' => $cv
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $cv = Cv::where('user_id', auth()->id())->find($id);

        if(!$cv){
            return response()->json([
                'success' => false,
                'message' => 'Cv introuvable'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:50',
            'data' => 'nullable|array'
        ]);

        if($request->has('data')){
            $cv->data = array_merge($cv->data ?? [], $request->data);
        }

        if($request->has('name')){
            $cv->name = $request->name;
        }
        $cv->save();
        $cv->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Cv modifier avec succès',
            'data' => $cv 
        ]);

    }
    //CV SAUVRGARDE
    public function mycvs()
    {
        $cvs = Cv::where('user_id', auth()->id())
            ->with('template')
            ->latest()
            ->get();
        return response()->json([
            'success' => true,
            'data' => $cvs
        ]);
    }

    // télecharger un cv qui a éte sauvegarder

    public function downloadCv($id)
    {
        $cv = Cv::where('user_id', auth()->id())
            ->with('template')
            ->findOrFail($id);

        $html = $this->renderCv($cv->template, $cv->data);

        $cv->update(['last_downloaded_at' => now()]);

        $pdf = $this->generatePdf($html);
        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="CV_' . str_replace(' ','_', $cv->name) . '.pdf"');

    }

    public function destroy($id)
    {
        $cv = Cv::where('user_id', auth()->id())
            ->findOrFail($id);
        $cv->delete();
        return response()->json([
            'success' => true,
            'message' => "CV supprimé."
        ]);
    }

    private function renderCv(CvTemplate $template, array $data): string
    {
        return view($template->blade_view, [
            'data' => $data,
        ])->render();
    }

    private function generatePdf(string $html): string
    {
        return Browsershot::html($html)
            ->format('A4')
            ->showBackground()
            ->fitToPaper()
            ->waitUntilNetworkIdle()
            ->pdf();
    }
}
