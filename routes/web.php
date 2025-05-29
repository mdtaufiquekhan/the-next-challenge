<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\WizardSessionController;
use App\Http\Controllers\WizardGPTController;

Route::get('/', [ChallengeController::class, 'index']);

Route::resource('challenges', ChallengeController::class);

// ✅ Stores temporary wizard fields to Laravel session
Route::post('/wizard/store-memory', [WizardSessionController::class, 'memory'])->name('wizard.memory');


// ✅ Step 4: Generate  type, and tags from previous inputs
Route::post('/wizard/step4-generate', [WizardGPTController::class, 'suggestStep4'])->name('wizard.step4');

// ✅ Step 5:
Route::post('/wizard/step5-generate', [WizardGPTController::class, 'suggestStep5'])->name('wizard.step5');


// ✅ Step 6:
Route::post('/wizard/step6-generate', [WizardGPTController::class, 'suggestStep6'])->name('wizard.step6');

// ✅ Step 7:
Route::post('/wizard/step7-generate', [WizardGPTController::class, 'suggestStep7'])->name('wizard.step7');

// ✅ Step 8:
Route::post('/wizard/step8-generate', [WizardGPTController::class, 'suggestStep8'])->name('wizard.step8');

// ✅ Step 9:
Route::post('/wizard/step9-generate', [WizardGPTController::class, 'suggestStep9'])->name('wizard.step9');

// ✅ Step 10:
Route::post('/wizard/step10-generate', [WizardGPTController::class, 'suggestStep10'])->name('wizard.step10');

// ✅ Step 11:
Route::post('/wizard/step11-generate', [WizardGPTController::class, 'suggestStep11'])->name('wizard.step11');

// ✅ Step 12:
Route::post('/wizard/step12-generate', [WizardGPTController::class, 'suggestStep12'])->name('wizard.step12');