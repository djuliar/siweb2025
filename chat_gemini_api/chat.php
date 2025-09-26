<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$userMessage = strtolower($data["message"] ?? "");

// Simpan pesan user ke database
$stmt = $conn->prepare("INSERT INTO chat_history (role, message) VALUES ('user', ?)");
$stmt->bind_param("s", $userMessage);
$stmt->execute();

$systemInstruction = "Anda adalah seorang Ahli Psikologi Profesional. 
Tugas Anda adalah memberikan konsultasi dan konseling psikologi kepada user. 
Jika pertanyaan user tidak berkaitan dengan psikologi, kesehatan mental, konseling, atau topik sejenis, 
jawablah dengan sopan: 'Maaf, saya hanya bisa membantu dalam hal psikologi dan konseling.'";

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . GEMINI_API_KEY;

$payload = [
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => $userMessage]
            ]
        ]
    ],
    "systemInstruction" => [
        "role" => "system",
        "parts" => [
            ["text" => $systemInstruction]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result["error"])) {
    $botReply = "Error dari Gemini API: " . $result["error"]["message"];
} elseif (isset($result["candidates"][0]["content"]["parts"][0]["text"])) {
    $botReply = $result["candidates"][0]["content"]["parts"][0]["text"];
} else {
    $botReply = "Tidak ada jawaban dari Gemini API. Debug: " . json_encode($result);
}

// ✅ Simpan jawaban bot chat ke database
$stmt = $conn->prepare("INSERT INTO chat_history (role, message) VALUES ('bot', ?)");
$stmt->bind_param("s", $botReply);
$stmt->execute();

header("Content-Type: application/json");
echo json_encode(["reply" => $botReply]);
?>
