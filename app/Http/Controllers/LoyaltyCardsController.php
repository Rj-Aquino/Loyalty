<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyCard;
use Illuminate\Http\Request;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Illuminate\Support\Facades\Http;

class LoyaltyCardsController extends Controller
{
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

        // Generate UniqueIdentifier manually
        $uniqueIdentifier = 'LID-' . strtoupper(str_random(6));

        // Create the new member using the validated data
        try {
            $newloyaltycard = LoyaltyCard::create([
                'FirstName' => $incomingFields['firstname'],
                'LastName' => $incomingFields['lastname'],
                'MiddleInitial' => $incomingFields['middleinitial'] ?? null,
                'Suffix' => $incomingFields['suffix'] ?? null,
                'ContactNo' => $incomingFields['contact_number'],
                'UniqueIdentifier' => $uniqueIdentifier, // Use the generated UniqueIdentifier
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

    // Store function to create a new loyalty card
    public function store(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'FirstName' => 'required|string',
            'LastName' => 'required|string',
            'MiddleInitial' => 'nullable|string|max:1',
            'Suffix' => 'nullable|string|max:10',
            'ContactNo' => 'required|string',
            'Points' => 'integer|min:0',
        ]);

        // Generate UniqueIdentifier manually
        $uniqueIdentifier = 'LID-' . strtoupper(str_random(6));

        // Create the loyalty card using validated data and the generated UniqueIdentifier
        $member = LoyaltyCard::create([
            'FirstName' => $validatedData['FirstName'],
            'LastName' => $validatedData['LastName'],
            'MiddleInitial' => $validatedData['MiddleInitial'] ?? null,
            'Suffix' => $validatedData['Suffix'] ?? null,
            'ContactNo' => $validatedData['ContactNo'],
            'Points' => $validatedData['Points'] ?? 0,
            'UniqueIdentifier' => $uniqueIdentifier, // Use the generated UniqueIdentifier
        ]);

        // Return the created loyalty card as a response
        return response()->json($member, 201);
    }

    // Function to fetch transactions from the API
    private function fetchTransactionsFromApi($loyaltycardID, $page = 1, $perPage = 2)
    {
        // Fetch the transactions with pagination parameters
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $this->getApiToken()])
                        ->get("https://pos-production-c2c1.up.railway.app/api/transactions/loyalty/{$loyaltycardID}", [
                            'page' => $page,
                            'per_page' => $perPage
                        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    // Method to get the API token
    private function getApiToken()
    {
        $response = Http::post('https://pos-production-c2c1.up.railway.app/api/generate-token');
        return $response->json()['token'] ?? '';
    }
    
    public function index()
    {
        return response()->json(LoyaltyCard::all(), 200);
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

        $member = LoyaltyCard::create($validatedData);
        return response()->json($member, 201);
    }

    public function show($id)
    {
        $loyaltyCard = LoyaltyCard::find($id);

        if (!$loyaltyCard) {
            return response()->json(['error' => 'Loyalty Card not found'], 404);
        }

        return response()->json($loyaltyCard, 200);
    }

    public function update(Request $request, $id)
    {
        $loyaltyCard = LoyaltyCard::find($id);

        if (!$loyaltyCard) {
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

        $loyaltyCard->update($validatedData);
        return response()->json($loyaltyCard, 200);
    }

    public function destroy($id)
    {
        $loyaltyCard = LoyaltyCard::find($id);

        if (!$loyaltyCard) {
            return response()->json(['error' => 'Loyalty Card not found'], 404);
        }

        $loyaltyCard->delete();
        return response()->json(['message' => 'Loyalty Card deleted successfully'], 200);
    }
}
