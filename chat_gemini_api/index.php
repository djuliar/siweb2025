<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Chat Interaktif dengan Gemini API</title>
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-12">
				<div class="row">
					<h2>💬 Chat dengan Gemini API (tersimpan ke MySQL)</h2>
					<div id="chat-box" class="col-lg-12">
						<?php
						// require 'vendor/autoload.php';
						include "Parsedown.php";
						$parsedown = new Parsedown();
						// Ambil riwayat chat dari database
						$result = $conn->query("SELECT * FROM chat_history ORDER BY created_at ASC");
						while ($row = $result->fetch_assoc()) {
							$class = $row["role"] == "user" ? "user" : "bot";
							$name    = $row["role"] == "user" ? "Anda" : "Konselor AI";
							echo "<div class='chat $class'><b>$name:</b> " . $parsedown->text($row["message"]) . "</div>";
						}
						?>
					</div>
					<div class="col-lg-12 px-0">
						<div class="input-group my-3">
							<input id="message" class="form-control" placeholder="Tulis pesan..." />
							<button class="btn btn-primary" onclick="sendMessage()">Kirim</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/dompurify@2.4.0/dist/purify.min.js"></script>
	<script>
	async function sendMessage() {
		const input = document.getElementById("message");
		const chatbox = document.getElementById("chat-box");
		const message = input.value;

		if (!message) return;

		// tampilkan pesan user langsung di UI
		chatbox.innerHTML += `<div class="chat user"><b>Anda:</b> ${message}</div>`;
		input.value = "";

		// kirim ke backend PHP
		const res = await fetch("chat.php", {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		body: JSON.stringify({ message })
		});

		const data = await res.json();
		const reply = (data.reply !== undefined && data.reply !== null) ? String(data.reply) : "";
		const markedOptions = {
			gfm: true,
			breaks: true,
			headerIds: false
		};
		const htmlFromMd = marked.parse(reply, markedOptions);
		// sanitasi balasan untuk mencegah XSS
		const cleanReply = DOMPurify.sanitize(htmlFromMd);

		// tampilkan balasan Gemini
		chatbox.innerHTML += `<div class="chat bot"><b>AI Konseling:</b> ${cleanReply}</div>`;
		chatbox.scrollTop = chatbox.scrollHeight;
	}
	</script>
</body>
</html>
