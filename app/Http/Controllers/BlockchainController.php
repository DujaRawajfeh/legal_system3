<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlockchainController extends Controller
{
    public function archive(Request $request)
    {
        try {
            $caseNumber     = 1234;
            $documentNumber = 1;
            $documentType   = 'Test';

            // 🔹 استدعاء سكربت Node.js
          $command = "node " . base_path('archiveCase.cjs') . " $caseNumber $documentNumber \"$documentType\"";


            $descriptorspec = [
                1 => ["pipe", "w"], // stdout
                2 => ["pipe", "w"]  // stderr
            ];

            $process = proc_open($command, $descriptorspec, $pipes);

            if (!is_resource($process)) {
                throw new \Exception("❌ لم يتم تشغيل سكربت Node.js");
            }

            $output = stream_get_contents($pipes[1]);
            $error  = stream_get_contents($pipes[2]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            $return_value = proc_close($process);

            Log::info("Blockchain Output", ['output' => $output, 'error' => $error]);

            $txHash = null;
            $block  = null;

            // 🔹 تعريف المتغيرات مسبقاً لتجنب تحذيرات Undefined variable
            $txMatches = [];
            $blockMatches = [];

            // استخراج TX hash
            if (preg_match('/TX_HASH=(\S+)/', $output, $txMatches)) {
                $txHash = $txMatches[1];
            }

            // استخراج Block Number
            if (preg_match('/BLOCK=(\d+)/', $output, $blockMatches)) {
                $block = $blockMatches[1];
            }

            // إذا في خطأ من Node.js
            if (!empty($error)) {
                throw new \Exception("❌ خطأ من Node.js: $error");
            }

            if (!$txHash) {
                throw new \Exception('❌ لم يتم إرسال المعاملة أو لم يتم العثور على TX hash');
            }

            return response()->json([
                'success' => true,
                'txHash'  => $txHash,
                'block'   => $block,
            ]);

        } catch (\Throwable $e) {
            Log::error('Blockchain error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
