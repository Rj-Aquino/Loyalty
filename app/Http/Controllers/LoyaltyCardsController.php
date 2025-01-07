<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyCard;
use Illuminate\Http\Request;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class LoyaltyCardsController extends Controller
{
    
    // Handle the form submission and add the member
    // Handle the form submission and add the member
    public function addLoyaltyCard(Request $request)
    {
        // Validate incoming data
        $incomingFields = $request->validate([
            'firstname' => ['required', 'min:3', 'max:50'],
            'lastname' => ['required', 'min:3', 'max:50'],
            'middleinitial' => ['nullable', 'max:1'],
            'suffix' => ['nullable', 'max:10'],
            'contact_number' => ['required', 'max:20'],
        ]);

        // Create the new member using the validated data
        try {
            $newloyaltycard = LoyaltyCard::create([
                'FirstName' => $incomingFields['firstname'],
                'LastName' => $incomingFields['lastname'],
                'MiddleInitial' => $incomingFields['middleinitial'] ?? null,
                'Suffix' => $incomingFields['suffix'] ?? null,
                'ContactNo' => $incomingFields['contact_number'],
            ]);

            // Prepare the barcode content
            $barcodeContent = strtoupper("{$newloyaltycard->LoyaltyCardID}-{$newloyaltycard->FirstName}-{$newloyaltycard->LastName}");
            $barcodeContent = preg_replace('/[^A-Z0-9\-]/', '', $barcodeContent); // Ensure it only contains valid characters for C39

            // Generate the barcode with a transparent background
            $barcode = DNS1D::getBarcodePNG($barcodeContent, 'C39', 2, 60, [0,0,0]);

            // Convert the barcode image from base64 to a PHP image resource
            $barcodeImage = imagecreatefromstring(base64_decode($barcode));

            // Create a new image with a white background
            $width = imagesx($barcodeImage);
            $height = imagesy($barcodeImage);
            $backgroundImage = imagecreatetruecolor($width, $height);

            // Set the background color to white
            $white = imagecolorallocate($backgroundImage, 255, 255, 255); // RGB for white
            imagefill($backgroundImage, 0, 0, $white);

            // Copy the barcode image onto the white background
            imagecopy($backgroundImage, $barcodeImage, 0, 0, 0, 0, $width, $height);

            // Save the barcode image with a white background
            $barcodePath = public_path("barcodes/{$newloyaltycard->LoyaltyCardID}.png");
            imagepng($backgroundImage, $barcodePath);  // Save the final image with a white background

            // Free up memory
            imagedestroy($barcodeImage);
            imagedestroy($backgroundImage);

            // Success message
            return back()->with([
                'success' => "Loyalty Card added successfully! LoyaltyCardID: {$newloyaltycard->LoyaltyCardID}",
                'barcodePath' => "barcodes/{$newloyaltycard->LoyaltyCardID}.png",
            ]);
            
        } catch (\Exception $e) {
            // Handle error
            return back()->with('error', 'An unexpected error occurred.');
        }
    }


    public function viewPoints(Request $request)
    {
        // Check if manual input fields are being used
        $useManualInput = $request->filled('manualLoyaltyCardID') &&
                        $request->filled('manualFirstname') &&
                        $request->filled('manualLastname');

        if ($useManualInput) {
            // Validate the manual input data
            $validatedData = $request->validate([
                'manualLoyaltyCardID' => ['required', 'integer', 'exists:LoyaltyCards,LoyaltyCardID'],
                'manualFirstname' => ['required', 'string', 'max:50'],
                'manualLastname' => ['required', 'string', 'max:50'],
            ]);

            $loyaltycardID = $validatedData['manualLoyaltyCardID'];
            $firstname = $validatedData['manualFirstname'];
            $lastname = $validatedData['manualLastname'];
        } else {
            // Validate the scanned data
            $validatedData = $request->validate([
                'loyaltycardID' => ['required', 'integer', 'exists:LoyaltyCards,LoyaltyCardID'],
                'firstname' => ['required', 'string', 'max:50'],
                'lastname' => ['required', 'string', 'max:50'],
            ]);

            $loyaltycardID = $validatedData['loyaltycardID'];
            $firstname = $validatedData['firstname'];
            $lastname = $validatedData['lastname'];
        }

        // Retrieve member by LoyaltyCardID with case-insensitive matching
        $loyaltycard = LoyaltyCard::where('LoyaltyCardID', $loyaltycardID)
                        ->whereRaw('LOWER(FirstName) = ?', [strtolower($firstname)])
                        ->whereRaw('LOWER(LastName) = ?', [strtolower($lastname)])
                        ->first();

        // Check if the member exists and match the data
        if ($loyaltycard) {
            // Return success with points
            return back()->with('success', "Member Found! Points: {$loyaltycard->Points}");
        } else {
            // Return error if member not found or data doesn't match
            return back()->with('error', 'Member not found or information does not match.');
        }
    }



    public function index()
    {
        return response()->json(loyaltycard::all(), 200);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'FirstName' => 'required|string',
            'LastName' => 'required|string',
            'MiddleInitial' => 'nullable|string|max:1',
            'Suffix' => 'nullable|string|max:10',
            'ContactNo' => 'required|string',
            'Points' => 'integer|min:0',
        ]);

        $member = loyaltycard::create($validatedData);
        return response()->json($member, 201);
    }


    public function show($id)
    {
        $loyaltycard = loyaltycard::find($id);

        if (!$loyaltycard) {
            return response()->json(['error' => 'Loyalty Card not found'], 404);
        }

        return response()->json($loyaltycard, 200);
    }


    public function update(Request $request, $id)
    {
        $loyaltycard = loyaltycard::find($id);

        if (!$loyaltycard) {
            return response()->json(['error' => 'Loyalty Card not found'], 404);
        }

        $validatedData = $request->validate([
            'FirstName' => 'sometimes|string',
            'LastName' => 'sometimes|string',
            'MiddleInitial' => 'nullable|string|max:1',
            'Suffix' => 'nullable|string|max:10',
            'ContactNo' => 'sometimes|string',
            'Points' => 'integer|min:0',
        ]);

        $loyaltycard->update($validatedData);
        return response()->json($loyaltycard, 200);
    }

    public function destroy($id)
    {
        $loyaltycard = loyaltycard::find($id);

        if (!$loyaltycard) {
            return response()->json(['error' => 'Loyalty Card not found'], 404);
        }

        $loyaltycard->delete();
        return response()->json(['message' => 'Loyalty Card deleted successfully'], 200);
    }

    }
